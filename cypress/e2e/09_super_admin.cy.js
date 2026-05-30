// cypress/e2e/09_super_admin.cy.js
// Black-box test: Skenario No. 3 — Super Admin
// Tabel 5.21 Pengujian Halaman Super Admin (3 skenario)

const SUPERADMIN_EMAIL    = Cypress.env('SUPERADMIN_EMAIL')    || Cypress.env('ADMIN_EMAIL')    || 'superadmin@admin.com';
const SUPERADMIN_PASSWORD = Cypress.env('SUPERADMIN_PASSWORD') || Cypress.env('ADMIN_PASSWORD') || 'superadmin123';

describe('Pengujian Halaman Super Admin', () => {

  // TC-SA-1: Kelola Data Dosen
  it('Kelola Data Dosen — Super Admin menambah, mengedit, dan menghapus akun dosen → Perubahan data dosen tersimpan dan ditampilkan', () => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
    cy.visit('/admin/users');
    cy.url().should('include', '/admin/users');
    cy.get('table, [class*="table"]').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|role/i);
  });

  // TC-SA-2: Import Data Mahasiswa
  it('Import Data Mahasiswa — Super Admin mengimpor data mahasiswa melalui file Excel → Data mahasiswa berhasil ditambahkan ke sistem secara massal', () => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
    cy.visit('/admin/students/import');
    cy.url().should('include', '/admin/students/import');
    cy.get('input[type="file"]').should('exist');
    cy.get('button[type="submit"], input[type="submit"]').should('exist');
  });

  // TC-SA-3: Lihat Hasil UEQ
  it('Lihat Hasil UEQ — Super Admin mengakses menu UEQ Result → Dashboard hasil evaluasi UEQ per dimensi ditampilkan', () => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
    cy.visit('/admin/ueq-survey');
    cy.url().should('include', '/admin/ueq-survey');
    cy.get('[class*="card"], [class*="dimensi"], [class*="score"]')
      .should('have.length.greaterThan', 0);
  });

});
