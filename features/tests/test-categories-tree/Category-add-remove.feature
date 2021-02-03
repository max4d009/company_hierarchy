@non-api
Feature: Category-add-remove

  Background:
    Given Set entity namespace as "App\Entity"

  Scenario: Test add and remove categories.

    Given Table for entity "Category" contains:
      | storage_key | name |
      | c1          | cat1 |
      | c2          | cat2 |
      | c3          | cat3 |

    Then Searched in table for entity "Category" and found the records:
      | id        | name |
      | {{c1.id}} | cat1 |
      | {{c2.id}} | cat2 |
      | {{c3.id}} | cat3 |

    Given Table for entity "Category" contains:
      | storage_key | name |
      | c4          | cat4 |
      | c5          | cat5 |

    Then Searched in table for entity "Category" and found the records:
      | id        | name |
      | {{c1.id}} | cat1 |
      | {{c2.id}} | cat2 |
      | {{c3.id}} | cat3 |
      | {{c4.id}} | cat4 |
      | {{c5.id}} | cat5 |

    Given Delete a table row for entity "Category" by id "{{c2.id}}"

    Then Searched in table for entity "Category" and found the records:
      | id        | name |
      | {{c1.id}} | cat1 |
      | {{c4.id}} | cat4 |
      | {{c5.id}} | cat5 |

    Then Searched in table for entity "Category" and not found the records:
      | name |
      | cat2 |