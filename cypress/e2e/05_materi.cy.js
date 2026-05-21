// cypress/e2e/05_materi.cy.js
// Black-box test: Skenario No. 5 — Materi
// Fungsi: Membaca materi PBO dan menonton video pembelajaran

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

describe('Materi - Akses dan Baca Materi PBO (Mahasiswa)', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials');
  });

  it('TC-MAT-01: Halaman daftar materi berhasil dimuat setelah login', () => {
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
  });

  it('TC-MAT-02: Setidaknya satu card materi ditampilkan', () => {
    cy.get('[class*="card"], [class*="materi"], [class*="material"]')
      .should('have.length.greaterThan', 0);
  });

  it('TC-MAT-03: Progress materi per-card terlihat setelah login', () => {
    cy.get('[class*="progress"], [class*="bar"]').should('have.length.greaterThan', 0);
  });

  it('TC-MAT-04: Search bar tersedia untuk mahasiswa yang sudah login', () => {
    cy.get('#navbarSearchInput, input[name="search"]').should('be.visible');
  });

  it('TC-MAT-05: Klik pada card materi menuju halaman detail materi', () => {
    cy.get('a[href*="/materials/"]')
      .filter((i, el) => /\/materials\/\d+$/.test(el.getAttribute('href') || ''))
      .first()
      .click();
    cy.url().should('match', /\/materials\/\d+/);
    cy.get('body').should('be.visible');
  });
});

describe('Materi - Baca Konten dan Tonton Video', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials/1');
  });

  it('TC-MAT-06: Halaman detail materi berhasil dimuat', () => {
    cy.url().should('match', /\/materials\/\d+/);
    cy.get('body').should('be.visible');
  });

  it('TC-MAT-07: Konten materi PBO ditampilkan lengkap beserta ilustrasi atau kode contoh', () => {
    // Konten materi ada di elemen dengan class content atau article
    cy.get('[class*="content"], [class*="materi"], article, .card-body')
      .should('have.length.greaterThan', 0);
  });

  it('TC-MAT-08: Video player tampil responsif jika materi memiliki video', () => {
    cy.get('body').then(($body) => {
      // Cek apakah ada video player (iframe YouTube atau elemen video)
      const hasVideo = $body.find('iframe[src*="youtube"], video, [class*="video"]').length > 0;
      if (hasVideo) {
        cy.get('iframe[src*="youtube"], video, [class*="video"]')
          .first()
          .should('be.visible');
        cy.log('Video player ditemukan dan terlihat');
      } else {
        cy.log('Materi ini tidak memiliki video — sesuai ekspektasi jika materi tanpa video');
      }
    });
  });

  it('TC-MAT-09: Navigasi sidebar materi tersedia dan statis di semua halaman', () => {
    cy.get('.sidenav, aside, nav[class*="side"], [class*="sidebar"]').should('be.visible');
  });
});

describe('Materi - Akses Tamu (Guest)', () => {
  it('TC-MAT-10: Tamu dapat mengakses halaman materi tanpa login', () => {
    cy.visit('/mahasiswa/materials');
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
  });

  it('TC-MAT-11: Tamu hanya melihat sebagian materi — tombol login/daftar tersedia', () => {
    cy.visit('/mahasiswa/materials');
    cy.get('a[href*="login"], a[href*="register"]').should('have.length.greaterThan', 0);
  });
});
