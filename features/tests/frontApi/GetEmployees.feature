@front-api-v1
Feature: GetEmployees.

  Background:
    Given Set entity namespace as "App\Entity"

  Scenario: GetEmployees.
    Given Table for entity "Category" contains:
      | storage_key | name |
      | c1          | cat1 |
      | c2          | cat2 |
      | c3          | cat3 |

    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email       | parent |
      | e1          | {{c1}}   | f1        | l1       | e1_@test.ru | null   |
      | e2          | {{c1}}   | f2        | l2       | e2_@test.ru | {{e1}} |
      | e3          | {{c2}}   | f3        | l3       | e3_@test.ru | {{e2}} |

    And I send a GET request to "/front-api/v1/employees"
    And print last response
    And the response should contain json:
    """
    {
     "employees": [
         {
             "id": "*",
             "first_name": "f3",
             "last_name": "l3",
             "email": "e3_@test.ru",
             "category": {
                 "id": "*",
                 "name": "cat2"
             },
             "subordinates_count": 0
         },
         {
             "id": "*",
             "first_name": "f2",
             "last_name": "l2",
             "email": "e2_@test.ru",
             "category": {
                 "id": "*",
                 "name": "cat1"
             },
             "subordinates_count": 1
         },
         {
             "id": "*",
             "first_name": "f1",
             "last_name": "l1",
             "email": "e1_@test.ru",
             "category": {
                 "id": "*",
                 "name": "cat1"
             },
             "subordinates_count": 2
         }
     ]
    }
    """
