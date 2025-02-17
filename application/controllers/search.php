<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Search extends CI_Controller { // Define the Search class extending CI_Controller

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

	public function index() // Main method to handle search functionality
	{
		$this->check_login(); // Ensure the user is logged in

		$word = $this->input->post("customer"); // Get the search query from POST data

		$result = $this->report_m->search_customers($word); // Search for customers using the model
		
		$data = array('title' => 'Search - ', 'page' => 'dashboard'); // Prepare data for the view
		$this->load->view('header', $data); // Load the header view

		$vdata = array( // Prepare data for the search results view
			'query' => $word, // The search query
			'result' => $result // The search results
		);
		$this->load->view('search', $vdata); // Load the search results view
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
