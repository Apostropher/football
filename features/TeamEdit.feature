Feature:
    An API user should be able to manipulate teams
    
    Scenario: Successfully replace a team's attributes
        Given I add a valid authentication header for user "A-NAME"
        And I add "Accept" header equal to "application/json"
        When I send a "PUT" request to "/leagues/2/teams/3" with body:
        """
        {
            "name": "TEAM 3 (edit)",
            "strip": "blue (edit)"
        }
        """
        Then the response status code should be 204
        When I add a valid authentication header for user "A-NAME"
        And I send a "GET" request to "/leagues/2/teams/3"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "id": 3,
            "league_id": 2,
            "name": "TEAM 3 (edit)",
            "strip": "blue (edit)"
        }
        """
