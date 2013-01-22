<?php

/**
 * Class that handles the 'tickets' side endpoint
 * for the ArcticDesk API.
 *
 * Methods Inherited:
 *   - delete
 *
 * Methods Overridden:
 *   - add
 *   - edit
 *   - all
 *   - id
 *
 * New Methods:
 *   - reply
 *   - reply_edit
 *   - reply_delete
 *
 * @package whmcs
 * @subpackage arcticdesk
 * @author Nikhil Gupta <me@nikhgupta.com>
 */
class ArcticDeskAPIForTicket extends ArcticDeskAPIComponent
{

  /**
   * Initialize this component
   *
   * @param  $arcticdes arctic desk api instance
   * @return void
   */
  public function __construct($arcticdesk) {
    parent::__construct($arcticdesk, "ticket");
    $this->methods = array(
      "all", "id", "add", "edit", "delete",
      "reply", "reply_edit", "reply_delete"
    );
  }

  /**
   * Get a list of all tickets.
   *
   * @param  $data    Search data array for the tickets.
   *                  Optional data:
   *                    - department_id
   *                    - status_id
   *                    - user_id
   *                    - user_email
   *                    - rows (number of records to return)
   *                    - page (current pagination)
   *                    - include_count (must be supplied as boolean)
   * @return api_response
   */
  public function all($data = array()) {
    # should I include the ticket count in the response?
    $data["include_count"] = ($data["include_count"] === false) ? "0" : "1";
    parent::all($data);
  }

  /**
   * Get a specific ticket.
   *
   * @param  $id  id for the ticket being fetched.
   * @return api_response
   */
  public function id($id) {
    if (!$id) throw new ArcticDeskAPIException("ID is required to view this ticket");

    return $this->__execute("view", array("id" => $id));
  }

  /**
   * Add a new ticket.
   *
   * @param  $data    Data for this new ticket.
   *                  Required data:
   *                    - subject
   *                    - message_text
   *                    - user_id (if 'email' and 'full_name' is not given)
   *                    - email (if 'user_id' is not given)
   *                    - full_name (if 'user_id' is not given)
   *                    - priority_id
   *                    - department_id
   *                  Optional data:
   *                    - company
   *                    - attachment_file (optional file array)
   *                    - operator_ticket (must be boolean)
   * @return api_response
   */
  public function add($data = array()) {
    $options = array();

    # add required attributes
    $this->__add_attribute($options, $data, "subject");
    $this->__add_attribute($options, $data, "message_text");
    if ($data["user_id"]) {
      $this->__add_attribute($options, $data, "user_id");
      $this->__add_optional_attribute($options, $data, "email");
      $this->__add_optional_attribute($options, $data, "full_name");
    } else {
      $this->__add_attribute($options, $data, "email");
      $this->__add_attribute($options, $data, "full_name");
      $this->__add_optional_attribute($options, $data, "user_id");
    }

    # add optional attributes
    $this->__add_optional_attribute($options, $data, "company");
    $this->__add_optional_attribute($options, $data, "priority_id");
    $this->__add_optional_attribute($options, $data, "department_id");
    $this->__add_optional_attribute($options, $data, "attachment_file");

    # is this ticket being created or edited by an operator?
    $options["operator_ticket"]   = ($data["operator_ticket"] === false) ? "0" : "1";

    # add this new ticket
    return parent::add($options); 
  }

  /**
   * Update an existing ticket.
   *
   * @param  $id      ID of the ticket being edited.
   * @param  $data    Updated data for this ticket.
   *                  Required data:
   *                    - subject
   *                    - department_id
   *                    - status_id
   *                    - priority_id
   *                  Optional data:
   *                    - assigned_to_operator_id
   * @return api_response
   */
  public function edit($id, $data = array()) {
    $options = array();

    $this->__add_attribute($options, $data, "subject");
    $this->__add_attribute($options, $data, "department_id");
    $this->__add_attribute($options, $data, "status_id");
    $this->__add_attribute($options, $data, "priority_id");

    # leaving this value blank means that the ticket is not assigned to any operator
    $options["assigned_to_operator_id"] = $data["assigned_to_operator_id"] || "";

    # update this ticket
    return parent::edit($id, $options);
  }

  /**
   * Reply to an existing ticket.
   *
   * @param  $data    Data array for this reply.
   *                  Required data:
   *                    - ticket_id
   *                    - message_text
   *                  Optional data:
   *                    - operator_reply (must be boolean)
   * @return api_response
   */
  public function reply($data = array()) {
    $options = array();
    $this->__add_attribute($options, $data, "ticket_id");
    $this->__add_attribute($options, $data, "message_text");

    # is this reply made by an operator?
    $data["operator_reply"]   = ($options["operator_reply"] === false) ? "0" : "1";
    
    return $this->__request("reply_add", $data, "POST");
  }

  /**
   * Make an edit to an existing reply.
   *
   * @param  $id      ID of the reply to edit.
   * @param  $data    Updated data array for this reply.
   *                  Required data:
   *                    - id (of the reply)
   *                    - message_text
   * @return api_response
   */
  public function reply_edit($id, $data = array()) {
    if (!$id) throw new ArcticDeskAPIException("Reply ID is required to edit a ticket reply!");

    $options = $this->__add_attribute(array(), $data, "message_text");

    $this->__execute("reply_edit", $options, "PUT");
  }

  /**
   * Delete an existing reply.
   *
   * @param  $id      ID of the reply to delete.
   * @return api_response
   */
  public function reply_delete($id) {
    if (!$id) throw new ArcticDeskAPIException("Reply ID is required to delete a ticket reply!");

    $this->__execute("reply_delete", array("id" => $id), "DELETE");
  }

}
