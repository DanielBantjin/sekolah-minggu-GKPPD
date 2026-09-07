import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

const levels = ["Semua", "Kecil", "Sedang", "Remaja"] as const;

export default async function TeacherReadingReportPage({
  searchParams,
}: {
  searchParams: Promise<{ date?: string; class?: string }>;
}) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "guru"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) redirect("/dashboard");

  const params = await searchParams;
  const selectedDate = params.date ?? new Date().toISOString().slice(0, 10);
  const classFilter = params.class ?? "Semua";
  const date = new Date(`${selectedDate}T00:00:00`);
  const start = new Date(date);
  start.setHours(0, 0, 0, 0);
  const end = new Date(date);
  end.setHours(23, 59, 59, 999);

  const tracks = await prisma.readingTrack.findMany({
    where: { readAt: { gte: start, lte: end } },
    include: { student: { include: { user: true } }, reflection: true },
    orderBy: { readAt: "desc" },
  });

  const filteredTracks = tracks.filter((track) => {
    const className = (track.student.classLabel ?? "Kecil").toLowerCase();
    if (classFilter === "Semua") return true;
    return className === classFilter.toLowerCase();
  });

  const summaries = levels.reduce((acc, level) => {
    acc[level] = level === "Semua"
      ? filteredTracks.length
      : filteredTracks.filter((track) => ((track.student.classLabel ?? "Kecil").toLowerCase()) === level.toLowerCase()).length;
    return acc;
  }, {} as Record<string, number>);

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Guru</p>
          <h1>Laporan Reading Track</h1>
          <p>Ikuti progres membaca murid per hari dan per kelas.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      <form className="filter-form attendance-filter" method="get">
        <label>Tanggal<input type="date" name="date" defaultValue={selectedDate} /></label>
        <label>Kelas<select name="class" defaultValue={classFilter}>
          {levels.map((level) => <option key={level} value={level}>{level === "Semua" ? "Semua kelas" : `Kelas ${level}`}</option>)}
        </select></label>
        <button type="submit">Tampilkan</button>
      </form>

      <section className="reading-summary-grid">
        {levels.map((level) => (
          <div key={level} className="summary-stat-card">
            <span>{level === "Semua" ? "Total" : `Kelas ${level}`}</span>
            <strong>{summaries[level]}</strong>
          </div>
        ))}
      </section>

      <section className="teacher-form-panel reading-panel">
        <h2>Daftar murid yang sudah membaca</h2>
        <div className="history-list">
          {filteredTracks.length ? filteredTracks.map((track) => (
            <div className="history-row reading-history-row" key={track.id}>
              <div>
                <strong>{track.student.user.name}</strong>
                <small>{track.student.classLabel ?? "Kelas umum"} · {track.reflection.title}</small>
              </div>
              <div className="reading-meta">
                <span>{track.duration} detik</span>
                <span className={`status-pill ${track.completed ? "status-present" : "status-absent"}`}>
                  {track.completed ? "Selesai" : "Proses"}
                </span>
              </div>
            </div>
          )) : <p className="empty-state">Belum ada murid yang membaca pada tanggal {new Date(`${selectedDate}T00:00:00`).toLocaleDateString("id-ID")}.</p>}
        </div>
      </section>
    </main>
  );
}
