<?php
class ControllerOnepagecheckoutLogin extends Controller {
	public function index() {
		$this->load->language('onepagecheckout/checkout');

		
		$data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_guest'] = $this->language->get('text_guest');
		$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$data['text_register_account'] = $this->language->get('text_register_account');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_login'] = $this->language->get('button_login');

		$data['checkout_guest'] = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = 'register';
		}

		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		
		$this->load->model('setting/setting');
		$onepagecheckout_info = $this->model_setting_setting->getSetting('onepagecheckout', $this->config->get('config_store_id'));
		
		$onepagecheckout_manage = (!empty($onepagecheckout_info['onepagecheckout_manage'])) ? $onepagecheckout_info['onepagecheckout_manage'] : array();
		
		$data['text_login'] = (!empty($onepagecheckout_manage['login']['heading_title'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['heading_title'][$this->config->get('config_language_id')] : $this->language->get('text_login');
		
		
		$data['entry_email'] = (!empty($onepagecheckout_manage['login']['email']['label'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['email']['label'][$this->config->get('config_language_id')] : $this->language->get('entry_email');
		
		 $data['entry_email_placeholder'] = (!empty($onepagecheckout_manage['login']['email']['placeholder'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['email']['placeholder'][$this->config->get('config_language_id')] : $this->language->get('entry_email');
		
		
		$data['entry_password'] = (!empty($onepagecheckout_manage['login']['password']['label'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['password']['label'][$this->config->get('config_language_id')] : $this->language->get('entry_password');
		
		 $data['entry_password_placeholder'] = (!empty($onepagecheckout_manage['login']['password']['placeholder'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['password']['placeholder'][$this->config->get('config_language_id')] : $this->language->get('entry_password');
		
		
		$data['button_login'] = (!empty($onepagecheckout_manage['login']['button_text'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['button_text'][$this->config->get('config_language_id')] : $this->language->get('button_login');
		
		
		if(version_compare(VERSION,'2.2.0.0','>=')){
				return $this->load->view('onepagecheckout/login', $data);
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/login.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/login.tpl', $data);
			} else {
				return $this->load->view('default/template/onepagecheckout/login.tpl', $data);
			}
		}
	}

	public function save() {
		$this->load->language('checkout/checkout');
		
		$this->load->model('setting/setting');
		$onepagecheckout_info = $this->model_setting_setting->getSetting('onepagecheckout', $this->config->get('config_store_id'));
		
		$onepagecheckout_manage = (!empty($onepagecheckout_info['onepagecheckout_manage'])) ? $onepagecheckout_info['onepagecheckout_manage'] : array();
		
		

		$json = array();

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('onepagecheckout/checkout', '', true);
		}

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers']))) {
			$json['redirect'] = $this->url->link('onepagecheckout/checkout');
		}

		if (!$json) {
			$this->load->model('account/customer');

			// Check how many login attempts have been made.
			$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

			if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
				$json['error']['warning'] = $this->language->get('error_attempts');
			}

			// Check if customer has been approved.
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info && !$customer_info['approved']) {
				$json['error']['warning'] = (!empty($onepagecheckout_manage['login']['approved_message'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['approved_message'][$this->config->get('config_language_id')] : $this->language->get('error_approved');
			}

			if (!isset($json['error'])) {
				if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
					$json['error']['warning'] = (!empty($onepagecheckout_manage['login']['wrong_message'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['wrong_message'][$this->config->get('config_language_id')] : $this->language->get('error_login');
					$this->model_account_customer->addLoginAttempt($this->request->post['email']);
				} else {
					$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
				}
			}
		}

		if (!$json) {
			// Unset guest
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);
			
			$json['success'] = (!empty($onepagecheckout_manage['login']['approved_message'][$this->config->get('config_language_id')])) ? $onepagecheckout_manage['login']['approved_message'][$this->config->get('config_language_id')] : '';

			$json['redirect'] = $this->url->link('onepagecheckout/checkout', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}