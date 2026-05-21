// cypress/e2e/06_access_control.cy.js
// Black-box test: Kontrol Akses (otorisasi / proteksi route)
// Melengkapi skenario dari file 02_tamu.cy.js dengan uji lintas-role

describe('Kontrol Akses - Pengunjung Tidak Login', () => {
  it('TC-ACC-01: Akses /mahasiswa/dashboard tanpa login diarahkan ke /login', () => {
    cy.visit('/mahasiswa/dashboard', { failOnStatusCode: false });
    cy.url().should('include', '/login');
  });

  it('TC-ACC-02: Akses /admin/dashboard tanpa login diarahkan ke /login', () => {
    cy.visit('/admin/dashboard', { failOnStatusCode: false });
    cy.url().should('include', '/login');
  });

  it('TC-ACC-03: Akses /mahasiswa/materials (halaman publik) berhasil tanpa login', () => {
    cy.visit('/mahasiswa/materials');
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
  });

  it('TC-ACC-04: Halaman login dapat diakses oleh pengunjung', () => {
    cy.visit('/login');
    cy.get('#email').should('be.visible');
    cy.get('#password').should('be.visible');
  });
});

describe('Kontrol Akses - Mahasiswa Tidak Bisa Akses Admin', () => {
  const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
  const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

  before(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
  });

  it('TC-ACC-05: Mahasiswa mencoba akses /admin/dashboard diarahkan atau ditolak', () => {
    cy.visit('/admin/dashboard', { failOnStatusCode: false });
    cy.url().should('not.include', '/admin/dashboard');
  });
});

describe('Kontrol Akses - Dosen Tidak Bisa Akses Fitur Super Admin', () => {
  const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
  const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

  before(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
  });

  it('TC-ACC-06: Dosen tidak dapat mengakses halaman manajemen user Super Admin', () => {
    // /admin/users hanya untuk superadmin (role 1)
    cy.visit('/admin/users', { failOnStatusCode: false });
    // Harus diarahkan ke dashboard atau ditolak — bukan halaman users
    cy.url().should('satisfy', (url) => {
      return !url.includes('/admin/users') || url.includes('/dashboard');
    });
  });
});
