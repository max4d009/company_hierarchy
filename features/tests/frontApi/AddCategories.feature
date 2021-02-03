@front-api-v1
Feature: addCategories

  Background:
    Given Set entity namespace as "App\Entity"
    Given User remember as "User1"
    Given Auth with user "User1"

  Scenario: addCategories.

    And I send a POST request to "/front-api/v1/category" with json:
    """
    {"name": "test1"}
    """
    And print last response
    And the response should contain json:
    """
    {"success": true}
    """
    Then Searched in table for entity "Category" and found the records:
      | name  |
      | test1 |
    And Searched for entity "Category" by field-val "name"-"test1" and remember as "test1"



    And I send a POST request to "/front-api/v1/category" with json:
    """
    {
     "name": "test2",
     "parentCategoryId": {{test1.id}}
    }
    """
    And Searched for entity "Category" by field-val "name"-"test1" and remember as "test2"
    Then Searched in table for entity "Category" and found the records:
      | name  |
      | test1 |