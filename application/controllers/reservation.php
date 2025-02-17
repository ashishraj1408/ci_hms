<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Reservation extends CI_Controller { // Define the Reservation class that extends CI_Controller

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

	public function check_login() // Method to check if a user is logged in
	{
		if(!UID) // If the UID (user ID) is not set
			redirect("login"); // Redirect to the login page
	} 

	public function check($ref="") { // Method to check customer reservation details
		$post = $this->input->post(); // Get POST data from the form

		$customer = $this->customer_m->get_customer($post['customer_TCno']); // Retrieve customer details using the TC number
		$viewdata = array(); // Initialize an array to hold view data

		$data = array('title' => 'Add Customer - ', 'page' => 'reservation'); // Prepare data for the header
		$this->load->view('header', $data); // Load the header view

		if(!$customer) { // If customer does not exist
			$viewdata['error'] = "Customer does not exist"; // Set error message
		} else {
			// Retrieve available rooms based on the provided room type and dates
			$rooms = $this->reservation_m->get_available_rooms($post['room_type'], $post['checkin_date'], $post['checkout_date']);
			if(!$rooms) { // If no rooms are available
				$viewdata['error'] = "No available rooms"; // Set error message
			}
		}

		// Check if there is an error message to display
		if(isset($viewdata['error'])){
			$room_types = $this->room_m->get_room_types(); // Retrieve available room types
			$viewdata['room_types'] = $room_types; // Add room types to view data
			$this->load->view('reservation/add', $viewdata); // Load the add reservation view with error message
		} else {
			// Prepare view data for successful room availability check
			$viewdata['rooms'] = $rooms;
			$viewdata['customer_TCno'] = $post['customer_TCno'];
			$viewdata['checkin_date'] = $post['checkin_date'];
			$viewdata['checkout_date'] = $post['checkout_date'];
			$viewdata['room_type'] = $post['room_type'];
			$this->load->view('reservation/list', $viewdata); // Load the list of available rooms view
		}

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to display the reservation form
	{
		$this->check_login(); // Check if user is logged in

		$room_types = $this->room_m->get_room_types(); // Retrieve available room types
		$viewdata = array('room_types' => $room_types); // Prepare view data with room types
		$data = array('title' => 'Reservation - ', 'page' => 'reservation'); // Prepare data for the header
		$this->load->view('header', $data); // Load the header view
		$this->load->view('reservation/add', $viewdata); // Load the add reservation view
		$this->load->view('footer'); // Load the footer view
	}

	public function make() // Method to create a reservation
	{
		$post = $this->input->post(); // Get POST data from the form

		$customer = $this->customer_m->get_customer($post['customer_TCno']); // Retrieve customer details
		$customer = $customer[0]; // Get the first result from the customer array
		$viewdata = array(); // Initialize an array for view data
		$data = array(); // Initialize an array for reservation data
		$data['customer_id'] = $customer->customer_id; // Set customer ID
		$data['room_id'] = $post['room_id']; // Set room ID
		$data['checkin_date'] = $post['checkin_date']; // Set check-in date
		$data['checkout_date'] = $post['checkout_date']; // Set check-out date
		$data['employee_id'] = UID; // Set the employee ID

		$date = new DateTime(); // Create a new DateTime object
		$date_s = $date->format('Y-m-d'); // Format the current date
		if($date_s > $data['checkin_date']) { // Check if the check-in date is before today
			$viewdata['error'] = "Checkin can't be before today"; // Set error message
		} else {
			// Call model methods to add the reservation and room sale
			$this->reservation_m->add_reservation($data);
			$this->room_m->add_room_sale($data, $date_s);
			$viewdata['success'] = 'Reservation successfully made'; // Set success message
		}

		$room_types = $this->room_m->get_room_types(); // Retrieve room types for the form
		$viewdata['room_types'] = $room_types; // Add room types to view data

		$data = array('title' => 'Reservation - ', 'page' => 'reservation'); // Prepare data for the header
		$this->load->view('header', $data); // Load the header view
		$this->load->view('reservation/add', $viewdata); // Load the add reservation view with success/error messages
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
