@front-api-v1
Feature: AddEmployees

  Background:
    Given Set entity namespace as "App\Entity"
    Given User remember as "User1"
    Given Auth with user "User1"
    Given Table for entity "Category" contains:
      | storage_key | name | parent |
      | c1          | cat1 | null   |
      | c2          | cat2 | {{c1}} |
      | c3          | cat3 | {{c2}} |

    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email       |
      | e1          | {{c1}}   | f1        | l1       | e1_@test.ru |

  Scenario: AddEmployees.

    And I send a POST request to "/front-api/v1/employee" with json:
    """
    {
      "firstName": "f2",
      "lastName": "l2",
      "email": "e2@test.ru",
      "parentEmail": "{{e1.email}}",
      "categoryId": {{c2.id}}
    }
    """
    And print last response
    And the response should contain json:
    """
    {"success": true}
    """
