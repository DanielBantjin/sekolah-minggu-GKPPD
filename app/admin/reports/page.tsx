import Link from "next/link";
import { redirect } from "next/navigation";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export default async function ReportsPage() {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "admin") redirect("/dashboard");
  const [attendance, finance] = await Promise.all([
    prisma.attendance.findMany({ include: { student: { include: { user: true } } }, orderBy: { date: "desc" }, take: 100 }),
    prisma.finance.findMany({ orderBy: { date: "desc" }, take: 100 }),
  ]);
  return <main className="dashboard-shell"><header className="dashboard-header"><div><p className="eyebrow">Panel Admin</p><h1>Laporan</h1><p>Rekap kehadiran dan transaksi yang siap dicetak.</p></div><div className="report-actions"><Link className="back-link" href="/dashboard">Beranda</Link><Link className="back-link" href="/api/reports/csv">Unduh CSV</Link></div></header><section className="report-sheet"><h2>Laporan Kehadiran</h2><table><thead><tr><th>Tanggal</th><th>Murid/Kelas</th><th>Status</th><th>Jumlah</th></tr></thead><tbody>{attendance.map((item) => <tr key={item.id}><td>{item.date.toLocaleDateString("id-ID")}</td><td>{item.student?.user.name ?? item.classLabel ?? "Kelas"}</td><td>{item.status}</td><td>{item.presentCount ?? "-"}/{item.totalCount ?? "-"}</td></tr>)}</tbody></table><h2>Laporan Keuangan</h2><table><thead><tr><th>Tanggal</th><th>Tipe</th><th>Deskripsi</th><th>Jumlah</th></tr></thead><tbody>{finance.map((item) => <tr key={item.id}><td>{item.date.toLocaleDateString("id-ID")}</td><td>{item.type}</td><td>{item.description}</td><td>Rp {Number(item.amount).toLocaleString("id-ID")}</td></tr>)}</tbody></table></section></main>;
}
