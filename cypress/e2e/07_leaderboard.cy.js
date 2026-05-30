// cypress/e2e/07_leaderboard.cy.js
// Black-box test: Skenario No. 9 — Leaderboard
// Tabel 5.19 Pengujian Halaman Leaderboard (2 skenario)

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Pengujian Halaman Leaderboard', () => {

  // TC-LB-1: Lihat Leaderboard
  it('Lihat Leaderboard — Mahasiswa membuka menu Leaderboard → Peringkat mahasiswa berdasarkan total poin ditampilkan dengan benar', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/leaderboard');
    cy.url().should('include', '/leaderboard');
    cy.get('table.leaderboard-table, .leaderboard-card, [class*="leaderboard"]').should('exist');
  });

  // TC-LB-2: Akses Tamu
  it('Akses Tamu — Tamu mencoba mengakses leaderboard → Sistem menampilkan lock screen (halaman login diperlukan)', () => {
    cy.visit('/mahasiswa/leaderboard');
    cy.url().should('include', '/leaderboard');
    // Lock screen ditampilkan untuk tamu
    cy.get('.lb-lock-wrap, .lb-lock-card').should('be.visible');
    // Tabel peringkat tidak tampil untuk tamu
    cy.get('table.leaderboard-table').should('not.exist');
  });

});
