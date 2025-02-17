<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Room extends CI_Controller { // Define the Room class extending CI_Controller

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or - 
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function check_login() // Method to verify user login
	{
		if(!UID) // If the user ID is not set
			redirect("login"); // Redirect to the login page
	} 

	public function add() // Method to add a new room range
	{
		$viewdata = array(); // Initialize view data
		// Check if required POST data is present
		if($this->input->post("room_type") && $this->input->post("min_id") && $this->input->post("max_id"))
		{
			// Retrieve form data
			$new_room_type = $this->input->post("room_type");
			$new_min_id = intval($this->input->post("min_id"));
			$new_max_id = intval($this->input->post("max_id"));

			// Check for existing room range availability
			$rooms_avail = count($this->room_m->getRoomRange($new_room_type, $new_min_id, $new_max_id));

			// Validate the provided range
			if($new_min_id > $new_max_id) {
				$viewdata['error'] = "Range is not valid [$new_min_id, $new_max_id]";
			} else if($rooms_avail !== 0) {
				$viewdata['error'] = "Range is not available [$new_min_id, $new_max_id]";
			} else {
				// Add the new room range if valid
				$this->room_m->addRoomRange($new_room_type, $new_min_id, $new_max_id);
				redirect("/room"); // Redirect to the room list
			}
		}
		// Prepare data for the view
		$data = array('title' => 'Add Rooms - ', 'page' => 'room');
		$this->load->view('header', $data); // Load the header view

		$room_types = $this->room_m->get_room_types(); // Get room types for the dropdown
		$viewdata['room_types'] = $room_types; // Pass room types to the view
		$this->load->view('room/add', $viewdata); // Load the add room view

		$this->load->view('footer'); // Load the footer view
	}

	function delete($min_id, $max_id) // Method to delete a room range
	{
		$this->room_m->deleteRoomRange($min_id, $max_id); // Call the model to delete the room range
		redirect("/room"); // Redirect to the room list
	}

	public function edit($room_type, $min_id, $max_id) // Method to edit a room range
	{
		$viewdata = array(); // Initialize view data
		// Check if required POST data is present
		if($this->input->post("room_type") && $this->input->post("min_id") && $this->input->post("max_id"))
		{
			// Retrieve form data
			$new_room_type = $this->input->post("room_type");
			$new_min_id = intval($this->input->post("min_id"));
			$new_max_id = intval($this->input->post("max_id"));

			// Check for existing room range availability
			$rooms_avail = count($this->room_m->isAvailRange($room_type, $new_min_id, $new_max_id));

			// Validate the provided range
			if($new_min_id > $new_max_id) {
				$viewdata['error'] = "Range is not valid [$new_min_id, $new_max_id]";
			} else if($rooms_avail !== 0) {
				$viewdata['error'] = "Range is not available [$new_min_id, $new_max_id]";
			} else {
				// Delete the old range and add the new range if valid
				$this->room_m->deleteRoomRange($min_id, $max_id);
				$this->room_m->addRoomRange($new_room_type, $new_min_id, $new_max_id);
				redirect("/room"); // Redirect to the room list
			}
		}
		// Prepare data for the view
		$data = array('title' => 'Edit Rooms - ', 'page' => 'room');
		$this->load->view('header', $data); // Load the header view

		$room_types = $this->room_m->get_room_types(); // Get room types for the dropdown

		// Prepare room range details for the edit form
		$room_range = new stdClass();
		$room_range->room_type = $room_type;
		$room_range->min_id = $min_id;
		$room_range->max_id = $max_id;
		$viewdata['room_range'] = $room_range; // Pass room range to the view
		$viewdata['room_types'] = $room_types; // Pass room types to the view
		$this->load->view('room/edit', $viewdata); // Load the edit room view

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to list all rooms
	{
		$rooms = $this->room_m->get_rooms(); // Get all rooms

		$viewdata = array('rooms' => $rooms); // Prepare view data

		$data = array('title' => 'Rooms - ', 'page' => 'room');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('room/list', $viewdata); // Load the room list view
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
