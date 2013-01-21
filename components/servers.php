<?php

/**
 * Class that handles the 'servers' side endpoint
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
class ArcticDeskAPIForServer extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "server");
    $this->methods = array('all', 'id');
  }

}
