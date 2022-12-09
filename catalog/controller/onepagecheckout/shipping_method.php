<?php
class ControllerOnepagecheckoutShippingMethod extends Controller {
	public function index(){
		$this->load->language('onepagecheckout/checkout');
		if(($this->request->server['REQUEST_METHOD'] == 'POST')){
			if(isset($this->session->data['shipping_address'])){
				unset($this->session->data['shipping_address']);
			}
			
			$type = $this->request->get['type'];
			if(isset($this->request->post[$type])){
				if($type=='payment_details'){
					$address_type = 'payment_address';
				}else{
					$address_type = 'shipping_address';
				}
				if (isset($this->request->post[$type][$address_type]) && $this->request->post[$type][$address_type] == 'existing'){
					$this->load->model('account/address');
					if(empty($this->request->post[$type]['address_id'])){
						$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
					} elseif (!in_array($this->request->post[$type]['address_id'], array_keys($this->model_account_address->getAddresses()))) {
						$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
					}
					if(!isset($data['error_warning'])){
						$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->post[$type]['address_id']);
					}
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}else{
					$this->session->data['shipping_address']['firstname'] = (isset($this->request->post[$type]['firstname']) ? $this->request->post[$type]['firstname'] : '');
					$this->session->data['shipping_address']['lastname'] = (isset($this->request->post[$type]['lastname']) ? $this->request->post[$type]['lastname'] : '');
					$this->session->data['shipping_address']['company'] = (isset($this->request->post[$type]['company']) ? $this->request->post[$type]['company'] : '');
					$this->session->data['shipping_address']['address_1'] = (isset($this->request->post[$type]['address_1']) ? $this->request->post[$type]['address_1'] : '');
					$this->session->data['shipping_address']['address_2'] = (isset($this->request->post[$type]['address_2']) ? $this->request->post[$type]['address_2'] : '');
					$this->session->data['shipping_address']['postcode'] = (isset($this->request->post[$type]['postcode']) ? $this->request->post[$type]['postcode'] : '');
					$this->session->data['shipping_address']['city'] = (isset($this->request->post[$type]['city']) ? $this->request->post[$type]['city'] : '');
					$this->session->data['shipping_address']['country_id']	= (isset($this->request->post[$type]['country_id']) ? $this->request->post[$type]['country_id'] : $this->config->get('config_country_id'));
					$this->session->data['shipping_address']['zone_id'] = (isset($this->request->post[$type]['zone_id']) ? $this->request->post[$type]['zone_id'] : '');
					
					$this->load->model('localisation/country');
					$country_info = $this->model_localisation_country->getCountry((isset($this->request->post[$type]['country_id']) ? $this->request->post[$type]['country_id'] : $this->config->get('config_country_id')));
					if($country_info){
						$this->session->data['shipping_address']['country'] = $country_info['name'];
						$this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
						$this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
						$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
					} else {
						$this->session->data['shipping_address']['country'] = '';
						$this->session->data['shipping_address']['iso_code_2'] = '';
						$this->session->data['shipping_address']['iso_code_3'] = '';
						$this->session->data['shipping_address']['address_format'] = '';
					}
				
					$this->load->model('localisation/zone');

					$zone_info = $this->model_localisation_zone->getZone((isset($this->request->post[$type]['zone_id']) ? $this->request->post[$type]['zone_id'] : ''));
					
					if ($zone_info) {
						$this->session->data['shipping_address']['zone'] = $zone_info['name'];
						$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
					} else {
						$this->session->data['shipping_address']['zone'] = '';
						$this->session->data['shipping_address']['zone_code'] = '';
					}

					if (isset($this->request->post[$type]['custom_field']['address'])) {
						$this->session->data['shipping_address']['custom_field'] = $this->request->post[$type]['custom_field']['address'];
					} else {
						$this->session->data['shipping_address']['custom_field'] = array();
					}
					
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}
			}
		}
		
		$onepagecheckout_manage = $this->config->get('onepagecheckout_manage');
		
		$this->load->model('tool/image');
		if(isset($this->session->data['shipping_address'])){
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status') && !empty($onepagecheckout_manage['delivery_method'][$result['code']]['status'])) {
					if(version_compare(VERSION,'2.3.0.0','>=')){
						$this->load->model('extension/shipping/' . $result['code']);
					}else{
						$this->load->model('shipping/' . $result['code']);
					}
					
					if(version_compare(VERSION,'2.3.0.0','>=')){
					 $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
					}else{
					 $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
					}
					
					if(!empty($onepagecheckout_manage['delivery_method'][$result['code']]['image'])){
						$img = $this->model_tool_image->resize($onepagecheckout_manage['delivery_method'][$result['code']]['image'],$this->config->get('onepagecheckout_delivery_method_width'),$this->config->get('onepagecheckout_delivery_method_height'));
					}else{
						$img = '';
					}
					

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => !empty($onepagecheckout_manage['delivery_method'][$result['code']]['label'][$this->config->get('config_language_id')]) ? $onepagecheckout_manage['delivery_method'][$result['code']]['label'][$this->config->get('config_language_id')] :$quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error'],
							'image'		 => $img	
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}
		
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}
		
		$data['code'] = '';
		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			if($onepagecheckout_manage['delivery_method'][$this->config->get('onepagecheckout_default_shipping')]['status']){
				if(version_compare(VERSION,'2.3.0.0','>=')){
					$tempcode = $this->{'model_extension_shipping_' . $this->config->get('onepagecheckout_default_shipping')}->getQuote($this->session->data['shipping_address']);
				}else{
					$tempcode = $this->{'model_shipping_' . $this->config->get('onepagecheckout_default_shipping')}->getQuote($this->session->data['shipping_address']);
				}
				if(isset($tempcode['quote'])){
					foreach ($tempcode['quote'] as $temps){
					  $data['code'] = $temps['code'];
					}
				}
			}
		}
		
		$data['loadpayments'] = $this->config->get('onepagecheckout_payment_method_load_payment_method');
		
		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}
		
		$data['isLogged'] = false;

		if($this->customer->isLogged()){

			$data['isLogged'] = true;

		}
		
		if(version_compare(VERSION,'2.2.0.0','>=')){
			$this->response->setOutput($this->load->view('onepagecheckout/shipping_method', $data));
		}else{
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/shipping_method.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/onepagecheckout/shipping_method.tpl', $data));
			}else{
				$this->response->setOutput($this->load->view('default/template/onepagecheckout/shipping_method.tpl', $data));
			}
		}
	}
	
	public function saveshipping(){
		$this->load->language('onepagecheckout/checkout');
		
		$json = array();
		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			$json['success']= true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}