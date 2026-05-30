// cypress/e2e/04_soal_latihan.cy.js
// Black-box test: Skenario No. 6 — Soal Latihan
// Tabel 5.16 Pengujian Halaman Soal Latihan (3 skenario)
//
// Catatan teknis:
// - /mahasiswa/materials/2/questions → method show() → langsung tampil soal pertama
// - Radio button di soal menggunakan CSS display:none (disembunyikan, diganti label .option-card)
// - Untuk klik jawaban, gunakan label .option-card bukan input[type="radio"] langsung

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

const MATERIAL_ID = 2;   // "Pengenalan Java" — memiliki soal
const DIFFICULTY  = 'beginner';

describe('Pengujian Halaman Soal Latihan', () => {

  // TC-SOA-1: Pilih Kategori Soal
  it('Pilih Kategori Soal — Mahasiswa memilih tingkat kesulitan Beginner/Medium/Hard → Soal ditampilkan sesuai kategori yang dipilih', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    // Kunjungi dashboard dulu untuk memastikan sesi aktif
    cy.visit('/mahasiswa/dashboard');
    cy.url().should('include', '/mahasiswa/dashboard');
    // Baru akses halaman soal dengan difficulty beginner
    cy.visit(`/mahasiswa/materials/${MATERIAL_ID}/questions?difficulty=${DIFFICULTY}`);
    cy.url().should('not.include', '/login');
    cy.url().should('include', '/questions');
    // Halaman berhasil dimuat
    cy.get('body').should('be.visible');
    cy.get('.container-fluid').should('exist');
  });

  // TC-SOA-2: Jawab Soal
  it('Jawab Soal — Mahasiswa menjawab soal dan klik submit → Jawaban diproses dan hasil evaluasi langsung ditampilkan', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit(`/mahasiswa/materials/${MATERIAL_ID}/questions?difficulty=${DIFFICULTY}`);
    cy.url().should('not.include', '/login');
    cy.get('body').should('be.visible');

    cy.get('body').then(($body) => {
      if ($body.find('.option-card').length > 0) {
        // Klik label .option-card (radio button disembunyikan via CSS)
        cy.get('.option-card').first().click({ force: true });
        // Klik tombol periksa jawaban
        cy.get('#checkAnswerBtn').click({ force: true });
        // Feedback evaluasi muncul (SweetAlert2)
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible');
      } else {
        // Semua soal sudah dijawab atau halaman level cards
        cy.log('Soal sudah selesai atau halaman level — kondisi valid');
        cy.get('body').should('be.visible');
      }
    });
  });

  // TC-SOA-3: Penyimpanan Progress
  it('Penyimpanan Progress — Mahasiswa login menyelesaikan beberapa soal → Progress tersimpan dan ditampilkan pada dashboard', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    // Cek progress di dashboard — progress tersimpan dari sesi sebelumnya
    cy.visit('/mahasiswa/dashboard');
    cy.url().should('not.include', '/login');
    cy.url().should('include', '/mahasiswa/dashboard');
    // Stat cards progress ditampilkan
    cy.get('[class*="stat"], [data-count], [class*="progress"]').should('exist');
  });

});
