<?php
class ControllerCheckoutOrderSuccessPage extends Controller {	
		private $error = array();
		private $ssl = 'SSL';
		private $tpl = '.tpl';
	 
	 public function __construct($registry){
		 parent::__construct( $registry );
		 $this->ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
		 $this->tpl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? false : '.tpl';
	 }
	
	public function index() {
		$this->load->language('checkout/order_success_page');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if(isset($this->request->get['store_id'])) {
			$data['store_id'] = $this->request->get['store_id'];
		}else{
			$data['store_id']	= 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('order_success_page', $this->request->post,$data['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');
			
				$this->response->redirect($this->url->link('checkout/order_success_page', '&store_id='.$data['store_id'].'&token=' . $this->session->data['token'] , $this->ssl));
			
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');

		//Entry
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_order_comments'] = $this->language->get('entry_order_comments');
		$data['entry_payment_address'] = $this->language->get('entry_payment_address');
		$data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$data['entry_show_image'] = $this->language->get('entry_show_image');
		$data['entry_products'] = $this->language->get('entry_products');
		$data['entry_payment_format'] = $this->language->get('entry_payment_format');
		$data['entry_order_details'] = $this->language->get('entry_order_details');
		$data['entry_shipping_format'] = $this->language->get('entry_shipping_format');
		$data['entry_order_details_heading'] = $this->language->get('entry_order_details_heading');
		$data['entry_image_height'] = $this->language->get('entry_image_height');
		$data['entry_auto_send_order'] = $this->language->get('entry_auto_send_order');
		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		$data['entry_order_invoice'] = $this->language->get('entry_order_invoice');
		$data['entry_print_invoice'] = $this->language->get('entry_print_invoice');
		$data['entry_order_heading'] = $this->language->get('entry_order_heading');
		$data['entry_promote_products'] = $this->language->get('entry_promote_products');
		$data['entry_products'] = $this->language->get('entry_products');
		$data['entry_google_analytics'] = $this->language->get('entry_google_analytics');
		$data['entry_bank_details'] = $this->language->get('entry_bank_details');
		
		
		
		//Tab
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_page'] = $this->language->get('tab_page');
		$data['tab_support'] = $this->language->get('tab_support');
		$data['tab_language'] = $this->language->get('tab_language');
		$data['tab_promote_product'] = $this->language->get('tab_promote_product');
		$data['tab_analytics'] = $this->language->get('tab_analytics');
		
		//help
		$data['help_order_comment'] = $this->language->get('help_order_comment');
		$data['help_payment_address'] = $this->language->get('help_payment_address');
		$data['help_shipping_address'] = $this->language->get('help_shipping_address');
		$data['help_order_details'] = $this->language->get('help_order_details');
		$data['help_complete_status'] = $this->language->get('help_complete_status');
		$data['help_order_invoice'] = $this->language->get('help_order_invoice');
		$data['help_print_invoice'] = $this->language->get('help_print_invoice');
		$data['help_promote_products'] = $this->language->get('help_promote_products');
		$data['help_products'] = $this->language->get('help_products');
		$data['help_bank_details'] = $this->language->get('help_bank_details');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['token'] =  $this->session->data['token'];
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/order_success_page', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('checkout/order_success_page', 'token=' . $this->session->data['token'] . '&store_id='. $data['store_id'], $this->ssl . '&type=module');
		
		$data['store_action'] =  $this->url->link('checkout/order_success_page','token=' . $this->session->data['token'], $this->ssl . '&type=module');

		$data['cancel'] = $this->url->link('checkout/onepagecheckout', 'token=' . $this->session->data['token']);
		
		
		$store_info = $this->model_setting_setting->getSetting('order_success_page', $data['store_id']);

		if (isset($this->request->post['order_success_page_status'])) {
			$data['order_success_page_status'] = $this->request->post['order_success_page_status'];
		}else if(isset($store_info['order_success_page_status'])){
			$data['order_success_page_status'] = $store_info['order_success_page_status'];
		} else {
			$data['order_success_page_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_order_invoice'])) {
			$data['order_success_page_order_invoice'] = $this->request->post['order_success_page_order_invoice'];
		}else if(isset($store_info['order_success_page_order_invoice'])){
			$data['order_success_page_order_invoice'] = $store_info['order_success_page_order_invoice'];
		} else {
			$data['order_success_page_order_invoice'] = '';
		}
		
		if (isset($this->request->post['order_success_page_promote_products'])) {
			$data['order_success_page_promote_products'] = $this->request->post['order_success_page_promote_products'];
		}else if(isset($store_info['order_success_page_promote_products'])){
			$data['order_success_page_promote_products'] = $store_info['order_success_page_promote_products'];
		} else {
			$data['order_success_page_promote_products'] = '';
		}
		
		if (isset($this->request->post['order_success_page_print_invoice'])) {
			$data['order_success_page_print_invoice'] = $this->request->post['order_success_page_print_invoice'];
		}else if(isset($store_info['order_success_page_print_invoice'])){
			$data['order_success_page_print_invoice'] = $store_info['order_success_page_print_invoice'];
		} else {
			$data['order_success_page_print_invoice'] = '';
		}
		
		if (isset($this->request->post['order_success_page_complete_status'])) {
			$data['order_success_page_complete_status'] = $this->request->post['order_success_page_complete_status'];
		}else if(isset($store_info['order_success_page_complete_status'])){
			$data['order_success_page_complete_status'] = $store_info['order_success_page_complete_status'];
		} else {
			$data['order_success_page_complete_status'] = array();
		}
		
		if (isset($this->request->post['order_success_page_send_button_status'])) {
			$data['order_success_page_send_button_status'] = $this->request->post['order_success_page_send_button_status'];
		}else if(isset($store_info['order_success_page_send_button_status'])){
			$data['order_success_page_send_button_status'] = $store_info['order_success_page_send_button_status'];
		} else {
			$data['order_success_page_send_button_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_send_bulk_status'])) {
			$data['order_success_page_send_bulk_status'] = $this->request->post['order_success_page_send_bulk_status'];
		}else if(isset($store_info['order_success_page_send_bulk_status'])){
			$data['order_success_page_send_bulk_status'] = $store_info['order_success_page_send_bulk_status'];
		} else {
			$data['order_success_page_send_bulk_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_pdf_stream'])) {
			$data['order_success_page_pdf_stream'] = $this->request->post['order_success_page_pdf_stream'];
		}else if(isset($store_info['order_success_page_pdf_stream'])){
			$data['order_success_page_pdf_stream'] = $store_info['order_success_page_pdf_stream'];
		} else {
			$data['order_success_page_pdf_stream'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_width'])) {
			$data['order_success_page_product_width'] = $this->request->post['order_success_page_product_width'];
		}else if(isset($store_info['order_success_page_product_width'])){
			$data['order_success_page_product_width'] = $store_info['order_success_page_product_width'];
		} else {
			$data['order_success_page_product_width'] = '100';
		}
		
		if (isset($this->request->post['order_success_page_product_height'])) {
			$data['order_success_page_product_height'] = $this->request->post['order_success_page_product_height'];
		}else if(isset($store_info['order_success_page_product_height'])){
			$data['order_success_page_product_height'] = $store_info['order_success_page_product_height'];
		} else {
			$data['order_success_page_product_height'] = '100';
		}
		
		if (isset($this->request->post['order_success_page_promote_product_width'])) {
			$data['order_success_page_promote_product_width'] = $this->request->post['order_success_page_promote_product_width'];
		}else if(isset($store_info['order_success_page_promote_product_width'])){
			$data['order_success_page_promote_product_width'] = $store_info['order_success_page_promote_product_width'];
		} else {
			$data['order_success_page_promote_product_width'] = '100';
		}
		
		if (isset($this->request->post['order_success_page_google_analytics'])) {
			$data['order_success_page_google_analytics'] = $this->request->post['order_success_page_google_analytics'];
		}else if(isset($store_info['order_success_page_google_analytics'])){
			$data['order_success_page_google_analytics'] = $store_info['order_success_page_google_analytics'];
		} else {
			$data['order_success_page_google_analytics'] = '';
		}
		
		if (isset($this->request->post['order_success_page_promote_product_height'])) {
			$data['order_success_page_promote_product_height'] = $this->request->post['order_success_page_promote_product_height'];
		}else if(isset($store_info['order_success_page_promote_product_height'])){
			$data['order_success_page_promote_product_height'] = $store_info['order_success_page_promote_product_height'];
		} else {
			$data['order_success_page_promote_product_height'] = '100';
		}
		
		if (isset($this->request->post['order_success_page_download_order_customer_status'])) {
			$data['order_success_page_download_order_customer_status'] = $this->request->post['order_success_page_send_button_status'];
		}else if(isset($store_info['order_success_page_download_order_customer_status'])){
			$data['order_success_page_download_order_customer_status'] = $store_info['order_success_page_download_order_customer_status'];
		} else {
			$data['order_success_page_download_order_customer_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_download_order_admin_status'])) {
			$data['order_success_page_download_order_admin_status'] = $this->request->post['order_success_page_download_order_admin_status'];
		}else if(isset($store_info['order_success_page_download_order_admin_status'])){
			$data['order_success_page_download_order_admin_status'] = $store_info['order_success_page_download_order_admin_status'];
		} else {
			$data['order_success_page_download_order_admin_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_title_backgound'])) {
			$data['order_success_page_title_backgound'] = $this->request->post['order_success_page_title_backgound'];
		}else if(isset($store_info['order_success_page_title_backgound'])){
			$data['order_success_page_title_backgound'] = $store_info['order_success_page_title_backgound'];
		} else {
			$data['order_success_page_title_backgound'] = '';
		}
		
		if (isset($this->request->post['order_success_page_title_color'])) {
			$data['order_success_page_title_color'] = $this->request->post['order_success_page_title_color'];
		}else if(isset($store_info['order_success_page_title_color'])){
			$data['order_success_page_title_color'] = $store_info['order_success_page_title_color'];
		} else {
			$data['order_success_page_title_color'] = '';
		}
		
		foreach($data['languages'] as $language) {
			if (isset($this->request->post['order_success_page_order_heading' . $language['language_id']])) {
				$data['order_success_page_order_heading' . $language['language_id']] = $this->request->post['order_success_page_order_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_order_heading'. $language['language_id']])){
				$data['order_success_page_order_heading'. $language['language_id']] = $store_info['order_success_page_order_heading'. $language['language_id']];
			} else {
				$data['order_success_page_order_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_order_details_heading' . $language['language_id']])) {
				$data['order_success_page_order_details_heading' . $language['language_id']] = $this->request->post['order_success_page_order_details_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_order_details_heading'. $language['language_id']])){
				$data['order_success_page_order_details_heading'. $language['language_id']] = $store_info['order_success_page_order_details_heading'. $language['language_id']];
			} else {
				$data['order_success_page_order_details_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_print_invoice_text' . $language['language_id']])) {
				$data['order_success_page_print_invoice_text' . $language['language_id']] = $this->request->post['order_success_page_print_invoice_text' . $language['language_id']];
			}else if(isset($store_info['order_success_page_print_invoice_text'. $language['language_id']])){
				$data['order_success_page_print_invoice_text'. $language['language_id']] = $store_info['order_success_page_print_invoice_text'. $language['language_id']];
			} else {
				$data['order_success_page_print_invoice_text' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_continue_text' . $language['language_id']])) {
				$data['order_success_page_continue_text' . $language['language_id']] = $this->request->post['order_success_page_continue_text' . $language['language_id']];
			}else if(isset($store_info['order_success_page_continue_text'. $language['language_id']])){
				$data['order_success_page_continue_text'. $language['language_id']] = $store_info['order_success_page_continue_text'. $language['language_id']];
			} else {
				$data['order_success_page_continue_text' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_order_comment_heading' . $language['language_id']])) {
				$data['order_success_page_order_comment_heading' . $language['language_id']] = $this->request->post['order_success_page_order_comment_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_order_comment_heading'. $language['language_id']])){
				$data['order_success_page_order_comment_heading'. $language['language_id']] = $store_info['order_success_page_order_comment_heading'. $language['language_id']];
			} else {
				$data['order_success_page_order_comment_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_payment_address_heading' . $language['language_id']])) {
				$data['order_success_page_payment_address_heading' . $language['language_id']] = $this->request->post['order_success_page_payment_address_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_payment_address_heading'. $language['language_id']])){
				$data['order_success_page_payment_address_heading'. $language['language_id']] = $store_info['order_success_page_payment_address_heading'. $language['language_id']];
			} else {
				$data['order_success_page_payment_address_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_bank_details_heading' . $language['language_id']])) {
				$data['order_success_page_bank_details_heading' . $language['language_id']] = $this->request->post['order_success_page_bank_details_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_bank_details_heading'. $language['language_id']])){
				$data['order_success_page_bank_details_heading'. $language['language_id']] = $store_info['order_success_page_bank_details_heading'. $language['language_id']];
			} else {
				$data['order_success_page_bank_details_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_shipping_address_heading' . $language['language_id']])) {
				$data['order_success_page_shipping_address_heading' . $language['language_id']] = $this->request->post['order_success_page_shipping_address_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_shipping_address_heading'. $language['language_id']])){
				$data['order_success_page_shipping_address_heading'. $language['language_id']] = $store_info['order_success_page_shipping_address_heading'. $language['language_id']];
			} else {
				$data['order_success_page_shipping_address_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_image_title' . $language['language_id']])) {
				$data['order_success_page_image_title' . $language['language_id']] = $this->request->post['order_success_page_image_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_image_title'. $language['language_id']])){
				$data['order_success_page_image_title'. $language['language_id']] = $store_info['order_success_page_image_title'. $language['language_id']];
			} else {
				$data['order_success_page_image_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_product_title' . $language['language_id']])) {
				$data['order_success_page_product_title' . $language['language_id']] = $this->request->post['order_success_page_product_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_product_title'. $language['language_id']])){
				$data['order_success_page_product_title'. $language['language_id']] = $store_info['order_success_page_product_title'. $language['language_id']];
			} else {
				$data['order_success_page_product_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_model_title' . $language['language_id']])) {
				$data['order_success_page_model_title' . $language['language_id']] = $this->request->post['order_success_page_model_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_model_title'. $language['language_id']])){
				$data['order_success_page_model_title'. $language['language_id']] = $store_info['order_success_page_model_title'. $language['language_id']];
			} else {
				$data['order_success_page_model_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_sku_title' . $language['language_id']])) {
				$data['order_success_page_sku_title' . $language['language_id']] = $this->request->post['order_success_page_sku_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_sku_title'. $language['language_id']])){
				$data['order_success_page_sku_title'. $language['language_id']] = $store_info['order_success_page_sku_title'. $language['language_id']];
			} else {
				$data['order_success_page_sku_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_qty_title' . $language['language_id']])) {
				$data['order_success_page_qty_title' . $language['language_id']] = $this->request->post['order_success_page_qty_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_qty_title'. $language['language_id']])){
				$data['order_success_page_qty_title'. $language['language_id']] = $store_info['order_success_page_qty_title'. $language['language_id']];
			} else {
				$data['order_success_page_qty_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_unit_title' . $language['language_id']])) {
				$data['order_success_page_unit_title' . $language['language_id']] = $this->request->post['order_success_page_unit_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_unit_title'. $language['language_id']])){
				$data['order_success_page_unit_title'. $language['language_id']] = $store_info['order_success_page_unit_title'. $language['language_id']];
			} else {
				$data['order_success_page_unit_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_total_title' . $language['language_id']])) {
				$data['order_success_page_total_title' . $language['language_id']] = $this->request->post['order_success_page_total_title' . $language['language_id']];
			}else if(isset($store_info['order_success_page_total_title'. $language['language_id']])){
				$data['order_success_page_total_title'. $language['language_id']] = $store_info['order_success_page_total_title'. $language['language_id']];
			} else {
				$data['order_success_page_total_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_guest_message' . $language['language_id']])) {
				$data['order_success_page_guest_message' . $language['language_id']] = $this->request->post['order_success_page_guest_message' . $language['language_id']];
			}else if(isset($store_info['order_success_page_guest_message'. $language['language_id']])){
				$data['order_success_page_guest_message'. $language['language_id']] = $store_info['order_success_page_guest_message'. $language['language_id']];
			} else {
				$data['order_success_page_guest_message' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_register_message' . $language['language_id']])) {
				$data['order_success_page_register_message' . $language['language_id']] = $this->request->post['order_success_page_register_message' . $language['language_id']];
			}else if(isset($store_info['order_success_page_register_message'. $language['language_id']])){
				$data['order_success_page_register_message'. $language['language_id']] = $store_info['order_success_page_register_message'. $language['language_id']];
			} else {
				$data['order_success_page_register_message' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_shipping_promote_product_heading' . $language['language_id']])) {
				$data['order_success_page_shipping_promote_product_heading' . $language['language_id']] = $this->request->post['order_success_page_shipping_promote_product_heading' . $language['language_id']];
			}else if(isset($store_info['order_success_page_shipping_promote_product_heading'. $language['language_id']])){
				$data['order_success_page_shipping_promote_product_heading'. $language['language_id']] = $store_info['order_success_page_shipping_promote_product_heading'. $language['language_id']];
			} else {
				$data['order_success_page_shipping_promote_product_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['order_success_page_order_details'])) {
			$data['order_success_page_order_details'] = $this->request->post['order_success_page_order_details'];
		}else if(isset($store_info['order_success_page_order_details'])){
			$data['order_success_page_order_details'] = $store_info['order_success_page_order_details'];
		} else {
			$data['order_success_page_order_details'] = '';
		}
		
		if (isset($this->request->post['order_success_page_comment_status'])) {
			$data['order_success_page_comment_status'] = $this->request->post['order_success_page_comment_status'];
		}else if(isset($store_info['order_success_page_comment_status'])){
			$data['order_success_page_comment_status'] = $store_info['order_success_page_comment_status'];
		} else {
			$data['order_success_page_comment_status'] = '';
		}
		}
		
		if (isset($this->request->post['order_success_page_width'])) {
			$data['order_success_page_width'] = $this->request->post['order_success_page_width'];
		}else if(isset($store_info['order_success_page_width'])){
			$data['order_success_page_width'] = $store_info['order_success_page_width'];
		} else {
			$data['order_success_page_width'] = 50;
		}
		
		if (isset($this->request->post['order_success_page_height'])) {
			$data['order_success_page_height'] = $this->request->post['order_success_page_height'];
		}else if(isset($store_info['order_success_page_height'])){
			$data['order_success_page_height'] = $store_info['order_success_page_height'];
		} else {
			$data['order_success_page_height'] = 50;
		}
		
		
		if (isset($this->request->post['order_success_page_shipping_address_status'])) {
			$data['order_success_page_shipping_address_status'] = $this->request->post['order_success_page_shipping_address_status'];
		}else if(isset($store_info['order_success_page_shipping_address_status'])){
			$data['order_success_page_shipping_address_status'] = $store_info['order_success_page_shipping_address_status'];
		} else {
			$data['order_success_page_shipping_address_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_bank_details_status'])) {
			$data['order_success_page_bank_details_status'] = $this->request->post['order_success_page_bank_details_status'];
		}else if(isset($store_info['order_success_page_bank_details_status'])){
			$data['order_success_page_bank_details_status'] = $store_info['order_success_page_bank_details_status'];
		} else {
			$data['order_success_page_bank_details_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_payment_address_status'])) {
			$data['order_success_page_payment_address_status'] = $this->request->post['order_success_page_payment_address_status'];
		}else if(isset($store_info['order_success_page_payment_address_status'])){
			$data['order_success_page_payment_address_status'] = $store_info['order_success_page_payment_address_status'];
		} else {
			$data['order_success_page_payment_address_status'] = '';
		}
		
		
		if (isset($this->request->post['order_success_page_product_image_status'])) {
			$data['order_success_page_product_image_status'] = $this->request->post['order_success_page_product_image_status'];
		}else if(isset($store_info['order_success_page_product_image_status'])){
			$data['order_success_page_product_image_status'] = $store_info['order_success_page_product_image_status'];
		} else {
			$data['order_success_page_product_image_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_name_status'])) {
			$data['order_success_page_product_name_status'] = $this->request->post['order_success_page_product_name_status'];
		}else if(isset($store_info['order_success_page_product_name_status'])){
			$data['order_success_page_product_name_status'] = $store_info['order_success_page_product_name_status'];
		} else {
			$data['order_success_page_product_name_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_model_status'])) {
			$data['order_success_page_product_model_status'] = $this->request->post['order_success_page_product_model_status'];
		}else if(isset($store_info['order_success_page_product_model_status'])){
			$data['order_success_page_product_model_status'] = $store_info['order_success_page_product_model_status'];
		} else {
			$data['order_success_page_product_model_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_sku_status'])) {
			$data['order_success_page_product_sku_status'] = $this->request->post['order_success_page_product_sku_status'];
		}else if(isset($store_info['order_success_page_product_sku_status'])){
			$data['order_success_page_product_sku_status'] = $store_info['order_success_page_product_sku_status'];
		} else {
			$data['order_success_page_product_sku_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_qty_status'])) {
			$data['order_success_page_product_qty_status'] = $this->request->post['order_success_page_product_qty_status'];
		}else if(isset($store_info['order_success_page_product_qty_status'])){
			$data['order_success_page_product_qty_status'] = $store_info['order_success_page_product_qty_status'];
		} else {
			$data['order_success_page_product_qty_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_unit_price_status'])) {
			$data['order_success_page_product_unit_price_status'] = $this->request->post['order_success_page_product_unit_price_status'];
		}else if(isset($store_info['order_success_page_product_unit_price_status'])){
			$data['order_success_page_product_unit_price_status'] = $store_info['order_success_page_product_unit_price_status'];
		} else {
			$data['order_success_page_product_unit_price_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_product_total_status'])) {
			$data['order_success_page_product_total_status'] = $this->request->post['order_success_page_product_total_status'];
		}else if(isset($store_info['order_success_page_product_total_status'])){
			$data['order_success_page_product_total_status'] = $store_info['order_success_page_product_total_status'];
		} else {
			$data['order_success_page_product_total_status'] = '';
		}
		
		if (isset($this->request->post['order_success_page_payment_address_format'])) {
			$data['order_success_page_payment_address_format'] = $this->request->post['order_success_page_payment_address_format'];
		}else if(isset($store_info['order_success_page_payment_address_format'])){
			$data['order_success_page_payment_address_format'] = $store_info['order_success_page_payment_address_format'];
		} else {
			$data['order_success_page_payment_address_format'] = $this->language->get('text_payment_address_format');
		}
		
		if (isset($this->request->post['order_success_page_shipping_address_format'])) {
			$data['order_success_page_shipping_address_format'] = $this->request->post['order_success_page_payment_address_format'];
		}else if(isset($store_info['order_success_page_shipping_address_format'])){
			$data['order_success_page_shipping_address_format'] = $store_info['order_success_page_shipping_address_format'];
		} else {
			$data['order_success_page_shipping_address_format'] = $this->language->get('text_payment_address_format');
		}
		
		
		
		$this->load->model('catalog/product');

		$data['products'] = array();
		
		if (isset($this->request->post['order_success_page_product'])) {
			$products = $this->request->post['order_success_page_product'];
		}else if(isset($store_info['order_success_page_product'])){
			$products = $store_info['order_success_page_product'];
		} else {
			$products = array();
		}
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['order_success_page_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['order_success_page_logo'], 100, 100);
		} elseif (isset($store_info['order_success_page_logo']) && is_file(DIR_IMAGE . $store_info['order_success_page_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($store_info['order_success_page_logo'], 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('checkout/order_success_page'.$this->tpl, $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'checkout/order_success_page')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}