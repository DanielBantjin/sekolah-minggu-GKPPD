import { redirect } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { EditFormModal } from "../components/EditFormModal";
import { ReflectionEditor } from "./ReflectionEditor";

export default async function TeacherReflectionsPage() {
  const user = await getCurrentUser();
  if (!user || user.role?.name !== "guru") redirect("/dashboard");

  const reflections = await prisma.reflection.findMany({
    where: { createdById: user.id },
    include: { creator: { select: { name: true } } },
    orderBy: { date: "desc" },
  });

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div>
          <p className="eyebrow">Guru</p>
          <h1>Kelola Renungan</h1>
          <p>Renungan dibuat dengan nama guru dari akun yang sedang aktif.</p>
        </div>
        <Link className="back-link" href="/dashboard">Beranda</Link>
      </header>

      <section className="teacher-form-panel">
        <h2>Tambah renungan</h2>
        <form className="crud-form" action="/api/admin/reflections" method="post">
          <label>Judul<input name="title" required /></label>
          <label>Tanggal<input name="date" type="date" required /></label>
          <label>Ayat Alkitab<input name="bibleVerse" /></label>
          <label>Aktif<select name="active" defaultValue="true">
            <option value="true">Aktif</option>
            <option value="false">Nonaktif</option>
          </select></label>
          <ReflectionEditor label="Konten renungan" />
          <button type="submit">Simpan renungan</button>
        </form>
      </section>

      <section className="record-list reading-list">
        {reflections.length ? reflections.map((reflection) => (
          <article className="record-row reflection-row" key={reflection.id}>
            <div className="reflection-summary">
              <strong>{reflection.title}</strong>
              <small>{reflection.date.toLocaleDateString("id-ID")}</small>
              <span>Oleh {reflection.creator.name}</span>
            </div>

            <div className="record-actions reflection-actions">
              <span className="status-text">{reflection.active ? "Aktif" : "Nonaktif"}</span>
              <EditFormModal title="Edit renungan">
                <form className="crud-form small-form modal-form" action="/api/admin/reflections" method="post">
                  <input type="hidden" name="_method" value="PUT" />
                  <input type="hidden" name="id" value={reflection.id} />
                  <label>Judul<input name="title" defaultValue={reflection.title} required /></label>
                  <label>Tanggal<input name="date" type="date" defaultValue={reflection.date.toISOString().slice(0, 10)} required /></label>
                  <label>Ayat Alkitab<input name="bibleVerse" defaultValue={reflection.bibleVerse ?? ""} /></label>
                  <label>Aktif<select name="active" defaultValue={reflection.active ? "true" : "false"}>
                    <option value="true">Aktif</option>
                    <option value="false">Nonaktif</option>
                  </select></label>
                  <ReflectionEditor label="Edit konten renungan" initialValue={reflection.content} />
                  <button type="submit">Perbarui</button>
                </form>
              </EditFormModal>

              <form action="/api/admin/reflections" method="post">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="hidden" name="id" value={reflection.id} />
                <button type="submit" className="delete-button">Hapus</button>
              </form>
            </div>
          </article>
        )) : <p>Belum ada renungan.</p>}
      </section>
    </main>
  );
}
