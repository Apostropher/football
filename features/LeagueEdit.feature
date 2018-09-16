Feature:
    An API user should be able to manipulate leagues
    
    Scenario: Successfully create a league
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "POST" request to "/leagues" with body:
        """
        {
            "name": "LEAGUE 3"
        }
        """
        Then the response status code should be 201
        When I add a valid authentication header for user "A-NAME"
        And I send a "GET" request to "/leagues"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "page": 1,
            "limit": 100,
            "total": 3,
            "leagues": [
                {
                    "id": 3,
                    "name": "LEAGUE 3"
                },
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

    Scenario: Successfully add a team
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "POST" request to "/leagues/2/teams" with body:
        """
        {
            "name": "TEAM 4",
            "strip": "WHITE"
        }
        """
        Then the response status code should be 201
        When I add a valid authentication header for user "A-NAME"
        And I send a "GET" request to "/leagues/2/teams"
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
                    "id": 4,
                    "league_id": 2,
                    "name": "TEAM 4",
                    "strip": "WHITE"
                },
                {
                    "id": 3,
                    "league_id": 2,
                    "name": "TEAM 3",
                    "strip": "blue"
                }
            ]
        }
        """

    Scenario: Successfully remove a league
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "DELETE" request to "/leagues/2"
        Then the response status code should be 204
        When I add a valid authentication header for user "A-NAME"
        And I send a "GET" request to "/leagues/2"
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "code": 404,
            "message": "league.not_found"
        }
        """
