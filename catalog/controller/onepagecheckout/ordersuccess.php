<?php
class ControllerOnepagecheckoutOrderSuccess extends Controller {

	public function index(){
	
		$this->load->language('checkout/success');
		$this->load->model('checkout/order');
		$data['heading_title'] = $this->language->get('heading_title');
		
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		}else{
			$order_id = 0;
		}
		
		if (isset($this->session->data['order_id'])) {
		    
		    /* begin ShareASale tracking business logic */
			//discounts
			$_sasCoupon = array();
			$this->load->model('extension/total/coupon');
			if(@$this->session->data['coupon']){
				$_sasCoupon = $this->model_extension_total_coupon->getCoupon($this->session->data['coupon']);
			}
			//product-level tracking
			//product model necessary to get SKU since cart->getProducts() returns different data than product->getProducts()...
			$this->load->model('catalog/product');	
			$_sasSkulist      = [];
			$_sasPricelist    = [];
			$_sasQuantitylist = [];
			$_sasProductCount = $this->cart->countProducts();
			foreach ($this->cart->getProducts() as $product) {
				$_sasSkulist[]      = $this->model_catalog_product->getProduct($product['product_id'])['sku'];
				$_sasQuantitylist[] = $product['quantity'];
				//check if product or cart-wide discount, and check if flat (type "F") or percent (type "P"). Ignore free shipping discounts.
				if(!empty($_sasCoupon['product'])){
					if(in_array($product['product_id'], $_sasCoupon['product'])){
						$_sasProductSubtotal = ($_sasCoupon['type'] == 'P') ? $product['price'] * (1 - ($_sasCoupon['discount'] / 100)) : (($_sasCoupon['type'] == 'F') ? $product['price'] - $_sasCoupon['discount'] : $product['price']);
					}else{
						$_sasProductSubtotal = $product['price'];
					}
				}else{
					$_sasProductSubtotal = (@$_sasCoupon['type'] == 'P') ? $product['price'] * (1 - ($_sasCoupon['discount'] / 100)) : ((@$_sasCoupon['type'] == 'F') ? $product['price'] - ($_sasCoupon['discount'] / $_sasProductCount) : $product['price']);
				}

				$_sasPricelist[] = round($_sasProductSubtotal, 2);
			}
			$_sasProductSubtotal = array_sum(array_map(function($price, $quantity) { return $price * $quantity; }, $_sasPricelist, $_sasQuantitylist));
			//check for store credit and apply to final balance
			$_sasBalance = $this->db->query("SELECT SUM(amount) AS balance FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_id<> '" . (int)$this->session->data['order_id'] . "'")->row['balance'];
			if($_sasBalance){
				//compare store credit to after-discount product subtotal to get percentage paid by store credit, if not its entirety
				$_sasShare = $_sasBalance / $_sasProductSubtotal>1 ?1 : $_sasBalance / $_sasProductSubtotal;
				$_sasPricelist = array_map(function($price) use($_sasShare) { return $price * (1 - $_sasShare); }, $_sasPricelist);
				$_sasTotal = $_sasProductSubtotal = array_sum(array_map(function($price, $quantity) { return $price * $quantity; }, $_sasPricelist, $_sasQuantitylist));
			}else{
				$_sasTotal = $_sasProductSubtotal;
			}

			$data['_sasProducts'] = [implode(',', $_sasSkulist), implode(',', $_sasPricelist), implode(',', $_sasQuantitylist)];
			//customer status tracking
			if ($this->customer->isLogged() && $this->customer->getId() != 0) {
			    $totalOrders = $this->db->query("SELECT COUNT(`order_id`) AS `total` FROM `" . DB_PREFIX . "order` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");
			    $data['_sasIsCustomerNew'] = (int)$totalOrders->row['total'] > 1 ? 0 : 1;
			}else {
				$data['_sasIsCustomerNew'] = '';
			}
			//standard order total and order ID tracking			
			$data['_sasTotal'] =  $_sasTotal;
			$data['_sasOrderId'] = $this->session->data['order_id'];
			//$data['_sasCurrencyCode'] = $this->session->data['currency']; _sasTotal always in USD even when currency changes...
			$data['_sasCoupons'] = !empty($_sasCoupon) ? $_sasCoupon['code'] : '';			
			/* end ShareASale tracking business logic */

			$this->cart->clear();
			
			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				if ($this->customer->isLogged()) {
					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
						'order_id'    => $this->session->data['order_id']
					);

					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = array(
						'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
						'order_id' => $this->session->data['order_id']
					);

					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
			unset($this->session->data['delivery_date']);
		}
		
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('onepagecheckout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('onepagecheckout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);
		
		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if($order_info){
			$find=array(
			  '{order_id}',
			  '{firstname}',
			  '{lastname}',
			  '{account}',
			  '{order_history}',
			  '{downloads}',
			  '{contact_us}',
			);
			
			$replace=array(
			  	'order_id' 		=> $order_info['order_id'],
			    'firstname'		=> $order_info['firstname'],
			    'lastname'		=> $order_info['lastname'],
			    'account'		=> $this->url->link('account/account','','SSL'),
			    'history'		=> $this->url->link('account/order','','SSL'),
			    'downloads'		=> $this->url->link('account/download','','SSL'),
			    'contact'		=> $this->url->link('information/contact','','SSL'),
			);
			
			
			$data['heading_title']  = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $this->config->get('order_success_page_order_heading'.$this->config->get('config_language_id'))))));
			if ($this->customer->isLogged()) {
				$data['text_message']  = html_entity_decode(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $this->config->get('order_success_page_register_message'.$this->config->get('config_language_id')))))));
			}else{
				$data['text_message']  = html_entity_decode(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $this->config->get('order_success_page_guest_message'.$this->config->get('config_language_id')))))));
			}
			
			
			$data['button_continue'] = ($this->config->get('order_success_page_continue_text'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_continue_text'.$this->config->get('config_language_id')) : $this->language->get('button_continue'));
			
			$data['text_print_invoice'] = ($this->config->get('order_success_page_print_invoice_text'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_print_invoice_text'.$this->config->get('config_language_id')) : 'Print Invoice');
				
			$data['invoice']= '';
			if($this->config->get('order_success_page_order_invoice')){
				
				$data['invoice']= $this->CreateInvoice($order_info['order_id']);
			}
			
			$data['products'] = '';
			if($this->config->get('order_success_page_promote_products')){
				$data['products']= $this->getpromoationproduct($order_info['order_id']);
			}
	
			$data['print_invoice'] = '';
			if($this->config->get('order_success_page_print_invoice')){
				$data['print_invoice']= $this->url->link('onepagecheckout/ordersuccess/printinvoice','&order_id='.$order_info['order_id']);
			}
			
			$data['show_bank_details'] = false;
			if($this->config->get('order_success_page_bank_details_status') && $order_info['payment_code']=='bank_transfer'){
				$data['show_bank_details'] = true;
				
				if(VERSION >= '2.2.0.0'){
					$this->load->language('extension/payment/bank_transfer');
				}else{
					$this->load->language('payment/bank_transfer');
				}
				
				$data['text_instruction'] = $this->language->get('text_instruction');
				$data['text_description'] = $this->language->get('text_description');
				$data['text_payment'] = $this->language->get('text_payment');
				$data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));
			}
			
			$data['order_success_page_google_analytics'] = html_entity_decode($this->config->get('order_success_page_google_analytics'), ENT_QUOTES, 'UTF-8');
		
		
		
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		
		if(version_compare(VERSION,'2.2.0.0','>=')){
			$this->response->setOutput($this->load->view('onepagecheckout/success', $data));
		}else{
			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/success.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/onepagecheckout/success.tpl', $data));
			}else{
				$this->response->setOutput($this->load->view('default/template/onepagecheckout/success.tpl', $data));
			}
		}
			
		}else{
			$this->load->language('account/order');
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('onepagecheckout/ordersuccess')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			if(version_compare(VERSION,'2.2.0.0','>=')){
			  $this->response->setOutput($this->load->view('error/not_found', $data));
			}else{
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
				}else{
					$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
				}
			}
		}
	}
	
	public function printinvoice(){
		if(isset($this->request->get['order_id'])){
			$order_id = $this->request->get['order_id'];
		}else{
			$order_id = 0;
		}
		
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if($order_info){
			
				$download_status = false;
	
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
	
				foreach ($order_product_query->rows as $order_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");
	
					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}
				
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/order');
	
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_info['order_status_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				if ($order_status_query->num_rows) {
					$order_status = $order_status_query->row['name'];
				} else {
					$order_status = '';
				}
			
				$data = array();
	
				$data['title'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
	
				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $order_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_order_detail'] = ($this->config->get('order_success_page_order_details_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_order_details_heading'.$this->config->get('config_language_id')) : $language->get('text_new_order_detail'));
				$data['text_instruction'] = ($this->config->get('order_success_page_order_comment_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_order_comment_heading'.$this->config->get('config_language_id')) : $language->get('text_new_instruction'));
				$data['text_order_id'] = $language->get('text_new_order_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_order_status'] = $language->get('text_new_order_status');
				$data['text_payment_address'] = ($this->config->get('order_success_page_payment_address_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_payment_address_heading'.$this->config->get('config_language_id')) : $language->get('text_new_payment_address'));
				$data['text_shipping_address'] = ($this->config->get('order_success_page_shipping_address_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_shipping_address_heading'.$this->config->get('config_language_id')) : $language->get('text_new_shipping_address'));
				
				$data['text_bank_details'] = ($this->config->get('order_success_page_bank_details_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_bank_details_heading'.$this->config->get('config_language_id')) : $language->get('text_new_bank_details'));
				
				$data['text_product'] = ($this->config->get('order_success_page_product_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_product_title'.$this->config->get('config_language_id')) : $language->get('text_new_product'));
				
				$data['text_model'] = ($this->config->get('order_success_page_model_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_model_title'.$this->config->get('config_language_id')) : $language->get('text_new_model'));
				
				$data['text_sku'] = ($this->config->get('order_success_page_sku_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_sku_title'.$this->config->get('config_language_id')) : 'Sku');
				
				$data['text_image'] = ($this->config->get('order_success_page_image_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_image_title'.$this->config->get('config_language_id')) : 'Image');
				
				$data['text_quantity'] = ($this->config->get('order_success_page_qty_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_qty_title'.$this->config->get('config_language_id')) : $language->get('text_new_quantity'));
				
				$data['text_price'] = ($this->config->get('order_success_page_unit_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_unit_title'.$this->config->get('config_language_id')) : $language->get('text_new_price'));
				
				$data['text_total'] = ($this->config->get('order_success_page_total_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_total_title'.$this->config->get('config_language_id')) : $language->get('text_new_total'));
				$data['text_footer'] = $language->get('text_new_footer');
				
				$data['logo_status'] = true;
				$data['print_status'] = true;
				
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['customer_id'] = $order_info['customer_id'];
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
	
				if ($download_status) {
					$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}
	
				$data['order_id'] = $order_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				$data['payment_method'] = $order_info['payment_method'];
				$data['shipping_method'] = $order_info['shipping_method'];
				$data['email'] = $order_info['email'];
				$data['telephone'] = $order_info['telephone'];
				$data['ip'] = $order_info['ip'];
				$data['order_status'] = $order_status;
				$data['comment'] = '';
				if ($order_info['comment']) {
					$data['comment'] = nl2br($order_info['comment']);
				}
				
				
				$payment_custom_field = $order_info['payment_custom_field'];
				$payment_custom_fields = array();
				$this->load->model('account/custom_field');
				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('onepagecheckout_customer_group_id'));
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['type'] == 'select') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) {
									$payment_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						if ($custom_field['type'] == 'radio') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) {
									$payment_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						
						if ($custom_field['type'] == 'checkbox') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								foreach($payment_custom_field[$custom_field['custom_field_id']] as $checkboxvalue):
								 if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $checkboxvalue) {
									$payment_custom_fields[] = $custom_field_value['name'];
								 }
								 endforeach;
							}
						}
						
						if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
							$payment_custom_fields[] = $payment_custom_field[$custom_field['custom_field_id']];
							
						}
					}
				}
				
				$payment_custom_field_address ='';
				if($payment_custom_fields){
					$payment_custom_field_address= implode('<br/>',$payment_custom_fields);
				}
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' ."\n".'{payment_custom_field}' ."\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{payment_custom_field}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
				);
	
				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'payment_custom_field' => $payment_custom_field_address,
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);
	
				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				
				$shipping_custom_field = $order_info['shipping_custom_field'];
				
				$shipping_custom_fields=array();
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['type'] == 'select') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $shipping_custom_field[$custom_field['custom_field_id']]) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						if ($custom_field['type'] == 'radio') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $shipping_custom_field[$custom_field['custom_field_id']]) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						
						if ($custom_field['type'] == 'checkbox') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								foreach($shipping_custom_field[$custom_field['custom_field_id']] as $checkboxvalue):
								 if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $checkboxvalue) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								 }
								 endforeach;
							}
						}
						
						if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
							$shipping_custom_fields[] = $shipping_custom_field[$custom_field['custom_field_id']];
							
						}
					}
				}
				
				$shipping_custom_field_address ='';
				if($shipping_custom_fields){
					$shipping_custom_field_address= implode('<br/>',$shipping_custom_fields);
				}
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' ."\n".'{shipping_custom_field}' ."\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{shipping_custom_field}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
	
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'shipping_custom_field' => $shipping_custom_field_address,
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);
	
				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
				$this->load->model('tool/upload');
	
				// Products
				$data['products'] = array();
	
				foreach ($order_product_query->rows as $product) {
					$option_data = array();
	
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
	
					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
	
							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}
	
						$option_data[] = array(
							'name'  => $option['name'],
							//'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
							'value' => $value
						);
					}
					
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					
					if($product_info['image']){
						$image = $this->model_tool_image->resize($product_info['image'],$this->config->get('order_success_page_width'),$this->config->get('order_success_page_height'));
					}else{
						$image = '';
					}
					
					
					$data['products'][] = array(
						'name'     => $product['name'],
						'image'    => $image,
						'model'    => $product['model'],
						'sku'      => (isset($product_info['sku']) ? $product_info['sku'] : ''),
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
	
				// Vouchers
				$data['vouchers'] = array();
	
				$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
	
				foreach ($order_voucher_query->rows as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
	
				// Order Totals
				$data['totals'] = array();
				
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
	
				foreach ($order_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
				
				$data['order_success_page_order_details'] = $this->config->get('order_success_page_order_details');
				$data['order_success_page_comment_status'] = $this->config->get('order_success_page_comment_status');
				$data['order_success_page_payment_address_status'] = $this->config->get('order_success_page_payment_address_status');
				$data['order_success_page_shipping_address_status'] = $this->config->get('order_success_page_shipping_address_status');
				$data['order_success_page_product_image_status'] = $this->config->get('order_success_page_product_image_status');
				$data['order_success_page_product_name_status'] = $this->config->get('order_success_page_product_name_status');
				$data['order_success_page_product_model_status'] = $this->config->get('order_success_page_product_model_status');
				$data['order_success_page_product_sku_status'] = $this->config->get('order_success_page_product_sku_status');
				$data['order_success_page_product_qty_status'] = $this->config->get('order_success_page_product_qty_status');
				$data['order_success_page_product_unit_price_status'] = $this->config->get('order_success_page_product_unit_price_status');
				$data['order_success_page_product_total_status'] = $this->config->get('order_success_page_product_total_status');
				
				$data['order_success_page_title_backgound'] = $this->config->get('order_success_page_title_backgound');
				$data['order_success_page_title_color'] = $this->config->get('order_success_page_title_color');
				
				
			
				
			if(version_compare(VERSION,'2.2.0.0','>=')){
			  $this->response->setOutput($this->load->view('onepagecheckout/ordertemplate', $data));
			}else{
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/ordertemplate.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/onepagecheckout/ordertemplate.tpl', $data));
				}else{
					$this->response->setOutput($this->load->view('default/template/onepagecheckout/ordertemplate.tpl', $data));
				}
			}
		}
	}
	
	public function CreateInvoice($order_id){
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if($order_info){
			
				$download_status = false;
	
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
	
				foreach ($order_product_query->rows as $order_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");
	
					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}
				
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/order');
	
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_info['order_status_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				if ($order_status_query->num_rows) {
					$order_status = $order_status_query->row['name'];
				} else {
					$order_status = '';
				}
			
				$data = array();
	
				$data['title'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
				
				$data['logo_status'] = false;
				$data['print_status'] = false;
	
				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $order_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_order_detail'] = ($this->config->get('order_success_page_order_details_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_order_details_heading'.$this->config->get('config_language_id')) : $language->get('text_new_order_detail'));
				$data['text_instruction'] = ($this->config->get('order_success_page_order_comment_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_order_comment_heading'.$this->config->get('config_language_id')) : $language->get('text_new_instruction'));
				$data['text_order_id'] = $language->get('text_new_order_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_order_status'] = $language->get('text_new_order_status');
				$data['text_payment_address'] = ($this->config->get('order_success_page_payment_address_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_payment_address_heading'.$this->config->get('config_language_id')) : $language->get('text_new_payment_address'));
				$data['text_shipping_address'] = ($this->config->get('order_success_page_shipping_address_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_shipping_address_heading'.$this->config->get('config_language_id')) : $language->get('text_new_shipping_address'));
				
				$data['text_bank_details'] = ($this->config->get('order_success_page_bank_details_heading'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_bank_details_heading'.$this->config->get('config_language_id')) : $language->get('text_new_bank_details'));
				
				$data['text_product'] = ($this->config->get('order_success_page_product_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_product_title'.$this->config->get('config_language_id')) : $language->get('text_new_product'));
				
				$data['text_model'] = ($this->config->get('order_success_page_model_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_model_title'.$this->config->get('config_language_id')) : $language->get('text_new_model'));
				
				$data['text_sku'] = ($this->config->get('order_success_page_sku_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_sku_title'.$this->config->get('config_language_id')) : 'Sku');
				
				$data['text_image'] = ($this->config->get('order_success_page_image_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_image_title'.$this->config->get('config_language_id')) : 'Image');
				
				$data['text_quantity'] = ($this->config->get('order_success_page_qty_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_qty_title'.$this->config->get('config_language_id')) : $language->get('text_new_quantity'));
				
				$data['text_price'] = ($this->config->get('order_success_page_unit_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_unit_title'.$this->config->get('config_language_id')) : $language->get('text_new_price'));
				
				$data['text_total'] = ($this->config->get('order_success_page_total_title'.$this->config->get('config_language_id')) ? $this->config->get('order_success_page_total_title'.$this->config->get('config_language_id')) : $language->get('text_new_total'));
				$data['text_footer'] = $language->get('text_new_footer');
	
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['customer_id'] = $order_info['customer_id'];
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
	
				if ($download_status) {
					$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}
	
				$data['order_id'] = $order_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				$data['payment_method'] = $order_info['payment_method'];
				$data['shipping_method'] = $order_info['shipping_method'];
				$data['email'] = $order_info['email'];
				$data['telephone'] = $order_info['telephone'];
				$data['ip'] = $order_info['ip'];
				$data['order_status'] = $order_status;
				$data['comment'] = '';
				if ($order_info['comment']) {
					$data['comment'] = nl2br($order_info['comment']);
				}
				
	
				$payment_custom_field = $order_info['payment_custom_field'];
				$payment_custom_fields = array();
				$this->load->model('account/custom_field');
				$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('onepagecheckout_customer_group_id'));
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['type'] == 'select') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) {
									$payment_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						if ($custom_field['type'] == 'radio') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) {
									$payment_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						
						if ($custom_field['type'] == 'checkbox') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								foreach($payment_custom_field[$custom_field['custom_field_id']] as $checkboxvalue):
								 if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $checkboxvalue) {
									$payment_custom_fields[] = $custom_field_value['name'];
								 }
								 endforeach;
							}
						}
						
						if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
							$payment_custom_fields[] = $payment_custom_field[$custom_field['custom_field_id']];
							
						}
					}
				}
				
				$payment_custom_field_address ='';
				if($payment_custom_fields){
					$payment_custom_field_address= implode('<br/>',$payment_custom_fields);
				}
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' ."\n".'{payment_custom_field}' ."\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{payment_custom_field}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}',
				);
	
				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'payment_custom_field' => $payment_custom_field_address,
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);
	
				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				
				$shipping_custom_field = $order_info['shipping_custom_field'];
				
				$shipping_custom_fields=array();
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['type'] == 'select') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $shipping_custom_field[$custom_field['custom_field_id']]) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						if ($custom_field['type'] == 'radio') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $shipping_custom_field[$custom_field['custom_field_id']]) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								}
							}
						}
						
						if ($custom_field['type'] == 'checkbox') {
							foreach ($custom_field['custom_field_value'] as $custom_field_value) {
								foreach($shipping_custom_field[$custom_field['custom_field_id']] as $checkboxvalue):
								 if (isset($shipping_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $checkboxvalue) {
									$shipping_custom_fields[] = $custom_field_value['name'];
								 }
								 endforeach;
							}
						}
						
						if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
							$shipping_custom_fields[] = $shipping_custom_field[$custom_field['custom_field_id']];
							
						}
					}
				}
				
				$shipping_custom_field_address ='';
				if($shipping_custom_fields){
					$shipping_custom_field_address= implode('<br/>',$shipping_custom_fields);
				}
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' ."\n".'{shipping_custom_field}' ."\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{shipping_custom_field}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
	
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'shipping_custom_field' => $shipping_custom_field_address,
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);
				
				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
				$this->load->model('tool/upload');
				
				// Products
				$data['products'] = array();
	
				foreach ($order_product_query->rows as $product) {
					$option_data = array();
	
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
	
					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
	
							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}
	
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
							//'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}
					
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					
					if($product_info['image']){
						$image = $this->model_tool_image->resize($product_info['image'],$this->config->get('order_success_page_width'),$this->config->get('order_success_page_height'));
					}else{
						$image = '';
					}
					
					
					$data['products'][] = array(
						'name'     => $product['name'],
						'image'    => $image,
						'model'    => $product['model'],
						'sku'      => (isset($product_info['sku']) ? $product_info['sku'] : ''),
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
	
				// Vouchers
				$data['vouchers'] = array();
	
				$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
	
				foreach ($order_voucher_query->rows as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
	
				// Order Totals
				$data['totals'] = array();
				
				$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
	
				foreach ($order_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
				
				$data['order_success_page_order_details'] = $this->config->get('order_success_page_order_details');
				$data['order_success_page_comment_status'] = $this->config->get('order_success_page_comment_status');
				$data['order_success_page_payment_address_status'] = $this->config->get('order_success_page_payment_address_status');
				$data['order_success_page_shipping_address_status'] = $this->config->get('order_success_page_shipping_address_status');
				$data['order_success_page_product_image_status'] = $this->config->get('order_success_page_product_image_status');
				$data['order_success_page_product_name_status'] = $this->config->get('order_success_page_product_name_status');
				$data['order_success_page_product_model_status'] = $this->config->get('order_success_page_product_model_status');
				$data['order_success_page_product_sku_status'] = $this->config->get('order_success_page_product_sku_status');
				$data['order_success_page_product_qty_status'] = $this->config->get('order_success_page_product_qty_status');
				$data['order_success_page_product_unit_price_status'] = $this->config->get('order_success_page_product_unit_price_status');
				$data['order_success_page_product_total_status'] = $this->config->get('order_success_page_product_total_status');
				
				$data['order_success_page_title_backgound'] = $this->config->get('order_success_page_title_backgound');
				$data['order_success_page_title_color'] = $this->config->get('order_success_page_title_color');
				
				
				
				if(version_compare(VERSION,'2.2.0.0','>=')){
					return $this->load->view('onepagecheckout/ordertemplate', $data);
				}else{
					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/ordertemplate.tpl')) {
						return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/ordertemplate.tpl', $data);
					}else{
						return $this->load->view('default/template/onepagecheckout/ordertemplate.tpl', $data);
					}
					}
				}
	}
	
	public function getpromoationproduct($order_id){
		if(version_compare(VERSION,'2.3.0.0','>=')){
			$this->load->language('extension/module/featured');
		}else{
			$this->load->language('module/featured');	
		}
		
		$this->load->language('checkout/success');
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$data['heading_title'] = $this->language->get('heading_title');
		if($order_info){
			$find=array(
			  '{order_id}',
			  '{firstname}',
			  '{lastname}',
			);
				
			$replace=array(
				'order_id' 		=> $order_info['order_id'],
				'firstname'		=> $order_info['firstname'],
				'lastname'		=> $order_info['lastname'],
			);
				
			$data['heading_title']  = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $this->config->get('order_success_page_shipping_promote_product_heading'.$this->config->get('config_language_id'))))));
		}
		
			
		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();
		
		if ($this->config->get('order_success_page_product')) {
			$products = array_slice($this->config->get('order_success_page_product'), 0, 10);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('order_success_page_promote_product_width'), $this->config->get('order_success_page_promote_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('order_success_page_promote_product_width'), $this->config->get('order_success_page_promote_product_height'));
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0,50) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
				if(version_compare(VERSION,'2.3.0.0','>=')){
					return $this->load->view('extension/module/featured', $data);
				}else if(version_compare(VERSION,'2.2.0.0','=')){
					return $this->load->view('module/featured.tpl', $data);
				}else{
					if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
						return $this->load->view($this->config->get('config_template') . '/template/module/featured.tpl', $data);
					}else{
						return $this->load->view('default/template/module/featured.tpl', $data);
					}
				}
		}
	}
}