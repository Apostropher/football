Feature:
    An API user should be able to list leagues
    
    Scenario: Successfully retrieve a list of leagues
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "page": 1,
            "limit": 100,
            "total": 2,
            "leagues": [
                {
                    "id": 2,
                    "name": "LEAGUE 2"
                },
                {
                    "id": 1,
                    "name": "LEAGUE 1"
                }
            ]
        }
        """

    Scenario Outline: Paginate leagues
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues?<filter>"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        <result>
        """

        Examples:
        | filter    | result              |
        |  | {"page":1,"limit":100,"total":2,"leagues":[{"id":2,"name":"LEAGUE 2"},{"id":1,"name":"LEAGUE 1"}]} |
        | page=1&limit=1    | {"page":1,"limit":1,"total":2,"leagues":[{"id":2,"name":"LEAGUE 2"}]}   |
        | page=2&limit=1    | {"page":2,"limit":1,"total":2,"leagues":[{"id":1,"name":"LEAGUE 1"}]}   |

    Scenario: Retrieve a single league
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/2"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "id": 2,
            "name": "LEAGUE 2"
        }
        """

    Scenario: Error response when league is non-existent
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/20"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
          "code": 404,
          "message": "League not found."
        }
        """
