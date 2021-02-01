Feature: getAsteroids

  Background:
    Given Set entity namespace as "App\Entity"

    When Table for entity "Category" has contain:
      | storage_key | name | parent    |
      | cat_1       | cat1 | null      |
      | cat_2       | cat2 | {{cat_1}} |
      | cat_3       | cat3 | {{cat_2}} |

    When Table for entity "Employee" has contain:
      | storage_key | email           | category  | firstName | lastName |
      | em_1        | test_1_@test.ru | {{cat_1}} | first_1   | first 2  |
      | em_2        | test_2_@test.ru | {{cat_2}} | first_2   | first 2  |
      | em_3        | 3tes_3_@test.ru | {{cat_3}} | first_3   | first 3  |

  Scenario: getAsteroids-oneOfString-Filter.