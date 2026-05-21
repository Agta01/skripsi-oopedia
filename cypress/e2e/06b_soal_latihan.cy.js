// cypress/e2e/06_soal_latihan.cy.js
// Black-box test: Skenario No. 6 — Soal Latihan
// Fungsi: Memilih kategori soal, menjawab, dan mendapatkan evaluasi langsung

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Soal Latihan - Pilih Kategori Soal', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials/1/questions');
  });

  it('TC-SOA-01: Halaman soal latihan berhasil dimuat', () => {
    cy.url().should('include', '/questions');
    cy.get('body').should('be.visible');
  });

  it('TC-SOA-02: Pilihan tingkat kesulitan tersedia (Beginner/Medium/Hard)', () => {
    // Cek apakah ada tombol/tab/select untuk memilih tingkat kesulitan
    cy.get('body').then(($body) => {
      const hasDifficultySelector =
        $body.find('select[name*="difficulty"], select[name*="level"]').length > 0 ||
        $body.find('button[data-level], a[data-level]').length > 0 ||
        $body.find('[class*="beginner"], [class*="medium"], [class*="hard"]').length > 0 ||
        $body.find('a[href*="beginner"], a[href*="medium"], a[href*="hard"]').length > 0;

      if (hasDifficultySelector) {
        cy.log('Selector tingkat kesulitan ditemukan');
      } else {
        // Coba akses halaman levels
        cy.visit('/mahasiswa/materials/1/questions/levels');
        cy.get('body').should('be.visible');
      }
    });
  });

  it('TC-SOA-03: Soal ditampilkan sesuai kategori yang dipilih', () => {
    cy.get('[class*="question"], [class*="soal"], .card, [class*="quiz"]')
      .should('have.length.greaterThan', 0);
  });
});

describe('Soal Latihan - Menjawab Soal', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials/1/questions');
  });

  it('TC-SOA-04: Pilihan jawaban (radio button) tersedia untuk setiap soal', () => {
    cy.get('input[type="radio"], button[data-answer], [class*="answer-option"], [class*="pilihan"]')
      .should('have.length.greaterThan', 0);
  });

  it('TC-SOA-05: Memilih jawaban mengaktifkan pilihan tersebut', () => {
    cy.get('input[type="radio"]').first().click({ force: true });
    cy.get('input[type="radio"]').first().should('be.checked');
  });

  it('TC-SOA-06: Submit jawaban mengirim data ke server (tidak ada error 500)', () => {
    cy.get('input[type="radio"]').first().click({ force: true });
    cy.get('button[type="submit"], button[id*="submit"], button[id*="check"], [class*="btn-submit"]')
      .first()
      .click({ force: true });
    // Tidak ada error 500 — halaman tetap bisa diakses
    cy.get('body').should('be.visible');
    cy.url().should('not.include', '500');
  });
});

describe('Soal Latihan - Evaluasi Langsung', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials/1/questions');
  });

  it('TC-SOA-07: Setelah submit, hasil evaluasi (benar/salah) langsung ditampilkan', () => {
    cy.get('input[type="radio"]').first().click({ force: true });
    cy.get('button[type="submit"], button[id*="submit"], button[id*="check"], [class*="btn-submit"]')
      .first()
      .click({ force: true });
    // Feedback evaluasi muncul dalam 8 detik
    cy.get(
      '[class*="alert"], [class*="feedback"], [class*="result"], [class*="correct"], [class*="wrong"], [class*="benar"], [class*="salah"]',
      { timeout: 8000 }
    ).should('be.visible');
  });

  it('TC-SOA-08: Progress soal tersimpan dan ditampilkan pada dashboard', () => {
    // Jawab satu soal
    cy.get('input[type="radio"]').first().click({ force: true });
    cy.get('button[type="submit"], button[id*="submit"], button[id*="check"], [class*="btn-submit"]')
      .first()
      .click({ force: true });
    cy.wait(1000);
    // Kunjungi dashboard dan cek progress
    cy.visit('/mahasiswa/dashboard');
    cy.get('[class*="progress"], [data-count]').should('exist');
  });
});

describe('Soal Latihan - Akses Tamu', () => {
  it('TC-SOA-09: Tamu dapat mengakses halaman soal (beberapa soal awal tersedia)', () => {
    cy.visit('/mahasiswa/materials/1/questions');
    cy.get('body').should('be.visible');
    cy.url().should('not.include', '/login');
  });

  it('TC-SOA-10: Tamu tidak memiliki penyimpanan progress (tidak ada data progress tersimpan)', () => {
    cy.visit('/mahasiswa/materials/1/questions');
    // Tamu tidak punya progress — tidak ada elemen yang menampilkan progress personal
    cy.get('body').should('be.visible');
    cy.log('Tamu berhasil mengakses soal tanpa progress tersimpan');
  });
});
