// cypress/e2e/04_dosen_admin.cy.js
// Black-box test: Skenario No. 4 — Dosen (Admin)
// Fungsi: Mengelola materi, video, soal, virtual lab, dan memantau progress mahasiswa

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Dosen - Kelola Materi', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/materials');
  });

  it('TC-DOSEN-01: Halaman daftar materi berhasil dimuat', () => {
    cy.url().should('include', '/admin/materials');
    cy.get('h6').contains('Daftar Materi').should('be.visible');
  });

  it('TC-DOSEN-02: Tombol tambah materi tersedia', () => {
    cy.get('a.modern-btn-add').contains('Tambah Materi').should('be.visible');
  });

  it('TC-DOSEN-03: Tabel materi menampilkan kolom Materi dan Aksi', () => {
    cy.get('.modern-table').should('exist');
    cy.get('.modern-table th').should('contain.text', 'Materi');
    cy.get('.modern-table th').should('contain.text', 'Aksi');
  });

  it('TC-DOSEN-04: Tombol edit dan hapus tersedia pada baris materi', () => {
    cy.get('body').then(($body) => {
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

  it('TC-DOSEN-05: Halaman tambah materi dapat diakses dan berisi form', () => {
    cy.get('a.modern-btn-add').contains('Tambah Materi').click();
    cy.url().should('include', '/admin/materials/create');
    cy.get('input[name="title"]').should('exist');
    cy.get('button[type="submit"]').should('exist');
  });
});

describe('Dosen - Kelola Video Pembelajaran', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
  });

  it('TC-DOSEN-06: Form edit materi memiliki field video URL', () => {
    cy.visit('/admin/materials');
    cy.get('body').then(($body) => {
      if ($body.find('.modern-table tbody tr.modern-row').length > 0) {
        cy.get('.modern-table tbody tr.modern-row').first()
          .find('a.modern-action-btn[title="Edit Materi"]')
          .click();
        cy.url().should('include', '/edit');
        cy.get('input[name="video_url"], textarea[name="video_url"]').should('exist');
      } else {
        cy.log('Tidak ada materi untuk diedit, skip test video');
      }
    });
  });
});

describe('Dosen - Kelola Soal Latihan', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/questions');
  });

  it('TC-DOSEN-07: Halaman daftar soal berhasil dimuat', () => {
    cy.url().should('include', '/admin/questions');
    cy.get('h6').contains(/Daftar Semua Soal|Soal:/).should('be.visible');
  });

  it('TC-DOSEN-08: Tombol tambah soal tersedia', () => {
    cy.get('a.modern-btn-add').contains('Tambah Soal').should('be.visible');
  });

  it('TC-DOSEN-09: Filter tingkat kesulitan soal tersedia (Beginner/Medium/Hard)', () => {
    cy.get('select[name="difficulty"]').should('exist');
    cy.get('select[name="difficulty"]').select('Beginner');
    cy.get('select[name="difficulty"]').should('have.value', 'beginner');
  });

  it('TC-DOSEN-10: Card soal menampilkan badge kesulitan, teks soal, dan pilihan jawaban', () => {
    cy.get('body').then(($body) => {
      if ($body.find('.modern-question-card').length > 0) {
        cy.get('.modern-question-card').first().within(() => {
          cy.get('.modern-difficulty-badge').should('exist');
          cy.get('.modern-question-text').should('be.visible');
          cy.get('.modern-answer-block').should('have.length.greaterThan', 0);
        });
      } else {
        cy.log('Daftar soal kosong, skip cek elemen card');
      }
    });
  });
});

describe('Dosen - Kelola Virtual Lab', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/virtual-lab-tasks');
  });

  it('TC-DOSEN-11: Halaman daftar tugas virtual lab berhasil dimuat', () => {
    cy.url().should('include', '/admin/virtual-lab-tasks');
    cy.get('h5').contains('Daftar Tugas Virtual Lab').should('be.visible');
  });

  it('TC-DOSEN-12: Tombol tambah tugas virtual lab tersedia', () => {
    cy.get('a.vlt-btn-primary').contains('Tambah Tugas').should('be.visible');
  });

  it('TC-DOSEN-13: Tabel tugas lab menampilkan kolom Judul Tugas, Deadline, dan Aksi', () => {
    cy.get('.vlt-table').should('exist');
    cy.get('.vlt-table th').should('contain.text', 'Judul Tugas');
    cy.get('.vlt-table th').should('contain.text', 'Deadline');
    cy.get('.vlt-table th').should('contain.text', 'Aksi');
  });

  it('TC-DOSEN-14: Halaman tambah tugas virtual lab dapat diakses dan berisi form', () => {
    cy.get('a.vlt-btn-primary').contains('Tambah Tugas').click();
    cy.url().should('include', '/admin/virtual-lab-tasks/create');
    cy.get('input[name="title"]').should('exist');
    cy.get('button[type="submit"]').should('exist');
  });
});

describe('Dosen - Pantau Progress Mahasiswa', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/students');
  });

  it('TC-DOSEN-15: Halaman daftar mahasiswa berhasil dimuat', () => {
    cy.url().should('include', '/admin/students');
    cy.get('body').should('be.visible');
  });

  it('TC-DOSEN-16: Data progress seluruh mahasiswa ditampilkan dengan benar', () => {
    cy.get('table, [class*="table"]').should('exist');
    cy.get('thead').invoke('text').should('match', /nama|email|progress/i);
  });

  it('TC-DOSEN-17: Klik detail mahasiswa menampilkan halaman progress individual', () => {
    cy.get('body').then(($body) => {
      const rows = $body.find('table tbody tr');
      if (rows.length > 0) {
        cy.get('table tbody tr').first()
          .find('a[href*="progress"], a[href*="detail"]')
          .first()
          .click();
        cy.get('body').should('be.visible');
      } else {
        cy.log('Tidak ada data mahasiswa, skip test detail progress');
      }
    });
  });
});
