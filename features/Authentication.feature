Feature:
    An API user should be able to authenticate

    Scenario: Authentication fails if token is unprovided
        When I send a "GET" request to "/leagues"
        Then the response status code should be 401
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {"code":401,"message":"http.request.unauthorised"}
        """
    
    Scenario: Authentication is successful if valid token is provided
        Given I add a valid authentication header for user "A-NAME"
        When I send a "GET" request to "/leagues"
        Then the response status code should be 200
