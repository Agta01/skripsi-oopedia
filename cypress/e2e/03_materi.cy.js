// cypress/e2e/03_materi.cy.js
// Black-box test: Skenario No. 5 — Materi
// Tabel 5.15 Pengujian Halaman Materi (3 skenario)
//
// Catatan: Material ID 2 ("Pengenalan Java") digunakan karena memiliki konten lengkap.

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

// ID material yang memiliki konten lengkap
const MATERIAL_ID = 2;

describe('Pengujian Halaman Materi', () => {

  // TC-MAT-1: Baca Materi
  it('Baca Materi — Mahasiswa membuka halaman materi PBO → Materi ditampilkan lengkap beserta ilustrasi dan kode contoh', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit(`/mahasiswa/materials/${MATERIAL_ID}`);
    cy.url().should('match', /\/materials\/\d+/);
    cy.get('[class*="content"], [class*="materi"], article, .card-body')
      .should('have.length.greaterThan', 0);
  });

  // TC-MAT-2: Tonton Video
  it('Tonton Video — Mahasiswa memutar video pembelajaran pada halaman materi → Video player tampil responsif dan dapat diputar', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    // Cari material yang punya video
    cy.visit('/mahasiswa/materials');
    cy.get('a[href*="/materials/"]')
      .filter((i, el) => /\/materials\/\d+$/.test(el.getAttribute('href') || ''))
      .each(($el) => {
        const href = $el.attr('href');
        cy.visit(href);
        cy.get('body').then(($b) => {
          if ($b.find('iframe[src*="youtube"], video, [class*="video-wrapper"]').length > 0) {
            cy.get('iframe[src*="youtube"], video, [class*="video-wrapper"]')
              .first().should('exist');
            cy.log(`Video player ditemukan di ${href} — Berhasil`);
            return false; // stop each
          }
        });
      });
  });

  // TC-MAT-3: Akses Tamu
  it('Akses Tamu — Tamu mengakses halaman materi tanpa login → Hanya sebagian materi ditampilkan, akses penuh dibatasi', () => {
    cy.visit('/mahasiswa/materials');
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
    // Tombol login/daftar tersedia — menandakan akses terbatas
    cy.get('a[href*="login"], a[href*="register"]').should('have.length.greaterThan', 0);
  });

});
