const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://127.0.0.1:8000',
    specPattern: 'cypress/e2e/**/*.cy.js',
    supportFile: 'cypress/support/e2e.js',
    viewportWidth: 1280,
    viewportHeight: 800,
    defaultCommandTimeout: 15000,
    pageLoadTimeout: 120000,   // 2 menit — toleransi CDN lambat
    requestTimeout: 15000,
    responseTimeout: 30000,
    video: false,              // matikan video agar lebih cepat
    screenshotOnRunFailure: true,
  },
});
