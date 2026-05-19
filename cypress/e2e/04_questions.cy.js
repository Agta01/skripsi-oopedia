// cypress/e2e/04_questions.cy.js
// Black-box test: Latihan Soal

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Latihan Soal Mahasiswa', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    // Akses halaman soal materi pertama (ID 1 — sesuaikan jika perlu)
    cy.visit('/mahasiswa/materials/1/questions');
  });

  it('TC-SOA-01: Halaman soal berhasil dimuat', () => {
    cy.url().should('include', '/questions');
    cy.get('body').should('be.visible');
  });

  it('TC-SOA-02: Soal ditampilkan di halaman', () => {
    // Sesuaikan selector dengan elemen soal Anda
    cy.get('[class*="question"], [class*="soal"], .card').should('have.length.greaterThan', 0);
  });

  it('TC-SOA-03: Pilihan jawaban tersedia', () => {
    cy.get('input[type="radio"], button[data-answer], [class*="answer"]')
      .should('have.length.greaterThan', 0);
  });

  it('TC-SOA-04: Memilih jawaban dan submit menampilkan feedback', () => {
    // Pilih jawaban pertama yang tersedia
    cy.get('input[type="radio"]').first().click({ force: true });
    // Submit / cek jawaban
    cy.get('button[type="submit"], button[id*="submit"], button[id*="check"]')
      .first()
      .click({ force: true });
    // Pastikan ada feedback (benar/salah)
    cy.get('[class*="alert"], [class*="feedback"], [class*="result"]', { timeout: 8000 })
      .should('be.visible');
  });
});
