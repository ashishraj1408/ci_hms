<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Login extends CI_Controller { // Define the Login class that extends CI_Controller

	public function check_login() // Method to check if a user is already logged in
	{
		if(UID) // If the UID (user ID) is set, indicating the user is logged in
			redirect("/"); // Redirect to the home page
	} 

	public function index() // Method to handle the login form
	{
		$this->check_login(); // Check if the user is already logged in

		$viewdata = array(); // Initialize an array for view data

		// Check if username and password fields are posted
		if($this->input->post("username") && $this->input->post("password"))
		{
			$username = $this->input->post("username"); // Get the username from posted data
			$password = $this->input->post("password"); // Get the password from posted data

			// Check if the user credentials are valid
			if($user = $this->user_m->check_login($username, $password)) {
				$this->user_l->login($user); // Log the user in using the user model
				redirect("/"); // Redirect to the home page after successful login
			}
			else {
				$viewdata["error"] = true; // Set an error flag if login fails
			}
		}

		// Prepare data for the login page
		$data = array('title' => 'Login - ', 'page' => 'login'); 
		$this->load->view('header', $data); // Load the header view with the specified data
		$this->load->view('login', $viewdata); // Load the login view with any error messages
		$this->load->view('footer'); // Load the footer view
	}

	public function logout() // Method to handle user logout
	{
		$this->user_l->logout(); // Call the logout method from the user library
		redirect("/"); // Redirect to the home page after logout
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
