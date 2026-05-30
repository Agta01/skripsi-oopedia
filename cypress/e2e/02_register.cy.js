// cypress/e2e/02_register.cy.js
// Black-box test: Skenario No. 2 — Login (Register)
// Tabel 5.14 Pengujian Halaman Register (2 skenario)

describe('Pengujian Halaman Register', () => {

  // Pastikan tidak ada sesi aktif sebelum tiap test
  beforeEach(() => {
    cy.clearCookies();
    cy.clearLocalStorage();
  });

  // TC-REG-1: Register Mahasiswa
  it('Register Mahasiswa — Mengisi form pendaftaran mahasiswa dengan data valid → Akun langsung aktif dan bisa login', () => {
    const ts = Date.now();
    cy.visit('/register');
    // Nama hanya boleh huruf dan spasi (regex: ^[\p{L}'\s]+$)
    cy.get('input[name="name"]').type('Test Mahasiswa Baru');
    cy.get('input[name="email"]').type(`test.mhs.${ts}@test.com`);
    // Password min 8 karakter sesuai Rules\Password::defaults()
    cy.get('input[name="password"]').type('Password123!');
    cy.get('input[name="password_confirmation"]').type('Password123!');
    // Pastikan checkbox "Daftar sebagai Dosen" TIDAK dicentang (default mahasiswa)
    cy.get('#register_as_admin').then(($cb) => {
      if ($cb.is(':checked')) {
        cy.get('label[for="register_as_admin"]').click();
      }
    });
    cy.get('button[type="submit"]').click();
    // Mahasiswa langsung aktif — diarahkan ke dashboard mahasiswa
    cy.url().should('include', '/mahasiswa/dashboard');
  });

  // TC-REG-2: Register Dosen
  it('Register Dosen — Mengisi form pendaftaran dosen dengan data valid → Akun tersimpan dan menunggu persetujuan Super Admin', () => {
    const ts = Date.now();
    cy.visit('/register');
    // Nama hanya boleh huruf dan spasi (regex: ^[\p{L}'\s]+$)
    cy.get('input[name="name"]').type('Test Dosen Baru');
    cy.get('input[name="email"]').type(`test.dosen.${ts}@test.com`);
    cy.get('input[name="password"]').type('Password123!');
    cy.get('input[name="password_confirmation"]').type('Password123!');
    // Centang checkbox "Daftar sebagai Dosen"
    cy.get('label[for="register_as_admin"]').click();
    cy.get('#register_as_admin').should('be.checked');
    cy.get('button[type="submit"]').click();
    // Dosen menunggu persetujuan — diarahkan ke halaman pending-approval
    cy.url().should('include', '/admin/pending-approval');
  });

});
