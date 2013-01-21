<?php

/**
 * Class that handles the 'announcements' side endpoint
 * for the ArcticDesk API.
 *
 * Methods Inherited:
 *   - all
 *
 * Methods Overridden:
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
class ArcticDeskAPIForAnnouncement extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "announce", "announcement");
    $this->methods = array('all', 'id');
  }

  /**
   * Get a specific announcement.
   *
   * @param  $id      ID for the announcement being fetched.
   * @param  $data    Optional data for this announcement.
   *                  Takes one optional:
   *                  - category_id
   * @return api_response
   */
  public function id($id, $data = array()) {
    if (!$id) throw new ArcticDeskAPIException("ID is required to view this announcement!");
    $data = array_merge($data, array("id" => $id));
    return $this->__execute("view", $data);
  }

}
