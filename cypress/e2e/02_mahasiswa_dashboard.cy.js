// cypress/e2e/02_mahasiswa_dashboard.cy.js
// Black-box test: Dashboard Mahasiswa

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Dashboard Mahasiswa', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/dashboard');
  });

  it('TC-DASH-01: Halaman dashboard berhasil dimuat', () => {
    cy.url().should('include', '/mahasiswa/dashboard');
    cy.get('.db-wrap').should('be.visible');
  });

  it('TC-DASH-02: Statistik "Soal Benar" ditampilkan', () => {
    // Label "Soal Benar" ada di .db-stat-card__label
    cy.contains('.db-stat-card__label', 'Soal Benar').should('be.visible');

    // Elemen angka soal benar menggunakan class .db-stat-card__num dan data-count
    cy.get('.db-stat-card--green .db-stat-card__num')
      .should('exist')
      .invoke('attr', 'data-count')
      .then((val) => {
        cy.log(`Nilai Soal Benar (data-count): ${val}`);
        expect(parseInt(val)).to.be.a('number');
      });
  });

  it('TC-DASH-03: Progress Soal ditampilkan sebagai persentase', () => {
    cy.contains('.db-stat-card__label', 'Progress Soal').should('be.visible');
    cy.get('.db-stat-card--orange .db-stat-card__num')
      .should('exist')
      .invoke('attr', 'data-count')
      .then((val) => {
        cy.log(`Progress Soal: ${val}%`);
        expect(parseInt(val)).to.be.at.least(0);
      });
  });

  it('TC-DASH-04: Stat card "Total Materi" dan "Total Soal" ditampilkan', () => {
    cy.contains('.db-stat-card__label', 'Total Materi').should('be.visible');
    cy.contains('.db-stat-card__label', 'Total Soal').should('be.visible');
  });

  it('TC-DASH-05: Progress materi list ditampilkan', () => {
    cy.get('.db-materi-list').should('exist');
  });

  it('TC-DASH-06: Tombol profil dropdown bisa dibuka', () => {
    cy.get('#profileDropdown').click();
    cy.get('.dropdown-menu').should('be.visible');
  });

  it('TC-DASH-07: Logout dari dashboard berhasil', () => {
    cy.get('#profileDropdown').click();
    cy.get('form[action*="logout"] button[type="submit"]').click();
    cy.url().should('include', '/login');
  });
});
