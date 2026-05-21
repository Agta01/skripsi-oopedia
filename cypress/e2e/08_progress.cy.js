// cypress/e2e/08_progress.cy.js
// Black-box test: Skenario No. 8 — Progress
// Fungsi: Melihat perkembangan pengerjaan materi dan soal

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Progress - Dashboard Mahasiswa', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/dashboard');
  });

  it('TC-PRG-01: Halaman dashboard berhasil dimuat', () => {
    cy.url().should('include', '/mahasiswa/dashboard');
    cy.get('body').should('be.visible');
  });

  it('TC-PRG-02: Statistik "Soal Benar" ditampilkan di dashboard', () => {
    cy.contains('[class*="label"], [class*="stat"]', 'Soal Benar').should('be.visible');
  });

  it('TC-PRG-03: Statistik "Progress Soal" ditampilkan sebagai persentase', () => {
    cy.contains('[class*="label"], [class*="stat"]', 'Progress Soal').should('be.visible');
    cy.get('[data-count]').should('exist');
  });

  it('TC-PRG-04: Statistik "Total Materi" dan "Total Soal" ditampilkan', () => {
    cy.contains('[class*="label"], [class*="stat"]', 'Total Materi').should('be.visible');
    cy.contains('[class*="label"], [class*="stat"]', 'Total Soal').should('be.visible');
  });

  it('TC-PRG-05: Daftar progress materi per-topik ditampilkan', () => {
    cy.get('[class*="materi-list"], [class*="progress-list"], [class*="db-materi"]').should('exist');
  });
});

describe('Progress - Halaman Progress Mahasiswa', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
  });

  it('TC-PRG-06: Menu Progress tersedia di sidebar navigasi', () => {
    cy.visit('/mahasiswa/dashboard');
    cy.get('.sidenav, aside, [class*="sidebar"]').within(() => {
      cy.get('a[href*="progress"], a:contains("Progress")').should('exist');
    });
  });

  it('TC-PRG-07: Klik menu Progress menampilkan rekapitulasi progress belajar mahasiswa', () => {
    cy.visit('/mahasiswa/dashboard');
    cy.get('.sidenav a[href*="progress"], aside a[href*="progress"], [class*="sidebar"] a[href*="progress"]')
      .first()
      .click({ force: true });
    cy.get('body').should('be.visible');
    cy.url().should('not.include', '/login');
  });

  it('TC-PRG-08: Progress materi ditampilkan dengan persentase penyelesaian', () => {
    cy.visit('/mahasiswa/dashboard');
    // Progress bar per materi ada di dashboard
    cy.get('[class*="progress-bar"], [class*="progress"]').should('have.length.greaterThan', 0);
  });
});

describe('Progress - In-Progress dan Completed', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
  });

  it('TC-PRG-09: Halaman in-progress dapat diakses', () => {
    cy.visit('/mahasiswa/dashboard/in-progress');
    cy.get('body').should('be.visible');
    cy.url().should('include', '/in-progress');
  });

  it('TC-PRG-10: Halaman completed dapat diakses', () => {
    cy.visit('/mahasiswa/dashboard/completed');
    cy.get('body').should('be.visible');
    cy.url().should('include', '/completed');
  });
});
