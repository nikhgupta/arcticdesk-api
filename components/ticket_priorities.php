<?php

/**
 * Class that handles the 'ticket_priorities' side endpoint
 * for the ArcticDesk API.
 *
 * Methods Inherited:
 *   - all
 *   - id
 *
 * Methods Not Supported:
 *   - add
 *   - edit
 *   - delete
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIForTicketPriority extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "ticket_priority");
    $this->methods = array('all', 'id');
    $this->pluralized = "ticket_priorities";
  }

}
