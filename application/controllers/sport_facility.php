<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Sport_facility extends CI_Controller { // Define the Sport_facility class extending CI_Controller

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -  
   *    http://example.com/index.php/welcome/index
   *  - or - 
   * Since this controller is set as the default controller in 
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */

  public function check_login() // Method to verify user login
  {
    if(!UID) // Check if the user ID is not set
      redirect("login"); // Redirect to the login page
  } 

  public function add() // Method to add a new sport facility
  {
    if($this->input->post("sportfacilityOpenTime")) // Check if the form is submitted
    {
      // Retrieve data from POST request
      $sportfacilityOpenTime = $this->input->post("sportfacilityOpenTime");
      $sportfacilityCloseTime = $this->input->post("sportfacilityCloseTime");
      $sportfacilityDetails = $this->input->post("sportfacilityDetails");
      
      // Call the model to add the sport facility
      $this->sport_facility_m->addSportfacility($sportfacilityOpenTime, $sportfacilityCloseTime, $sportfacilityDetails);
      redirect("/sport_facility"); // Redirect to the sport facility list
    }

    // Prepare data for the view
    $data = array('title' => 'Add Sport Facility - ', 'page' => 'sport_facility');
    $this->load->view('header', $data);
    $this->load->view('sport_facility/add'); // Load the add view
    $this->load->view('footer'); // Load the footer
  }

  function delete($sportfacility_id) // Method to delete a sport facility by ID
  {
    $this->sport_facility_m->deleteSportfacility($sportfacility_id); // Call the model to delete the facility
    redirect("/sport_facility"); // Redirect to the sport facility list
  }

  public function edit($sportfacility_id) // Method to edit a sport facility
  {
    if($this->input->post("sportfacilityOpenTime")) // Check if the form is submitted
    {
      // Retrieve data from POST request
      $sportfacility_open_time = $this->input->post("sportfacilityOpenTime");
      $sportfacility_close_time = $this->input->post("sportfacilityCloseTime");
      $sportfacility_details = $this->input->post("sportfacilityDetails");
      
      // Call the model to update the sport facility
      $this->sport_facility_m->editSportfacility($sportfacility_id, $sportfacility_open_time, $sportfacility_close_time, $sportfacility_details);
      redirect("/sport_facility"); // Redirect to the sport facility list
    }
    
    // Prepare data for the view
    $data = array('title' => 'Edit Sport Facility - ', 'page' => 'sport_facility');
    $this->load->view('header', $data);
    $sportFacility = $this->sport_facility_m->get_sportfacility($sportfacility_id); // Get the sport facility data
    $viewdata = array('sport_facility'  => $sportFacility[0]); // Pass the data to the view
    $this->load->view('sport_facility/edit', $viewdata); // Load the edit view
    $this->load->view('footer'); // Load the footer 
  }

  public function index() // Method to display the list of sport facilities
  {
    $sportFacilities = $this->sport_facility_m->get_sportFacilities(); // Get all sport facilities
    $customers = $this->customer_m->get_active_customers(); // Get active customers

    // Prepare data for the view
    $viewdata = array('sportFacilities' => $sportFacilities, 'customers' => $customers);
    $data = array('title' => 'Sport Facility - ', 'page' => 'sport_facility');
    $this->load->view('header', $data);
    $this->load->view('sport_facility/list', $viewdata); // Load the list view
    $this->load->view('footer'); // Load the footer
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
