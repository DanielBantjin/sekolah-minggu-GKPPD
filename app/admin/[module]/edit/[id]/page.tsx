import { redirect, notFound } from "next/navigation";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { hasAdminAccess } from "@/lib/permissions";

const definitions = { roles: ["name", "description"], users: ["name", "username", "email", "password", "role"], students: ["name", "birthDate", "phone", "parentName", "parentPhone", "classLabel"], reflections: ["createdById", "title", "content", "date", "bibleVerse"], "reading-tracks": ["studentId", "reflectionId", "duration", "completed"], attendances: ["studentId", "date", "status", "classLabel", "presentCount", "totalCount"], finances: ["studentId", "type", "description", "amount", "date", "category", "notes"], activities: ["createdById", "title", "date", "startTime", "endTime", "location", "category", "description"] } as const;
type ModuleName = keyof typeof definitions;

export default async function EditModulePage({ params }: { params: Promise<{ module: string; id: string }> }) {
  const user = await getCurrentUser();
  const { module, id } = await params;
  if (!user || !hasAdminAccess(user.role?.name)) redirect("/dashboard");
  if (!(module in definitions)) notFound();
  if (module === "attendances" || module === "reading-tracks") notFound();
  const model = module.replace("-tracks", "Track").replace(/s$/, "").replace(/^./, (letter) => letter.toLowerCase());
  const record = await (prisma as unknown as Record<string, { findUnique: (args: object) => Promise<Record<string, unknown> | null> }>)[model]?.findUnique({ where: { id: Number(id) } });
  if (!record) notFound();
  const optional = ["description", "password", "bibleVerse", "classLabel", "parentName", "parentPhone", "category", "notes", "startTime", "endTime"];
  return <main className="dashboard-shell"><header className="dashboard-header"><div><p className="eyebrow">Panel Admin</p><h1>Edit {module.replaceAll("-", " ")}</h1></div><Link className="back-link" href={`/admin/${module}`}>Kembali</Link></header><section className="crud-panel"><form className="crud-form" action={`/api/admin/${module}?id=${id}`} method="post"><input type="hidden" name="_method" value="PUT" />{definitions[module as ModuleName].map((field) => <label className={field === "content" ? "wide-field" : ""} key={field}>{field === "role" ? "Peran" : field === "content" ? "Konten renungan" : field === "classLabel" ? "Kelas" : field}{field === "role" ? <select name={field} defaultValue={String(record[field] ?? "guru")}><option value="admin">Administrator</option><option value="guru">Guru</option><option value="sekretaris">Sekretaris</option><option value="bendahara">Bendahara</option><option value="murid">Murid</option></select> : field === "classLabel" ? <select name={field} defaultValue={String(record[field] ?? "Kecil")}><option value="Kecil">Kelas Kecil</option><option value="Sedang">Kelas Sedang</option><option value="Remaja">Kelas Remaja</option></select> : field === "content" || field === "description" || field === "notes" ? <textarea name={field} rows={field === "content" ? 14 : 4} defaultValue={String(record[field] ?? "")} required={!optional.includes(field)} /> : <input name={field} defaultValue={String(record[field] ?? "")} type={field === "password" ? "password" : field === "date" ? "date" : "text"} required={!optional.includes(field)} />}</label>)}<button type="submit">Simpan perubahan</button></form></section></main>;
}
