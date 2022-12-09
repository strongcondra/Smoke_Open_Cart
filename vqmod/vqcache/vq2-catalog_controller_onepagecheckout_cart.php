<?php
class ControllerOnepagecheckoutCart extends Controller {
	
	public function index() {
		$ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
		$this->load->language('onepagecheckout/checkout');
		$this->load->language('checkout/cart');
	
		$this->load->model('setting/setting');
		$data['redirect'] = '';
		if (!$this->cart->hasProducts()) {
			if(empty($this->session->data['vouchers'])){
			  $data['redirect'] = $this->url->link('onepagecheckout/checkout','',$ssl);
			}
		}
		
		if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
			$data['error_warning'] = $this->language->get('error_stock');
		} elseif (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$onepagecheckout_info = $this->model_setting_setting->getSetting('onepagecheckout', $this->config->get('config_store_id'));
				
		$onepagecheckout_manage = (!empty($onepagecheckout_info['onepagecheckout_manage'])) ? $onepagecheckout_info['onepagecheckout_manage'] : array();
		
		$data['logged'] = $this->customer->isLogged();
		
		
		if(!empty($onepagecheckout_manage['shopping_cart']['shopping_cart_status'])) {
			
			$data['text_shopping_cart'] = (!empty($onepagecheckout_manage['shopping_cart']['heading_title'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['shopping_cart']['heading_title'][$this->config->get('config_language_id')] : $this->language->get('text_shopping_cart');
			
			$data['alert_message'] = (!empty($onepagecheckout_manage['shopping_cart']['alert_message'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['shopping_cart']['alert_message'][$this->config->get('config_language_id')] : 'are you sure to delete this item ?';
			
			$data['clear_cart_text'] = (!empty($onepagecheckout_manage['shopping_cart']['clear_cart_text'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['shopping_cart']['clear_cart_text'][$this->config->get('config_language_id')] : 'Remove';
			
			$data['weight'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_weight']) ? $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point')) : '');
			
			$data['qty_update_permission'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_qnty_update']) ? true : false);
			
			
			$data['show_product_image'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_image']) ? true : false);
			$data['show_product_name'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_title']) ? true : false);
			$data['show_product_model'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_model']) ? true : false);
			$data['show_product_quantity'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_quantity']) ? true : false);
			$data['show_product_unit'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_unit']) ? true : false);
			$data['show_product_total_price'] = (!empty($onepagecheckout_manage['shopping_cart']['show_product_total_price']) ? true : false);
			
			$data['show_m_product_image'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_image']) ? true : false);
			$data['show_m_product_name'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_title']) ? true : false);
			$data['show_m_product_model'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_model']) ? true : false);
			$data['show_m_product_quantity'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_quantity']) ? true : false);
			$data['show_m_product_unit'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_unit']) ? true : false);
			$data['show_m_product_total_price'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['show_product_total_price']) ? true : false);
			
			$data['shipping'] = $this->cart->hasShipping();
			
			
			if(!empty($onepagecheckout_manage['delivery']['delivery_status'])){
				$data['delivery_status'] = $onepagecheckout_manage['delivery']['delivery_status'];
			}else{
				$data['delivery_status'] = false;
			}
			
			
			if($this->isMobile()){
				$data['show_colspan'] = (!empty($onepagecheckout_manage['shopping_cart_mb']['colspan']) ? $onepagecheckout_manage['shopping_cart_mb']['colspan'] : 6);
			}else{
				$data['show_colspan'] = (!empty($onepagecheckout_manage['shopping_cart']['colspan']) ? $onepagecheckout_manage['shopping_cart']['colspan'] : 6);
			}
			
			$image_width = (!empty($onepagecheckout_manage['shopping_cart']['show_product_image_width']) ? $onepagecheckout_manage['shopping_cart']['show_product_image_width'] : 50);
			$image_height = (!empty($onepagecheckout_manage['shopping_cart']['show_product_image_height']) ? $onepagecheckout_manage['shopping_cart']['show_product_image_height'] : 50);
			
			if(isset($this->request->post['account_type'])) {
				$account_type = $this->request->post['account_type'];
			} else {
				$account_type = 'login';
			}
			
			if($account_type == 'register') {
				$coupon_status 	= 'coupon_register_status';
				$reward_status 	= 'reward_register_status';
				$voucher_status = 'voucher_register_status';
			}else if($account_type == 'guest') {
				$coupon_status 	= 'coupon_guest_status';
				$reward_status 	= 'reward_guest_status';
				$voucher_status = 'voucher_guest_status';
			}else{
				$coupon_status 	= 'coupon_login_status';
				$reward_status 	= 'reward_login_status';
				$voucher_status = 'voucher_login_status';
			}
			
			if(!empty($onepagecheckout_manage['shopping_cart'][$coupon_status])) {
				$data['coupon_module'] = $this->load->controller('onepagecheckout/coupon');	
			}else{
				$data['coupon_module'] = '';
			}
			
			if(!empty($onepagecheckout_manage['shopping_cart'][$reward_status])) {
				$data['reward_module'] = $this->load->controller('onepagecheckout/reward');
			}else{
				$data['reward_module'] = '';
			}
			
			if(!empty($onepagecheckout_manage['shopping_cart'][$voucher_status])) {
				$data['voucher_module'] = $this->load->controller('onepagecheckout/voucher');
			}else{
				$data['voucher_module'] = '';
			}
			
			
			$data['text_recurring_item'] = $this->language->get('text_recurring_item');
			$data['text_next'] = $this->language->get('text_next');
			$data['text_next_choice'] = $this->language->get('text_next_choice');
			
			$data['column_image'] = $this->language->get('column_image');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_sku'] = $this->language->get('column_sku');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['button_update'] = $this->language->get('button_update');
			$data['button_remove'] = $this->language->get('button_remove');
			$data['button_shopping'] = $this->language->get('button_shopping');
			$data['button_checkout'] = $this->language->get('button_checkout');
			
			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $image_width, $image_height);
				} else {
					$image = '';
				}

				$option_data = array();


				$base_reward = $product['reward'];
				$total_option_reward = 0;
				$total_qty = $product['quantity'];
                foreach ($product['option'] as $option) {
                    $total_option_reward = ($total_option_reward + $option['points']) * $total_qty;
                }
				$product['reward'] = ($base_reward + $total_option_reward);
            
				foreach ($product['option'] as $option) {
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
						'value' => $value,
						'price' => $this->currency->format($option['price'], $this->session->data['currency']),
						'price_prefix' => $option['price_prefix']
					);
				}

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
				} else {
					$total = false;
				}

				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year'),
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}
				
				if(version_compare(VERSION,'2.1.0.1','>=')){
					$key = $product['cart_id'];
				}else{
					$key = $product['key'];
				}

				$data['products'][] = array(
					'cart_id'   => $key,
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}
			
			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}
			
			// Totals
			$this->load->model('extension/extension');
			if(version_compare(VERSION,'2.2.0.0','>=')){
				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
				
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);
				
				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
								$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
							}else{
								if(version_compare(VERSION,'2.2.0.0','=')){
									$this->load->model('total/' . $result['code']);
									$this->{'model_total_' . $result['code']}->getTotal($total_data);
								}else{
									$this->load->model('total/' . $result['code']);
									$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
								}
							}
							
							// We have to put the totals in an array so that they pass by reference.
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}

				$data['totals'] = array();

				foreach ($totals as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
					);
				}
			}else{
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_extension_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
					}

					$sort_order = array();
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data);
				}

				$data['totals'] = array();

				foreach ($total_data as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'])
					);
				}
			}
			
			if(version_compare(VERSION,'2.2.0.0','>=')){
				$this->response->setOutput($this->load->view('onepagecheckout/cart', $data));
			}else{
				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/cart.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/onepagecheckout/cart.tpl', $data));
				}else{
					$this->response->setOutput($this->load->view('default/template/onepagecheckout/cart.tpl', $data));
				}
			}
		}
	}
	
	public function edit() {
		$this->load->language('onepagecheckout/checkout');
		
		$json = array();

		// Update
		if (!empty($this->request->post['key']) && !empty($this->request->post['quantity'])) {
			$this->cart->update($this->request->post['key'], $this->request->post['quantity']);
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
			
			// Totals
			$this->load->model('extension/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;
	
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						// We have to put the totals in an array so that they pass by reference.
						if(version_compare(VERSION,'2.3.0.0','>=')){
							$this->load->model('extension/total/' . $result['code']);
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}else{
							if(version_compare(VERSION,'2.2.0.0','=')){
								$this->load->model('total/' . $result['code']);
								$this->{'model_total_' . $result['code']}->getTotal($total_data);
							}else{
								$this->load->model('total/' . $result['code']);
								$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
							}
						}
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}
			
			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function isMobile(){
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}
