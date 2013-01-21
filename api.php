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
   * various components for this ArcticDesk instance.
   */
  public $operators;
  public $departments;
  public $operator_groups;
  public $countries;
  public $ticket_priorities;
  public $ticket_statuses;
  public $users;

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
	public function __construct($domain = nil, $key = nil, $as_index = false) {
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

    var_dump($this); die();
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
