@front-api-v1
Feature: getCategories

  Background:
    Given Set entity namespace as "App\Entity"

  Scenario: getCategories.
    Given Table for entity "Category" contains:
      | storage_key | name |
      | c1          | cat1 |
      | c2          | cat2 |
      | c3          | cat3 |

    And I send a GET request to "/front-api/v1/categories"
    And print last response
    And the response should contain json:
    """
    {
     "categories": [
         {
             "id": {{c1.id}},
             "name": "cat1"
         },
         {
             "id": {{c2.id}},
             "name": "cat2"
         },
         {
             "id": {{c3.id}},
             "name": "cat3"
         }
     ]
    }
    """