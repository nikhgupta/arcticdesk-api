<?php

/**
 * A PHP wrapper around ArcticDesk API.
 *
 * Example code:
 *     
 *     require 'arcticdesk.php';
 *     $ad = new ArcticDesk("yourdomain.com", "your_api_key");
 *     
 *     # grab a list of all departments
 *     $departments = $ad->departments->all();
 *
 * @package whmcs
 * @author Nikhil Gupta <me@nikhgupta.com>
 **/
class ArcticDeskAPI {

  /**
   * Domain name for the ArcticDesk server.
   *
   * @var string
   */
	public $domain;

  /**
   * Api key for the above ArcticDesk server.
   *
   * @var string
   */
	public $key;

  /**
   * Api endpoint for the above ArcticDesk server.
   * This is, automatically, set by the constructor method.
   *
   * @var string
   */
  public $url;

  /**
   * ID of the operator using the API.
   * This can also be the ID of a unique operator
   * specially created for the API purposes, so that
   * all API calls/transactions can be tracked, easily.
   *
   * @var integer
   */
  public $operator_on_work;

  /**
   * Constructor method for this ArcticDesk instance.
   *
   * @param  $domain     domain name for the ArcticDesk server
   * @param  $key        API key for this ArcticDesk server
   * @param  $as_index   Set this to true, if 'index.php' is required when 
   *                     calling the API. This is useful when the ArcticDesk's 
   *                     API is accessible only at:
   *                     http://yourdomain.com/api/index.php
   *                     and not:
   *                     http://yourdomain.com/api/
   * @return void
   */
	public function __construct($domain = null, $key = null, $as_index = false) {
		$this->domain = $domain;
		$this->key    = $key;
    $this->url    = "http://{$this->domain}/api";
    if ($as_index) $this->url .= "/index.php";

    # populate our components
    foreach(get_declared_classes() as $class){
      if(is_subclass_of($class, "ArcticDeskAPIComponent")) {
        # we have a new component, lets proceed
        $component = new $class($this);
        $pluralized_identifier = $component->pluralized;
        $this->$pluralized_identifier = $component;
      }
    }
  }

  /**
   * make the API use a particular operator for its calls.
   *
   * @param  $operator_id  id of the operator doing the current API calls
   * @return void
   */
  public function set_operator_on_work($operator_id) {
    if (!$operator_id) throw new ArcticDeskAPIException("Operator ID can not be empty!");
    # ensure that this operator exists
    $this->operators->id($operator_id); // will throw an error otherwise.
    $this->operator_on_work = $operator_id;
  }

  /**
   * make the API use anonymous calls
   *
   * @return void
   */
  public function remove_operator_from_work() {
    $this->operator_on_work = null;
  }
}

/**
 * Class for Exceptions raised/used by ArcticDesk API
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIException extends Exception
{

}
