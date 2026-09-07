import { NextResponse } from "next/server";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

function csvCell(value: unknown) {
  return `"${String(value ?? "").replaceAll('"', '""')}"`;
}

export async function GET(request: Request) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "bendahara"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const url = new URL(request.url);
  const start = url.searchParams.get("start") ?? "";
  const end = url.searchParams.get("end") ?? "";

  const dateRange = {
    ...(start ? { gte: new Date(`${start}T00:00:00`) } : {}),
    ...(end ? { lte: new Date(`${end}T23:59:59`) } : {}),
  };

  const items = await prisma.finance.findMany({
    where: {
      recordedById: user.id,
      ...(Object.keys(dateRange).length ? { date: dateRange } : {}),
    },
    orderBy: { date: "desc" },
  });

  const rows = [
    ["LAPORAN KEUANGAN GURU"],
    [],
    ["Tanggal", "Jenis", "Deskripsi", "Jumlah", "Kategori", "Catatan"],
    ...items.map((item) => [
      item.date.toISOString().slice(0, 10),
      item.type,
      item.description,
      String(item.amount),
      item.category ?? "",
      item.notes ?? "",
    ]),
  ];

  const content = rows.map((row) => row.map(csvCell).join(",")).join("\r\n");

  return new NextResponse(`\uFEFF${content}`, {
    headers: {
      "Content-Type": "text/csv; charset=UTF-8",
      "Content-Disposition": `attachment; filename=laporan-keuangan-${user.name.replace(/\s+/g, "-").toLowerCase()}.csv`,
    },
  });
}
