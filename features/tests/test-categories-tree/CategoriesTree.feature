Feature: CategoriesTree

  Background:
    Given Set entity namespace as "App\Entity"

  Scenario: test nested sets categories.

    # Add one tree hierarchy
    Given Table for entity "Category" contains:
      | storage_key | name | parent |
      | c1          | cat1 | null   |
      | c2          | cat2 | {{c1}} |
      | c3          | cat3 | {{c2}} |

    # Check the hierarchy
    Then Searched in table for entity "Category" and found the records:
      | id        | name | lft | lvl | rgt | root      | parent |
      | {{c1.id}} | cat1 | 1   | 0   | 6   | {{c1.id}} | null   |
      | {{c2.id}} | cat2 | 2   | 1   | 5   | {{c1.id}} | {{c1.id}} |
      | {{c3.id}} | cat3 | 3   | 2   | 4   | {{c1.id}} | {{c2.id}} |

    # Added a second tree
    Given Table for entity "Category" contains:
      | storage_key | name | parent |
      | c4          | cat4 | null   |
      | c5          | cat5 | {{c4}} |

    # Check the hierarchy
    Then Searched in table for entity "Category" and found the records:
      | id        | name | lft | lvl | rgt | root      | parent    |
      | {{c1.id}} | cat1 | 1   | 0   | 6   | {{c1.id}} | null      |
      | {{c2.id}} | cat2 | 2   | 1   | 5   | {{c1.id}} | {{c1.id}} |
      | {{c3.id}} | cat3 | 3   | 2   | 4   | {{c1.id}} | {{c2.id}} |
      | {{c4.id}} | cat4 | 1   | 0   | 4   | {{c4.id}} | null      |
      | {{c5.id}} | cat5 | 2   | 1   | 3   | {{c4.id}} | {{c4.id}} |

    # Remove a row from the first tree with parent
    Given Delete a table row for entity "Category" by id "{{c2.id}}"

    # Check the hierarchy
    Then Searched in table for entity "Category" and found the records:
      | id        | name | lft | lvl | rgt | root      | parent    |
      | {{c1.id}} | cat1 | 1   | 0   | 2   | {{c1.id}} | null      |
      | {{c4.id}} | cat4 | 1   | 0   | 4   | {{c4.id}} | null      |
      | {{c5.id}} | cat5 | 2   | 1   | 3   | {{c4.id}} | {{c4.id}} |

    # Check the hierarchy
    Then Searched in table for entity "Category" and not found the records:
      | name |
      | cat2 |
      | cat3 |


#    When Clear table for entity "Category"