import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { ReadingTracker } from "./ReadingTracker";

export default async function StudentReadingPage({
  searchParams,
}: {
  searchParams: Promise<{ reflection?: string; saved?: string }>;
}) {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "murid" || !user.student) redirect("/dashboard");

  const params = await searchParams;
  const selectedReflectionId = Number(params.reflection ?? 0);

  const [latestReflection, recentReads] = await Promise.all([
    prisma.reflection.findFirst({
      where: selectedReflectionId
        ? { id: selectedReflectionId, active: true, date: { lte: new Date() } }
        : { active: true, date: { lte: new Date() } },
      orderBy: { date: "desc" },
    }),
    prisma.readingTrack.findMany({
      where: { studentId: user.student.id },
      include: { reflection: true },
      orderBy: { readAt: "desc" },
      take: 6,
    }),
  ]);

  const reflection = latestReflection;

  if (!reflection) {
    return (
      <main className="dashboard-shell">
        <header className="dashboard-header">
          <div>
            <p className="eyebrow">Reading Track</p>
            <h1>Renungan Hari Ini</h1>
            <p>Baca renungan dan tandai selesai untuk mencatat progres Anda.</p>
          </div>
          <Link className="back-link" href="/dashboard">Beranda</Link>
        </header>
        <section className="empty-state">
          <strong>Belum ada renungan aktif</strong>
          <span>Renungan akan muncul setelah guru atau admin menerbitkannya.</span>
        </section>
      </main>
    );
  }

  const latestTrack = await prisma.readingTrack.findFirst({
    where: { studentId: user.student.id, reflectionId: reflection.id },
    orderBy: { readAt: "desc" },
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Reading Track</p>
          <h1>Renungan Hari Ini</h1>
          <p>Baca renungan dan tandai selesai untuk mencatat progres Anda.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      {params.saved && <p className="success-message">Progres membaca berhasil disimpan.</p>}

      <article className="reading-card student-reading-hero">
        <div className="student-reading-topline">
          <p className="eyebrow">{reflection.bibleVerse ?? "Renungan Sekolah Minggu"}</p>
          <span className="student-status-pill student-status-done">{latestTrack?.completed ? "Selesai" : "Belum selesai"}</span>
        </div>

        <h2>{reflection.title}</h2>
        <div className="student-reading-meta">
          <span>{reflection.date.toLocaleDateString("id-ID")}</span>
          <span>{latestTrack?.duration ?? 0} detik dibaca</span>
        </div>

        <ReadingTracker reflectionId={reflection.id} />
        <p className="reading-content">{reflection.content}</p>

        <form action="/api/reading-track" method="post" className="student-reading-form">
          <input type="hidden" name="reflectionId" value={reflection.id} />
          <input type="hidden" name="completed" value="true" />
          <button type="submit">Tandai selesai</button>
        </form>
      </article>

      <section className="teacher-form-panel">
        <h2>Riwayat membaca</h2>
        <div className="history-list">
          {recentReads.length ? recentReads.map((track) => (
            <div className="history-row" key={`${track.id}-${track.readAt.toISOString()}`}>
              <div>
                <strong>{track.reflection.title}</strong>
                <small>{new Date(track.readAt).toLocaleDateString("id-ID")} · {track.duration} detik</small>
              </div>
              <span className={`status-pill ${track.completed ? "status-present" : "status-absent"}`}>
                {track.completed ? "Selesai" : "Proses"}
              </span>
            </div>
          )) : <p className="empty-state">Belum ada riwayat membaca.</p>}
        </div>
      </section>
    </main>
  );
}
