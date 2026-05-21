// cypress/e2e/01_auth.cy.js
// Black-box test: Skenario No. 2 — Login
// Fungsi: Memasukkan akun ke sistem dengan email dan password

describe('Login - Mahasiswa', () => {
  beforeEach(() => {
    cy.visit('/login');
  });

  it('TC-LOGIN-01: Halaman login memiliki elemen form yang benar', () => {
    cy.get('#email').should('exist');
    cy.get('#password').should('exist');
    cy.get('button[type="submit"]').should('contain.text', 'MASUK');
    cy.get('a[href*="register"]').should('exist');
  });

  it('TC-LOGIN-02: Tombol toggle password mengubah tipe input', () => {
    cy.get('#password').should('have.attr', 'type', 'password');
    cy.get('#togglePassword').click();
    cy.get('#password').should('have.attr', 'type', 'text');
    cy.get('#togglePassword').click();
    cy.get('#password').should('have.attr', 'type', 'password');
  });

  it('TC-LOGIN-03: Login dengan email/password salah menampilkan pesan kesalahan', () => {
    cy.get('#email').type('salah@email.com');
    cy.get('#password').type('passwordsalah');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/login');
    cy.get('.alert-danger').should('be.visible');
  });

  it('TC-LOGIN-04: Login dengan akun mahasiswa valid diarahkan ke dashboard mahasiswa', () => {
    cy.get('#email').type(Cypress.env('MAHASISWA_EMAIL') || 'mahasiswa@test.com');
    cy.get('#password').type(Cypress.env('MAHASISWA_PASSWORD') || 'password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/mahasiswa/dashboard');
  });
});

describe('Login - Dosen (Admin)', () => {
  beforeEach(() => {
    cy.visit('/login');
  });

  it('TC-LOGIN-05: Login dengan akun dosen yang telah disetujui diarahkan ke dashboard admin', () => {
    cy.get('#email').type(Cypress.env('ADMIN_EMAIL') || 'admin@test.com');
    cy.get('#password').type(Cypress.env('ADMIN_PASSWORD') || 'password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });
});

describe('Login - Super Admin', () => {
  beforeEach(() => {
    cy.visit('/login');
  });

  it('TC-LOGIN-06: Login dengan akun Super Admin diarahkan ke dashboard admin', () => {
    cy.get('#email').type(Cypress.env('SUPERADMIN_EMAIL') || Cypress.env('ADMIN_EMAIL') || 'admin@test.com');
    cy.get('#password').type(Cypress.env('SUPERADMIN_PASSWORD') || Cypress.env('ADMIN_PASSWORD') || 'password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });
});

describe('Register - Mahasiswa', () => {
  it('TC-REG-01: Halaman register dapat diakses dan berisi form pendaftaran', () => {
    cy.visit('/register');
    cy.get('input[name="name"]').should('exist');
    cy.get('input[name="email"]').should('exist');
    cy.get('input[name="password"]').should('exist');
    cy.get('input[name="password_confirmation"]').should('exist');
    cy.get('button[type="submit"]').should('exist');
  });

  it('TC-REG-02: Mengisi form pendaftaran mahasiswa dengan data valid membuat akun aktif', () => {
    const timestamp = Date.now();
    cy.visit('/register');
    cy.get('input[name="name"]').type(`Test Mahasiswa ${timestamp}`);
    cy.get('input[name="email"]').type(`test.mhs.${timestamp}@test.com`);
    cy.get('input[name="password"]').type('password123');
    cy.get('input[name="password_confirmation"]').type('password123');
    cy.get('button[type="submit"]').click();
    // Mahasiswa langsung aktif — diarahkan ke dashboard atau halaman materi
    cy.url().should('not.include', '/register');
  });
});
