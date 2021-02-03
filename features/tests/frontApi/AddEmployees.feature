@front-api-v1
Feature: AddEmployees

  Background:
    Given Set entity namespace as "App\Entity"
    Given User remember as "User1"
    Given Auth with user "User1"


  Scenario: AddEmployees.
    Given Table for entity "Category" contains:
      | storage_key | name |
      | c1          | cat1 |
      | c2          | cat2 |
      | c3          | cat3 |

    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email       |
      | e1          | {{c1}}   | f1        | l1       | e1_@test.ru |

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
    And the response should contain json:
    """
    {"success": true}
    """
    Then Searched for entity "Employee" by field-val "firstName"-"f2" and remember as "e2"
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent    | category  |
      | {{e1.id}}  | 1                      | 1   | 0   | 4   | {{e1.id}} | null      | {{c1.id}} |
      | {{e2.id}}  | 0                      | 2   | 1   | 3   | {{e1.id}} | {{e1.id}} | {{c2.id}} |




  Scenario: Initial set of data with categories list and employees
    Given Table for entity "Category" contains:
      | storage_key | name        |
      | c1          | Board       |
      | c2          | Head        |
      | c3          | Management  |
      | c4          | Development |
      | c5          | Accounting  |

    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email                   | parent |
      | e1          | {{c1}}   | CEO       | X        | ceo@example.com         | null   |
      | e2          | {{c2}}   | Head      | A        | head.a@example.com      | {{e1}} |
      | e3          | {{c2}}   | Head      | B        | head.b@example.com      | {{e1}} |
      | e4          | {{c2}}   | Manager   | C        | manager.c@example.com   | {{e2}} |
      | e5          | {{c2}}   | Manager   | D        | manager.d@example.com   | {{e2}} |
      | e6          | {{c4}}   | Accounter | H        | accounter.h@example.com | {{e3}} |
      | e7          | {{c3}}   | Developer | E        | developer.e@example.com | {{e4}} |
      | e8          | {{c3}}   | Designer  | F        | designer.f@example.com  | {{e4}} |
      | e9          | {{c3}}   | Developer | G        | developer.g@example.com | {{e5}} |

    And I send a GET request to "/front-api/v1/employees"
    And print last response
    And the response should contain json:
    """
    {
     "employees": [
         {
             "id": "*",
             "first_name": "Developer",
             "last_name": "E",
             "email": "developer.e@example.com",
             "category": {
                 "id": "*",
                 "name": "Management"
             },
             "subordinates_count": 0
         },
         {
             "id": "*",
             "first_name": "Accounter",
             "last_name": "H",
             "email": "accounter.h@example.com",
             "category": {
                 "id": "*",
                 "name": "Development"
             },
             "subordinates_count": 0
         },
         {
             "id": "*",
             "first_name": "Developer",
             "last_name": "G",
             "email": "developer.g@example.com",
             "category": {
                 "id": "*",
                 "name": "Management"
             },
             "subordinates_count": 0
         },
         {
             "id": "*",
             "first_name": "Designer",
             "last_name": "F",
             "email": "designer.f@example.com",
             "category": {
                 "id": "*",
                 "name": "Management"
             },
             "subordinates_count": 0
         },
         {
             "id": "*",
             "first_name": "Manager",
             "last_name": "D",
             "email": "manager.d@example.com",
             "category": {
                 "id": "*",
                 "name": "Head"
             },
             "subordinates_count": 1
         },
         {
             "id": "*",
             "first_name": "Head",
             "last_name": "B",
             "email": "head.b@example.com",
             "category": {
                 "id": "*",
                 "name": "Head"
             },
             "subordinates_count": 1
         },
         {
             "id": "*",
             "first_name": "Manager",
             "last_name": "C",
             "email": "manager.c@example.com",
             "category": {
                 "id": "*",
                 "name": "Head"
             },
             "subordinates_count": 2
         },
         {
             "id": "*",
             "first_name": "Head",
             "last_name": "A",
             "email": "head.a@example.com",
             "category": {
                 "id": "*",
                 "name": "Head"
             },
             "subordinates_count": 5
         },
         {
             "id": "*",
             "first_name": "CEO",
             "last_name": "X",
             "email": "ceo@example.com",
             "category": {
                 "id": "*",
                 "name": "Board"
             },
             "subordinates_count": 8
         }
     ]
    }
    """