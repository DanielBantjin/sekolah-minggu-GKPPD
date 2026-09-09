import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { isAdmin } from "@/lib/permissions";

export default async function AdminPage() {
  const user = await getCurrentUser();
  if (!user || !isAdmin(user.role?.name)) redirect("/dashboard");
  return <main className="dashboard-shell"><header className="dashboard-header"><div><p className="eyebrow">Panel Admin</p><h1>Dashboard Administrator</h1><p>Kelola seluruh data sekolah minggu dalam satu ruang kerja.</p></div>  <div className="report-actions"><Link className="back-link" href="/dashboard">Beranda</Link><Link className="back-link" href="/admin/reports">Laporan</Link></div></header><section className="module-grid">{["roles","users","students","reflections","reading-tracks","attendances","finances","activities"].map((module) => <Link className="module-card" href={`/admin/${module}`} key={module}><strong>{module.replaceAll("-", " ")}</strong><span>Kelola data</span></Link>)}</section></main>;
}
