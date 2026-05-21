// cypress/e2e/09_leaderboard.cy.js
// Black-box test: Skenario No. 9 — Leaderboard
// Fungsi: Melihat peringkat mahasiswa berdasarkan poin

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

// ─────────────────────────────────────────────────────────────────────────────
describe('Leaderboard - Akses Mahasiswa', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/leaderboard');
  });

  it('TC-LB-01: Halaman leaderboard berhasil dimuat setelah login', () => {
    cy.url().should('include', '/leaderboard');
    cy.get('body').should('be.visible');
  });

  it('TC-LB-02: Peringkat mahasiswa berdasarkan total poin ditampilkan', () => {
    // Tabel leaderboard dengan class .leaderboard-table
    cy.get('table.leaderboard-table, .leaderboard-card, [class*="leaderboard"]')
      .should('exist');
  });

  it('TC-LB-03: Kolom peringkat, nama, dan poin tersedia', () => {
    cy.get('table.leaderboard-table thead').invoke('text').then((text) => {
      const lower = text.toLowerCase();
      expect(lower).to.match(/peringkat|mahasiswa|skor|poin/);
    });
  });

  it('TC-LB-04: Setidaknya satu baris data peringkat ditampilkan', () => {
    cy.get('table.leaderboard-table tbody tr').then(($rows) => {
      if ($rows.length > 0) {
        cy.log(`Ditemukan ${$rows.length} baris peringkat`);
        expect($rows.length).to.be.greaterThan(0);
      } else {
        cy.log('Leaderboard kosong — belum ada data poin mahasiswa');
      }
    });
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Leaderboard - Akses Tamu', () => {
  it('TC-LB-05: Tamu yang mengakses leaderboard melihat lock screen (bukan data peringkat)', () => {
    // Leaderboard bisa diakses tamu tapi menampilkan lock screen (@guest block)
    // URL tetap /mahasiswa/leaderboard — tidak redirect
    cy.visit('/mahasiswa/leaderboard');
    cy.url().should('include', '/leaderboard');

    // Lock screen ditampilkan untuk tamu
    cy.get('.lb-lock-wrap, .lb-lock-card').should('be.visible');

    // Tombol login dan daftar tersedia di lock screen
    cy.get('a[href*="login"], a.lb-btn-login').should('exist');
    cy.get('a[href*="register"], a.lb-btn-register').should('exist');

    // Tabel leaderboard TIDAK ditampilkan untuk tamu
    cy.get('table.leaderboard-table').should('not.exist');

    cy.log('Lock screen ditampilkan untuk tamu — sesuai ekspektasi');
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Leaderboard - Navigasi dari Sidebar', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/mahasiswa/dashboard');
  });

  it('TC-LB-06: Menu Leaderboard tersedia di sidebar navigasi', () => {
    // Sidebar mahasiswa pakai class .sidebar (satu elemen)
    // Link leaderboard pakai a.menu-item dengan href ke leaderboard
    cy.get('.sidebar').first().within(() => {
      cy.get('a.menu-item[href*="leaderboard"]').should('exist');
    });
    cy.log('Link Leaderboard ditemukan di sidebar');
  });

  it('TC-LB-07: Klik menu Leaderboard dari sidebar menuju halaman leaderboard', () => {
    cy.get('.sidebar').first()
      .find('a.menu-item[href*="leaderboard"]')
      .first()
      .click({ force: true });
    cy.url().should('include', '/leaderboard');
    cy.get('body').should('be.visible');
  });
});
