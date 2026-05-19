// cypress/e2e/07_admin_materials.cy.js
// Black-box test: Kelola Materi (Admin)

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Admin - Kelola Materi', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/materials');
  });

  it('TC-ADM-MAT-01: Halaman daftar materi berhasil dimuat', () => {
    cy.url().should('include', '/admin/materials');
    cy.get('h6').contains('Daftar Materi').should('be.visible');
  });

  it('TC-ADM-MAT-02: Terdapat tombol tambah materi', () => {
    cy.get('a.modern-btn-add').contains('Tambah Materi').should('be.visible');
  });

  it('TC-ADM-MAT-03: Pencarian materi dapat menerima input', () => {
    cy.get('input[name="search"]').type('Materi Test');
    cy.get('input[name="search"]').should('have.value', 'Materi Test');
    // Batal cari untuk test selanjutnya
    cy.get('input[name="search"]').clear();
  });

  it('TC-ADM-MAT-04: Tabel materi ditampilkan dengan kolom yang sesuai', () => {
    cy.get('.modern-table').should('exist');
    cy.get('.modern-table th').should('contain.text', 'Materi');
    cy.get('.modern-table th').should('contain.text', 'Aksi');
  });

  it('TC-ADM-MAT-05: Tombol aksi (edit/hapus) tersedia pada baris data materi', () => {
    // Memastikan setidaknya ada 1 baris materi untuk dicek (jika tabel tidak kosong)
    cy.get('body').then($body => {
      if ($body.find('.modern-table tbody tr.modern-row').length > 0) {
        cy.get('.modern-table tbody tr.modern-row').first().within(() => {
          cy.get('a.modern-action-btn[title="Edit Materi"]').should('exist');
          cy.get('button.btn-delete[title="Hapus Materi"]').should('exist');
        });
      } else {
        cy.log('Tabel materi kosong, skip cek tombol aksi');
        cy.contains('Belum ada materi').should('be.visible');
      }
    });
  });
});
