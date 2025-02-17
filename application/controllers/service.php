<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Service extends CI_Controller { // Define the Service class extending CI_Controller

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
    if(!UID) // If the user ID is not set
      redirect("login"); // Redirect to the login page
  } 

  public function restaurant() // Method to handle adding restaurant service
  {
    $this->check_login(); // Ensure the user is logged in
    // Retrieve data from POST request
    $restaurant = $this->input->post("restaurant");
    $customer = $this->input->post("customer");
    $date = $this->input->post("date");
    $table_num = $this->input->post("table_num");
    $price = $this->input->post("price");

    // Add the service using the model
    $this->restaurant_m->add_service($restaurant, $customer, $date, $table_num, $price);

    // Prepare data for the view
    $data = array('page' => 'restaurant', 'title' => 'Add Restaurant Service');
    $vdata = array('type' => 'restaurant');
    // Load the views
    $this->load->view("header", $data);
    $this->load->view('service_success', $vdata);
    $this->load->view('footer');
  }

  public function massage_room() // Method to handle adding massage room service
  {
    $this->check_login(); // Ensure the user is logged in
    // Retrieve data from POST request
    $massage = $this->input->post("massage");
    $customer = $this->input->post("customer");
    $date = $this->input->post("date");
    $details = $this->input->post("details");
    $price = $this->input->post("price");

    // Add the service using the model
    $this->massage_room_m->add_service($massage, $customer, $date, $details, $price);

    // Prepare data for the view
    $data = array('page' => 'massage_room', 'title' => 'Add Massage Service');
    $vdata = array('type' => 'massage_room');
    // Load the views
    $this->load->view("header", $data);
    $this->load->view('service_success', $vdata);
    $this->load->view('footer');
  }

  public function sport_facility() // Method to handle adding sport facility service
  {
    $this->check_login(); // Ensure the user is logged in
    // Retrieve data from POST request
    $sport = $this->input->post("sport");
    $customer = $this->input->post("customer");
    $date = $this->input->post("date");
    $details = $this->input->post("details");
    $price = $this->input->post("price");

    // Add the service using the model
    $this->sport_facility_m->add_service($sport, $customer, $date, $details, $price);

    // Prepare data for the view
    $data = array('page' => 'sport_facility', 'title' => 'Add Sport Service');
    $vdata = array('type' => 'sport_facility');
    // Load the views
    $this->load->view("header", $data);
    $this->load->view('service_success', $vdata);
    $this->load->view('footer');
  }

}

/* End of file welcome.php */ // End of the file
/* Location: ./application/controllers/welcome.php */ // Location of the file in the application
