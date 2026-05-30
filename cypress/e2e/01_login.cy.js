// cypress/e2e/01_login.cy.js
// Black-box test: Skenario No. 2 — Login
// Tabel 5.13 Pengujian Halaman Login (4 skenario)

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'andi@mahasiswa.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'mhs123';
const ADMIN_EMAIL        = Cypress.env('ADMIN_EMAIL')        || 'ahmad@dosen.com';
const ADMIN_PASSWORD     = Cypress.env('ADMIN_PASSWORD')     || 'dosen123';
const SUPERADMIN_EMAIL   = Cypress.env('SUPERADMIN_EMAIL')   || Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const SUPERADMIN_PASSWORD= Cypress.env('SUPERADMIN_PASSWORD')|| Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Pengujian Halaman Login', () => {

  // TC-LOGIN-1: Login Mahasiswa
  it('Login Mahasiswa — Input email dan password valid, klik tombol login → Berhasil masuk ke dashboard mahasiswa', () => {
    cy.visit('/login');
    cy.get('#email').type(MAHASISWA_EMAIL);
    cy.get('#password').type(MAHASISWA_PASSWORD);
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/mahasiswa/dashboard');
  });

  // TC-LOGIN-2: Login Dosen
  it('Login Dosen — Dosen yang telah disetujui Super Admin login dengan akun terdaftar → Berhasil masuk ke dashboard dosen', () => {
    cy.visit('/login');
    cy.get('#email').type(ADMIN_EMAIL);
    cy.get('#password').type(ADMIN_PASSWORD);
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  // TC-LOGIN-3: Login Super Admin
  it('Login Super Admin — Input akun Super Admin → Berhasil masuk ke dashboard Super Admin', () => {
    cy.visit('/login');
    cy.get('#email').type(SUPERADMIN_EMAIL);
    cy.get('#password').type(SUPERADMIN_PASSWORD);
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  // TC-LOGIN-4: Login Gagal
  it('Login Gagal — Mengisi email/password salah → Gagal login dan menampilkan pesan kesalahan', () => {
    cy.visit('/login');
    cy.get('#email').type('salah@email.com');
    cy.get('#password').type('passwordsalah123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/login');
    cy.get('.alert-danger').should('be.visible');
  });

});
