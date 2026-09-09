import Link from "next/link";
import { redirect } from "next/navigation";
import { prisma } from "@/lib/prisma";
import { getCurrentUser } from "@/lib/auth";
import { AnalyticsChart } from "./AnalyticsChart";
import { hasAdminAccess } from "@/lib/permissions";

export default async function DashboardPage({ searchParams }: { searchParams: Promise<{ start?: string; end?: string }> }) {
  const user = await getCurrentUser();
  if (!user) redirect("/");
  const filters = await searchParams;
  const attendanceWhere = { ...(filters.start || filters.end ? { date: { ...(filters.start ? { gte: new Date(filters.start) } : {}), ...(filters.end ? { lte: new Date(filters.end) } : {}) } } : {}) };
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const [students, reflections, activities, finances, attendance, attendanceRows, financeRows, upcomingActivities] = await Promise.all([
    prisma.student.count(),
    prisma.reflection.count(),
    prisma.activity.count(),
    prisma.finance.count(),
    prisma.attendance.count({ where: attendanceWhere }),
    prisma.attendance.findMany({ where: attendanceWhere, select: { date: true, status: true, presentCount: true, totalCount: true } }),
    prisma.finance.findMany({ where: filters.start || filters.end ? { date: { ...(filters.start ? { gte: new Date(filters.start) } : {}), ...(filters.end ? { lte: new Date(filters.end) } : {}) } } : {}, select: { date: true, type: true, amount: true } }),
    prisma.activity.findMany({
      where: { date: { gte: today } },
      orderBy: { date: "asc" },
      take: 20,
    }),
  ]);
  const attendanceChart = summarizeAttendance(attendanceRows);
  const financeChart = summarizeFinance(financeRows);
  const financeTotals = summarizeFinanceTotals(financeRows);
  const isAdmin = hasAdminAccess(user.role?.name);
  const isStudent = user.role?.name === "murid";
  const modules = isAdmin
    ? [
        ["Kelola Role", "/admin/roles"], ["Kelola User", "/admin/users"], ["Kelola Murid", "/admin/students"],
        ["Kelola Renungan", "/admin/reflections"], ["Reading Track", "/admin/reading-tracks"], ["Kelola Kegiatan", "/admin/activities"],
        ["Kelola Kehadiran", "/admin/attendances"], ["Kelola Keuangan", "/admin/finances"],
      ]
    : user.role?.name === "guru"
      ? [["Renungan", "/teacher/reflections"], ["Catat Kehadiran", "/teacher/attendance"], ["Laporan Reading", "/teacher/reading-report"]]
      : user.role?.name === "sekretaris"
        ? [["Kegiatan", "/teacher/activities"]]
        : user.role?.name === "bendahara"
          ? [["Keuangan", "/teacher/finance"]]
          : [["Renungan Hari Ini", "/student/reading"], ["Daftar Renungan", "/student/reflections"], ["Kegiatan", "/student/activities"]];
  const roleBadge = user.role?.name ? user.role.name.charAt(0).toUpperCase() + user.role.name.slice(1) : "Pengguna";

  return <main className="dashboard-shell">
    <header className="dashboard-header">
      <div>
        <p className="eyebrow">GKPPD • Sekolah Minggu</p>
        <h1>Selamat datang, {user.name}</h1>
        <p>Dashboard {user.role?.name ?? "pengguna"} untuk mengelola aktivitas sekolah minggu.</p>
      </div>
      <div className="header-actions-row">
        <span className="role-badge">{roleBadge}</span>
        <form action="/api/auth/logout" method="post"><button className="logout-button">Keluar</button></form>
      </div>
    </header>
    {isStudent ? (
      <>
        <section className="stats-grid"><Stat label="Renungan aktif" value={reflections} /><Stat label="Kegiatan mendatang" value={upcomingActivities.length} /><Stat label="Total kegiatan" value={activities} /><Stat label="Kelas" value={user.student?.classLabel ? 1 : 0} /></section>
        <section className="student-upcoming-card">
          <div className="student-upcoming-header">
            <div>
              <p className="eyebrow">Agenda</p>
              <h2>Kegiatan yang akan datang</h2>
            </div>
            <Link className="student-link small-link" href="/student/activities">Lihat kalender</Link>
          </div>

          {upcomingActivities.length ? (
            <div className="upcoming-activity-list">
              {upcomingActivities.map((activity) => (
                <article className="upcoming-activity-item" key={activity.id}>
                  <div className="upcoming-date-badge">
                    <strong>{new Date(activity.date).getDate()}</strong>
                    <span>{new Date(activity.date).toLocaleDateString("id-ID", { month: "short" }).replace(".", "")}</span>
                  </div>
                  <div className="upcoming-content">
                    <h3>{activity.title}</h3>
                    <p>{activity.description ?? "Kegiatan sekolah minggu yang siap dijalani."}</p>
                    <div className="upcoming-meta">
                      {(activity.startTime || activity.endTime) && <span>{activity.startTime ?? "--"} - {activity.endTime ?? "--"}</span>}
                      {activity.location && <span>{activity.location}</span>}
                    </div>
                  </div>
                </article>
              ))}
            </div>
          ) : (
            <div className="empty-state compact-state">
              <strong>Belum ada kegiatan mendatang</strong>
              <span>Informasi kegiatan akan muncul di sini.</span>
            </div>
          )}
        </section>
      </>
    ) : (
      <>
        <section className="stats-grid"><Stat label="Murid" value={students} /><Stat label="Renungan" value={reflections} /><Stat label="Kegiatan" value={activities} /><Stat label="Transaksi" value={finances} />{isAdmin && <Stat label="Kehadiran" value={attendance} />}</section>
        {isAdmin && <section className="finance-summary"><MoneyStat label="Total pemasukan" value={financeTotals.income} className="income-text" /><MoneyStat label="Total pengeluaran" value={financeTotals.expense} className="expense-text" /><MoneyStat label="Saldo akhir" value={financeTotals.balance} className={financeTotals.balance >= 0 ? "income-text" : "expense-text"} /></section>}
        {isAdmin && <><form className="filter-form" method="get"><label>Dari<input type="date" name="start" defaultValue={filters.start} /></label><label>Sampai<input type="date" name="end" defaultValue={filters.end} /></label><button type="submit">Terapkan</button><Link href="/dashboard">Reset</Link></form><div className="report-actions"><Link href="/api/reports/csv">Unduh laporan CSV</Link><Link href="/admin/attendances">Kelola kehadiran</Link><Link href="/admin/reports">Laporan cetak</Link></div></>}
        {isAdmin && <section className="chart-grid"><AnalyticsChart title="Grafik Kehadiran" items={attendanceChart.map((item) => ({ label: item.label, value: item.value, color: "#2563eb" }))} /><AnalyticsChart title="Grafik Keuangan" items={financeChart.map((item) => ({ label: item.label, value: item.value, color: item.type === "pemasukan" ? "#059669" : "#dc2626" }))} format="currency" /></section>}
      </>
    )}
    <section className="module-grid">{modules.map(([label, href]) => <Link className="module-card" href={href} key={href}><strong>{label}</strong><span>Buka fitur</span></Link>)}</section>
  </main>;
}

function Stat({ label, value }: { label: string; value: number }) { return <div className="stat-card"><span>{label}</span><strong>{value}</strong></div>; }
function summarizeAttendance(rows: { date: Date; status: string; presentCount: number | null; totalCount: number | null }[]) { const grouped = new Map<string, number>(); rows.forEach((row) => { const label = row.date.toLocaleDateString("id-ID", { month: "short", day: "numeric" }); const value = row.presentCount ?? (row.status === "hadir" ? 1 : 0); grouped.set(label, (grouped.get(label) ?? 0) + value); }); return Array.from(grouped, ([label, value]) => ({ label, value })).slice(-8); }
function summarizeFinance(rows: { date: Date; type: string; amount: unknown }[]) { return rows.slice(-8).map((row) => ({ label: row.date.toLocaleDateString("id-ID", { month: "short", day: "numeric" }), value: Number(row.amount), type: row.type })); }
function summarizeFinanceTotals(rows: { type: string; amount: unknown }[]) { const income = rows.filter((row) => row.type === "pemasukan").reduce((total, row) => total + Number(row.amount), 0); const expense = rows.filter((row) => row.type === "pengeluaran").reduce((total, row) => total + Number(row.amount), 0); return { income, expense, balance: income - expense }; }
function MoneyStat({ label, value, className }: { label: string; value: number; className: string }) { return <div className="money-stat"><span>{label}</span><strong className={className}>Rp {value.toLocaleString("id-ID")}</strong></div>; }
