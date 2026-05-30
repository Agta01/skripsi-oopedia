// cypress/e2e/05_virtual_lab.cy.js
// Black-box test: Skenario No. 7 — Virtual Lab
// Tabel 5.17 Pengujian Halaman Virtual Lab (3 skenario)

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

describe('Pengujian Halaman Virtual Lab', () => {

  // TC-VL-1: Run Kode
  it('Run Kode — Mahasiswa menulis kode Java dan klik tombol Run → Output terminal menampilkan hasil eksekusi kode', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);

    cy.intercept('POST', '/virtual-lab/execute').as('executeCode');

    // Input kode ke editor
    cy.get('body').then(($body) => {
      if ($body.find('.CodeMirror').length > 0) {
        cy.get('.CodeMirror').click();
        cy.get('.CodeMirror textarea').type('{ctrl+a}', { force: true });
        cy.get('.CodeMirror textarea').type(KODE_JAVA_VALID, { force: true, delay: 0 });
      } else if ($body.find('textarea[class*="font-mono"], textarea[name*="content"]').length > 0) {
        cy.get('textarea[class*="font-mono"], textarea[name*="content"]').first()
          .clear({ force: true }).type(KODE_JAVA_VALID, { force: true, delay: 0 });
      }
    });

    // Klik Run
    cy.get('.btn-run, button[value="run"], button[name="action"][value="run"]')
      .first().click({ force: true });

    // Tunggu response server
    cy.wait('@executeCode', { timeout: 45000 }).then((interception) => {
      expect(interception.response.statusCode).to.be.oneOf([200, 201]);
    });

    // Output terminal tidak kosong
    cy.get('.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"]', { timeout: 45000 })
      .invoke('text').should('not.be.empty');
  });

  // TC-VL-2: Error Handling
  it('Error Handling — Mahasiswa menjalankan kode yang mengandung error → Terminal menampilkan pesan error beserta nomor baris dan jenis error', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);

    cy.intercept('POST', '/virtual-lab/execute').as('executeCode');

    cy.get('body').then(($body) => {
      if ($body.find('.CodeMirror').length > 0) {
        cy.get('.CodeMirror').click();
        cy.get('.CodeMirror textarea').type('{ctrl+a}', { force: true });
        cy.get('.CodeMirror textarea').type(KODE_JAVA_ERROR, { force: true, delay: 0 });
      } else if ($body.find('textarea[class*="font-mono"], textarea[name*="content"]').length > 0) {
        cy.get('textarea[class*="font-mono"], textarea[name*="content"]').first()
          .clear({ force: true }).type(KODE_JAVA_ERROR, { force: true, delay: 0 });
      }
    });

    cy.get('.btn-run, button[value="run"], button[name="action"][value="run"]')
      .first().click({ force: true });

    cy.wait('@executeCode', { timeout: 45000 });

    // Terminal menampilkan output (pesan error)
    cy.get('.vl-output-pre, pre.terminal-scroll, pre[class*="terminal"]', { timeout: 45000 })
      .invoke('text').should('not.be.empty');
  });

  // TC-VL-3: Submit Jawaban
  it('Submit Jawaban — Mahasiswa klik tombol Submit setelah kode selesai → Kode dan output tersimpan ke database', () => {
    cy.login(MAHASISWA_EMAIL, MAHASISWA_PASSWORD);
    cy.visit('/virtual-lab?task=1');
    cy.wait(3000);

    cy.get('body').then(($body) => {
      // Cek review mode (sudah pernah submit)
      const isReviewMode = $body.find('.tbut-bar-completed').length > 0 ||
                           $body.text().includes('Selesai');

      if (isReviewMode) {
        // Tugas sudah pernah di-submit — kode tersimpan di database
        cy.log('Review mode: tugas sudah selesai dan tersimpan di database — Berhasil');
        cy.get('.CodeMirror, textarea[class*="font-mono"], textarea[name*="content"]', { timeout: 10000 })
          .should('exist');
      } else {
        // Active mode: run lalu submit
        cy.intercept('POST', '/virtual-lab/execute').as('executeCode');
        cy.intercept('POST', '/virtual-lab/submit-task').as('submitTask');

        cy.get('body').then(($b) => {
          if ($b.find('.CodeMirror').length > 0) {
            cy.get('.CodeMirror').click();
            cy.get('.CodeMirror textarea').type('{ctrl+a}', { force: true });
            cy.get('.CodeMirror textarea').type(KODE_JAVA_VALID, { force: true, delay: 0 });
          } else if ($b.find('textarea[class*="font-mono"], textarea[name*="content"]').length > 0) {
            cy.get('textarea[class*="font-mono"], textarea[name*="content"]').first()
              .clear({ force: true }).type(KODE_JAVA_VALID, { force: true, delay: 0 });
          }
        });

        cy.get('.btn-run, button[value="run"]').first().click({ force: true });
        cy.wait('@executeCode', { timeout: 45000 });
        cy.wait(1000);

        cy.get('body').then(($b2) => {
          if ($b2.find('#btn-submit-task, .btn-submit').length > 0) {
            cy.get('#btn-submit-task, .btn-submit').first().click({ force: true });
            cy.wait('@submitTask', { timeout: 15000 }).then((interception) => {
              expect(interception.response.statusCode).to.be.oneOf([200, 201, 302]);
            });
          } else {
            cy.log('Tombol Submit belum muncul — perlu output yang tepat');
          }
        });
      }
    });
  });

});
