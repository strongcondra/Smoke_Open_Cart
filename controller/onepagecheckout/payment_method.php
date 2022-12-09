<?php
class ControllerOnepagecheckoutPaymentMethod extends Controller {
	public function index() {
		
		$this->load->language('onepagecheckout/checkout');
		
		if(($this->request->server['REQUEST_METHOD'] == 'POST')){
			if(isset($this->session->data['payment_address'])){
				unset($this->session->data['payment_address']);
			}
				$type = $this->request->get['type'];
				if(isset($this->request->post[$type])){
						if (isset($this->request->post[$type]['payment_address']) && $this->request->post[$type]['payment_address'] == 'existing'){
								$this->load->model('account/address');
								if(empty($this->request->post[$type]['address_id'])){
									$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
								} elseif (!in_array($this->request->post[$type]['address_id'], array_keys($this->model_account_address->getAddresses()))) {
									$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
								}
								if(!isset($data['error_warning'])){
									$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post[$type]['address_id']);
								}
							}else{
								$this->session->data['payment_address']['firstname'] = (isset($this->request->post[$type]['firstname']) ? $this->request->post[$type]['firstname'] : '');
								$this->session->data['payment_address']['lastname'] = (isset($this->request->post[$type]['lastname']) ? $this->request->post[$type]['lastname'] : '');
								$this->session->data['payment_address']['company'] = (isset($this->request->post[$type]['company']) ? $this->request->post[$type]['company'] : '');
								$this->session->data['payment_address']['address_1'] = (isset($this->request->post[$type]['address_1']) ? $this->request->post[$type]['address_1'] : '');
								$this->session->data['payment_address']['address_2'] = (isset($this->request->post[$type]['address_2']) ? $this->request->post[$type]['address_2'] : '');
								$this->session->data['payment_address']['postcode'] = (isset($this->request->post[$type]['postcode']) ? $this->request->post[$type]['postcode'] : '');
								$this->session->data['payment_address']['city'] = (isset($this->request->post[$type]['city']) ? $this->request->post[$type]['city'] : '');
								$this->session->data['payment_address']['country_id']	= (isset($this->request->post[$type]['country_id']) ? $this->request->post[$type]['country_id'] : $this->config->get('config_country_id'));
								$this->session->data['payment_address']['zone_id'] = (isset($this->request->post[$type]['zone_id']) ? $this->request->post[$type]['zone_id'] : '');
								
								$this->load->model('localisation/country');
								$country_info = $this->model_localisation_country->getCountry((isset($this->request->post[$type]['country_id']) ? $this->request->post[$type]['country_id'] : $this->config->get('config_country_id')));
								if($country_info){
									$this->session->data['payment_address']['country'] = $country_info['name'];
									$this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
									$this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
									$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
								} else {
									$this->session->data['payment_address']['country'] = '';
									$this->session->data['payment_address']['iso_code_2'] = '';
									$this->session->data['payment_address']['iso_code_3'] = '';
									$this->session->data['payment_address']['address_format'] = '';
								}
							
						
						
						$this->load->model('localisation/zone');

						$zone_info = $this->model_localisation_zone->getZone((isset($this->request->post[$type]['zone_id']) ? $this->request->post[$type]['zone_id'] : ''));
						
						if ($zone_info) {
							$this->session->data['payment_address']['zone'] = $zone_info['name'];
							$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
						} else {
							$this->session->data['payment_address']['zone'] = '';
							$this->session->data['payment_address']['zone_code'] = '';
						}

						if (isset($this->request->post[$type]['custom_field']['address'])) {
							$this->session->data['payment_address']['custom_field'] = $this->request->post[$type]['custom_field']['address'];
						} else {
							$this->session->data['payment_address']['custom_field'] = array();
						}
					}
				}
				
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
		}

		$data['text_payment_methods'] = $this->language->get('text_payment_methods');
		
		$onepagecheckout_manage = $this->config->get('onepagecheckout_manage');
		
		if (isset($this->session->data['payment_address'])){
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('extension/extension');

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					if(version_compare(VERSION,'2.3.0.0','>=')){
					$this->load->model('extension/total/' . $result['code']);
					// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}else{
						if(version_compare(VERSION,'2.2.0.0','=')){
							$this->load->model('total/' . $result['code']);
							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_total_' . $result['code']}->getTotal($total_data);
						}else{
							$this->load->model('total/' . $result['code']);
							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
					}
				}
			}
			
			// Payment Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status') && !empty($onepagecheckout_manage['payment_method'][$result['code']]['status'])) {
					if(version_compare(VERSION,'2.3.0.0','>=')){
						$this->load->model('extension/payment/' . $result['code']);
						$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);
					}else{
						$this->load->model('payment/' . $result['code']);
						$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);
					}

					if ($method) {
						if ($recurring) {
							if(version_compare(VERSION,'2.3.0.0','>=')){
								if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
									$method_data[$result['code']] = $method;
								}
							}else{
								if (property_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
									$method_data[$result['code']] = $method;
								}
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}

		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = $this->config->get('onepagecheckout_default_payment');
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['scripts'] = $this->document->getScripts();
		
		if(!empty($onepagecheckout_manage['confirm_order']['terms'])){
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($onepagecheckout_manage['confirm_order']['terms']);

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}
		
		$this->load->model('tool/image');
		
		
		foreach($onepagecheckout_manage['payment_method'] as $key => $paymentinfo){
			if(!empty($paymentinfo['image'])){
				$data['image'.$key] = $this->model_tool_image->resize($paymentinfo['image'],($this->config->get('onepagecheckout_payment_method_width') ? $this->config->get('onepagecheckout_payment_method_width') : 50),($this->config->get('onepagecheckout_payment_method_height') ? $this->config->get('onepagecheckout_payment_method_height') : 50));
			}else{
				$data['image'.$key] = '';
			}
			
			if(!empty($paymentinfo['label'])){
				$data['title'.$key] = $paymentinfo['label'][$this->config->get('config_language_id')];
			}
		}
		
		$data['onepagecheckout_payment_method_load_cart'] =  $this->config->get('onepagecheckout_payment_method_load_cart');

		
		if(version_compare(VERSION,'2.2.0.0','>=')){
			$this->response->setOutput($this->load->view('onepagecheckout/payment_method', $data));
		}else{
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/payment_method.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/onepagecheckout/payment_method.tpl', $data));
			}else{
				$this->response->setOutput($this->load->view('default/template/onepagecheckout/payment_method.tpl', $data));
			}
		}
	}
	
	public function savepayment(){
		$this->load->language('onepagecheckout/checkout');
		$json = array();
		
		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
			$json['success'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}