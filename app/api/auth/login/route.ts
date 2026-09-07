import { compare } from "bcryptjs";
import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import { createSession } from "@/lib/auth";

const attempts = new Map<string, { count: number; resetAt: number }>();
const WINDOW_MS = 15 * 60 * 1000;
const MAX_ATTEMPTS = 10;

export async function POST(request: Request) {
  const ip = request.headers.get("x-forwarded-for")?.split(",")[0]?.trim() ?? "unknown";
  const now = Date.now();
  const current = attempts.get(ip);
  if (current && current.resetAt > now && current.count >= MAX_ATTEMPTS) {
    return NextResponse.json({ error: "Too many login attempts. Try again later." }, {
      status: 429,
      headers: { "Retry-After": String(Math.ceil((current.resetAt - now) / 1000)) },
    });
  }

  const formData = await request.formData();
  const identifier = String(formData.get("identifier") ?? formData.get("email") ?? formData.get("username") ?? "").trim();
  const normalizedIdentifier = identifier.toLowerCase();
  const password = String(formData.get("password") ?? "");

  if (!identifier || identifier.length > 255 || !password || password.length > 200) {
    return NextResponse.redirect(new URL("/?error=invalid", request.url), 303);
  }

  const user = await prisma.user.findFirst({
    where: {
      OR: [
        { email: normalizedIdentifier },
        { username: normalizedIdentifier },
      ],
    },
  });

  if (!user || !(await compare(password, user.password))) {
    const next = current && current.resetAt > now ? { count: current.count + 1, resetAt: current.resetAt } : { count: 1, resetAt: now + WINDOW_MS };
    attempts.set(ip, next);
    return NextResponse.redirect(new URL("/?error=invalid", request.url), 303);
  }

  attempts.delete(ip);
  await createSession(user.id);
  return NextResponse.redirect(new URL("/dashboard", request.url), 303);
}
