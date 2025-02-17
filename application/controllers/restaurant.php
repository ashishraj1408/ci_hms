<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Restaurant extends CI_Controller { // Define the Restaurant class extending CI_Controller

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

	public function add() // Method to add a new restaurant
	{
		if($this->input->post("restaurantName")) // Check if the form has been submitted
		{
			// Retrieve form data
			$restaurantName = $this->input->post("restaurantName");
			$restaurantOpenTime = $this->input->post("restaurantOpenTime");
			$restaurantCloseTime = $this->input->post("restaurantCloseTime");
			$restaurantDetails = $this->input->post("restaurantDetails");
			$tableCount = $this->input->post("tableCount");
			
			// Call the model method to add the restaurant
			$this->restaurant_m->addRestaurant($restaurantName, $restaurantOpenTime, $restaurantCloseTime, $restaurantDetails, $tableCount);
			redirect("/restaurant"); // Redirect to the restaurant list after adding
		}

		// Prepare data for the view
		$data = array('title' => 'Add Restaurant - ', 'page' => 'restaurant');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('restaurant/add'); // Load the add restaurant view
		$this->load->view('footer'); // Load the footer view
	}

	function delete($restaurant_name) // Method to delete a restaurant
	{
		$restaurant_name = urldecode($restaurant_name); // Decode the restaurant name
		$this->restaurant_m->deleteRestaurant($restaurant_name); // Call the model to delete the restaurant
		redirect("/restaurant"); // Redirect to the restaurant list
	}

	public function edit($restaurant_name) // Method to edit a restaurant
	{
		$restaurant_name = urldecode($restaurant_name); // Decode the restaurant name
		if($this->input->post("restaurantName")) // Check if the form has been submitted
		{
			// Retrieve form data
			$restaurant_name = $this->input->post("restaurantName");
			$restaurant_open_time = $this->input->post("restaurantOpenTime");
			$restaurant_close_time = $this->input->post("restaurantCloseTime");
			$restaurant_details = $this->input->post("restaurantDetails");
			$table_count = $this->input->post("tableCount");
			
			// Call the model method to edit the restaurant
			$this->restaurant_m->editRestaurant($restaurant_name, $restaurant_open_time, $restaurant_close_time, $restaurant_details, $table_count);
			redirect("/restaurant"); // Redirect to the restaurant list after editing
		}
		$data = array('title' => 'Edit Restaurant - ', 'page' => 'restaurant');
		$this->load->view('header', $data); // Load the header view
		$restaurant = $this->restaurant_m->getRestaurant($restaurant_name); // Get restaurant details
		$viewdata = array('restaurant' => $restaurant[0]); // Prepare view data with the restaurant details
		$this->load->view('restaurant/edit', $viewdata); // Load the edit restaurant view

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to list all restaurants
	{
		$restaurants = $this->restaurant_m->get_restaurants(); // Get all restaurants
		$customers = $this->customer_m->get_active_customers(); // Get active customers

		// Prepare view data
		$viewdata = array('restaurants' => $restaurants, 'customers' => $customers);

		$data = array('title' => 'Restaurants - ', 'page' => 'restaurant');
		$this->load->view('header', $data); // Load the header view
		$this->load->view('restaurant/list', $viewdata); // Load the restaurant list view
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
