// cypress/e2e/03_super_admin.cy.js
// Black-box test: Skenario No. 3 — Super Admin
// Fungsi: Mengelola akun dosen dan mahasiswa, mengimpor data Excel

const SUPERADMIN_EMAIL    = Cypress.env('SUPERADMIN_EMAIL')    || Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const SUPERADMIN_PASSWORD = Cypress.env('SUPERADMIN_PASSWORD') || Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Super Admin - Dashboard', () => {
  beforeEach(() => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
    cy.visit('/admin/dashboard');
  });

  it('TC-SA-01: Super Admin berhasil masuk ke halaman dashboard admin', () => {
    cy.url().should('include', '/admin/dashboard');
    cy.get('body').should('be.visible');
  });

  it('TC-SA-02: Sidebar menu admin tersedia dan dapat dilihat', () => {
    cy.get('.sidenav, aside, nav[class*="side"], [class*="sidebar"]').should('be.visible');
  });
});

describe('Super Admin - Kelola Akun Dosen', () => {
  beforeEach(() => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
  });

  it('TC-SA-03: Halaman daftar pengguna (dosen) dapat diakses', () => {
    cy.visit('/admin/users');
    cy.url().should('include', '/admin/users');
    cy.get('body').should('be.visible');
  });

  it('TC-SA-04: Tabel daftar dosen menampilkan kolom nama dan email', () => {
    cy.visit('/admin/users');
    cy.get('table, [class*="table"]').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|role/i);
  });

  it('TC-SA-05: Tombol tambah/kelola dosen tersedia', () => {
    cy.visit('/admin/users');
    // Tombol tambah user atau link ke form create
    cy.get('a[href*="users/create"], a[href*="create"], button').should('exist');
  });

  it('TC-SA-06: Halaman pending approval dosen dapat diakses', () => {
    cy.visit('/admin/pending-admins');
    cy.url().should('include', '/admin/pending-admins');
    cy.get('body').should('be.visible');
  });
});

describe('Super Admin - Kelola Akun Mahasiswa', () => {
  beforeEach(() => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
    cy.visit('/admin/students');
  });

  it('TC-SA-07: Halaman daftar mahasiswa berhasil dimuat', () => {
    cy.url().should('include', '/admin/students');
    cy.get('body').should('be.visible');
  });

  it('TC-SA-08: Tabel mahasiswa menampilkan header kolom yang sesuai', () => {
    cy.get('table, [class*="table"]').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|progress/i);
  });

  it('TC-SA-09: Tombol hapus mahasiswa tersedia pada baris data', () => {
    cy.get('body').then(($body) => {
      if ($body.find('table tbody tr').length > 0) {
        cy.get('table tbody tr').first().within(() => {
          cy.get('button[class*="delete"], button[class*="hapus"], a[class*="delete"]').should('exist');
        });
      } else {
        cy.log('Tabel mahasiswa kosong, skip cek tombol hapus');
      }
    });
  });
});

describe('Super Admin - Import Data Mahasiswa via Excel', () => {
  beforeEach(() => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
  });

  it('TC-SA-10: Halaman import data mahasiswa dapat diakses', () => {
    cy.visit('/admin/students/import');
    cy.url().should('include', '/admin/students/import');
    cy.get('body').should('be.visible');
  });

  it('TC-SA-11: Form upload file Excel tersedia di halaman import', () => {
    cy.visit('/admin/students/import');
    cy.get('input[type="file"]').should('exist');
    cy.get('button[type="submit"], input[type="submit"]').should('exist');
  });

  it('TC-SA-12: Tombol download template Excel tersedia', () => {
    cy.visit('/admin/students/import');
    cy.get('a[href*="download-template"], a[href*="template"]').should('exist');
  });
});

describe('Super Admin - Lihat Hasil UEQ', () => {
  beforeEach(() => {
    cy.login(SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD);
  });

  it('TC-SA-13: Halaman hasil UEQ dapat diakses oleh Super Admin', () => {
    cy.visit('/admin/ueq-survey');
    cy.url().should('include', '/admin/ueq-survey');
    cy.get('body').should('be.visible');
  });

  it('TC-SA-14: Dashboard UEQ menampilkan skor per dimensi', () => {
    cy.visit('/admin/ueq-survey');
    // Pastikan ada elemen yang menampilkan dimensi UEQ
    cy.get('[class*="card"], [class*="dimensi"], [class*="score"]')
      .should('have.length.greaterThan', 0);
  });
});
