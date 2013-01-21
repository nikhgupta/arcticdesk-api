<?php

/**
 * Class that handles the 'users' side endpoint
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
class ArcticDeskAPIForUser extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "user");
  }

  /**
   * Add a new user.
   *
   * @param  $data    Data for this new user.
   *                  Required data:
   *                    - full_name
   *                    - email
   *                    - password
   *                  Optional data:
   *                    - company
   *                    - country_two_letter_iso_code
   *                    - twitter
   *                    - facebook_id
   *                    - send_details (must be boolean)
   * @return api_response
   */
  public function add($data = array()) {
    $data = $this->__prepare( $data );
    # add this new user
    return parent::add($data); 
  }

  /**
   * Update an existing user.
   *
   * @param  $id      ID of the user being edited.
   * @param  $data    Updated data for this user.
   *                  Required data:
   *                    - id (of the user)
   *                    - full_name
   *                    - email
   *                    - password
   *                  Optional data:
   *                    - company
   *                    - country_two_letter_iso_code
   *                    - twitter
   *                    - facebook_id
   *                    - send_details (must be boolean)
   * @return api_response
   */
  public function edit($id, $data = array()) {
    $data = $this->__prepare( $data );
    # update this user
    return parent::edit($id, $data);
  }

  /**
   * Prepare data for a post request for this user.
   *
   * @param  $options   the data attributes for this request
   * @return api_response
   */
  private function __prepare($options = array()) {
    $data = array();

    # add required attributes
    $this->__add_attribute($data, $options, "full_name");
    $this->__add_attribute($data, $options, "password");
    $this->__add_attribute($data, $options, "email");

    # add optional attributes
    $this->__add_optional_attribute($data, $options, "company");
    $this->__add_optional_attribute($data, $options, "country_iso_two_letter_code");
    $this->__add_optional_attribute($data, $options, "twitter");
    $this->__add_optional_attribute($data, $options, "facebook_id");

    # should I send the new details to this user?
    $data["send_details"]   = ($options["send_details"] === false) ? "0" : "1";

    return $data;
  }

}
