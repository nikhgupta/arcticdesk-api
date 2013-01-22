PHP Wrapper for ArcticDesk API
==============================

A PHP wrapper for the [ArcticDesk](http://arcticdesk.com) API, built
according to the `cheat-sheet.txt` provided by ArcticDesk.

Note that, the response from any call to this API wrapper will return
the json-decoded form of the data retrieved from the actual API request.
Also, all methods raise an `ArcticDeskAPIException`, if required. If we receive
an error from the actual API request, then an error is raised with appropriate information.

Usage Example
-------------

    <?php
    require 'arcticdesk.php';

    try {
      $desk = new ArcticDesk( "yourdomain.com", "your_api_key" );

      # get a list of all departments for this ArcticDesk
      $response = $desk->departments->all();
      // ^^ will be a json-decoded representation of the api-response

      # create a new user
      $data = array(
        "full_name" => "Test API User",
        "email"     => "test@api-user.com",
        "password"  => "testpass",
        "send_details" => false
      );
      $response = $desk->users->add($data);

      # add a ticket for this user
      $data = array(
        "subject" => "Test ticket for the API user",
        "message_text" => "a random description for this ticket!",
        "user_id" => 1,
        "department_id" => 1,
        "priority_id" => 3
      );
      $response = $desk->tickets->add($data);

    } catch (ArcticDeskAPIException $e) {
      echo "<h1>Encountered an error!</h1>";
      echo "<h2>{$e->getMessage()}</h2>";
    }
