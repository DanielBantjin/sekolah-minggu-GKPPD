import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export default async function StudentReflectionsPage() {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "murid" || !user.student) redirect("/dashboard");

  const [reflections, tracks] = await Promise.all([
    prisma.reflection.findMany({
      where: { active: true, date: { lte: new Date() } },
      orderBy: { date: "desc" },
    }),
    prisma.readingTrack.findMany({
      where: { studentId: user.student.id },
      orderBy: { readAt: "desc" },
    }),
  ]);

  const progressByReflection = new Map<number, { duration: number; completed: boolean }>();
  tracks.forEach((track) => {
    const current = progressByReflection.get(track.reflectionId);
    const next = {
      duration: (current?.duration ?? 0) + track.duration,
      completed: current?.completed || track.completed,
    };
    progressByReflection.set(track.reflectionId, next);
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Murid</p>
          <h1>Daftar Renungan</h1>
          <p>Pilih renungan yang tersedia untuk dibaca dan lihat progres membaca Anda.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      <section className="student-grid">
        {reflections.length ? reflections.map((reflection) => {
          const progress = progressByReflection.get(reflection.id);
          const completed = Boolean(progress?.completed);
          const duration = progress?.duration ?? 0;

          return (
            <article className="student-reflection-card" key={reflection.id}>
              <div className="student-reflection-meta">
                <span className={`student-status-pill ${completed ? "student-status-done" : "student-status-pending"}`}>
                  {completed ? "Selesai" : "Belum dibaca"}
                </span>
                <small>{reflection.date.toLocaleDateString("id-ID")}</small>
              </div>

              <h2>{reflection.title}</h2>
              <p>{reflection.bibleVerse ?? "Renungan hari ini"}</p>

              <div className="student-progress-row">
                <span>Durasi</span>
                <strong>{duration} detik</strong>
              </div>

              <Link className="primary-button student-link" href={`/student/reading?reflection=${reflection.id}`}>
                {completed ? "Baca ulang" : "Mulai baca"}
              </Link>
            </article>
          );
        }) : (
          <div className="empty-state student-empty">
            <strong>Belum ada renungan aktif</strong>
            <span>Renungan akan muncul saat guru atau admin menyiapkan materi.</span>
          </div>
        )}
      </section>
    </main>
  );
}
