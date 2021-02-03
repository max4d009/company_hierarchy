@non-api
Feature: Employees-add-remove

  Background:
    Given Set entity namespace as "App\Entity"

    # The category structure
    Given Table for entity "Category" contains:
      | storage_key | name        |
      | c1          | Board       |
      | c2          | Management  |
      | c3          | Development |
      | c4          | Accounting  |



  Scenario: Test add and remove employees.

    # Add an employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     |
      | e1          | {{c1}}   | f1        | l1       | e1_@st.ru |

    # Check countAllEmployeesCache in Categories tree
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent | category  |
      | {{e1.id}}  | 0                      | 1   | 0   | 2   | {{e1.id}} | null   | {{c1.id}} |


    # Add another employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     | parent |
      | e2          | {{c1}}   | f2        | l2       | e2_@st.ru | {{e1}} |
    # Check countAllEmployeesCache
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent    | category  |
      | {{e1.id}}  | 1                      | 1   | 0   | 4   | {{e1.id}} | null      | {{c1.id}} |
      | {{e2.id}}  | 0                      | 2   | 1   | 3   | {{e1.id}} | {{e1.id}} | {{c1.id}} |


    # Add another employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     | parent |
      | e3          | {{c2}}   | f3        | l3       | e3_@st.ru | {{e1}} |
    # Check countAllEmployeesCache
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent    | category  |
      | {{e1.id}}  | 2                      | 1   | 0   | 6   | {{e1.id}} | null      | {{c1.id}} |
      | {{e2.id}}  | 0                      | 2   | 1   | 3   | {{e1.id}} | {{e1.id}} | {{c1.id}} |
      | {{e3.id}}  | 0                      | 4   | 1   | 5   | {{e1.id}} | {{e1.id}} | {{c2.id}} |

      # Add another employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     | parent |
      | e4          | {{c3}}   | f4        | l4       | e4_@st.ru | {{e3}} |
    # Check countAllEmployeesCache
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent    | category  |
      | {{e1.id}}  | 3                      | 1   | 0   | 8   | {{e1.id}} | null      | {{c1.id}} |
      | {{e2.id}}  | 0                      | 2   | 1   | 3   | {{e1.id}} | {{e1.id}} | {{c1.id}} |
      | {{e3.id}}  | 1                      | 4   | 1   | 7   | {{e1.id}} | {{e1.id}} | {{c2.id}} |
      | {{e4.id}}  | 0                      | 5   | 2   | 6   | {{e1.id}} | {{e3.id}} | {{c3.id}} |

        # Add another employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     | parent |
      | e5          | {{c4}}   | f5        | l5       | e5_@st.ru | {{e4}} |
    # Check countAllEmployeesCache
    Then Searched in table for entity "Employee" and found the records:
      | id         | countAllEmployeesCache | lft | lvl | rgt | root      | parent    | category  |
      | {{e1.id}}  | 4                      | 1   | 0   | 10  | {{e1.id}} | null      | {{c1.id}} |
      | {{e2.id}}  | 0                      | 2   | 1   | 3   | {{e1.id}} | {{e1.id}} | {{c1.id}} |
      | {{e3.id}}  | 2                      | 4   | 1   | 9   | {{e1.id}} | {{e1.id}} | {{c2.id}} |
      | {{e4.id}}  | 1                      | 5   | 2   | 8   | {{e1.id}} | {{e3.id}} | {{c3.id}} |
      | {{e5.id}}  | 0                      | 6   | 3   | 7   | {{e1.id}} | {{e4.id}} | {{c4.id}} |
