import { hash } from "bcryptjs";
import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import { createSession } from "@/lib/auth";

export async function POST(request: Request) {
  const formData = await request.formData();
  const name = String(formData.get("name") ?? "").trim();
  const username = String(formData.get("username") ?? "").trim().toLowerCase();
  const email = String(formData.get("email") ?? "").trim().toLowerCase();
  const studentId = String(formData.get("studentId") ?? "").trim();
  const classLabel = String(formData.get("classLabel") ?? "Kecil").trim();
  const password = String(formData.get("password") ?? "");

  if (!name || !username || !email || !studentId || !classLabel || password.length < 8) {
    return NextResponse.redirect(new URL("/register?error=invalid", request.url), 303);
  }

  const role = await prisma.role.findUnique({ where: { name: "murid" } });
  if (!role) return NextResponse.json({ error: "Student role is not configured" }, { status: 500 });

  try {
    const user = await prisma.user.create({
      data: {
        name,
        username,
        email,
        password: await hash(password, 12),
        roleId: role.id,
        student: { create: { studentId, classLabel } },
      },
    });
    await createSession(user.id);
    return NextResponse.redirect(new URL("/dashboard", request.url), 303);
  } catch {
    return NextResponse.redirect(new URL("/register?error=exists", request.url), 303);
  }
}
