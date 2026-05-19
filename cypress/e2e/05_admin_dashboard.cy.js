// cypress/e2e/05_admin_dashboard.cy.js
// Black-box test: Admin Dashboard & TBUT

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Admin Dashboard', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/dashboard');
  });

  it('TC-ADM-01: Admin berhasil masuk ke halaman dashboard', () => {
    cy.url().should('include', '/admin/dashboard');
    cy.get('body').should('be.visible');
  });

  it('TC-ADM-02: Sidebar menu admin tersedia', () => {
    cy.get('.sidenav, aside, nav[class*="side"]').should('be.visible');
  });

  it('TC-ADM-03: Statistik mahasiswa ditampilkan', () => {
    cy.get('[class*="card"], [class*="stat"]').should('have.length.greaterThan', 0);
  });
});

describe('Admin - Halaman Rekap TBUT', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/tbut');
  });

  it('TC-TBUT-01: Halaman TBUT berhasil dimuat', () => {
    cy.url().should('include', '/admin/tbut');
    cy.get('body').should('be.visible');
  });

  it('TC-TBUT-02: Tabel rekap TBUT ditampilkan', () => {
    cy.get('table').should('exist');
    cy.get('thead').should('contain.text', 'Materi');
  });

  it('TC-TBUT-03: Klasifikasi kesulitan ditampilkan (badge/label)', () => {
    // Tabel setidaknya punya badge / span berwarna untuk level kesulitan
    cy.get('[class*="badge"], span[style*="background"]').should('have.length.greaterThan', 0);
  });
});

describe('Admin - Manajemen Mahasiswa', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/students');
  });

  it('TC-STU-01: Halaman daftar mahasiswa dimuat', () => {
    cy.url().should('include', '/admin/students');
    cy.get('body').should('be.visible');
  });

  it('TC-STU-02: Tabel mahasiswa berisi setidaknya header', () => {
    cy.get('table thead').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|progress/i);
  });
});
