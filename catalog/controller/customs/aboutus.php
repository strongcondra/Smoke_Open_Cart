<?php

class ControllerCustomsAboutus extends Controller { 
  private $error = array();
    
  public function index() {
    $this->language->load('customs/aboutus'); //Optional. This calls for your language file

    $this->document->setTitle($this->language->get('heading_title')); //Optional. Set the title of your web page.

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home')
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('customs/aboutus')
    );   
       
    $data['heading_title'] = $this->language->get('heading_title'); //Get "heading title" from language file.

    $data['column_left'] = $this->load->controller('common/column_left');
    $data['column_right'] = $this->load->controller('common/column_right');
    $data['content_top'] = $this->load->controller('common/content_top');
    $data['content_bottom'] = $this->load->controller('common/content_bottom');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');

    // OpenCart 2.1 and below CHOOSE ACCORDINGLY
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/customs/aboutus.tpl')) { //if file exists in your current template folder
      $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/customs/aboutus.tpl', $data)); //get it
    } else {
      $this->response->setOutput($this->load->view('customs/aboutus.tpl', $data)); //or get the file from the default folder
    }
    
    // OpenCart 2.2 and above CHOOSE ACCORDINGLY
    $this->response->setOutput($this->load->view('customs/aboutus', $data)); 
  }
}
