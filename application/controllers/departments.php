<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Departments extends CI_Controller { // Define the Departments class that extends CI_Controller

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

	public function check_login() // Method to check if the user is logged in
	{
		if(!UID) // If the UID (user ID) is not set
			redirect("login"); // Redirect to the login page
	} 

	public function add() // Method to add a new department
	{
		if($this->input->post("departmentName")) // Check if the departmentName field is posted
		{
			$departmentName = $this->input->post("departmentName"); // Get the department name from posted data
			$departmentBudget = $this->input->post("departmentBudget"); // Get the department budget from posted data
			
			$this->departments_m->addDepartment($departmentName, $departmentBudget); // Call the model method to add the department
			redirect("/departments"); // Redirect to the departments list page
		}

		$data = array('title' => 'Add Department - ', 'page' => 'departments'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view with the specified data
		$departments = $this->departments_m->get_departments(); // Retrieve the list of departments from the model
		$viewdata = array('departments' => $departments); // Prepare data for the add view
		$this->load->view('departments/add',$viewdata); // Load the add department view with department data
		$this->load->view('footer'); // Load the footer view
	}

	function delete($department_id) // Method to delete a department by ID
	{
		$this->departments_m->deleteDepartment($department_id); // Call model method to delete the department
		redirect("/departments"); // Redirect to the departments list page
	}

	public function edit($department_id) // Method to edit a department's details
	{
		if($this->input->post("departmentName")) // Check if the departmentName field is posted
		{
			$departmentName = $this->input->post("departmentName"); // Get the department name from posted data
			$departmentBudget = $this->input->post("departmentBudget"); // Get the department budget from posted data
			
			$this->departments_m->editEmployee($department_id, $departmentName, $departmentBudget); // Call model method to edit the department
			redirect("/departments"); // Redirect to the departments list page
		}
		
		$data = array('title' => 'Edit Department - ', 'page' => 'departments'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view

		$department = $this->departments_m->getDepartment($department_id); // Retrieve department data by ID
		
		$viewdata = array('department'  => $department[0]); // Prepare data for the edit view

		$this->load->view('departments/edit',$viewdata); // Load the edit department view with the department data

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to display the list of departments
	{
		$departments = $this->departments_m->get_departments(); // Retrieve the list of departments from the model

		$viewdata = array('departments' => $departments); // Prepare data for the view

		$data = array('title' => 'Departments - ', 'page' => 'departments'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view
		$this->load->view('departments/list',$viewdata); // Load the departments list view with department data
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
