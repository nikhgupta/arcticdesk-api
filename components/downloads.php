<?php

/**
 * Class that handles the 'downloads' side endpoint
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
 * New Methods:
 *   - in_category
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIForDownload extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "download");
    $this->methods = array('all', 'id', 'in_category');
  }

  /**
   * Get a specific download.
   *
   * @param  $id      id for the download being fetched.
   * @return api_response
   */
  public function id($id) {
    if (!$id) throw new ArcticDeskAPIException("ID is required to fetch this download!");
    return $this->__execute("view", array("id" => $id));
  }

  /**
   * List all downloads in a particular category
   *
   * @param  $category_id id of the category being polled.
   * @return api_response
   */
  public function in_category($category_id) {
    if (!$category_id) throw new ArticleDeskAPIException("Category ID is required to list all downloads for a category!");
    return $this->__execute("category_view", array("category_id" => $category_id));
  }

}
