Feature:
    An API user should be able to list teams
    
    Scenario: Successfully retrieve a list of teams
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/1/teams"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "page": 1,
            "limit": 100,
            "total": 2,
            "teams": [
                {
                    "id": 2,
                    "league_id": 1,
                    "name": "TEAM 2",
                    "strip": "green"
                },
                {
                    "id": 1,
                    "league_id": 1,
                    "name": "TEAM 1",
                    "strip": "red"
                }
            ]
        }
        """

    Scenario Outline: Paginate teams
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/1/teams?<filter>"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        <result>
        """

        Examples:
        | filter    | result              |
        |  | {"page":1,"limit":100,"total":2,"teams":[{"id":2,"league_id":1,"name":"TEAM 2","strip":"green"},{"id":1,"league_id":1,"name":"TEAM 1","strip":"red"}]} |
        | page=1&limit=1    | {"page":1,"limit":1,"total":2,"teams":[{"id":2,"league_id":1,"name":"TEAM 2","strip":"green"}]}   |
        | page=2&limit=1    | {"page":2,"limit":1,"total":2,"teams":[{"id":1,"league_id":1,"name":"TEAM 1","strip":"red"}]}   |

    Scenario: Retrieve a single team
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/2/teams/3"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "id": 3,
            "league_id": 2,
            "name": "TEAM 3",
            "strip": "blue"
        }
        """

    Scenario: Error response when team is non-existent
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "GET" request to "/leagues/2/teams/30"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
          "code": 404,
          "message": "team.not_found"
        }
        """
