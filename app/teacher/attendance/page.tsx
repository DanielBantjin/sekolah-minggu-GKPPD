import Link from "next/link";
import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { canManageWeeklyData } from "@/lib/permissions";

const levels = ["Kecil", "Sedang", "Remaja"] as const;

export default async function TeacherAttendancePage({
  searchParams,
}: {
  searchParams: Promise<{ date?: string; saved?: string }>;
}) {
  const user = await getCurrentUser();
  if (!user || !canManageWeeklyData(user.role?.name)) redirect("/dashboard");

  const params = await searchParams;
  const selectedDate = params.date ?? new Date().toISOString().slice(0, 10);
  const date = new Date(`${selectedDate}T00:00:00`);

  const [students, attendanceEntries, recentHistory] = await Promise.all([
    prisma.student.findMany({
      include: { user: true },
      orderBy: [{ classLabel: "asc" }, { user: { name: "asc" } }],
    }),
    prisma.attendance.findMany({
      where: { date },
      orderBy: [{ studentId: "asc" }],
    }),
    prisma.attendance.findMany({
      include: { student: { include: { user: true } } },
      orderBy: { date: "desc" },
      take: 60,
    }),
  ]);

  const attendanceMap = new Map(attendanceEntries.map((entry) => [entry.studentId, entry]));
  const historyByWeek = new Map<string, typeof recentHistory>();
  recentHistory.forEach((entry) => {
    const current = new Date(entry.date);
    const sunday = new Date(current);
    sunday.setUTCDate(current.getUTCDate() - current.getUTCDay());
    const weekKey = sunday.toISOString().slice(0, 10);
    historyByWeek.set(weekKey, [...(historyByWeek.get(weekKey) ?? []), entry]);
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Guru</p>
          <h1>Catat Kehadiran</h1>
          <p>Pilih tanggal lalu beri tanda hadir untuk setiap murid berdasarkan kelas.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      {params.saved && <p className="success-message">Kehadiran berhasil disimpan.</p>}

      <form className="filter-form attendance-filter" method="get">
        <label>Tanggal<input type="date" name="date" defaultValue={selectedDate} /></label>
        <button type="submit">Muat daftar</button>
      </form>

      <form className="attendance-form" action="/api/teacher/attendance" method="post">
        <input type="hidden" name="date" value={selectedDate} />
        <nav className="attendance-tabs" aria-label="Kategori kelas">
          {levels.map((level) => (
            <a key={level} href={`#kelas-${level.toLowerCase()}`} className="attendance-tab-link">
              Kelas {level}
            </a>
          ))}
        </nav>

        {levels.map((level) => {
          const group = students.filter((student) => (student.classLabel ?? "Kecil").toLowerCase() === level.toLowerCase());
          const records = group.map((student) => {
            const attendance = attendanceMap.get(student.id);
            const checked = attendance?.status === "hadir";
            return { student, checked };
          });

          return (
            <section className="attendance-level-panel" id={`kelas-${level.toLowerCase()}`} key={level}>
              <div className="attendance-level-header">
                <h2>Kelas {level}</h2>
                <span>{records.length} murid</span>
              </div>

              <div className="attendance-list">
                {records.length ? records.map(({ student, checked }) => (
                  <label className="attendance-row" key={student.id}>
                    <div className="attendance-person">
                      <strong>{student.user.name}</strong>
                      <small>{student.user.email}</small>
                    </div>
                    <div className="attendance-checker">
                      <input type="checkbox" name={`student-${student.id}`} value="hadir" defaultChecked={checked} />
                      <span>Hadir</span>
                    </div>
                  </label>
                )) : <p className="empty-state">Belum ada murid di kelas ini.</p>}
              </div>
            </section>
          );
        })}

        <div className="attendance-submit-row">
          <button type="submit" className="primary-button">Simpan kehadiran</button>
        </div>
      </form>

      <section className="teacher-form-panel attendance-history-panel">
        <h2>Riwayat Absensi</h2>
        <div className="attendance-weeks history-weeks">
          {historyByWeek.size ? Array.from(historyByWeek.entries()).map(([weekKey, entries]) => (
            <details className="attendance-week" key={weekKey} open>
              <summary>
                Minggu, {new Date(`${weekKey}T00:00:00`).toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" })}
              </summary>
              <div className="history-list weekly-class-groups">
                {(["Kecil", "Sedang", "Remaja"] as const).map((level) => {
                  const levelEntries = entries.filter((entry) => (entry.classLabel ?? "Kecil").toLowerCase() === level.toLowerCase());

                  return (
                    <div className="weekly-class-group" key={level}>
                      <h3>Kelas {level}</h3>
                      {levelEntries.length ? (
                        <div className="history-list">
                          {levelEntries.map((entry) => (
                            <div className="history-row" key={entry.id}>
                              <div>
                                <strong>{entry.student?.user.name ?? "Murid"}</strong>
                                <small>{new Date(entry.date).toLocaleDateString("id-ID")} · {entry.classLabel ?? "Kelas umum"}</small>
                              </div>
                              <span className={`status-pill ${entry.status === "hadir" ? "status-present" : "status-absent"}`}>
                                {entry.status}
                              </span>

                              <form action="/api/teacher/attendance" method="post" className="history-edit-form">
                                <input type="hidden" name="_method" value="PUT" />
                                <input type="hidden" name="id" value={entry.id} />
                                <select name="status" defaultValue={entry.status}>
                                  <option value="hadir">Hadir</option>
                                  <option value="izin">Izin</option>
                                  <option value="sakit">Sakit</option>
                                  <option value="alpa">Alpa</option>
                                  <option value="tidak hadir">Tidak hadir</option>
                                </select>
                                <button type="submit" className="secondary-button">Edit</button>
                              </form>
                            </div>
                          ))}
                        </div>
                      ) : (
                        <p className="empty-state">Tidak ada absensi untuk kelas {level}.</p>
                      )}
                    </div>
                  );
                })}
              </div>
            </details>
          )) : <p className="empty-state">Belum ada riwayat absensi.</p>}
        </div>
      </section>
    </main>
  );
}
