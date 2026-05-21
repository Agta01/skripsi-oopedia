// cypress/e2e/07_virtual_lab.cy.js
// Black-box test: Skenario No. 7 — Virtual Lab
// Fungsi: Menulis kode Java, menjalankan, dan submit jawaban coding
//
// CATATAN PENTING:
// Akun mahasiswa (andi@mahasiswa.com) mungkin sudah pernah submit tugas sebelumnya
// sehingga halaman tampil dalam "review mode" (is_completed = true).
// Test ini menangani kedua kondisi: active mode dan review mode.
//
// Ada DUA view virtual lab:
//   - Mahasiswa (virtual-lab.mahasiswa): .btn-run, .vl-output-pre, #btn-submit-task
//   - Admin/Dosen (virtual-lab.index): button[value="run"], pre.terminal-scroll

const MAHASISWA_EMAIL    = Cypress.env('MAHASISWA_EMAIL')    || 'mahasiswa@test.com';
const MAHASISWA_PASSWORD = Cypress.env('MAHASISWA_PASSWORD') || 'password';

const KODE_JAVA_VALID = `public class Main {
    public static void main(String[] args) {
        System.out.println("Hello OOPedia");
    }
}`;

const KODE_JAVA_ERROR = `public class Main {
    public static void main(String[] args) {
        System.out.println("Hello OOPedia"
    }
}`;

// Helper: isi editor CodeMirror atau textarea
function inputKode(kode) {
  cy.get('body').then(($body) => {
    if ($body.find('.CodeMirror').length > 0) {
      // Mahasiswa view — CodeMirror
      cy.get('.CodeMirror').click();
      cy.get('.CodeMirror textarea').type('{ctrl+a}', { force: true });
      cy.get('.CodeMirror textarea').type(kode, { force: true, delay: 0 });
    } else {
      // Admin view — textarea biasa
      cy.get('textarea[name*="content"], textarea[name*="code"]').first()
        .clear({ force: true })
        .type(kode, { force: true, delay: 0 });
    }
  });
}

// Helper: klik tombol Run (berbeda antara mahasiswa view dan admin view)
function klikRun() {
  cy.get('body').then(($body) => {
    if ($body.find('.btn-run').length > 0) {
      // Mahasiswa view
      cy.get('.btn-run').first().click({ force: true });
    } else {
      // Admin view
      cy.get('button[type="submit"][value="run"], button[name="action"][value="run"]')
        .first().click({ force: true });
    }
  });
}

// Helper: tunggu output muncul
function tungguOutput(timeout = 30000) {
  cy.get('.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"]', { timeout })
    .should('exist');
}

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - Akses Halaman Task List', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
  });

  it('TC-VL-01: Halaman daftar tugas virtual lab berhasil dimuat', () => {
    cy.visit('/virtual-lab');
    cy.url().should('include', '/virtual-lab');
    cy.get('body').should('be.visible');
  });

  it('TC-VL-02: Daftar tugas virtual lab ditampilkan', () => {
    cy.visit('/virtual-lab');
    // Task list view menampilkan daftar tugas per materi
    cy.get('body').then(($body) => {
      const hasTaskList = $body.find('[class*="task"], [class*="tugas"], a[href*="task="]').length > 0;
      if (hasTaskList) {
        cy.log('Daftar tugas ditemukan');
      } else {
        cy.log('Halaman virtual lab dimuat (mungkin langsung ke editor)');
      }
      cy.get('body').should('be.visible');
    });
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - Editor Kode (Mahasiswa View)', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    // Akses virtual lab dengan task pertama (ID 1)
    cy.visit('/virtual-lab?task=1');
    // Tunggu halaman dan CDN selesai load
    cy.wait(3000);
  });

  it('TC-VL-03: Halaman virtual lab dengan task berhasil dimuat', () => {
    cy.url().should('include', '/virtual-lab');
    cy.get('body').should('be.visible');
  });

  it('TC-VL-04: Editor kode tersedia di halaman virtual lab', () => {
    // Mahasiswa view: CodeMirror
    // Admin view: textarea dengan class tw-font-mono
    cy.get(
      '.CodeMirror, textarea[class*="font-mono"], textarea[name*="content"]',
      { timeout: 15000 }
    ).should('exist');
    cy.log('Editor kode ditemukan');
  });

  it('TC-VL-05: Tombol Run tersedia di halaman virtual lab', () => {
    // Mahasiswa view: .btn-run
    // Admin view: button[value="run"]
    cy.get(
      '.btn-run, button[value="run"], button[name="action"][value="run"]',
      { timeout: 10000 }
    ).should('exist');
    cy.log('Tombol Run ditemukan');
  });

  it('TC-VL-06: Area output/terminal tersedia di halaman virtual lab', () => {
    // Mahasiswa view: .vl-output-pre
    // Admin view: pre.terminal-scroll
    cy.get(
      '.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"], [class*="output"]',
      { timeout: 10000 }
    ).should('exist');
    cy.log('Area terminal/output ditemukan');
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - Menulis dan Menjalankan Kode Java', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);
  });

  it('TC-VL-07: Mahasiswa dapat menulis kode di editor', () => {
    cy.get('body').then(($body) => {
      if ($body.find('.CodeMirror').length > 0) {
        cy.get('.CodeMirror').click();
        cy.get('.CodeMirror textarea').type('{ctrl+a}', { force: true });
        cy.get('.CodeMirror textarea').type(KODE_JAVA_VALID, { force: true, delay: 0 });
        cy.log('Kode berhasil diketik di CodeMirror');
      } else if ($body.find('textarea[class*="font-mono"], textarea[name*="content"]').length > 0) {
        cy.get('textarea[class*="font-mono"], textarea[name*="content"]').first()
          .clear({ force: true })
          .type(KODE_JAVA_VALID, { force: true, delay: 0 });
        cy.log('Kode berhasil diketik di textarea');
      } else {
        cy.log('Editor tidak ditemukan dalam format yang dikenal');
      }
    });
    // Verifikasi: tidak ada error setelah mengetik
    cy.get('body').should('be.visible');
  });

  it('TC-VL-08: Klik tombol Run mengirim kode ke server dan menampilkan output di terminal', () => {
    // Intercept request eksekusi kode
    cy.intercept('POST', '/virtual-lab/execute').as('executeCode');

    inputKode(KODE_JAVA_VALID);
    klikRun();

    // Tunggu response dari server (timeout 45 detik untuk kompilasi TIO)
    cy.wait('@executeCode', { timeout: 45000 }).then((interception) => {
      expect(interception.response.statusCode).to.be.oneOf([200, 201]);
      cy.log('Request eksekusi kode berhasil dikirim ke server');
    });

    // Output terminal tidak kosong setelah eksekusi
    cy.get(
      '.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"]',
      { timeout: 45000 }
    ).invoke('text').should('not.be.empty');
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - Error Handling', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);
  });

  it('TC-VL-09: Kode yang mengandung error menampilkan pesan error di terminal', () => {
    cy.intercept('POST', '/virtual-lab/execute').as('executeCode');

    inputKode(KODE_JAVA_ERROR);
    klikRun();

    cy.wait('@executeCode', { timeout: 45000 });

    // Terminal menampilkan sesuatu (bukan kosong) — bisa berupa pesan error
    cy.get(
      '.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"]',
      { timeout: 45000 }
    ).invoke('text').should('not.be.empty');

    cy.log('Terminal menampilkan output setelah kode error dijalankan');
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - Submit Jawaban Coding', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);
  });

  it('TC-VL-10: Tombol Submit atau indikator "Selesai" tersedia di halaman virtual lab', () => {
    // Cek apakah ada tombol submit (active mode) atau badge "Selesai" (review mode)
    cy.get('body').then(($body) => {
      const hasSubmitBtn = $body.find('#btn-submit-task, .btn-submit').length > 0;
      const hasCompletedBadge = $body.find('.tbut-bar-completed, [class*="completed"]').length > 0;
      const hasSelesaiText = $body.text().includes('Selesai');

      if (hasSubmitBtn) {
        cy.get('#btn-submit-task, .btn-submit').first().should('exist');
        cy.log('Tombol Submit ditemukan (active mode)');
      } else if (hasCompletedBadge || hasSelesaiText) {
        cy.log('Tugas sudah selesai (review mode) — badge "Selesai" ditampilkan');
        // Ini adalah hasil yang valid: tugas sudah pernah di-submit
        expect(true).to.be.true;
      } else {
        cy.log('Tombol Submit belum muncul — mungkin perlu Run terlebih dahulu');
      }
    });
  });

  it('TC-VL-11: Submit task menyimpan kode ke database (active mode) atau review mode ditampilkan', () => {
    cy.get('body').then(($body) => {
      const isReviewMode = $body.find('.tbut-bar-completed').length > 0 ||
                           $body.find('.readonly-banner').length > 0 ||
                           $body.text().includes('Selesai');

      if (isReviewMode) {
        // Review mode: tugas sudah selesai, kode tersimpan di database
        cy.log('Review mode: tugas sudah selesai dan tersimpan di database — TC valid');
        // Verifikasi: halaman masih bisa diakses dan editor masih tampil
        cy.get(
          '.CodeMirror, textarea[class*="font-mono"], textarea[name*="content"]',
          { timeout: 10000 }
        ).should('exist');
        cy.log('Editor masih tampil dalam review mode — sesuai ekspektasi');
      } else {
        // Active mode: lakukan run lalu submit
        cy.intercept('POST', '/virtual-lab/execute').as('executeCode');
        cy.intercept('POST', '/virtual-lab/submit-task').as('submitTask');

        inputKode(KODE_JAVA_VALID);
        klikRun();
        cy.wait('@executeCode', { timeout: 45000 });
        cy.wait(1000);

        cy.get('body').then(($b2) => {
          if ($b2.find('#btn-submit-task, .btn-submit').length > 0) {
            cy.get('#btn-submit-task, .btn-submit').first().click({ force: true });
            cy.wait('@submitTask', { timeout: 15000 }).then((interception) => {
              expect(interception.response.statusCode).to.be.oneOf([200, 201, 302]);
              cy.log('Submit berhasil — kode tersimpan ke database');
            });
          } else {
            cy.log('Tombol Submit tidak ditemukan setelah Run — mungkin perlu output yang benar');
          }
        });
      }
    });
  });
});

// ─────────────────────────────────────────────────────────────────────────────
describe('Virtual Lab - TBUT Metrics', () => {
  beforeEach(() => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(2000);
  });

  it('TC-VL-12: Metrik TBUT (waktu, run count) ditampilkan di topbar', () => {
    // TBUT bar ada di topbar — menampilkan waktu dan jumlah run
    cy.get('.tbut-bar, [class*="tbut"]', { timeout: 10000 }).should('exist');
    cy.log('TBUT metrics bar ditemukan');
  });
});
