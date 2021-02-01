Feature: getCategories

  Background:
    Given Set entity namespace as "App\Entity"

  Scenario: getCategories.

    Given Table for entity "Category" contains:
      | storage_key | name | parent |
      | c1          | cat1 | null   |
      | c2          | cat2 | {{c1}} |
      | c3          | cat3 | {{c2}} |

    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email       |
      | e1          | {{c1}}   | f1        | l1       | e1_@test.ru |
      | e2          | {{c1}}   | f2        | l2       | e2_@test.ru |
      | e3          | {{c2}}   | f3        | l3       | e3_@test.ru |


    And I send a GET request to "/front-api/v1/categories"
    And print last response