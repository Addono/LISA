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
})