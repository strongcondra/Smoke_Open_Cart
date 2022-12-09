<?php 
#################################################################
## Open Cart Module:  ZOPIM LIVE CHAT WIDGET			       ##
##-------------------------------------------------------------##
## Copyright © 2016 MB "Programanija" All rights reserved.     ##
## http://www.opencartextensions.eu						       ##
## http://www.programanija.com   						       ##
##-------------------------------------------------------------##
## Permission is hereby granted, when purchased, to  use this  ##
## mod on one domain. This mod may not be reproduced, copied,  ##
## redistributed, published and/or sold.				       ##
##-------------------------------------------------------------##
## Violation of these rules will cause loss of future mod      ##
## updates and account deletion				      			   ##
#################################################################

class ControllerExtensionModuleZopim extends Controller {
	
	private $error = array(); 

	public function index() {
		
		$this->load->language('extension/module/zopim');

		$this->document->setTitle($this->language->get('heading_title_m'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('zopim', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));

		}
		
		$text_array = array('heading_title', 'heading_title_m', 'advert', 'text_edit', 'text_enabled', 'text_disabled',
                            'text_on', 'text_off', 'entry_test', 'button_save', 'button_cancel', 'entry_zopim_code');
		
		foreach($text_array as $key){
			$data[$key] = $this->language->get($key);
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['zopim_code'])) {
			$data['error_zopim_code'] = $this->error['zopim_code'];
		} else {
			$data['error_zopim_code'] = '';
		}


  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
   		);

   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_m'),
			'href'      => $this->url->link('extension/module/zopim', 'token=' . $this->session->data['token'], true)
   		);
				
		$data['action'] = $this->url->link('extension/module/zopim', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/module/zopim', 'token=' . $this->session->data['token'], true);
		
		if (isset($this->request->post['zopim_code'])) {
			$data['zopim_code'] = $this->request->post['zopim_code'];
		} else {
			$data['zopim_code'] = $this->config->get('zopim_code');
		}
		
		
		if (isset($this->request->post['zopim_status'])) {
			$data['zopim_status'] = $this->request->post['zopim_status'];
		} else {
			$data['zopim_status'] = $this->config->get('zopim_status');
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/zopim', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/zopim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['zopim_code']) {
			$this->error['zopim_code'] = $this->language->get('error_zopim_code');
		}

		return !$this->error;
		
	}	
}
?>