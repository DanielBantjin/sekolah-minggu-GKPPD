import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { canManageWeeklyData } from "@/lib/permissions";

const tabs = [
  { key: "hari-ini", label: "Hari ini" },
  { key: "akan-datang", label: "Akan datang" },
  { key: "sudah-lewat", label: "Sudah lewat" },
] as const;

export default async function TeacherActivitiesPage({
  searchParams,
}: {
  searchParams: Promise<{ view?: string }>;
}) {
  const user = await getCurrentUser();
  if (!user || !canManageWeeklyData(user.role?.name)) redirect("/dashboard");

  const params = await searchParams;
  const selectedView = tabs.some((tab) => tab.key === params.view) ? params.view! : "hari-ini";
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const activities = await prisma.activity.findMany({
    where: {
      createdById: user.id,
      ...(selectedView === "hari-ini"
        ? { date: { gte: today, lt: new Date(today.getTime() + 24 * 60 * 60 * 1000) } }
        : selectedView === "akan-datang"
          ? { date: { gte: new Date(today.getTime() + 24 * 60 * 60 * 1000) } }
          : { date: { lt: today } }),
    },
    orderBy: { date: "asc" },
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">{user.role?.name === "sekretaris" ? "Sekretaris" : "Guru"}</p>
          <h1>Kegiatan</h1>
          <p>Buat jadwal kegiatan tanpa memasukkan ID pembuat.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      <section className="teacher-form-panel">
        <h2>Tambah kegiatan</h2>
        <form className="crud-form" action="/api/admin/activities" method="post">
          <label>Nama kegiatan<input name="title" required /></label>
          <label>Tanggal<input name="date" type="date" required /></label>
          <label>Waktu mulai<input name="startTime" type="time" /></label>
          <label>Waktu selesai<input name="endTime" type="time" /></label>
          <label>Lokasi<input name="location" /></label>
          <label>Kategori<input name="category" /></label>
          <label>Deskripsi<textarea name="description" rows={4} /></label>
          <button type="submit">Simpan kegiatan</button>
        </form>
      </section>

      <section className="teacher-form-panel activity-panel">
        <div className="activity-header-row">
          <h2>Daftar kegiatan</h2>
          <nav className="activity-tabs" aria-label="Menu kegiatan">
            {tabs.map((tab) => (
              <Link
                key={tab.key}
                className={`activity-tab ${selectedView === tab.key ? "active" : ""}`}
                href={{ pathname: "/teacher/activities", query: { view: tab.key } }}
              >
                {tab.label}
              </Link>
            ))}
          </nav>
        </div>

        <div className="activity-list">
          {activities.length ? activities.map((activity) => (
            <div className="activity-item" key={activity.id}>
              <div className="activity-main">
                <div>
                  <strong>{activity.title}</strong>
                  <small>{new Date(activity.date).toLocaleDateString("id-ID")} {activity.startTime ? `• ${activity.startTime}` : ""}{activity.endTime ? ` - ${activity.endTime}` : ""}</small>
                </div>
                <span className={`status-pill ${activity.date < today ? "status-absent" : activity.date.getTime() === today.getTime() ? "status-present" : "status-warning"}`}>
                  {activity.date < today ? "Lewat" : activity.date.getTime() === today.getTime() ? "Hari ini" : "Mendatang"}
                </span>
              </div>
              {(activity.location || activity.category || activity.description) && (
                <div className="activity-meta">
                  {activity.location && <span>{activity.location}</span>}
                  {activity.category && <span>{activity.category}</span>}
                  {activity.description && <span>{activity.description}</span>}
                </div>
              )}
            </div>
          )) : (
            <p className="empty-state">Belum ada kegiatan pada kategori ini.</p>
          )}
        </div>
      </section>
    </main>
  );
}
