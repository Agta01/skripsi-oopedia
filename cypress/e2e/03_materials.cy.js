// cypress/e2e/03_materials.cy.js
// Black-box test: Halaman Materi (dapat diakses tamu & mahasiswa)

describe('Halaman Materi - Akses Tamu (Guest)', () => {
  beforeEach(() => {
    cy.visit('/mahasiswa/materials');
  });

  it('TC-MAT-01: Halaman materi berhasil dimuat tanpa login', () => {
    cy.url().should('include', '/materials');
    cy.get('body').should('be.visible');
  });

  it('TC-MAT-02: Setidaknya satu card materi ditampilkan', () => {
    cy.get('[class*="card"], [class*="materi"]').should('have.length.greaterThan', 0);
  });

  it('TC-MAT-03: Klik pada materi menuju halaman detail materi', () => {
    // Gunakan selector yang lebih spesifik: link ke materi dengan ID angka
    // Exclude link yang mengandung kata "questions" atau tidak berakhir dengan angka
    cy.get('a[href*="/materials/"]')
      .filter((i, el) => {
        const href = el.getAttribute('href') || '';
        // Hanya ambil link yang path-nya berakhir dengan angka (ID materi)
        return /\/materials\/\d+$/.test(href);
      })
      .first()
      .click();

    cy.url().should('match', /\/materials\/\d+/);
  });

  it('TC-MAT-04: Navigasi menu tersedia untuk tamu (guest tidak memiliki search bar)', () => {
    // Guest memiliki menu navigasi (Materi, Latihan Soal, Peringkat), bukan search bar
    cy.get('.nav-menu, nav ul, .navbar ul')
      .should('exist');
  });

  it('TC-MAT-04b: Tombol login dan daftar tersedia untuk tamu', () => {
    cy.get('a[href*="login"], a[href*="register"]').should('have.length.greaterThan', 0);
  });
});

describe('Halaman Materi - Akses Mahasiswa', () => {
  const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
  const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/materials');
  });

  it('TC-MAT-05: Progress materi per-card terlihat setelah login', () => {
    cy.get('[class*="progress"], [class*="bar"]').should('have.length.greaterThan', 0);
  });

  it('TC-MAT-06: Search bar tersedia setelah login', () => {
    // Search bar hanya ada untuk mahasiswa yang sudah login
    cy.get('#navbarSearchInput, input[name="search"]').should('be.visible');
  });
});
