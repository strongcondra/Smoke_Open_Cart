<?php
class ControllerOnepagecheckoutShippingAddress extends Controller {
	private $ssl = 'SSL';
	
	public function __construct($registry){
			parent::__construct( $registry );
			$this->ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
	}
	public function index(){
		
		$this->load->language('onepagecheckout/checkout');

		$data['text_select'] = $this->language->get('text_select');
		
		$data['text_address_existing'] = $this->language->get('text_address_existing');
		$data['text_address_new'] = $this->language->get('text_address_new');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		
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
		
		$heading_title = (!empty($onepagecheckout_manage['general']['heading_title'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['general']['heading_title'][$this->config->get('config_language_id')] : $this->language->get('heading_title');
		
		$data['feilds']=array();
		
		//register_status
		$fields	= (isset($onepagecheckout_manage['delivery']['fields']) ? $onepagecheckout_manage['delivery']['fields'] : array());
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
					'status'			=> $status,
					'required'		=> $required,
					'key'					=> $key,
					'label'				=> $onepagecheckout_manage['delivery_detail'][$key]['label'][$this->config->get('config_language_id')],
					'placeholder'	=> $onepagecheckout_manage['delivery_detail'][$key]['placeholder'][$this->config->get('config_language_id')]
				);
			}
		}
		
		$data['zoneplaceholder']	= ($onepagecheckout_manage['delivery_detail']['zone']['placeholder'][$this->config->get('config_language_id')] ? $onepagecheckout_manage['delivery_detail']['zone']['placeholder'][$this->config->get('config_language_id')] : $this->language->get('text_select'));
		
		function sortaddress($a, $b){
			return $a['sort_order'] < $b['sort_order'] ? -1 : 1;
		}
		
		$data['entry_heading'] = $onepagecheckout_manage['delivery_detail']['heading_title'][$this->config->get('config_language_id')];

		usort($data['feilds'], "sortaddress");

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->session->data['shipping_address']['address_id'])) {
			$data['address_id'] = $this->session->data['shipping_address']['address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}

		$this->load->model('account/address');
		
		if (isset($data['address_id'])) {
			$address_info = $this->model_account_address->getAddress($data['address_id']);
		}
		
		
		if (isset($address_info)) {
			$address_custom_field = $address_info['custom_field'];
		} else {
			$address_custom_field = array();
		}
		
		// Custom Fields
		$data['address_custom_fields'] = array();
		$this->load->model('account/custom_field');
		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('onepagecheckout_customer_group_id'));
		foreach ($custom_fields as $custom_field) {
			
			if ($custom_field['location'] == 'address') {
				if ($custom_field['type'] == 'select') {
					foreach ($custom_field['custom_field_value'] as $custom_field_value) {
						if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) {
							$data['address_custom_fields'][] = $custom_field_value['name'];
						}
					}
				}
				if ($custom_field['type'] == 'radio') {
					foreach ($custom_field['custom_field_value'] as $custom_field_value) {
						if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) {
							$data['address_custom_fields'][] = $custom_field_value['name'];
						}
					}
				}
				
				if ($custom_field['type'] == 'checkbox') {
					foreach ($custom_field['custom_field_value'] as $custom_field_value) {
						foreach($address_custom_field[$custom_field['custom_field_id']] as $checkboxvalue):
						 if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $checkboxvalue) {
							$data['address_custom_fields'][] = $custom_field_value['name'];
						 }
						 endforeach;
					}
				}
				
				if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
					$data['address_custom_fields'][] = (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : '');
					
				}
			}
		}
		
		$data['showcustomfeildsaddress']='';
		if($data['address_custom_fields']){
			$data['showcustomfeildsaddress']= implode(', ',$data['address_custom_fields']);
		}
		if($this->customer->isLogged()){
		 $data['addresses'] = $this->model_account_address->getAddresses();
		}else{
		 $data['addresses'] = '';	
		}

		
		if (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('onepagecheckout_country_id');
		}

		if (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = $this->config->get('onepagecheckout_zone_id');
		}

		
		if (!empty($this->session->data['shipping_address']['firstname'])) {
			$data['firstname'] = $this->session->data['shipping_address']['firstname'];
		} else {
			$data['firstname'] = $this->customer->getFirstName();
		}
		
		if (!empty($this->session->data['shipping_address']['lastname'])) {
			$data['lastname'] = $this->session->data['shipping_address']['lastname'];
		} else {
			$data['lastname'] = $this->customer->getLastName();
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

		$data['custom_fields'] = $custom_fields;
		if (isset($this->session->data['shipping_address']['custom_field'])) {
			$data['shipping_address_custom_field'] = $this->session->data['shipping_address']['custom_field'];
		} else {
			$data['shipping_address_custom_field'] = array();
		}
		
		$data['isLogged'] = false;
		if($this->customer->isLogged()){
			$data['isLogged'] = true;
		}
		
		if(version_compare(VERSION,'2.2.0.0','>=')){
			return $this->load->view('onepagecheckout/delivery_details', $data);
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/delivery_details.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/delivery_details.tpl', $data);
			} else {
				return $this->load->view('default/template/onepagecheckout/delivery_details.tpl', $data);
			}
		}
	}
}