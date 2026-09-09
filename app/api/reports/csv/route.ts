import { NextResponse } from "next/server";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { hasAdminAccess } from "@/lib/permissions";

function csvCell(value: unknown) { return `"${String(value ?? "").replaceAll('"', '""')}"`; }

export async function GET() {
  const user = await getCurrentUser();
  if (!user || !hasAdminAccess(user.role?.name)) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  const [attendance, finance] = await Promise.all([
    prisma.attendance.findMany({ include: { student: { include: { user: true } } }, orderBy: { date: "desc" }, take: 1000 }),
    prisma.finance.findMany({ orderBy: { date: "desc" }, take: 1000 }),
  ]);
  const rows = [
    ["LAPORAN KEHADIRAN DAN KEUANGAN"],
    [],
    ["KEHADIRAN"],
    ["Tanggal", "Murid", "Status", "Kelas", "Hadir", "Total"],
    ...attendance.map((item) => [item.date.toISOString().slice(0, 10), item.student?.user.name ?? "Kelas", item.status, item.classLabel ?? "", item.presentCount ?? "", item.totalCount ?? ""]),
    [],
    ["KEUANGAN"],
    ["Tanggal", "Tipe", "Deskripsi", "Jumlah", "Kategori", "Catatan"],
    ...finance.map((item) => [item.date.toISOString().slice(0, 10), item.type, item.description, item.amount.toString(), item.category ?? "", item.notes ?? ""]),
  ];
  const content = rows.map((row) => row.map(csvCell).join(",")).join("\r\n");
  return new NextResponse(`\uFEFF${content}`, { headers: { "Content-Type": "text/csv; charset=UTF-8", "Content-Disposition": "attachment; filename=laporan-gkppd.csv" } });
}
