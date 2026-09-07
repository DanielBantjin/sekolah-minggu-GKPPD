import Link from "next/link";
import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { StudentActivityCalendar } from "./StudentActivityCalendar";

export default async function StudentActivitiesPage() {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "murid") redirect("/dashboard");

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const activities = await prisma.activity.findMany({
    where: { date: { gte: today } },
    orderBy: { date: "asc" },
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Murid</p>
          <h1>Kalender Kegiatan</h1>
          <p>Ikuti jadwal kegiatan sekolah minggu dan lihat detailnya per tanggal.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      <StudentActivityCalendar activities={activities} />
    </main>
  );
}
