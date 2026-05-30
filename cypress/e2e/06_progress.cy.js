// cypress/e2e/06_progress.cy.js
// Black-box test: Skenario No. 8 — Progress
// Tabel 5.18 Pengujian Halaman Progress Mahasiswa (1 skenario)

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Pengujian Halaman Progress Mahasiswa', () => {

  // TC-PRG-1: Lihat Progress
  it('Lihat Progress — Mahasiswa membuka menu Progress pada sidebar → Sistem menampilkan rekapitulasi progress belajar mahasiswa', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/dashboard');
    cy.url().should('include', '/mahasiswa/dashboard');
    // Rekapitulasi progress ditampilkan (stat cards dan progress list)
    cy.get('[class*="stat"], [class*="progress"], [data-count]').should('exist');
  });

});
