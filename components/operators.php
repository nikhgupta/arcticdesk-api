<?php

/**
 * Class that handles the 'operators' side endpoint
 * for the ArcticDesk API.
 *
 * Methods Inherited:
 *   - all
 *   - id
 *   - delete
 *
 * Methods Overridden:
 *   - add
 *   - edit
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIForOperator extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "operator");
  }

  /**
   * Add a new operator.
   *
   * @param  $data   Data for this new operator.
   *                 Required data:
   *                   - username
   *                   - password
   *                   - first_name
   *                   - last_name
   *                   - email
   *                   - group_id
   *                 Optional data:
   *                   - allowed_login (must be supplied as boolean)
   *                   - departments
   *                     - can be an array of Department IDs
   *                     - can be a comma-separated list of IDs
   * @return api_response
   */
  public function add($data = array()) {
    $data = $this->__prepare( $data );
    # add this new operator
    return parent::add($data); 
  }

  /**
   * Update an existing operator.
   *
   * @param  $id      ID of the operator being edited.
   * @param  $data    Updated data for this operator.
   *                  Required data:
   *                    - id (of the operator)
   *                    - username
   *                    - password
   *                    - first_name
   *                    - last_name
   *                    - email
   *                    - group_id
   *                  Optional data:
   *                    - allowed_login (must be supplied as boolean)
   *                    - departments
   *                      - can be an array of Department IDs
   *                      - can be a comma-separated list of IDs
   * @return api_response
   */
  public function edit($id, $data = array()) {
    $data = $this->__prepare( $data );
    # update this operator
    return parent::edit($id, $data);
  }

  /**
   * Prepare data for a post request for this operator.
   *
   * @param  $options   the data attributes for this request
   * @return api_response
   */
  private function __prepare($options = array()) {
    $data = array();

    $this->__add_attribute($data, $options, "username");
    $this->__add_attribute($data, $options, "password");
    $this->__add_attribute($data, $options, "first_name");
    $this->__add_attribute($data, $options, "last_name");
    $this->__add_attribute($data, $options, "email");
    $this->__add_attribute($data, $options, "group_id");

    # attach the operators for this department
    if (is_array($options["departments"])) $data["departments"] = $options["departments"];
    elseif (is_string($optoins["departments"])) $data["departments"] = explode($options["departments"], ",");
    else $data["departments"] = array();

    # is this operator allowed to login (i.e. is the operator active) ?
    $data["allowed_login"]   = ($options["allowed_login"] === false) ? "0" : "1";

    return $data;
  }

}
