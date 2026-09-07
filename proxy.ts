import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";

const securityHeaders: Record<string, string> = {
  "Content-Security-Policy": "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; object-src 'none'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval'",
  "Referrer-Policy": "strict-origin-when-cross-origin",
  "X-Content-Type-Options": "nosniff",
  "X-Frame-Options": "DENY",
  "Permissions-Policy": "camera=(), microphone=(), geolocation=()",
  "Cross-Origin-Opener-Policy": "same-origin",
};

export function proxy(request: NextRequest) {
  const pathname = request.nextUrl.pathname;
  const session = request.cookies.get("gkppd_session")?.value;
  const isAuthRoute = pathname === "/api/auth/login" || pathname === "/api/auth/register" || pathname === "/api/auth/logout";
  const isProtectedPage = pathname === "/dashboard" || pathname.startsWith("/admin") || pathname.startsWith("/teacher") || pathname.startsWith("/student");
  const isProtectedApi = pathname.startsWith("/api/") && !isAuthRoute;

  if (!session && isProtectedPage) {
    const response = NextResponse.redirect(new URL("/", request.url));
    Object.entries(securityHeaders).forEach(([name, value]) => response.headers.set(name, value));
    return response;
  }
  if (!session && isProtectedApi) {
    return NextResponse.json({ error: "Authentication required" }, { status: 401, headers: securityHeaders });
  }

  if (request.method === "TRACE" || request.method === "CONNECT") {
    return new NextResponse(null, { status: 405, headers: securityHeaders });
  }

  if (request.nextUrl.pathname.startsWith("/api/") && ["POST", "PUT", "PATCH", "DELETE"].includes(request.method)) {
    const origin = request.headers.get("origin");
    if (origin && origin !== request.nextUrl.origin) {
      return NextResponse.json({ error: "Cross-site request blocked" }, { status: 403, headers: securityHeaders });
    }
  }

  const response = NextResponse.next();
  Object.entries(securityHeaders).forEach(([name, value]) => response.headers.set(name, value));
  if (process.env.NODE_ENV === "production") {
    response.headers.set("Strict-Transport-Security", "max-age=31536000; includeSubDomains");
  }
  return response;
}

export const config = {
  matcher: ["/((?!_next/static|_next/image|favicon.ico).*)"],
};
