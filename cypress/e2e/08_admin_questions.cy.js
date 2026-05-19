// cypress/e2e/08_admin_questions.cy.js
// Black-box test: Kelola Soal (Admin)

const ADMIN_EMAIL    = Cypress.env('ADMIN_EMAIL')    || 'admin@test.com';
const ADMIN_PASSWORD = Cypress.env('ADMIN_PASSWORD') || 'password';

describe('Admin - Kelola Soal', () => {
  beforeEach(() => {
    cy.login(ADMIN_EMAIL, ADMIN_PASSWORD);
    cy.visit('/admin/questions');
  });

  it('TC-ADM-QUE-01: Halaman daftar soal berhasil dimuat', () => {
    cy.url().should('include', '/admin/questions');
    cy.get('h6').contains(/Daftar Semua Soal|Soal:/).should('be.visible');
  });

  it('TC-ADM-QUE-02: Terdapat tombol tambah soal', () => {
    cy.get('a.modern-btn-add').contains('Tambah Soal').should('be.visible');
  });

  it('TC-ADM-QUE-03: Filter tingkat kesulitan soal tersedia', () => {
    cy.get('select[name="difficulty"]').should('exist');
    cy.get('select[name="difficulty"]').select('Beginner');
    cy.get('select[name="difficulty"]').should('have.value', 'beginner');
  });

  it('TC-ADM-QUE-04: Card soal menampilkan elemen yang lengkap (tingkat kesulitan, pertanyaan, jawaban)', () => {
    // Memastikan setidaknya ada 1 card soal untuk dicek (jika tidak kosong)
    cy.get('body').then($body => {
      if ($body.find('.modern-question-card').length > 0) {
        cy.get('.modern-question-card').first().within(() => {
          cy.get('.modern-difficulty-badge').should('exist');
          cy.get('.modern-question-text').should('be.visible');
          cy.get('.modern-answer-block').should('have.length.greaterThan', 0);
        });
      } else {
        cy.log('Daftar soal kosong, skip cek elemen card');
        cy.contains('Belum ada soal').should('be.visible');
      }
    });
  });

  it('TC-ADM-QUE-05: Tombol aksi (edit/hapus) tersedia pada card soal', () => {
    cy.get('body').then($body => {
      if ($body.find('.modern-question-card').length > 0) {
        cy.get('.modern-question-card').first().within(() => {
          cy.get('a.modern-btn-outline[title="Edit Soal"]').should('exist');
          cy.get('button.modern-btn-outline[title="Hapus Soal"]').should('exist');
        });
      }
    });
  });
});
