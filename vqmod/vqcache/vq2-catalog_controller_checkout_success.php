<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

		if($this->config->get('order_success_page_status')){
		  $this->response->redirect($this->url->link('onepagecheckout/ordersuccess&order_id='.$this->session->data['order_id'], '', 'SSL'));
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
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
		
		$this->load->model('tool/image');

		$data['page_banner'] = $this->model_tool_image->resize($this->config->get('config_common_banner'), $this->config->get($this->config->get('config_theme') . '_slider_category_width'), $this->config->get($this->config->get('config_theme') . '_slider_category_height'));

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}