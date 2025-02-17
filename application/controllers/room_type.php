<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Room_type extends CI_Controller { // Define the Room_type class extending CI_Controller

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

	public function add() // Method to add a new room type
	{
		$viewdata = array(); // Initialize view data

		// Check if required POST data is present
		if($this->input->post("type") && $this->input->post("price") /*&& $this->input->post("quantity")*/)
		{
			// Retrieve form data
			$type = $this->input->post("type");
			$price = $this->input->post("price");
			$details = $this->input->post("details");
			$quantity = $this->input->post("quantity");

			// Check if the room type already exists
			if(count($this->room_m->getRoomType($type)) == 0) {
				// Call the model method to add the room type
				$this->room_m->addRoomType($type, $price, $details, $quantity);
				redirect("/room-type"); // Redirect to the room type list
			} else {
				$viewdata['error'] = "Room type already exists"; // Set error message if room type exists
			}
		}

		// Prepare data for the view
		$data = array('title' => 'Add Room Type - ', 'page' => 'room_type');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('room-type/add', $viewdata); // Load the add room type view
		$this->load->view('footer'); // Load the footer view
	}

	function delete($room_type) // Method to delete a room type
	{
		$this->room_m->deleteRoomType($room_type); // Call the model to delete the room type
		redirect("/room-type"); // Redirect to the room type list
	}

	public function edit($room_type) // Method to edit a room type
	{
		// Check if required POST data is present
		if($this->input->post("type") && $this->input->post("price") /*&& $this->input->post("quantity")*/)
		{
			// Retrieve form data
			$type = $this->input->post("type");
			$price = $this->input->post("price");
			$details = $this->input->post("details");
			$quantity = $this->input->post("quantity");

			// Call the model method to edit the room type
			$this->room_m->editRoomType($type, $price, $details, $quantity);
			redirect("/room-type"); // Redirect to the room type list
		}
		
		$data = array('title' => 'Edit Room Type - ', 'page' => 'room_type');
		$this->load->view('header', $data); // Load the header view

		// Get the current room type details
		$room_type = $this->room_m->getRoomType($room_type);
		
		$viewdata = array('room_type' => $room_type[0]); // Prepare view data with room type details
		$this->load->view('room-type/edit', $viewdata); // Load the edit room type view

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to list all room types
	{
		$room_types = $this->room_m->get_room_types(); // Get all room types

		$viewdata = array('room_types' => $room_types); // Prepare view data

		$data = array('title' => 'Rooms - ', 'page' => 'room_type');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('room-type/list', $viewdata); // Load the room type list view
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
