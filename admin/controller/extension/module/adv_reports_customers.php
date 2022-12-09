<?php
class ControllerExtensionModuleAdvReportsCustomers extends Controller {
	private $error = array(); 

	public function index() {  		
		$this->load->language('extension/module/adv_reports_customers');
		
		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['heading_title_main'] = $this->language->get('heading_title_main');
		$data['text_edit'] = $this->language->get('text_edit');
			
		$data['tab_about'] = $this->language->get('tab_about');
		
		$data['text_help_request'] = $this->language->get('text_help_request');
		$data['text_asking_help'] = $this->language->get('text_asking_help');		
		$data['text_terms'] = $this->language->get('text_terms');		

		$data['button_documentation'] = $this->language->get('button_documentation');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['button_cancel'] = $this->language->get('button_cancel');	
		
		$data['token'] = $this->session->data['token'];
		
  		$data['breadcrumbs'] = array();
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)

   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_extension'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_main'),
			'href'      => $this->url->link('extension/module/adv_reports_customers', 'token=' . $this->session->data['token'], true)
   		);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/adv_reports_customers', $data));
	}
	
	public function install(){
		// Optimize all tables
		//$alltables = mysql_query("SHOW TABLES");
		//while ($table = mysql_fetch_assoc($alltables)) {
		//	foreach ($table as $db => $tablename) {
		//		mysql_query("OPTIMIZE TABLE `" . $tablename . "`")
		//		or die(mysql_error());
		//	}
		//}
		
		// Add indexes
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "order_product` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` ADD INDEX (product_id,total,price,tax,quantity);");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` ADD INDEX (order_id);");
			}	
			
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "order_total` WHERE Key_name != 'PRIMARY' AND Key_name != 'idx_orders_total_orders_id';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_total` ADD INDEX (order_id,value,code);");
			}	
			
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "order_option` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD INDEX (order_product_id,type,name,product_option_value_id);");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD INDEX (order_id);");
			}
			
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "order_history` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_history` ADD INDEX (order_status_id);");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_history` ADD INDEX (order_id);");
			}
			
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "order` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD INDEX (customer_id,date_added,total,email,firstname,lastname,payment_company);");
			}

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "product` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD INDEX (product_id,model,sku,manufacturer_id,sort_order,status);");
			}	

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "category` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD INDEX (category_id,parent_id);");
			}	

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "option` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "option` ADD INDEX (sort_order);");
			}
			
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "option_description` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "option_description` ADD INDEX (name);");
			}	

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "option_value` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "option_value` ADD INDEX (option_id,sort_order);");
			}

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "option_value_description` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "option_value_description` ADD INDEX (option_id,name);");
			}

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "product_option` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` ADD INDEX (product_id,option_id);");
			}

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "product_option_value` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX (product_id,option_id,option_value_id,quantity,price);");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX (product_option_id);");
			}

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "return` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "return` ADD INDEX (order_id);");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "return` ADD INDEX (product_id);");
			}	

		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "customer_reward` WHERE Key_name != 'PRIMARY';");
			if (!$query->rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_reward` ADD INDEX (order_id);");
			}
			
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'report/adv_customers');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'report/adv_customers');		
	}
}