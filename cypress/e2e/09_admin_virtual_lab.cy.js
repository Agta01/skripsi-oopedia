// cypress/e2e/09_admin_virtual_lab.cy.js
// Black-box test: Kelola Tugas Virtual Lab (Admin)

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Admin - Kelola Tugas Virtual Lab', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/virtual-lab-tasks');
  });

  it('TC-ADM-VLT-01: Halaman daftar tugas lab berhasil dimuat', () => {
    cy.url().should('include', '/admin/virtual-lab-tasks');
    cy.get('h5').contains('Daftar Tugas Virtual Lab').should('be.visible');
  });

  it('TC-ADM-VLT-02: Terdapat stat card informasi tugas', () => {
    cy.get('.vlt-stats-row').should('be.visible');
    cy.get('.vlt-stat-card').should('have.length.at.least', 4);
    cy.contains('.vlt-stat-label', 'Total Tugas').should('exist');
  });

  it('TC-ADM-VLT-03: Terdapat tombol tambah tugas', () => {
    cy.get('a.vlt-btn-primary').contains('Tambah Tugas').should('be.visible');
  });

  it('TC-ADM-VLT-04: Filter materi tugas lab tersedia', () => {
    cy.get('select[name="material_id"]').should('exist');
  });

  it('TC-ADM-VLT-05: Tabel tugas lab ditampilkan dengan kolom yang sesuai', () => {
    cy.get('.vlt-table').should('exist');
    cy.get('.vlt-table th').should('contain.text', 'Judul Tugas');
    cy.get('.vlt-table th').should('contain.text', 'Deadline');
    cy.get('.vlt-table th').should('contain.text', 'Aksi');
  });

  it('TC-ADM-VLT-06: Tombol aksi (edit/hapus) tersedia pada baris data tugas', () => {
    // Memastikan setidaknya ada 1 baris tugas untuk dicek (jika tabel tidak kosong)
    cy.get('body').then($body => {
      if ($body.find('.vlt-table tbody tr:not(:has(.vlt-empty))').length > 0) {
        cy.get('.vlt-table tbody tr').first().within(() => {
          cy.get('a.vlt-btn-edit').should('exist');
          cy.get('button.vlt-btn-delete').should('exist');
        });
      } else {
        cy.log('Tabel tugas lab kosong, skip cek tombol aksi');
        cy.contains('Belum ada tugas').should('be.visible');
      }
    });
  });
});
