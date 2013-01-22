<?php

/**
 * Class that handles API endpoint for a specific
 * component inside ArcticDesk
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIComponent {

  /**
   * Identifier for this particular component.
   *
   * @var string
   */
  public $identifier;

  /**
   * Pluralized identifier for this particular component.
   *
   * @var string
   */
  public $pluralized;

  /**
   * Name of this particular component.
   * Will be set by constructor method.
   *
   * @var string
   */
  public $name;

  /**
   * An array of available API methods for this component
   *
   * @var string
   */
  public $methods;

  /**
   * Initialize our new component.
   *
   * @param  $arcticdesk arcticdesk api instance
   * @param  $identifier identifier for this component
   * @return void
   */
  public function __construct($arcticdesk, $identifier, $nice_name = null) {
    if (!$nice_name) $nice_name = $identifier;
    $this->name       = ucwords(str_replace("_", " ", $nice_name));
    $this->arcticdesk = $arcticdesk;
    $this->identifier = $identifier;
    $this->pluralized = "{$nice_name}s";
    $this->methods    = array("all", "id", "add", "edit", "delete");
  }

  /**
   * Get a list all items for this component.
   *
   * @param  $data    search data for this item
   * @return api_response
   */
  public function all($data = array()) {
    $this->__raise_exception_unless_supported(__FUNCTION__);

    return $this->__execute("all", $data);
  }

  /**
   * Get a specific item for this component.
   *
   * Since, no direct method is provided for this purpose,
   * we first fetch a list of all items for this component,
   * and then, return the specific id being requested.
   *
   * In general, since this method fetches a list of all items
   * to return the requisite item, it is advised to use `all()`
   * method when we need more than one department, and thus,
   * save some api requests from our side.
   *
   * @param  $id id of the item
   * @return api_response
   */
  public function id($id) {
    $this->__raise_exception_unless_supported(__FUNCTION__);
    if (!$id) throw new ArcticDeskAPIException("ID is required to get this {$this->name}");

    $key  = "{$this->identifier}_id";
    $list = $this->all();
    foreach ($list as $item) {
      if ($item->$key == $id) return $item;
    }

    # raise an exception if the $id was not found
    throw new ArcticDeskAPIException("{$this->name} with ID: '$id' was not found!");
  }

  /**
   * Add a new item for this component.
   *
   * @param  $data an array of data for this item
   * @return api_response
   */
  public function add($data = array()) {
    $this->__raise_exception_unless_supported(__FUNCTION__);

    return $this->__execute("add", $data, "POST");
  }

  /**
   * Edit an existing item for this component.
   *
   * @param  $id   id of the item being edited
   * @param  $data an array of data for this item
   * @return api_response
   */
  public function edit($id, $data = array()) {
    $this->__raise_exception_unless_supported(__FUNCTION__);
    if (!$id) throw new ArcticDeskAPIException("ID is required to edit this {$this->name}");

    $data = array_merge($data, array("id" => $id));
    return $this->__execute("edit", $data, "PUT");
  }

  /**
   * Delete an existing item for this component.
   *
   * @param  $id   id of the item being deleted
   * @return api_response
   */
  public function delete($id) {
    $this->__raise_exception_unless_supported(__FUNCTION__);
    if (!$id) throw new ArcticDeskAPIException("ID is required to delete this {$this->name}");

    return $this->__execute("delete", array("id" => $id), "DELETE");
  }

  /**
   * Raise an exception if the given method is not supported by the current
   * component.
   *
   * @param  $method   name of the method being checked for support
   * @return void
   * @throw  ArcticDeskAPIException
   */
  public function __raise_exception_unless_supported($method) {
    if ($method && in_array($method, $this->methods)) return;
    throw new ArcticDeskAPIException(
      "Method: '$method' is not supported for component: '{$this->name}'"
    );
  }

  /**
   * Execute an API call using this component as an endpoint.
   *
   * @param  $endpoint   String representation of the end-part of the url
   *                     where the CURL request will be posted. This should not 
   *                     include the domain name or the identifier for this 
   *                     component, e.g. in order to post a request to:
   *                     http://yourdomain.com/api/department/all
   *                     you must provide: "all" as the $endpoint, if we the 
   *                     current component is "department"
   * @param  $data       An array of data to be posted (or supplied in the 
   *                     query form) along with this request
   * @param  $method     A string representing the type of CURL request, we
   *                     are making. It can be one of the following:
   *                     GET, POST, PUT, DELETE
   * @return api_response
   */
	public function __execute($endpoint = "", $data = false, $method = "GET") {
    // create the requisite api url for this request
    // we must supply 'time' and 'hash', as required by ArcticDesk, here.
    $time = date('U');
    $hash = md5($this->arcticdesk->key . "@@@@" . $time);
    $auth = "time={$time}&hash={$hash}";
    $url  = $this->arcticdesk->url;
    if ($this->identifier) $url .= "/" . $this->identifier;
    if ($endpoint) $url .= "/" . $endpoint;
    $url .= ".json?{$auth}";

    // set the operator who is on work
    if ($this->operator_on_work) $url .= "&staff_id={$this->operator_on_work}";

    // initiate a curl session and setup options, as required.
    $curl = curl_init();
    switch ($method) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
      case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
      default:
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) $url = sprintf("%s&%s", $url, http_build_query($data));
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // execute this curl, and close it afterwards.
    $data = curl_exec($curl);
    curl_close($curl);

    // decode the json response from ArcticDesk
    $data = json_decode($data);

    # raise an exception if the API gave us an error
    if ($data->error) throw new ArcticDeskAPIException("API {$data->error->code} Error: {$data->error->message}");

    return $data;
  }

  /**
   * add an attribute in the request data
   *
   * @param  $data         request data array for this request
   * @param  $options      user supplied data array for this request
   * @param  $attribute    attribute we are adding
   * @param  $is_required  true, if the attribute is required
   * @return array
   */
  public function __add_attribute(&$data, $options, $attribute, $is_required = true) {
    if (!$attribute) return;
    if ($is_required && !$options[$attribute])
      throw new ArcticDeskAPIException("{$this->name}: '$attribute' is required!");
    if ($options[$attribute]) $data[$attribute] = $options[$attribute];
    return $data;
  }

  /**
   * add an optional attribute in the request data
   *
   * @param  $data         request data array for this request
   * @param  $options      user supplied data array for this request
   * @param  $attribute    attribute we are adding
   * @return array
   */
  public function __add_optional_attribute(&$data, $options, $attribute) {
    return $this->__add_attribute($data, $options, $attribute, false);
  }
}
