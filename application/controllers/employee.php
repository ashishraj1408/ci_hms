<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access to this script if CodeIgniter is not defined

class Employee extends CI_Controller { // Define the Employee class that extends CI_Controller

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

	public function add() // Method to add a new employee
	{
		// Check if required fields are posted
		if($this->input->post("username") && $this->input->post("password") && $this->input->post("email"))
		{
			$username = $this->input->post("username"); // Get the username from posted data
			$password = $this->input->post("password"); // Get the password from posted data
			$firstname = $this->input->post("firstname"); // Get the first name from posted data
			$lastname = $this->input->post("lastname"); // Get the last name from posted data
			$telephone = $this->input->post("telephone"); // Get the telephone from posted data
			$email = $this->input->post("email"); // Get the email from posted data
			$department_id = $this->input->post("department_id"); // Get the department ID from posted data
			$type = $this->input->post("type"); // Get the type from posted data
			$salary = $this->input->post("salary"); // Get the salary from posted data
			$hiring_date = $this->input->post("hiring_date"); // Get the hiring date from posted data
			
			$this->employee_m->addEmployee($username, $password, $firstname, $lastname, $telephone, $email, $department_id, $type, $salary, $hiring_date); // Call model method to add the employee
			redirect("/employee"); // Redirect to the employee list page
		}

		$data = array('title' => 'Add Employee - ', 'page' => 'employee'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view with the specified data
		$departments = $this->employee_m->getDepartments(); // Retrieve the list of departments from the model
		$viewdata = array('departments' => $departments); // Prepare data for the add view
		$this->load->view('employee/add',$viewdata); // Load the add employee view with department data
		$this->load->view('footer'); // Load the footer view
	}

	function delete($employee_id) // Method to delete an employee by ID
	{
		$this->employee_m->deleteEmployee($employee_id); // Call model method to delete the employee
		redirect("/employee"); // Redirect to the employee list page
	}

	public function edit($employee_id) // Method to edit an employee's details
	{
		// Check if required fields are posted
		if($this->input->post("username") && $this->input->post("password") && $this->input->post("email"))
		{
			$username = $this->input->post("username"); // Get the username from posted data
			$password = $this->input->post("password"); // Get the password from posted data
			$firstname = $this->input->post("firstname"); // Get the first name from posted data
			$lastname = $this->input->post("lastname"); // Get the last name from posted data
			$telephone = $this->input->post("telephone"); // Get the telephone from posted data
			$email = $this->input->post("email"); // Get the email from posted data
			$department_id = $this->input->post("department_id"); // Get the department ID from posted data
			$type = $this->input->post("type"); // Get the type from posted data
			$salary = $this->input->post("salary"); // Get the salary from posted data
			$hiring_date = $this->input->post("hiring_date"); // Get the hiring date from posted data
			
			$this->employee_m->editEmployee($employee_id, $username, $password, $firstname, $lastname, $telephone, $email, $department_id, $type, $salary, $hiring_date); // Call model method to edit the employee
			redirect("/employee"); // Redirect to the employee list page
		}
		
		$data = array('title' => 'Edit Employee - ', 'page' => 'employee'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view

		$departments = $this->employee_m->getDepartments(); // Retrieve department data from the model
		$employee = $this->employee_m->getEmployee($employee_id); // Retrieve employee data by ID
		
		$viewdata = array('departments' => $departments, 'employee'  => $employee[0]); // Prepare data for the edit view

		$this->load->view('employee/edit',$viewdata); // Load the edit employee view with department and employee data

		$this->load->view('footer'); // Load the footer view
	}

	public function index() // Method to display the list of employees
	{
		$employees = $this->employee_m->get_employees(); // Retrieve the list of employees from the model

		$viewdata = array('employees' => $employees); // Prepare data for the view

		$data = array('title' => 'Employees - ', 'page' => 'employee'); // Prepare data for the header view
		$this->load->view('header', $data); // Load the header view
		$this->load->view('employee/list',$viewdata); // Load the employees list view with employee data
		$this->load->view('footer'); // Load the footer view
	}
}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
