import { NextResponse } from "next/server";
import { getCurrentUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const user = await getCurrentUser();
  if (!user?.student) return NextResponse.json({ error: "Student access required" }, { status: 403 });
  const contentType = request.headers.get("content-type") ?? "";
  const body = contentType.includes("application/json") ? await request.json() : Object.fromEntries((await request.formData()).entries());
  const reflectionId = Number(body.reflectionId);
  const duration = Math.max(0, Number(body.duration ?? 0));
  if (!Number.isInteger(reflectionId) || !Number.isFinite(duration)) return NextResponse.json({ error: "Invalid payload" }, { status: 400 });
  const reflection = await prisma.reflection.findFirst({ where: { id: reflectionId, active: true, date: { lte: new Date() } } });
  if (!reflection) return NextResponse.json({ error: "Reflection unavailable" }, { status: 404 });
  const day = new Date();
  day.setHours(0, 0, 0, 0);
  const track = await prisma.readingTrack.findFirst({ where: { studentId: user.student.id, reflectionId, readAt: { gte: day } }, orderBy: { id: "desc" } });
  const saved = track
    ? await prisma.readingTrack.update({ where: { id: track.id }, data: { duration: { increment: duration }, completed: Boolean(body.completed) || track.completed } })
    : await prisma.readingTrack.create({ data: { studentId: user.student.id, reflectionId, duration, completed: Boolean(body.completed) } });
  return contentType.includes("application/json") ? NextResponse.json(saved) : NextResponse.redirect(new URL("/student/reading?saved=1", request.url), 303);
}
