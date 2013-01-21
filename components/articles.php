<?php

/**
 * Class that handles the 'knowledgebase' side endpoint
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
class ArcticDeskAPIForArticle extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "knowledgebase", "article");
    $this->methods = array('all', 'id', 'in_category');
  }

  /**
   * Get a specific article.
   *
   * @param  $id      id for the knowledgebase article being fetched.
   * @return api_response
   */
  public function id($id) {
    if (!$id) throw new ArcticDeskAPIException("ID is required to view this knowledgebase article!");
    return $this->__execute("view", array("id" => $id));
  }

  /**
   * List all knowledgebase articles in a particular category
   *
   * @param  $category_id id of the category being polled.
   * @return api_response
   */
  public function in_category($category_id) {
    if (!$category_id) throw new ArticleDeskAPIException("Category ID is required to list all knowledgebase articles for a category!");
    return $this->__execute("category_view", array("category_id" => $category_id));
  }

}
