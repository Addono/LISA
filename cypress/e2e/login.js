const USERNAME_IDENTIFIER = '[data-cy=username]'
const PASSWORD_IDENTIFIER = '[data-cy=password]'

describe('Tests the login functionality of the application', () => {
  beforeEach(() => {
    cy.visit('login')
    cy.get(USERNAME_IDENTIFIER).type('admin')
    cy.get(PASSWORD_IDENTIFIER).type('admin312')
  })

  it('Allows the user to login', () => {
    cy.get('form').submit()

    cy.get('.navbar').contains('Logout')
    cy.url().should('not.include', 'login')
  })

  it('Does not allow to login with an empty password', () => {
    cy.get(PASSWORD_IDENTIFIER).clear()

    cy.get('form').submit()

    cy.get('.alert > div').should('contain', 'Password is required, please enter it.')
    cy.url().should('include', 'login')
  })

  it('Does not allow to login with an empty username', () => {
    cy.get(USERNAME_IDENTIFIER).clear()

    cy.get('form').submit()

    cy.get('.alert > div').should('contain', 'Username is required, please enter it.')
    cy.url().should('include', 'login')
  })

  it('Renders administrator pages through CI4', () => {
    cy.get('form').submit()

    for (const path of ['admin', 'admin/NewUser', 'admin/UserOverview', 'admin/Transactions']) {
      cy.visit(path)
      cy.get('.page-header').should('be.visible')
      cy.get('body').should('not.contain', 'Whoops!')
    }
  })

  it('Rejects AJAX requests without a CSRF token', () => {
    cy.request({
      method: 'POST',
      url: 'default/TransactionApi',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      form: true,
      body: { action: 'updateData' },
      failOnStatusCode: false,
    }).its('status').should('eq', 403)
  })

  it('Returns JSON access denial for an anonymous API request', () => {
    cy.get('input[name=csrf_token]').invoke('val').then((token) => {
      cy.request({
        method: 'POST',
        url: 'default/TransactionApi',
        form: true,
        body: { action: 'updateData', csrf_token: token },
      }).then((response) => {
        expect(response.headers['content-type']).to.contain('application/json')
        expect(response.body.status).to.eq('accessDenied')
      })
    })
  })

  it('Creates a user and renders their consumption and transaction pages', () => {
    const username = `migration-${Date.now()}`
    const password = 'migration-password'
    cy.get('form').submit()
    cy.visit('admin/NewUser')
    cy.get('[name=username]').type(username)
    cy.get('[name=email]').type('migration@example.com')
    cy.get('[name=password]').type(password)
    cy.get('[name=confirm-password]').type(password)
    cy.get('[name=first-name]').type('Migration')
    cy.get('[name=last-name]').type('Test')
    cy.contains('.checkbox', 'user').find('input').check()
    cy.get('form').submit()
    cy.get('.alert-success').should('be.visible')

    cy.visit('logout')
    cy.visit('login')
    cy.get(USERNAME_IDENTIFIER).type(username)
    cy.get(PASSWORD_IDENTIFIER).type(password)
    cy.get('form').submit()
    cy.url().should('include', 'consume')
    cy.get('footer').should('exist')
    cy.getCookie('csrf_cookie').then((cookie) => {
      cy.request({
        method: 'POST',
        url: 'default/TransactionApi',
        form: true,
        body: { action: 'updateData', csrf_token: cookie.value },
      }).its('body.status').should('eq', 'success')
      cy.get('.buy').first().invoke('attr', 'data-id').then((id) => {
        cy.request({
          method: 'POST',
          url: 'default/TransactionApi',
          form: true,
          body: { action: 'buy', id, csrf_token: cookie.value },
        }).then((response) => {
          expect(response.body.status).to.eq('success')
          expect(Number(response.body.new_amount)).to.eq(-1)
        })
      })
    })
    cy.visit('transactions')
    cy.contains('h4', 'My transactions').should('be.visible')
    cy.get('body').should('not.contain', 'Whoops!')
    cy.visit('leaderboard')
    cy.get('footer').should('exist')
    cy.get('#snackbar').should('exist')
  })
})