@non-api
Feature: Employees-add-remove

  Background:
    Given Set entity namespace as "App\Entity"

    # The category structure
    Given Table for entity "Category" contains:
      | storage_key | name | parent |
      | c1          | c1   | null   |
      | c2          | c2   | {{c1}} |
      | c3          | c3   | {{c1}} |
      | c4          | c4   | {{c2}} |
      | c5          | c5   | {{c2}} |
      | c6          | c6   | {{c3}} |
      | c7          | c7   | {{c3}} |
      | c8          | c8   | {{c4}} |
      | c9          | c9   | {{c5}} |
      | c10         | c10  | {{c5}} |
      | c11         | c11  | {{c6}} |
      | c12         | c12  | {{c7}} |
      | c13         | c13  | {{c7}} |
      | c14         | c14  | {{c7}} |


  Scenario: Test add and remove employees.

    # Add an employee to the department
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     |
      | e1          | {{c11}}  | f1        | l1       | e1_@st.ru |
    # Check countAllEmployeesCache in Categories tree
    Then Searched in table for entity "Category" and found the records:
      | id         | countAllEmployeesCache |
      | {{c1.id}}  | 1                      |
      | {{c2.id}}  | 0                      |
      | {{c3.id}}  | 1                      |
      | {{c4.id}}  | 0                      |
      | {{c5.id}}  | 0                      |
      | {{c6.id}}  | 1                      |
      | {{c7.id}}  | 0                      |
      | {{c8.id}}  | 0                      |
      | {{c9.id}}  | 0                      |
      | {{c10.id}} | 0                      |
      | {{c11.id}} | 1                      |

    # Add another employee to the same department
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     |
      | e2          | {{c11}}  | f2        | l2       | e2_@st.ru |
    # Check countAllEmployeesCache in Categories tree
    Then Searched in table for entity "Category" and found the records:
      | id         | countAllEmployeesCache |
      | {{c1.id}}  | 2                      |
      | {{c2.id}}  | 0                      |
      | {{c3.id}}  | 2                      |
      | {{c4.id}}  | 0                      |
      | {{c5.id}}  | 0                      |
      | {{c6.id}}  | 2                      |
      | {{c7.id}}  | 0                      |
      | {{c8.id}}  | 0                      |
      | {{c9.id}}  | 0                      |
      | {{c10.id}} | 0                      |
      | {{c11.id}} | 2                      |

    # Add an employee to the department above
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     |
      | e3          | {{c6}}   | f3        | l3       | e3_@st.ru |
    # Check countAllEmployeesCache in Categories tree
    Then Searched in table for entity "Category" and found the records:
      | id         | countAllEmployeesCache |
      | {{c1.id}}  | 3                      |
      | {{c2.id}}  | 0                      |
      | {{c3.id}}  | 3                      |
      | {{c4.id}}  | 0                      |
      | {{c5.id}}  | 0                      |
      | {{c6.id}}  | 3                      |
      | {{c7.id}}  | 0                      |
      | {{c8.id}}  | 0                      |
      | {{c9.id}}  | 0                      |
      | {{c10.id}} | 0                      |
      | {{c11.id}} | 2                      |

    # Add one employee
    Given Table for entity "Employee" contains:
      | storage_key | category | firstName | lastName | email     |
      | e4          | {{c8}}   | f4        | l4       | e4_@st.ru |
    # Adding an employee to the department on the left in the tree structure relative to the previous department
    Then Searched in table for entity "Category" and found the records:
      | id         | countAllEmployeesCache |
      | {{c1.id}}  | 4                      |
      | {{c2.id}}  | 1                      |
      | {{c3.id}}  | 3                      |
      | {{c4.id}}  | 1                      |
      | {{c5.id}}  | 0                      |
      | {{c6.id}}  | 3                      |
      | {{c7.id}}  | 0                      |
      | {{c8.id}}  | 1                      |
      | {{c9.id}}  | 0                      |
      | {{c10.id}} | 0                      |
      | {{c11.id}} | 2                      |

    # Remove one employee
    Given Delete a table row for entity "Employee" by id "{{e3.id}}"
    # Check countAllEmployeesCache in Categories tree
    Then Searched in table for entity "Category" and found the records:
      | id         | countAllEmployeesCache |
      | {{c1.id}}  | 3                      |
      | {{c2.id}}  | 1                      |
      | {{c3.id}}  | 2                      |
      | {{c4.id}}  | 1                      |
      | {{c5.id}}  | 0                      |
      | {{c6.id}}  | 2                      |
      | {{c7.id}}  | 0                      |
      | {{c8.id}}  | 1                      |
      | {{c9.id}}  | 0                      |
      | {{c10.id}} | 0                      |
      | {{c11.id}} | 2                      |
