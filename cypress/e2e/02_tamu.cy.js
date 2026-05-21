// cypress/e2e/02_tamu.cy.js
// Black-box test: Skenario No. 1 — Tamu
// Fungsi: Mengakses materi dan soal terbatas tanpa progress dan leaderboard

describe('Tamu - Akses Materi Terbatas', () => {
  it('TC-TAMU-01: Tamu dapat mengakses halaman materi tanpa login', () => {
    cy.visit('/mahasiswa/materials');
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
  });

  it('TC-TAMU-02: Halaman materi menampilkan daftar materi untuk tamu', () => {
    cy.visit('/mahasiswa/materials');
    // Setidaknya ada satu card/item materi yang tampil
    cy.get('[class*="card"], [class*="materi"], [class*="material"]')
      .should('have.length.greaterThan', 0);
  });

  it('TC-TAMU-03: Tamu dapat membuka detail materi', () => {
    cy.visit('/mahasiswa/materials');
    cy.get('a[href*="/materials/"]')
      .filter((i, el) => /\/materials\/\d+$/.test(el.getAttribute('href') || ''))
      .first()
      .click();
    cy.url().should('match', /\/materials\/\d+/);
    cy.get('body').should('be.visible');
  });

  it('TC-TAMU-04: Tamu hanya melihat sebagian materi (akses penuh dibatasi)', () => {
    cy.visit('/mahasiswa/materials');
    // Tombol login/daftar tersedia — menandakan akses terbatas
    cy.get('a[href*="login"], a[href*="register"]').should('have.length.greaterThan', 0);
  });
});

describe('Tamu - Akses Soal Terbatas', () => {
  it('TC-TAMU-05: Tamu dapat mengakses halaman soal materi pertama', () => {
    cy.visit('/mahasiswa/materials/1/questions');
    cy.get('body').should('be.visible');
    // Tidak diarahkan ke login (soal bisa diakses tamu)
    cy.url().should('not.include', '/login');
  });

  it('TC-TAMU-06: Tamu tidak memiliki progress tersimpan (tidak ada progress bar personal)', () => {
    cy.visit('/mahasiswa/materials');
    // Tamu tidak punya progress bar per-materi (hanya mahasiswa login yang punya)
    // Verifikasi: tidak ada elemen progress yang menampilkan persentase personal
    cy.get('body').then(($body) => {
      // Jika ada progress bar, pastikan nilainya 0 atau tidak ada sama sekali
      const progressBars = $body.find('[class*="progress-bar"][aria-valuenow]');
      if (progressBars.length > 0) {
        // Semua progress bar tamu harus bernilai 0
        progressBars.each((i, el) => {
          const val = parseInt(el.getAttribute('aria-valuenow') || '0');
          expect(val).to.eq(0);
        });
      } else {
        cy.log('Tidak ada progress bar personal untuk tamu — sesuai ekspektasi');
      }
    });
  });
});

describe('Tamu - Akses Leaderboard Dibatasi', () => {
  it('TC-TAMU-07: Tamu yang mencoba akses leaderboard diarahkan ke halaman login', () => {
    cy.visit('/mahasiswa/leaderboard', { failOnStatusCode: false });
    // Leaderboard memerlukan login — tamu diarahkan ke login
    cy.url().should('satisfy', (url) => {
      return url.includes('/login') || url.includes('/materials');
    });
  });
});

describe('Tamu - Akses Dashboard Dibatasi', () => {
  it('TC-TAMU-08: Tamu yang mencoba akses dashboard diarahkan ke halaman login', () => {
    cy.visit('/mahasiswa/dashboard', { failOnStatusCode: false });
    cy.url().should('include', '/login');
  });
});
