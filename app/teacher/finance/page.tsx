import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { EditFormModal } from "../components/EditFormModal";

export default async function TeacherFinancePage({
  searchParams,
}: {
  searchParams: Promise<{ start?: string; end?: string }>;
}) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "bendahara"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) redirect("/dashboard");

  const params = await searchParams;
  const start = params.start ?? "";
  const end = params.end ?? "";

  const dateFilter = start || end
    ? {
        date: {
          ...(start ? { gte: new Date(`${start}T00:00:00`) } : {}),
          ...(end ? { lte: new Date(`${end}T23:59:59`) } : {}),
        },
      }
    : {};

  const [items, totals] = await Promise.all([
    prisma.finance.findMany({
      where: { recordedById: user.id, ...dateFilter },
      orderBy: { date: "desc" },
    }),
    prisma.finance.groupBy({
      by: ["type"],
      where: { recordedById: user.id, ...dateFilter },
      _sum: { amount: true },
    }),
  ]);

  const totalIncome = Number(totals.find((item) => item.type === "pemasukan")?._sum.amount ?? 0);
  const totalExpense = Number(totals.find((item) => item.type === "pengeluaran")?._sum.amount ?? 0);
  const totalCash = totalIncome - totalExpense;

  const exportUrl = `/api/teacher/finance${start || end ? `?start=${encodeURIComponent(start)}&end=${encodeURIComponent(end)}` : ""}`;

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">{user.role?.name === "bendahara" ? "Bendahara" : "Guru"}</p>
          <h1>Keuangan</h1>
          <p>Pencatatan pemasukan dan pengeluaran dengan ringkasan kas dan ekspor laporan.</p>
        </div>
        <div className="header-actions-row">
          <Link className="back-link" href="/dashboard">Beranda</Link>
          <a className="back-link" href={exportUrl}>Export CSV</a>
        </div>
      </header>

      <section className="teacher-form-panel">
        <h2>Tambah transaksi</h2>
        <form className="crud-form" action="/api/admin/finances" method="post">
          <label>Jenis<select name="type">
            <option value="pemasukan">Pemasukan</option>
            <option value="pengeluaran">Pengeluaran</option>
          </select></label>
          <label>Deskripsi<input name="description" required /></label>
          <label>Jumlah<input name="amount" type="number" min="0" required /></label>
          <label>Tanggal<input name="date" type="date" required /></label>
          <label>Kategori<input name="category" /></label>
          <label>Catatan<textarea name="notes" rows={4} /></label>
          <button type="submit">Simpan transaksi</button>
        </form>
      </section>

      <section className="finance-summary-grid">
        <div className="summary-stat-card">
          <span>Total kas</span>
          <strong className={totalCash >= 0 ? "income-text" : "expense-text"}>Rp {totalCash.toLocaleString("id-ID")}</strong>
        </div>
        <div className="summary-stat-card">
          <span>Pemasukan</span>
          <strong className="income-text">Rp {totalIncome.toLocaleString("id-ID")}</strong>
        </div>
        <div className="summary-stat-card">
          <span>Pengeluaran</span>
          <strong className="expense-text">Rp {totalExpense.toLocaleString("id-ID")}</strong>
        </div>
        <div className="summary-stat-card">
          <span>Transaksi</span>
          <strong>{items.length}</strong>
        </div>
      </section>

      <form className="filter-form attendance-filter" method="get">
        <label>Dari<input type="date" name="start" defaultValue={start} /></label>
        <label>Sampai<input type="date" name="end" defaultValue={end} /></label>
        <button type="submit">Tampilkan</button>
        <Link href="/teacher/finance">Reset</Link>
      </form>

      <section className="record-list reading-list">
        {items.length ? items.map((item) => (
          <article className="record-row finance-row" key={item.id}>
            <div className="finance-summary-info">
              <strong>{item.description}</strong>
              <small>{item.date.toLocaleDateString("id-ID")}</small>
              <span>{item.category ?? "Kategori umum"}</span>
            </div>

            <div className="record-actions finance-actions">
              <strong className={item.type === "pemasukan" ? "income-text" : "expense-text"}>
                Rp {Number(item.amount).toLocaleString("id-ID")}
              </strong>

              <EditFormModal title="Edit transaksi">
                <form className="crud-form small-form modal-form" action="/api/admin/finances" method="post">
                  <input type="hidden" name="_method" value="PUT" />
                  <input type="hidden" name="id" value={item.id} />
                  <label>Jenis<select name="type" defaultValue={item.type}>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                  </select></label>
                  <label>Deskripsi<input name="description" defaultValue={item.description} required /></label>
                  <label>Jumlah<input name="amount" type="number" min="0" defaultValue={Number(item.amount)} required /></label>
                  <label>Tanggal<input name="date" type="date" defaultValue={item.date.toISOString().slice(0, 10)} required /></label>
                  <label>Kategori<input name="category" defaultValue={item.category ?? ""} /></label>
                  <label>Catatan<textarea name="notes" rows={4} defaultValue={item.notes ?? ""} /></label>
                  <button type="submit">Perbarui</button>
                </form>
              </EditFormModal>

              <form action="/api/admin/finances" method="post">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="hidden" name="id" value={item.id} />
                <button type="submit" className="delete-button">Hapus</button>
              </form>
            </div>
          </article>
        )) : <p>Belum ada transaksi.</p>}
      </section>
    </main>
  );
}
