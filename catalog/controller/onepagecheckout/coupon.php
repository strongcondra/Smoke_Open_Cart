<?php
class ControllerOnepagecheckoutCoupon extends Controller {
	public function index() {
		if ($this->config->get('coupon_status')) {
			if(version_compare(VERSION,'2.3.0.0','>=')){
			   $this->load->language('extension/total/coupon');
			}else{
				if(version_compare(VERSION,'2.0.3.1','<=')){
					 $this->load->language('checkout/coupon');
				}else{
					$this->load->language('total/coupon');
				}
			}
			
			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_coupon'] = $this->language->get('entry_coupon');

			$data['button_coupon'] = $this->language->get('button_coupon');

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}
			
			if(version_compare(VERSION,'2.2.0.0','>=')){
				return $this->load->view('onepagecheckout/coupon', $data);
			}else{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/coupon.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/coupon.tpl', $data);
				} else {
					return $this->load->view('default/template/onepagecheckout/coupon.tpl', $data);
				}
			}
		}
	}

	public function coupon() {
		if(version_compare(VERSION,'2.3.0.0','>=')){
		 $this->load->language('extension/total/coupon');
		 $this->load->model('extension/total/coupon');
		}else{
			if(version_compare(VERSION,'2.0.3.1','<=')){
				$this->load->language('checkout/coupon');
				$this->load->model('checkout/coupon');
			}else{
				 $this->load->language('total/coupon');	
				$this->load->model('total/coupon');
			}
		}

		$json = array();
		
		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}
		
		if(version_compare(VERSION,'2.3.0.0','>=')){
			$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);
		}else{
			if(version_compare(VERSION,'2.0.3.1','<=')){
				 $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
			}else{
				 $coupon_info = $this->model_total_coupon->getCoupon($coupon);
			}
		}

		if (empty($this->request->post['coupon'])) {
			$json['error'] = $this->language->get('error_empty');

			unset($this->session->data['coupon']);
		} elseif ($coupon_info) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_coupon');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
