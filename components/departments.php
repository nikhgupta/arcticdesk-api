<?php

/**
 * Class that handles the 'department' side endpoint
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
class ArcticDeskAPIForDepartment extends ArcticDeskAPIComponent {

  /**
   * Initialize this component.
   *
   * @param  $arcticdesk arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "department");
  }

  /**
   * Add a new department.
   *
   * @param  $data    Data for this new department.
   *                  Required data:
   *                    - name
   *                  Optional data:
   *                    - parent (department id)
   *                    - email  (for this department)
   *                    - description
   *                    - visible (must supply as boolean)
   *                    - operators
   *                      - can be an array of operator IDs
   *                      - can be a comma-separated list of IDs
   * @return api_response
   */
  public function add($data = array()) {
    $data = $this->__prepare( $data );
    # add this department
    return parent::add($data);
  }

  /**
   * Update an existing department.
   *
   * @param  $id      ID of the department being edited
   * @param  $data    Updated data for this department
   *                  Required data:
   *                    - id (of the department)
   *                    - name
   *                  Optional data:
   *                    - parent (department id)
   *                    - email  (for this department)
   *                    - description
   *                    - visible (must supply as boolean)
   *                    - operators
   *                      - can be an array of operator IDs
   *                      - can be a comma-separated list of IDs
   * @return api_response
   */
  public function edit($id, $data = array()) {
    $data = $this->__prepare( $data );
    # update this department
    return parent::edit($id, $data);
  }

  /**
   * Prepare data for a post request for this department.
   *
   * @param  $options  the data attributes for this request
   * @return api_response
   */
  private function __prepare($options = array()) {
    $data = array();

    # add required attributes
    $this->__add_attribute($data, $options, "name");

    # add optional attributes
    $this->__add_optional_attribute($data, $options, "email");
    $this->__add_optional_attribute($data, $options, "parent");
    $this->__add_optional_attribute($data, $options, "description");

    # attach the operators for this department
    if (is_array($options["operators"])) $data["operators"] = $options["operators"];
    elseif (is_string($options["operators"])) $data["operators"] = explode($options["operators"], ",");
    else $data["operators"] = array();

    # is the department visible
    $data["visible"]   = ($options["visible"] === false) ? "0" : "1";

    return $data;
  }
}
