import { NextResponse } from "next/server";
import { hash } from "bcryptjs";
import { prisma } from "@/lib/prisma";
import { getCurrentUser } from "@/lib/auth";
import { canManageWeeklyData, isAdmin } from "@/lib/permissions";
import { getNextUserId } from "@/lib/ids";

const definitions = {
  roles: { model: "role", fields: ["name", "description"] },
  users: { model: "user", fields: ["name", "username", "email", "password", "role"] },
  students: { model: "student", fields: ["name", "birthDate", "phone", "parentName", "parentPhone", "classLabel"] },
  reflections: { model: "reflection", fields: ["createdById", "title", "content", "date", "image", "bibleVerse", "active"] },
  "reading-tracks": { model: "readingTrack", fields: ["studentId", "reflectionId", "readAt", "duration", "completed"] },
  attendances: { model: "attendance", fields: ["studentId", "recordedById", "date", "status", "classLabel", "presentCount", "totalCount"] },
  finances: { model: "finance", fields: ["studentId", "recordedById", "type", "description", "amount", "date", "category", "notes"] },
  activities: { model: "activity", fields: ["createdById", "title", "date", "startTime", "endTime", "location", "category", "description"] },
} as const;

type ModuleName = keyof typeof definitions;

function canAccessModule(userRole: string | undefined, moduleName: string) {
  const roleName = userRole ?? "";
  if (isAdmin(roleName)) return true;
  if (canManageWeeklyData(roleName)) return ["reflections", "activities"].includes(moduleName);
  return false;
}

function parseValue(field: string, value: FormDataEntryValue) {
  const text = String(value);
  if (["roleId", "userId", "createdById", "studentId", "reflectionId", "recordedById", "duration", "presentCount", "totalCount"].includes(field)) return Number(text);
  if (["active", "completed"].includes(field)) return text === "true";
  if (["date", "birthDate", "readAt"].includes(field)) return new Date(text);
  if (field === "amount") return Number(text);
  return text;
}

export async function GET(_request: Request, context: { params: Promise<{ module: string }> }) {
  const user = await getCurrentUser();
  const { module } = await context.params;
  if (!user || !canAccessModule(user.role?.name, module)) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  if (!(module in definitions)) return NextResponse.json({ error: "Unknown module" }, { status: 404 });
  if (module === "attendances" || module === "reading-tracks") return NextResponse.json({ error: "Modul ini hanya untuk melihat laporan" }, { status: 405 });
  const definition = definitions[module as ModuleName];
  const records = await (prisma as unknown as Record<string, { findMany: (args: object) => Promise<unknown> }>)[definition.model].findMany({ take: 100, orderBy: { id: "desc" } });
  return NextResponse.json({ records, fields: definition.fields });
}

export async function POST(request: Request, context: { params: Promise<{ module: string }> }) {
  const user = await getCurrentUser();
  const { module } = await context.params;
  if (!user || !canAccessModule(user.role?.name, module)) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  if (!(module in definitions)) return NextResponse.json({ error: "Unknown module" }, { status: 404 });
  if (module === "attendances" || module === "reading-tracks") return NextResponse.json({ error: "Modul ini hanya untuk melihat laporan" }, { status: 405 });
  const definition = definitions[module as ModuleName];
  const formData = await request.formData();
  if (formData.get("_method") === "PUT") {
    const idValue = formData.get("id") ?? new URL(request.url).searchParams.get("id");
    const id = Number(idValue);
    if (!Number.isInteger(id)) return NextResponse.json({ error: "Invalid id" }, { status: 400 });
    const data: Record<string, unknown> = {};
    for (const field of definition.fields) { const value = formData.get(field); if (value !== null && value !== "") data[field] = parseValue(field, value); }
    if (typeof data.password === "string") { const { hash } = await import("bcryptjs"); data.password = await hash(data.password, 12); }
    if (module === "users" && typeof data.username === "string") data.username = data.username.trim().toLowerCase();
    if (module === "users" && typeof data.role === "string") {
      const role = await prisma.role.findUnique({ where: { name: data.role } });
      if (!role) return NextResponse.json({ error: "Role tidak valid" }, { status: 422 });
      data.roleId = role.id;
      delete data.role;
    }
    await (prisma as unknown as Record<string, { update: (args: object) => Promise<unknown> }>)[definition.model].update({ where: { id }, data });
    const redirectTarget = module === "reflections" ? "/teacher/reflections" : `/admin/${module}`;
    return NextResponse.redirect(new URL(redirectTarget, request.url), 303);
  }
  if (formData.get("_method") === "DELETE") {
    const idValue = formData.get("id") ?? new URL(request.url).searchParams.get("id");
    const id = Number(idValue);
    if (!Number.isInteger(id)) return NextResponse.json({ error: "Invalid id" }, { status: 400 });
    await (prisma as unknown as Record<string, { delete: (args: object) => Promise<unknown> }>)[definition.model].delete({ where: { id } });
    const redirectTarget = module === "reflections" ? "/teacher/reflections" : `/admin/${module}`;
    return NextResponse.redirect(new URL(redirectTarget, request.url), 303);
  }
  const data: Record<string, unknown> = {};
  for (const field of definition.fields) {
    const value = formData.get(field);
    if (value !== null && value !== "") data[field] = parseValue(field, value);
  }
  if (module === "users" && typeof data.password === "string") {
    const { hash } = await import("bcryptjs");
    data.password = await hash(data.password, 12);
  }
  if (module === "roles") {
    const roles = await prisma.role.findMany({ select: { id: true }, orderBy: { id: "asc" } });
    const occupiedIds = new Set(roles.map((role) => role.id));
    let nextId = 1;
    while (occupiedIds.has(nextId)) nextId += 1;
    data.id = nextId;
  }
  if (module === "users") {
    if (typeof data.username !== "string" || !data.username.trim() || typeof data.email !== "string" || !data.email.trim() || typeof data.password !== "string" || !data.password || typeof data.role !== "string" || !data.role) {
      return NextResponse.json({ error: "Nama pengguna, email, password, dan role wajib diisi" }, { status: 422 });
    }
    data.username = data.username.trim().toLowerCase();
    data.id = await getNextUserId();
  }
  if (module === "reflections") data.createdById = user.id;
  if (module === "activities") data.createdById = user.id;
  if (module === "finances") data.recordedById = user.id;
  if (module === "users" && typeof data.role === "string") {
    const role = await prisma.role.findUnique({ where: { name: data.role } });
    if (!role) return NextResponse.json({ error: "Role tidak valid" }, { status: 422 });
    data.roleId = role.id;
    delete data.role;
    if (role.name === "guru") {
      data.teacherCode = `G-${new Date().getFullYear()}-${String(Date.now()).slice(-6)}`;
    }
  }
  if (module === "students") {
    const code = `M-${new Date().getFullYear()}-${String(Date.now()).slice(-6)}`;
    const muridRole = await prisma.role.findUnique({ where: { name: "murid" } });
    if (!muridRole) return NextResponse.json({ error: "Role murid belum tersedia" }, { status: 422 });
    await prisma.user.create({ data: { id: await getNextUserId(), name: String(data.name), email: `${code.toLowerCase()}@gkppd.local`, password: await hash(code, 12), roleId: muridRole.id, student: { create: { studentId: code, birthDate: data.birthDate as Date | undefined, phone: data.phone as string | undefined, parentName: data.parentName as string | undefined, parentPhone: data.parentPhone as string | undefined, classLabel: data.classLabel as string | undefined } } } });
    return NextResponse.redirect(new URL(`/admin/${module}?created=${code}`, request.url), 303);
  }
  await (prisma as unknown as Record<string, { create: (args: object) => Promise<unknown> }>)[definition.model].create({ data });
  return NextResponse.redirect(new URL(`/admin/${module}`, request.url), 303);
}

export async function DELETE(request: Request, context: { params: Promise<{ module: string }> }) {
  const user = await getCurrentUser();
  const { module } = await context.params;
  if (!user || !canAccessModule(user.role?.name, module)) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  if (!(module in definitions)) return NextResponse.json({ error: "Unknown module" }, { status: 404 });
  if (module === "attendances" || module === "reading-tracks") return NextResponse.json({ error: "Modul ini hanya untuk melihat laporan" }, { status: 405 });
  const id = Number(new URL(request.url).searchParams.get("id"));
  if (!Number.isInteger(id)) return NextResponse.json({ error: "Invalid id" }, { status: 400 });
  const definition = definitions[module as ModuleName];
  await (prisma as unknown as Record<string, { delete: (args: object) => Promise<unknown> }>)[definition.model].delete({ where: { id } });
  return NextResponse.json({ ok: true });
}

export async function PUT(request: Request, context: { params: Promise<{ module: string }> }) {
  const user = await getCurrentUser();
  const { module } = await context.params;
  if (!user || !canAccessModule(user.role?.name, module)) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  if (!(module in definitions)) return NextResponse.json({ error: "Unknown module" }, { status: 404 });
  if (module === "attendances" || module === "reading-tracks") return NextResponse.json({ error: "Modul ini hanya untuk melihat laporan" }, { status: 405 });
  const id = Number(new URL(request.url).searchParams.get("id"));
  if (!Number.isInteger(id)) return NextResponse.json({ error: "Invalid id" }, { status: 400 });
  const formData = await request.formData();
  const data: Record<string, unknown> = {};
  for (const field of definitions[module as ModuleName].fields) {
    const value = formData.get(field);
    if (value !== null && value !== "") data[field] = parseValue(field, value);
  }
  if (typeof data.password === "string") {
    const { hash } = await import("bcryptjs");
    data.password = await hash(data.password, 12);
  }
  if (module === "users" && typeof data.role === "string") {
    const role = await prisma.role.findUnique({ where: { name: data.role } });
    if (!role) return NextResponse.json({ error: "Role tidak valid" }, { status: 422 });
    data.roleId = role.id;
    delete data.role;
  }
  if (module === "students") {
    const studentData = { birthDate: data.birthDate, phone: data.phone, parentName: data.parentName, parentPhone: data.parentPhone, classLabel: data.classLabel };
    const student = await prisma.student.update({ where: { id }, data: studentData as Parameters<typeof prisma.student.update>[0]["data"] });
    if (data.name) await prisma.user.update({ where: { id: student.userId }, data: { name: String(data.name) } });
    return NextResponse.redirect(new URL(`/admin/${module}`, request.url), 303);
  }
  delete data.role;
  const definition = definitions[module as ModuleName];
  const record = await (prisma as unknown as Record<string, { update: (args: object) => Promise<unknown> }>)[definition.model].update({ where: { id }, data });
  return NextResponse.json(record);
}
