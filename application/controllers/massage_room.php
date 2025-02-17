<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Massage_room extends CI_Controller { // Define the Massage_room class that extends CI_Controller

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

	public function add() // Method to add a new massage room
	{
		// Check if the massageroomOpenTime field is posted
		if($this->input->post("massageroomOpenTime"))
		{
			$massageroomOpenTime = $this->input->post("massageroomOpenTime"); // Get the open time from posted data
			$massageroomCloseTime = $this->input->post("massageroomCloseTime"); // Get the close time from posted data
			$massageroomDetails = $this->input->post("massageroomDetails"); // Get the details from posted data
			
			// Call model method to add the massage room with the provided details
			$this->massage_room_m->addMassageroom($massageroomOpenTime, $massageroomCloseTime, $massageroomDetails);
			redirect("/massage_room"); // Redirect to the list of massage rooms
		}

		// Prepare data for the add massage room page
		$data = array('title' => 'Add Massage Room - ', 'page' => 'massage_room');
		$this->load->view('header', $data); // Load the header view with the specified data
		$this->load->view('massage_room/add'); // Load the add massage room view
		$this->load->view('footer'); // Load the footer view
	}

	function delete($massageroom_id) // Method to delete a massage room by ID
	{
		$this->massage_room_m->deleteMassageroom($massageroom_id); // Call model method to delete the massage room
		redirect("/massage_room"); // Redirect to the list of massage rooms
	}

	public function edit($massageroom_id) // Method to edit a massage room's details
	{
		// Check if the massageroomOpenTime field is posted
		if($this->input->post("massageroomOpenTime"))
		{
			$massageroom_open_time = $this->input->post("massageroomOpenTime"); // Get the open time from posted data
			$massageroom_close_time = $this->input->post("massageroomCloseTime"); // Get the close time from posted data
			$massageroom_details = $this->input->post("massageroomDetails"); // Get the details from posted data
			
			// Call model method to edit the massage room with the provided details
			$this->massage_room_m->editMassageroom($massageroom_id, $massageroom_open_time, $massageroom_close_time, $massageroom_details);
			redirect("/massage_room"); // Redirect to the list of massage rooms
		}

		// Prepare data for the edit massage room page
		$data = array('title' => 'Edit Massage Room - ', 'page' => 'massage_room');
		$this->load->view('header', $data); // Load the header view
		$Massagerooms = $this->massage_room_m->get_massageroom($massageroom_id); // Get the massage room details by ID
		$viewdata = array('massage_room'  => $Massagerooms[0]); // Prepare data for the edit view
		$this->load->view('massage_room/edit',$viewdata); // Load the edit massage room view with the retrieved data

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to display the list of massage rooms
	{
		$massageRooms = $this->massage_room_m->get_massageRooms(); // Retrieve the list of massage rooms from the model
		$customers = $this->customer_m->get_active_customers(); // Retrieve the list of active customers from the model

		$viewdata = array('massageRooms' => $massageRooms, 'customers' => $customers); // Prepare data for the list view

		// Prepare data for the header view
		$data = array('title' => 'Massage Room - ', 'page' => 'massage_room');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('massage_room/list',$viewdata); // Load the list of massage rooms view with the retrieved data
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
