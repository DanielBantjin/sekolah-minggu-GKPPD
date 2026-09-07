import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import { getCurrentUser } from "@/lib/auth";

export async function POST(request: Request) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "guru"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const formData = await request.formData();
  const methodOverride = String(formData.get("_method") ?? "");
  if (methodOverride === "PUT") {
    return PUT(request);
  }
  if (methodOverride === "DELETE") {
    return DELETE(request);
  }

  const dateValue = String(formData.get("date") ?? "");
  const date = new Date(`${dateValue}T00:00:00`);
  if (!dateValue || Number.isNaN(date.getTime())) {
    return NextResponse.json({ error: "Tanggal kehadiran tidak valid" }, { status: 422 });
  }

  const students = await prisma.student.findMany({ select: { id: true, classLabel: true } });
  await prisma.$transaction(async (transaction) => {
    for (const student of students) {
      const status = formData.get(`student-${student.id}`) === "hadir" ? "hadir" : "tidak hadir";
      const existing = await transaction.attendance.findFirst({ where: { studentId: student.id, date } });
      if (existing) {
        await transaction.attendance.update({
          where: { id: existing.id },
          data: { status, classLabel: student.classLabel, recordedById: user.id },
        });
      } else {
        await transaction.attendance.create({
          data: { studentId: student.id, date, status, classLabel: student.classLabel, recordedById: user.id },
        });
      }
    }
  });

  return NextResponse.redirect(new URL(`/teacher/attendance?date=${dateValue}&saved=1`, request.url), 303);
}

export async function PUT(request: Request) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "guru"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const formData = await request.formData();
  const id = Number(formData.get("id") ?? 0);
  const status = String(formData.get("status") ?? "tidak hadir");

  if (!Number.isInteger(id)) {
    return NextResponse.json({ error: "ID absensi tidak valid" }, { status: 400 });
  }

  const existing = await prisma.attendance.findUnique({ where: { id } });
  if (!existing) {
    return NextResponse.json({ error: "Data absensi tidak ditemukan" }, { status: 404 });
  }

  await prisma.attendance.update({
    where: { id },
    data: {
      status,
      recordedById: user.id,
      classLabel: existing.classLabel,
    },
  });

  const redirectDate = existing.date.toISOString().slice(0, 10);
  return NextResponse.redirect(new URL(`/teacher/attendance?date=${redirectDate}&saved=1`, request.url), 303);
}

export async function DELETE(request: Request) {
  const user = await getCurrentUser();
  const allowedRoles = ["admin", "guru"];
  if (!user || !allowedRoles.includes(user.role?.name ?? "")) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const formData = await request.formData();
  const id = Number(formData.get("id") ?? 0);
  if (!Number.isInteger(id)) {
    return NextResponse.json({ error: "ID absensi tidak valid" }, { status: 400 });
  }

  const existing = await prisma.attendance.findUnique({ where: { id } });
  if (!existing) {
    return NextResponse.json({ error: "Data absensi tidak ditemukan" }, { status: 404 });
  }

  await prisma.attendance.delete({ where: { id } });
  const redirectDate = existing.date.toISOString().slice(0, 10);
  return NextResponse.redirect(new URL(`/teacher/attendance?date=${redirectDate}&saved=1`, request.url), 303);
}
