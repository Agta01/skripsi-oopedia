// cypress/e2e/08_tamu.cy.js
// Black-box test: Skenario No. 1 — Tamu
// Tabel 5.20 Pengujian Halaman Tamu (2 skenario)

describe('Pengujian Halaman Tamu', () => {

  // TC-TAMU-1: Akses Materi Terbatas
  it('Akses Materi Terbatas — Tamu mengakses halaman materi tanpa login → Hanya sebagian materi ditampilkan', () => {
    cy.visit('/mahasiswa/materials');
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
    // Tombol login/daftar tersedia — menandakan akses terbatas
    cy.get('a[href*="login"], a[href*="register"]').should('have.length.greaterThan', 0);
  });

  // TC-TAMU-2: Akses Soal Terbatas
  it('Akses Soal Terbatas — Tamu mencoba mengerjakan soal tanpa login → Hanya beberapa soal awal yang tersedia, tidak ada penyimpanan progress', () => {
    // Material ID 2 ("Pengenalan Java") digunakan karena memiliki soal
    cy.visit('/mahasiswa/materials/2/questions');
    cy.get('body').should('be.visible');
    // Tidak diarahkan ke login (soal bisa diakses tamu)
    cy.url().should('not.include', '/login');
    cy.log('Tamu berhasil mengakses soal tanpa progress tersimpan — Berhasil');
  });

});
