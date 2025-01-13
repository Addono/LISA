describe('Tests the database migration functionality', () => {
  it('installs updates successfully', () => {
    cy.visit('/install')
    
    cy.get('[data-cy=update-successful]')
  })
})