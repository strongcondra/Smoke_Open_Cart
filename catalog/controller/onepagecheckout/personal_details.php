<?php
class ControllerOnepagecheckoutPersonalDetails extends Controller {
	private $ssl = 'SSL';
	
	public function __construct($registry){
		parent::__construct( $registry );
		$this->ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
	}
	public function index(){
		$this->load->language('onepagecheckout/checkout');
		
		$data['text_select'] = $this->language->get('text_select');
		
		
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_your_address'] = $this->language->get('text_your_address');
		$data['text_none'] = $this->language->get('text_none');
		
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		
		$this->load->model('setting/setting');
		
		$onepagecheckout_info = $this->model_setting_setting->getSetting('onepagecheckout', $this->config->get('config_store_id'));
		
		if(empty($onepagecheckout_info['onepagecheckout_status'])) {
			$this->response->redirect($this->url->link('checkout/checkout'));
		}
		
		if(!$this->config->get('onepagecheckout_field_layout')){
		  $data['class1'] = 'extsm-6';
		}else{
		 $data['class1'] = '';
		}
		
		$onepagecheckout_manage = (!empty($onepagecheckout_info['onepagecheckout_manage'])) ? $onepagecheckout_info['onepagecheckout_manage'] : array();
		
		$data['text_personal_details'] = (!empty($onepagecheckout_manage['register']['heading_title'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['register']['heading_title'][$this->config->get('config_language_id')] : $this->language->get('text_personal_details');
		
		$data['feilds']=array();
		
		//register_status
		$fields	= (isset($onepagecheckout_manage['personaldetails']['fields']) ? $onepagecheckout_manage['personaldetails']['fields'] : array());
		
		foreach($fields as $key => $feild){
			$required = false;
			$status = false;
			switch($feild['show']){
				case 1:
				$status = true;
				break;
				case 2:
				$status = true;
				$required = true;
				break;
				case 3:
				$status = false;
				$required = false;
				break;
			}
			$chide = false;
			if($key=='country' && $feild['show']==0){
			 $chide = true;
			 $status = true;
			}
			
			$zhide = false;
			if($key=='zone' && $feild['show']==0){
			 $zhide = true;
			 $status = true;
			}
			
			
			if($status){
				$data['feilds'][$key]=array(
					'chide'	=> $chide,
					'zhide'	=> $zhide,
					'sort_order'	=> $feild['sort_order'],
					'status'		=> $status,
					'required'		=> $required,
					'key'			=> $key,
					'label'			=> $onepagecheckout_manage['register'][$key]['label'][$this->config->get('config_language_id')],
					'placeholder'	=> $onepagecheckout_manage['register'][$key]['placeholder'][$this->config->get('config_language_id')]
				);
			}
		}
		
		$data['zoneplaceholder']	= ($onepagecheckout_manage['register']['zone']['placeholder'][$this->config->get('config_language_id')] ? $onepagecheckout_manage['register']['zone']['placeholder'][$this->config->get('config_language_id')] : $this->language->get('text_select'));
		
		function sortIt( $a, $b ){
			return $a['sort_order'] < $b['sort_order'] ? -1 : 1;
		}

		usort($data['feilds'], "sortIt");
		
		if(!empty($onepagecheckout_manage['personaldetails']['newsletter_status'])){
			$data['newsletter_status'] = $onepagecheckout_manage['personaldetails']['newsletter_status'];
		}else{
			$data['newsletter_status'] = false;
		}
		
		
		if(!empty($onepagecheckout_manage['delivery']['delivery_status'])){
			$data['delivery_status'] = $onepagecheckout_manage['delivery']['delivery_status'];
		}else{
			$data['delivery_status'] = false;
		}
		
		
		$this->load->model('localisation/country');
		$data['countries']=array();
		if(!empty($onepagecheckout_info['onepagecheckout_selected_countries'])){
			foreach($onepagecheckout_info['onepagecheckout_selected_countries'] as $country_id){
				$country_info = $this->model_localisation_country->getCountry($country_id);
				$data['countries'][]=array(
					'country_id' => $country_info['country_id'],
					'name'		=> $country_info['name'],
				);
			}
		}else{
			$data['countries'] = $this->model_localisation_country->getCountries();
		}
		
		// Custom Fields
		$this->load->model('account/custom_field');
		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		
		$data['customer_groups'] = array();
		$selectedcustomer_groups=array();
		if(!$this->config->get('onepagecheckout_customer_group_display')){
			$selectedcustomer_groups = array($this->config->get('onepagecheckout_customer_group_id'));
		}else{
			 $selectedcustomer_groups = $this->config->get('onepagecheckout_customer_group_display');
		}
		
		
		if (is_array($selectedcustomer_groups)) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $selectedcustomer_groups)) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		$data['customer_group_id'] = $this->config->get('onepagecheckout_customer_group_id');
		
		$data['customer_group_type'] = $this->config->get('onepagecheckout_customer_group_type');

		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();
		
		if (!empty($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = $this->config->get('onepagecheckout_postal_code');
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('onepagecheckout_country_id');
		}

		if(isset($this->session->data['shipping_address']['zone_id'])){
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = $this->config->get('onepagecheckout_zone_id');
		}
		
		if (!empty($onepagecheckout_manage['personaldetails']['terms'])) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($onepagecheckout_manage['personaldetails']['terms']);

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $onepagecheckout_manage['personaldetails']['terms'], true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		
		$data['account_terms'] = (!empty($onepagecheckout_manage['personaldetails']['account_terms']) ? 'checked=checked' : '');
		$data['newsletter_terms'] = (!empty($onepagecheckout_manage['personaldetails']['newsletter_status']) ? 'checked=checked' : '');
		
		if(!empty($onepagecheckout_manage['delivery']['delivery_auto_status'])){
			$data['delivery_auto_status'] = $onepagecheckout_manage['delivery']['delivery_auto_status'];
		}else{
			$data['delivery_auto_status'] = false;
		}
		
		
		
		if(version_compare(VERSION,'2.2.0.0','>=')){
			return $this->load->view('onepagecheckout/personal_details', $data);
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/personal_details.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/personal_details.tpl', $data);
			} else {
				return $this->load->view('default/template/onepagecheckout/personal_details.tpl', $data);
			}
		}
	}
}