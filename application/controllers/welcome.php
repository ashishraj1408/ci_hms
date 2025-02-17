<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Prevent direct access if CodeIgniter is not defined

class Welcome extends CI_Controller { // Define the Welcome class extending CI_Controller

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

  public function index() // Index method, typically the default action
  {
    $this->check_login(); // Ensure user is logged in

    // Fetch various statistics for the dashboard
    $today_stats = $this->report_m->today_stats();
    $customer_pay_list = $this->report_m->get_customer_freq_list();
    $customer_most_paid = $this->report_m->get_customer_most_paid();
    $next_week_freq = $this->report_m->get_next_week_freq();
    
    // Prepare data for the header and footer
    $data = array('title' => '', 'page' => 'dashboard');
    $this->load->view('header', $data);

    // Prepare view data for the main dashboard view
    $viewdata = array(
      'today_stats' => $today_stats,
      'customer_pay_list' => $customer_pay_list,
      'customer_most_paid' => $customer_most_paid,
      'next_week_freq' => $next_week_freq
    );
    $this->load->view('welcome_message', $viewdata); // Load the main dashboard view
    $this->load->view('footer', array("next_week_freq"=>$next_week_freq)); // Load footer view

    // Set session data to show a guide
    $this->session->set_userdata('show_guide', true);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
