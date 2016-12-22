Feature: Domain suite
  In order to have use multiple domains
  as a User
  I want to check each domain

  Scenario: Check that the domains exist and set correct base url
    Given I am on the domain "Example"
    Then the response status code should be 200
    Then I should see "Example Domain"
    When I go to "/"
    Then the response status code should be 200
    Then I should see "Example Domain"

  Scenario: Check that the base url gets reset for each scenario
    When I go to "/"
    Then the response status code should be 200
    Then I should not see the text "Example Domain"