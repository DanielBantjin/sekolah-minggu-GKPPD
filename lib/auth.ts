import { cookies } from "next/headers";
import { jwtVerify, SignJWT } from "jose";
import { prisma } from "@/lib/prisma";

const COOKIE_NAME = "gkppd_session";
const authSecret = process.env.AUTH_SECRET;
if (process.env.NODE_ENV === "production" && (!authSecret || authSecret.length < 32)) {
  throw new Error("AUTH_SECRET must be configured with at least 32 characters in production.");
}
const secret = new TextEncoder().encode(authSecret ?? "local-development-secret-change-before-production");
const SESSION_MAX_AGE = 60 * 60 * 24 * 7;

type SessionPayload = { userId: number };

export async function createSession(userId: number) {
  const token = await new SignJWT({ userId } satisfies SessionPayload)
    .setProtectedHeader({ alg: "HS256", typ: "JWT" })
    .setIssuer("gkppd-sekolah-minggu")
    .setAudience("gkppd-web")
    .setIssuedAt()
    .setExpirationTime(SESSION_MAX_AGE)
    .sign(secret);
  const cookieStore = await cookies();
  cookieStore.set(COOKIE_NAME, token, {
    httpOnly: true,
    sameSite: "strict",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: SESSION_MAX_AGE,
  });
}

export async function clearSession() {
  const cookieStore = await cookies();
  cookieStore.delete(COOKIE_NAME);
}

export async function getCurrentUser() {
  const token = (await cookies()).get(COOKIE_NAME)?.value;
  if (!token) return null;
  try {
    const { payload } = await jwtVerify(token, secret, {
      algorithms: ["HS256"],
      issuer: "gkppd-sekolah-minggu",
      audience: "gkppd-web",
    });
    const userId = Number(payload.userId);
    if (!Number.isInteger(userId)) return null;
    return prisma.user.findUnique({ where: { id: userId }, include: { role: true, student: true } });
  } catch {
    return null;
  }
}
