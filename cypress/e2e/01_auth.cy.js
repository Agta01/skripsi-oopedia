// cypress/e2e/01_auth.cy.js
// Black-box test: Authentication (Login & Register)

describe('Autentikasi - Login', () => {
  beforeEach(() => {
    cy.visit('/login');
  });

  it('TC-AUTH-01: Halaman login memiliki elemen yang benar', () => {
    cy.get('#email').should('exist');
    cy.get('#password').should('exist');
    cy.get('button[type="submit"]').should('contain.text', 'MASUK');
    cy.get('a[href*="register"]').should('exist');
  });

  it('TC-AUTH-02: Tombol toggle password (mata) mengubah tipe input', () => {
    cy.get('#password').should('have.attr', 'type', 'password');
    cy.get('#togglePassword').click();
    cy.get('#password').should('have.attr', 'type', 'text');
    cy.get('#togglePassword').click();
    cy.get('#password').should('have.attr', 'type', 'password');
  });

  it('TC-AUTH-03: Login dengan kredensial kosong tidak bisa submit', () => {
    cy.get('button[type="submit"]').click();
    // browser native validation should block or error shown
    cy.url().should('include', '/login');
  });

  it('TC-AUTH-04: Login dengan email valid tapi password salah menampilkan error', () => {
    cy.get('#email').type('test@example.com');
    cy.get('#password').type('wrong_password_123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/login');
    cy.get('.alert-danger').should('be.visible');
  });

  it('TC-AUTH-05: Login dengan akun mahasiswa valid diarahkan ke dashboard', () => {
    // Ganti dengan email & password mahasiswa yang valid di database Anda
    cy.get('#email').type(Cypress.env('MAHASISWA_EMAIL') || 'mahasiswa@test.com');
    cy.get('#password').type(Cypress.env('MAHASISWA_PASSWORD') || 'password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/mahasiswa/dashboard');
  });
});

describe('Autentikasi - Halaman Register', () => {
  it('TC-AUTH-06: Halaman register dapat diakses dan berisi form', () => {
    cy.visit('/register');
    cy.get('input[name="name"]').should('exist');
    cy.get('input[name="email"]').should('exist');
    cy.get('input[name="password"]').should('exist');
    cy.get('input[name="password_confirmation"]').should('exist');
    cy.get('button[type="submit"]').should('exist');
  });
});
