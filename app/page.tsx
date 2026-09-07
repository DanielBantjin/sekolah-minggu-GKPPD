export default async function Home({ searchParams }: { searchParams: Promise<{ error?: string }> }) {
  const params = await searchParams;
  return (
    <main className="login-shell">
      <section className="login-brand">
        <div className="brand-pattern" />
        <div className="brand-content">
          <div className="cross-mark" aria-hidden="true">+</div>
          <h1>Selamat Datang</h1>
          <p>Masuk ke sistem tracking renungan sekolah minggu dengan tampilan yang lebih modern dan nyaman.</p>
          <span className="brand-pill">GKPPD • Sekolah Minggu</span>
        </div>
      </section>

      <section className="login-panel" aria-labelledby="login-title">
        <div className="login-content">
          <p className="eyebrow">Login</p>
          <h2 id="login-title">Masuk ke akun Anda</h2>
          <p className="intro">Silakan gunakan username atau email dan password Anda untuk melanjutkan.</p>

          {params.error === "invalid" && <p className="form-error" role="alert">Username/email atau password salah.</p>}
          <form className="login-form" action="/api/auth/login" method="post">
            <label htmlFor="identifier">Username atau Email</label>
            <input
              id="identifier"
              name="identifier"
              type="text"
              autoComplete="username"
              placeholder="Masukkan username atau email"
              required
            />

            <label htmlFor="password">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="current-password"
              placeholder="Masukkan password"
              required
            />

              <button type="submit">Log in</button>
          </form>

          <p className="register-link">Belum punya akun? <a href="/register">Daftar sebagai murid</a></p>
        </div>
      </section>
    </main>
  );
}
