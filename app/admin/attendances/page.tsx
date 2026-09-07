import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

const levels = ["Kecil", "Sedang", "Remaja"] as const;

export default async function AttendancePage({ searchParams }: { searchParams: Promise<{ month?: string }> }) {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "admin") redirect("/dashboard");
  const requestedMonth = (await searchParams).month ?? "";
  const month = /^\d{4}-\d{2}$/.test(requestedMonth) ? requestedMonth : new Date().toISOString().slice(0, 7);
  const start = new Date(`${month}-01T00:00:00.000Z`);
  const end = new Date(start);
  end.setUTCMonth(end.getUTCMonth() + 1);
  const rows = await prisma.attendance.findMany({ where: { date: { gte: start, lt: end }, status: "hadir" }, include: { student: { include: { user: true } } }, orderBy: [{ date: "desc" }, { studentId: "asc" }] });
  const weeks = new Map<string, typeof rows>();
  for (const row of rows) { const sunday = new Date(row.date); sunday.setUTCDate(sunday.getUTCDate() - sunday.getUTCDay()); const key = sunday.toISOString().slice(0, 10); weeks.set(key, [...(weeks.get(key) ?? []), row]); }
  return <main className="dashboard-shell"><header className="dashboard-header"><div><p className="eyebrow">Panel Admin</p><h1>Kelola Kehadiran</h1><p>Pilih bulan untuk melihat daftar murid yang hadir setiap minggu berdasarkan tingkat.</p></div><div className="header-actions-row"><Link className="back-link" href="/dashboard">Beranda</Link><Link className="back-link" href="/admin">Semua modul</Link></div></header><form className="filter-form attendance-filter" method="get"><label>Bulan<input type="month" name="month" defaultValue={month} /></label><button type="submit">Tampilkan</button></form><section className="attendance-weeks">{Array.from(weeks.entries()).map(([week, weekRows]) => <details className="attendance-week" key={week} open><summary>Minggu, {new Date(`${week}T00:00:00.000Z`).toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" })}</summary><div className="attendance-groups">{levels.map((level) => { const group = weekRows.filter((row) => (row.student?.classLabel ?? "").toLowerCase().includes(level.toLowerCase())); return <div className="attendance-group" key={level}><h3>Kelas {level}</h3>{group.length ? <ol>{group.map((row) => <li key={row.id}>{row.student?.user.name ?? "Nama belum tersedia"}<span className="level-note"> | {level}</span></li>)}</ol> : <p>Belum ada murid hadir.</p>}</div>; })}</div></details>)}{weeks.size === 0 && <section className="empty-state"><strong>Belum ada data kehadiran</strong><span>Pilih bulan lain untuk melihat data.</span></section>}</section></main>;
}
