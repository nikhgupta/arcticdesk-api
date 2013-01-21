PHP Wrapper for ArcticDesk API
==============================

A PHP wrapper for the [ArcticDesk](http://arcticdesk.com) API, built
according to the `cheat-sheet.txt` provided by ArcticDesk.

Example
-------

    <?php
    require 'arcticdesk.php';

    $desk = new ArcticDesk( "yourdomain.com", "your_api_key" );

    # get a list of all departments for this ArcticDesk
    $departments = $desk->departments->all();
