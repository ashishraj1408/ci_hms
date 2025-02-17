<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Medical_service extends CI_Controller { // Define the Medical_service class that extends CI_Controller

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

	public function add() // Method to add a new medical service
	{
		// Check if the medicalserviceOpenTime field is posted
		if($this->input->post("medicalserviceOpenTime"))
		{
			$medicalserviceOpenTime = $this->input->post("medicalserviceOpenTime"); // Get the open time from posted data
			$medicalserviceCloseTime = $this->input->post("medicalserviceCloseTime"); // Get the close time from posted data
			$medicalserviceDetails = $this->input->post("medicalserviceDetails"); // Get the details from posted data
			
			// Call model method to add the medical service with the provided details
			$this->medical_service_m->addMedicalservice($medicalserviceOpenTime, $medicalserviceCloseTime, $medicalserviceDetails);
			redirect("/medical_service"); // Redirect to the list of medical services
		}

		// Prepare data for the add medical service page
		$data = array('title' => 'Add Medical Service - ', 'page' => 'medical_service');
		$this->load->view('header', $data); // Load the header view with the specified data
		$this->load->view('medical_service/add'); // Load the add medical service view
		$this->load->view('footer'); // Load the footer view
	}

	function delete($medicalservice_id) // Method to delete a medical service by ID
	{
		$this->medical_service_m->deleteMedicalservice($medicalservice_id); // Call model method to delete the medical service
		redirect("/medical_service"); // Redirect to the list of medical services
	}

	public function edit($medicalservice_id) // Method to edit a medical service's details
	{
		// Check if the medicalserviceOpenTime field is posted
		if($this->input->post("medicalserviceOpenTime"))
		{
			$medicalservice_open_time = $this->input->post("medicalserviceOpenTime"); // Get the open time from posted data
			$medicalservice_close_time = $this->input->post("medicalserviceCloseTime"); // Get the close time from posted data
			$medicalservice_details = $this->input->post("medicalserviceDetails"); // Get the details from posted data
			
			// Call model method to edit the medical service with the provided details
			$this->medical_service_m->editMedicalservice($medicalservice_id, $medicalservice_open_time, $medicalservice_close_time, $medicalservice_details);
			redirect("/medical_service"); // Redirect to the list of medical services
		}
		
		// Prepare data for the edit medical service page
		$data = array('title' => 'Edit Medical Service - ', 'page' => 'medical_service');
		$this->load->view('header', $data); // Load the header view
		$medicalService = $this->medical_service_m->get_medicalservice($medicalservice_id); // Get the medical service details by ID
		$viewdata = array('medical_service'  => $medicalService[0]); // Prepare data for the edit view
		$this->load->view('medical_service/edit',$viewdata); // Load the edit medical service view with the retrieved data

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to display the list of medical services
	{
		$medicalServices = $this->medical_service_m->get_medicalServices(); // Retrieve the list of medical services from the model
		$customers = $this->customer_m->get_active_customers(); // Retrieve the list of active customers from the model

		$viewdata = array('medicalServices' => $medicalServices, 'customers' => $customers); // Prepare data for the list view

		// Prepare data for the header view
		$data = array('title' => 'Medical Service - ', 'page' => 'medical_service');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('medical_service/list',$viewdata); // Load the list of medical services view with the retrieved data
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
