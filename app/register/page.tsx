export default function RegisterPage() {
  return (
    <main className="login-shell">
      <section className="login-panel register-panel">
        <div className="login-content">
          <p className="eyebrow">Pendaftaran</p>
          <h2>Buat akun murid</h2>
          <p className="intro">Daftarkan akun untuk mengikuti renungan sekolah minggu.</p>
          <p className="form-note">Username hanya dipakai untuk login, sedangkan nama lengkap tetap tampil di data murid.</p>
          <form className="login-form" action="/api/auth/register" method="post">
            <label htmlFor="name">Nama lengkap</label>
            <input id="name" name="name" required />

            <label htmlFor="username">Username</label>
            <input id="username" name="username" required />

            <label htmlFor="email">Email</label>
            <input id="email" name="email" type="email" required />

            <label htmlFor="studentId">ID Murid</label>
            <input id="studentId" name="studentId" required />

            <label htmlFor="classLabel">Kelas</label>
            <select id="classLabel" name="classLabel" defaultValue="Kecil" required>
              <option value="Kecil">Kelas Kecil</option>
              <option value="Sedang">Kelas Sedang</option>
              <option value="Remaja">Kelas Remaja</option>
            </select>

            <label htmlFor="password">Password</label>
            <input id="password" name="password" type="password" minLength={8} required />

            <button type="submit">Daftar</button>
          </form>
        </div>
      </section>
    </main>
  );
}
