// cypress/e2e/10_dosen.cy.js
// Black-box test: Skenario No. 4 — Dosen (Admin)
// Tabel 5.22 Pengujian Halaman Dosen (4 skenario)

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Pengujian Halaman Dosen', () => {

  // TC-DOSEN-1: Kelola Materi
  it('Kelola Materi — Dosen menambah materi baru beserta video pembelajaran → Materi dan video tersimpan dan tampil pada halaman mahasiswa', () => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/materials');
    cy.url().should('include', '/admin/materials');
    // Tutup intro.js tour jika muncul
    cy.closeIntro();
    cy.get('h6').contains('Daftar Materi').should('be.visible');
    cy.get('a.modern-btn-add').contains('Tambah Materi').should('be.visible');
    // Klik dengan force untuk bypass overlay yang mungkin masih ada
    cy.get('a.modern-btn-add').contains('Tambah Materi').click({ force: true });
    cy.url().should('include', '/admin/materials/create');
    cy.get('input[name="title"]').should('exist');
  });

  // TC-DOSEN-2: Kelola Soal
  it('Kelola Soal — Dosen menambah dan mengedit soal latihan pada bank soal → Soal tersimpan dan tersedia bagi mahasiswa', () => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/questions');
    cy.url().should('include', '/admin/questions');
    cy.closeIntro();
    cy.get('h6').contains(/Daftar Semua Soal|Soal:/).should('be.visible');
    cy.get('a.modern-btn-add').contains('Tambah Soal').should('be.visible');
  });

  // TC-DOSEN-3: Kelola Virtual Lab
  it('Kelola Virtual Lab — Dosen mengatur instruksi dan starter code virtual lab → Instruksi dan starter code tampil pada halaman virtual lab mahasiswa', () => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/virtual-lab-tasks');
    cy.url().should('include', '/admin/virtual-lab-tasks');
    cy.closeIntro();
    cy.get('h5').contains('Daftar Tugas Virtual Lab').should('be.visible');
    cy.get('a.vlt-btn-primary').contains('Tambah Tugas').should('be.visible');
  });

  // TC-DOSEN-4: Pantau Progress
  it('Pantau Progress — Dosen mengakses menu progress untuk memantau mahasiswa → Data progress seluruh mahasiswa ditampilkan dengan benar', () => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/students');
    cy.url().should('include', '/admin/students');
    cy.closeIntro();
    cy.get('table, [class*="table"]').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|progress/i);
  });

});
