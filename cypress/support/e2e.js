// cypress/support/e2e.js
// Global support file for all E2E tests

// Ignore uncaught exceptions from the application (not from test code).
Cypress.on('uncaught:exception', () => false);

// Custom command: login via HTTP request (tidak perlu cy.visit ke halaman login)
// Mengambil CSRF token dari GET /login, lalu POST form login.
Cypress.Commands.add('login', (email, password) => {
  // Step 1: ambil CSRF token via HTTP request (bukan cy.visit agar tidak ada page load timeout)
  cy.request('GET', '/login').then((loginPage) => {
    const match = loginPage.body.match(/name="_token"\s+value="([^"]+)"/);
    const csrfToken = match ? match[1] : null;

    expect(csrfToken, 'CSRF token harus ada di halaman login').to.be.a('string');

    // Step 2: POST login
    cy.request({
      method: 'POST',
      url: '/login',
      form: true,
      followRedirect: true,
      body: {
        _token: csrfToken,
        email: email,
        password: password,
      },
    }).then((response) => {
      // Status 200 = berhasil (Laravel redirect setelah login berhasil)
      expect(response.status).to.eq(200);
    });
  });
});

// Custom command: logout
Cypress.Commands.add('logout', () => {
  cy.get('form[action*="logout"]').first().within(() => {
    cy.get('button[type="submit"]').click();
  });
});
