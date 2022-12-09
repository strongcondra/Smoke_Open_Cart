<?php
static $config = NULL;
static $log = NULL;

// Error Handler
function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

function fatal_error_shutdown_handler_for_export() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ControllerReportAdvCustomers extends Controller { 
	private $error = array();
	
	public function index() { 			
		$this->load->language('report/adv_customers');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'adv_reports_customers'");
		if (empty($query->num_rows)) {	
			$this->session->data['success'] = $this->language->get('error_installed');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));		
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/adv_customers');

	    $this->document->addScript('view/javascript/bootstrap/js/bootstrap-multiselect.js');
	    $this->document->addStyle('view/javascript/bootstrap/css/bootstrap-multiselect.css');
		$this->document->addScript('view/javascript/bootstrap/js/bootstrap-select.min.js');
		$this->document->addStyle('view/javascript/bootstrap/css/bootstrap-select.css');
		
		if (isset($this->request->get['filter_date_start'])) {
			$this->session->data['filter_date_start'] = $filter_date_start = $this->request->get['filter_date_start'];			
		} else {
			$this->session->data['filter_date_start'] = $filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$this->session->data['filter_date_end'] = $filter_date_end = $this->request->get['filter_date_end'];			
		} else {
			$this->session->data['filter_date_end'] = $filter_date_end = '';
		}

		$data['ranges'] = array();
		
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_custom'),
			'value' => 'custom',
			'style' => 'color:#666',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_today'),
			'value' => 'today',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_yesterday'),
			'value' => 'yesterday',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_week'),
			'value' => 'week',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_month'),
			'value' => 'month',
			'style' => 'color:#090',
		);					
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_quarter'),
			'value' => 'quarter',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_year'),
			'value' => 'year',
			'style' => 'color:#090',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_week'),
			'value' => 'current_week',
			'style' => 'color:#06C',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_month'),
			'value' => 'current_month',
			'style' => 'color:#06C',
		);	
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_quarter'),
			'value' => 'current_quarter',
			'style' => 'color:#06C',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_year'),
			'value' => 'current_year',
			'style' => 'color:#06C',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_week'),
			'value' => 'last_week',
			'style' => 'color:#F90',
		);
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_month'),
			'value' => 'last_month',
			'style' => 'color:#F90',
		);	
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_quarter'),
			'value' => 'last_quarter',
			'style' => 'color:#F90',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_year'),
			'value' => 'last_year',
			'style' => 'color:#F90',
		);			
		$data['ranges'][] = array(
			'text'  => $this->language->get('stat_all_time'),
			'value' => 'all_time',
			'style' => 'color:#F00',
		);
		
		if (isset($this->request->get['filter_range'])) {
			$this->session->data['filter_range'] = $filter_range = $this->request->get['filter_range'];		
		} else {
			$this->session->data['filter_range'] = $filter_range = 'current_year'; //show Current Year in Statistical Range by default
		}

		$data['report'] = array();

		$data['report'][] = array(
			'text'		=> $this->language->get('text_all_registered_customers'),
			'value'		=> 'all_registered_customers_with_without_orders',
			'subtext'	=> $this->language->get('text_with_without_orders'),
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'		=> $this->language->get('text_registered_customers'),
			'value'		=> 'registered_customers_with_orders',
			'subtext'	=> $this->language->get('text_with_orders'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'		=> $this->language->get('text_registered_customers'),
			'value'		=> 'registered_customers_without_orders',
			'subtext'	=> $this->language->get('text_without_orders'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'		=> $this->language->get('text_registered_guest_customers'),
			'value'		=> 'registered_and_guest_customers',
			'subtext'	=> $this->language->get('text_with_orders'),
			'divider' 	=> '',
		);				
		$data['report'][] = array(
			'text'		=> $this->language->get('text_guest_customers'),
			'value'		=> 'guest_customers',
			'subtext'	=> $this->language->get('text_with_orders'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'		=> $this->language->get('text_new_customers'),
			'value'		=> 'new_customers',
			'subtext'	=> $this->language->get('text_new_customers_text'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'		=> $this->language->get('text_old_customers'),
			'value'		=> 'old_customers',
			'subtext'	=> $this->language->get('text_old_customers_text'),
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'  	=> '',
			'value' 	=> '',
			'subtext' 	=> '',
			'divider'	=> 'true',
		);			
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_customers_abandoned_orders'),
			'value'		=> 'customers_abandoned_orders',
			'subtext'	=> $this->language->get('text_customers_abandoned'),
			'divider' 	=> '',
		);	
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_customers_shopping_carts'),
			'value' 	=> 'customers_shopping_carts',
			'subtext'	=> $this->language->get('text_shopping_carts'),	
			'divider' 	=> '',
		);
		$data['report'][] = array(
			'text'  	=> $this->language->get('text_customers_wishlists'),
			'value' 	=> 'customers_wishlists',
			'subtext'	=> $this->language->get('text_customer_wishlists'),	
			'divider' 	=> '',
		);			
		
		if (isset($this->request->get['filter_report'])) {
			$this->session->data['filter_report'] = $filter_report = $this->request->get['filter_report'];				
		} else {
			$this->session->data['filter_report'] = $filter_report = 'registered_and_guest_customers'; //show All Registered and Guests in Report By default
		}

		$data['details'] = array();

		$data['details'][] = array(
			'text'  	=> $this->language->get('text_no_details'),
			'value' 	=> 'no_details',
			'subtext' 	=> '',
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_basic_details'),
			'value' 	=> 'basic_details',
			'subtext' 	=> '',
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_all_details'),
			'value' 	=> 'all_details_products',
			'subtext'	=> $this->language->get('text_all_details_products'),	
		);
		$data['details'][] = array(
			'text'  	=> $this->language->get('text_all_details'),
			'value' 	=> 'all_details_orders',
			'subtext'	=> $this->language->get('text_all_details_orders'),	
		);	
		
		if (isset($this->request->get['filter_details'])) {
			$this->session->data['filter_details'] = $filter_details = $this->request->get['filter_details'];			
		} else {
			$this->session->data['filter_details'] = $filter_details = 'no_details';
		}	
		
		$data['group'] = array();

		$data['group'][] = array(
			'text'  => $this->language->get('text_no_group'),
			'value' => 'no_group',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_quarter'),
			'value' => 'quarter',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);
		$data['group'][] = array(
			'text'  => $this->language->get('text_order'),
			'value' => 'order',
		);
		
		if (isset($this->request->get['filter_group'])) {
			$this->session->data['filter_group'] = $filter_group = $this->request->get['filter_group'];			
		} else {
			$this->session->data['filter_group'] = $filter_group = 'no_group';
		}				

		if ($this->config->get('advco' . $this->user->getId() . '_settings_scw_columns')) {
			$advco_settings_scw_columns = $this->config->get('advco' . $this->user->getId() . '_settings_scw_columns');
		} else {
			$advco_settings_scw_columns = array();
		}
		
		if ($this->config->get('advco' . $this->user->getId() . '_settings_cwo_columns')) {
			$advco_settings_cwo_columns = $this->config->get('advco' . $this->user->getId() . '_settings_cwo_columns');
		} else {
			$advco_settings_cwo_columns = array();
		}

		if ($this->config->get('advco' . $this->user->getId() . '_settings_mv_columns')) {
			$advco_settings_mv_columns = $this->config->get('advco' . $this->user->getId() . '_settings_mv_columns');
		} else {
			$advco_settings_mv_columns = array();
		}
		
		$data['sort'] = array();

		$data['sort'][] = array(
			'text'  => $this->language->get('column_date'),
			'value' => 'date',
		);
		if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
		if (!$advco_settings_scw_columns or (in_array('scw_id', $advco_settings_scw_columns))) {
		$data['sort'][] = array(
			'text'  => $this->language->get('column_id'),
			'value' => 'id',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_customer', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer'),
			'value' => 'customer',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_email', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_email'),
			'value' => 'email',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_telephone', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_telephone'),
			'value' => 'telephone',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_customer_group', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_group'),
			'value' => 'customer_group',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_customer_status', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_status'),
			'value' => 'customer_status',
		);
		}
		if (in_array('scw_first_name', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_first_name'),
			'value' => 'first_name',
		);
		}
		if (in_array('scw_last_name', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_name'),
			'value' => 'last_name',
		);
		}
		if (in_array('scw_company', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_company'),
			'value' => 'company',
		);
		}
		if (in_array('scw_address_1', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_1'),
			'value' => 'address_1',
		);
		}
		if (in_array('scw_address_2', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_2'),
			'value' => 'address_2',
		);
		}
		if (in_array('scw_city', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_city'),
			'value' => 'city',
		);
		}
		if (in_array('scw_postcode', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_postcode'),
			'value' => 'postcode',
		);
		}
		if (in_array('scw_country_id', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_id'),
			'value' => 'country_id',
		);
		}
		if (in_array('scw_country', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country'),
			'value' => 'country',
		);
		}
		if (in_array('scw_country_code', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_code'),
			'value' => 'country_code',
		);
		}
		if (in_array('scw_zone_id', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_zone_id'),
			'value' => 'zone_id',
		);
		}
		if (in_array('scw_region_state', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state'),
			'value' => 'region_state',
		);
		}
		if (in_array('scw_region_state_code', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state_code'),
			'value' => 'region_state_code',
		);
		}
		if (in_array('scw_newsletter', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_newsletter'),
			'value' => 'newsletter',
		);
		}
		if (in_array('scw_approved', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_approved'),
			'value' => 'approved',
		);
		}
		if (in_array('scw_safe', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_safe'),
			'value' => 'safe',
		);
		}
		if (in_array('scw_ip', $advco_settings_scw_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_ip'),
			'value' => 'ip',
		);
		}
		if (!$advco_settings_scw_columns or (in_array('scw_total_logins', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_logins'),
			'value' => 'total_logins',
		);
		}	
		if (!$advco_settings_scw_columns or (in_array('scw_last_login', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_login'),
			'value' => 'last_login',
		);
		}		
		if ($filter_report == 'customers_shopping_carts') {
		if (!$advco_settings_scw_columns or (in_array('scw_cart_quantity', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_cart_quantity'),
			'value' => 'cart_quantity',
		);
		}
		} else if ($filter_report == 'customers_wishlists') {
		if (!$advco_settings_scw_columns or (in_array('scw_wishlist_quantity', $advco_settings_scw_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_wishlist_quantity'),
			'value' => 'wishlist_quantity',
		);
		}
		}	
		} else if ($filter_report == 'registered_customers_without_orders') {
		if (!$advco_settings_cwo_columns or (in_array('cwo_id', $advco_settings_cwo_columns))) {
		$data['sort'][] = array(
			'text'  => $this->language->get('column_id'),
			'value' => 'id',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_customer', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer'),
			'value' => 'customer',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_email', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_email'),
			'value' => 'email',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_telephone', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_telephone'),
			'value' => 'telephone',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_customer_group', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_group'),
			'value' => 'customer_group',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_customer_status', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_status'),
			'value' => 'customer_status',
		);
		}
		if (in_array('cwo_first_name', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_first_name'),
			'value' => 'first_name',
		);
		}
		if (in_array('cwo_last_name', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_name'),
			'value' => 'last_name',
		);
		}
		if (in_array('cwo_company', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_company'),
			'value' => 'company',
		);
		}
		if (in_array('cwo_address_1', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_1'),
			'value' => 'address_1',
		);
		}
		if (in_array('cwo_address_2', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_2'),
			'value' => 'address_2',
		);
		}
		if (in_array('cwo_city', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_city'),
			'value' => 'city',
		);
		}
		if (in_array('cwo_postcode', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_postcode'),
			'value' => 'postcode',
		);
		}
		if (in_array('cwo_country_id', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_id'),
			'value' => 'country_id',
		);
		}
		if (in_array('cwo_country', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country'),
			'value' => 'country',
		);
		}
		if (in_array('cwo_country_code', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_code'),
			'value' => 'country_code',
		);
		}
		if (in_array('cwo_zone_id', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_zone_id'),
			'value' => 'zone_id',
		);
		}
		if (in_array('cwo_region_state', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state'),
			'value' => 'region_state',
		);
		}
		if (in_array('cwo_region_state_code', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state_code'),
			'value' => 'region_state_code',
		);
		}
		if (in_array('cwo_newsletter', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_newsletter'),
			'value' => 'newsletter',
		);
		}
		if (in_array('cwo_approved', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_approved'),
			'value' => 'approved',
		);
		}
		if (in_array('cwo_safe', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_safe'),
			'value' => 'safe',
		);
		}
		if (in_array('cwo_ip', $advco_settings_cwo_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_ip'),
			'value' => 'ip',
		);
		}
		if (!$advco_settings_cwo_columns or (in_array('cwo_total_logins', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_logins'),
			'value' => 'total_logins',
		);
		}	
		if (!$advco_settings_cwo_columns or (in_array('cwo_last_login', $advco_settings_cwo_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_login'),
			'value' => 'last_login',
		);
		}		
		} else {
		if (!$advco_settings_mv_columns or (in_array('mv_id', $advco_settings_mv_columns))) {
		$data['sort'][] = array(
			'text'  => (($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_with_orders' or $filter_report == 'registered_customers_without_orders') ? $this->language->get('column_id') : $this->language->get('column_id_guest')),
			'value' => 'id',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_customer', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer'),
			'value' => 'customer',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_email', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_email'),
			'value' => 'email',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_telephone', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_telephone'),
			'value' => 'telephone',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_customer_group', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_group'),
			'value' => 'customer_group',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_customer_status', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_status'),
			'value' => 'customer_status',
		);
		}
		if (in_array('mv_first_name', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_first_name'),
			'value' => 'first_name',
		);
		}
		if (in_array('mv_last_name', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_name'),
			'value' => 'last_name',
		);
		}
		if (in_array('mv_company', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_company'),
			'value' => 'company',
		);
		}
		if (in_array('mv_address_1', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_1'),
			'value' => 'address_1',
		);
		}
		if (in_array('mv_address_2', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_address_2'),
			'value' => 'address_2',
		);
		}
		if (in_array('mv_city', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_city'),
			'value' => 'city',
		);
		}
		if (in_array('mv_postcode', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_postcode'),
			'value' => 'postcode',
		);
		}
		if (in_array('mv_country_id', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_id'),
			'value' => 'country_id',
		);
		}
		if (in_array('mv_country', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country'),
			'value' => 'country',
		);
		}
		if (in_array('mv_country_code', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_country_code'),
			'value' => 'country_code',
		);
		}
		if (in_array('mv_zone_id', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_zone_id'),
			'value' => 'zone_id',
		);
		}
		if (in_array('mv_region_state', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state'),
			'value' => 'region_state',
		);
		}
		if (in_array('mv_region_state_code', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_region_state_code'),
			'value' => 'region_state_code',
		);
		}
		if (in_array('mv_newsletter', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_newsletter'),
			'value' => 'newsletter',
		);
		}
		if (in_array('mv_approved', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_approved'),
			'value' => 'approved',
		);
		}
		if (in_array('mv_safe', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_safe'),
			'value' => 'safe',
		);
		}
		if (in_array('mv_ip', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_ip'),
			'value' => 'ip',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_mostrecent', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_mostrecent'),
			'value' => 'mostrecent',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_total_logins', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total_logins'),
			'value' => 'total_logins',
		);
		}	
		if (!$advco_settings_mv_columns or (in_array('mv_last_login', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_last_login'),
			'value' => 'last_login',
		);
		}		
		if (!$advco_settings_mv_columns or (in_array('mv_orders', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_orders'),
			'value' => 'orders',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_products', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_products'),
			'value' => 'products',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_total', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_total'),
			'value' => 'total',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_aov', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_aov'),
			'value' => 'aov',
		);
		}
		if (!$advco_settings_mv_columns or (in_array('mv_refunds', $advco_settings_mv_columns))) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_refunds'),
			'value' => 'refunds',
		);
		}
		if (in_array('mv_reward_points', $advco_settings_mv_columns)) {		
		$data['sort'][] = array(
			'text'  => $this->language->get('column_customer_reward_points'),
			'value' => 'reward_points',
		);
		}
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$this->session->data['filter_sort'] = $filter_sort = $this->request->get['filter_sort'];		
		} else {
			if ($filter_report == 'registered_customers_without_orders') {
				$this->session->data['filter_sort'] = $filter_sort = 'id';
			} elseif ($filter_report == 'customers_shopping_carts') {
				$this->session->data['filter_sort'] = $filter_sort = 'cart_quantity';		
			} elseif ($filter_report == 'customers_wishlists') {
				$this->session->data['filter_sort'] = $filter_sort = 'wishlist_quantity';					
			} else {
				$this->session->data['filter_sort'] = $filter_sort = 'orders';
			}
		}	

		$data['order'] = array();

		$data['order'][] = array(
			'text'  => $this->language->get('text_asc'),
			'value' => 'asc',
		);
		$data['order'][] = array(
			'text'  => $this->language->get('text_desc'),
			'value' => 'desc',
		);
		
		if (isset($this->request->get['filter_order'])) {
			$this->session->data['filter_order'] = $filter_order = $this->request->get['filter_order'];
		} else {
			if ($filter_report == 'registered_customers_without_orders') {
				$this->session->data['filter_order'] = $filter_order = 'asc';				
			} else {
				$this->session->data['filter_order'] = $filter_order = 'desc';
			}
		}

		$data['limit'] = array();

		$data['limit'][] = array(
			'text'  	=> '10',
			'value' 	=> '10',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '25',
			'value' 	=> '25',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '50',
			'value' 	=> '50',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '100',
			'value' 	=> '100',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '500',
			'value' 	=> '500',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> '1000',
			'value' 	=> '1000',
			'subtext'	=> '',
		);			
		$data['limit'][] = array(
			'text'  	=> $this->language->get('text_all'),
			'value' 	=> '99999',
			'subtext'	=> '',
		);
		$data['limit'][] = array(
			'text'  	=> $this->language->get('text_all'),
			'value' 	=> '999999',
			'subtext'	=> $this->language->get('text_for_export'),
		);		
		
		if (isset($this->request->get['filter_limit'])) {
			$this->session->data['filter_limit'] = $filter_limit = $this->request->get['filter_limit'];
		} else {
			$this->session->data['filter_limit'] = $filter_limit = 25;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['filter_status_date_start'])) {
			$this->session->data['filter_status_date_start'] = $filter_status_date_start = $this->request->get['filter_status_date_start'];			
		} else {
			$this->session->data['filter_status_date_start'] = $filter_status_date_start = '';
		}

		if (isset($this->request->get['filter_status_date_end'])) {
			$this->session->data['filter_status_date_end'] = $filter_status_date_end = $this->request->get['filter_status_date_end'];		
		} else {
			$this->session->data['filter_status_date_end'] = $filter_status_date_end = '';
		}

		$data['order_statuses'] = $this->model_report_adv_customers->getOrderStatuses(); 	
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = explode(',', $this->request->get['filter_order_status_id']);
			$this->session->data['filter_order_status_id'] = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = array();
			$this->session->data['filter_order_status_id'] = '';
		}

		if (isset($this->request->get['filter_order_id_from'])) {
			if (is_numeric(trim($this->request->get['filter_order_id_from']))) {
				$this->session->data['filter_order_id_from'] = $filter_order_id_from = trim($this->request->get['filter_order_id_from']);
			} else {
				$this->session->data['filter_order_id_from'] = $filter_order_id_from = '';
			}
		} else {
			$this->session->data['filter_order_id_from'] = $filter_order_id_from = '';
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			if (is_numeric(trim($this->request->get['filter_order_id_to']))) {
				$this->session->data['filter_order_id_to'] = $filter_order_id_to = trim($this->request->get['filter_order_id_to']);
			} else {
				$this->session->data['filter_order_id_to'] = $filter_order_id_to = '';
			}
		} else {
			$this->session->data['filter_order_id_to'] = $filter_order_id_to = '';
		}

		if (isset($this->request->get['filter_order_value_min'])) {
			if (is_numeric(trim($this->request->get['filter_order_value_min']))) {
				$this->session->data['filter_order_value_min'] = $filter_order_value_min = trim($this->request->get['filter_order_value_min']);
			} else {
				$this->session->data['filter_order_value_min'] = $filter_order_value_min = '';
			}
		} else {
			$this->session->data['filter_order_value_min'] = $filter_order_value_min = '';
		}
		
		if (isset($this->request->get['filter_order_value_max'])) {
			if (is_numeric(trim($this->request->get['filter_order_value_max']))) {
				$this->session->data['filter_order_value_max'] = $filter_order_value_max = trim($this->request->get['filter_order_value_max']);
			} else {
				$this->session->data['filter_order_value_max'] = $filter_order_value_max = '';
			}
		} else {
			$this->session->data['filter_order_value_max'] = $filter_order_value_max = '';
		}
		
		$data['stores'] = $this->model_report_adv_customers->getOrderStores();
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = explode(',', $this->request->get['filter_store_id']);
			$this->session->data['filter_store_id'] = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = array();
			$this->session->data['filter_store_id'] = '';
		}

		$data['currencies'] = $this->model_report_adv_customers->getOrderCurrencies();
		if (isset($this->request->get['filter_currency'])) {
			$filter_currency = explode(',', $this->request->get['filter_currency']);
			$this->session->data['filter_currency'] = $this->request->get['filter_currency'];
		} else {
			$filter_currency = array();
			$this->session->data['filter_currency'] = '';
		}

		$data['taxes'] = $this->model_report_adv_customers->getOrderTaxes();	
		if (isset($this->request->get['filter_taxes'])) {
			$filter_taxes = explode(',', $this->request->get['filter_taxes']);
			$this->session->data['filter_taxes'] = $this->request->get['filter_taxes'];
		} else {
			$filter_taxes = array();
			$this->session->data['filter_taxes'] = '';
		}

		$data['tax_classes'] = $this->model_report_adv_customers->getOrderTaxClasses();
		if (isset($this->request->get['filter_tax_classes'])) {
			$filter_tax_classes = explode(',', $this->request->get['filter_tax_classes']);
			$this->session->data['filter_tax_classes'] = $this->request->get['filter_tax_classes'];
		} else {
			$filter_tax_classes = array();
			$this->session->data['filter_tax_classes'] = '';
		}

		$data['geo_zones'] = $this->model_report_adv_customers->getOrderGeoZones();
		if (isset($this->request->get['filter_geo_zones'])) {
			$filter_geo_zones = explode(',', $this->request->get['filter_geo_zones']);
			$this->session->data['filter_geo_zones'] = $this->request->get['filter_geo_zones'];
		} else {
			$filter_geo_zones = array();
			$this->session->data['filter_geo_zones'] = '';
		}

		$data['customer_groups'] = $this->model_report_adv_customers->getOrderCustomerGroups();	
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = explode(',', $this->request->get['filter_customer_group_id']);
			$this->session->data['filter_customer_group_id'] = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = array();
			$this->session->data['filter_customer_group_id'] = '';
		}

		$data['customer_statuses'] = $this->model_report_adv_customers->getOrderCustomerStatuses();	
		if (isset($this->request->get['filter_customer_status'])) {
			$filter_customer_status = explode(',', $this->request->get['filter_customer_status']);
			$this->session->data['filter_customer_status'] = $this->request->get['filter_customer_status'];
		} else {
			$filter_customer_status = array();
			$this->session->data['filter_customer_status'] = '';
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$this->session->data['filter_customer_name'] = $filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$this->session->data['filter_customer_name'] = $filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$this->session->data['filter_customer_email'] = $filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$this->session->data['filter_customer_email'] = $filter_customer_email = '';
		}

		if (isset($this->request->get['filter_customer_telephone'])) {
			$this->session->data['filter_customer_telephone'] = $filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$this->session->data['filter_customer_telephone'] = $filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$this->session->data['filter_ip'] = $filter_ip = $this->request->get['filter_ip'];
		} else {
			$this->session->data['filter_ip'] = $filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$this->session->data['filter_payment_company'] = $filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$this->session->data['filter_payment_company'] = $filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$this->session->data['filter_payment_address'] = $filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$this->session->data['filter_payment_address'] = $filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$this->session->data['filter_payment_city'] = $filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$this->session->data['filter_payment_city'] = $filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$this->session->data['filter_payment_zone'] = $filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$this->session->data['filter_payment_zone'] = $filter_payment_zone = '';
		}

		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$this->session->data['filter_payment_postcode'] = $filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$this->session->data['filter_payment_postcode'] = $filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$this->session->data['filter_payment_country'] = $filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$this->session->data['filter_payment_country'] = $filter_payment_country = '';
		}

		$data['payment_methods'] = $this->model_report_adv_customers->getOrderPaymentMethods();	
		if (isset($this->request->get['filter_payment_method'])) {
			$filter_payment_method = explode(',', $this->request->get['filter_payment_method']);
			$this->session->data['filter_payment_method'] = $this->request->get['filter_payment_method'];
		} else {
			$filter_payment_method = array();
			$this->session->data['filter_payment_method'] = '';
		}
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$this->session->data['filter_shipping_company'] = $filter_shipping_company = $this->request->get['filter_shipping_company'];
		} else {
			$this->session->data['filter_shipping_company'] = $filter_shipping_company = '';
		}
		
		if (isset($this->request->get['filter_shipping_address'])) {
			$this->session->data['filter_shipping_address'] = $filter_shipping_address = $this->request->get['filter_shipping_address'];
		} else {
			$this->session->data['filter_shipping_address'] = $filter_shipping_address = '';
		}

		if (isset($this->request->get['filter_shipping_city'])) {
			$this->session->data['filter_shipping_city'] = $filter_shipping_city = $this->request->get['filter_shipping_city'];
		} else {
			$this->session->data['filter_shipping_city'] = $filter_shipping_city = '';
		}
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$this->session->data['filter_shipping_zone'] = $filter_shipping_zone = $this->request->get['filter_shipping_zone'];
		} else {
			$this->session->data['filter_shipping_zone'] = $filter_shipping_zone = '';
		}
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$this->session->data['filter_shipping_postcode'] = $filter_shipping_postcode = $this->request->get['filter_shipping_postcode'];
		} else {
			$this->session->data['filter_shipping_postcode'] = $filter_shipping_postcode = '';
		}

		if (isset($this->request->get['filter_shipping_country'])) {
			$this->session->data['filter_shipping_country'] = $filter_shipping_country = $this->request->get['filter_shipping_country'];
		} else {
			$this->session->data['filter_shipping_country'] = $filter_shipping_country = '';
		}

		$data['shipping_methods'] = $this->model_report_adv_customers->getOrderShippingMethods();	
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = explode(',', $this->request->get['filter_shipping_method']);
			$this->session->data['filter_shipping_method'] = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = array();
			$this->session->data['filter_shipping_method'] = '';
		}

		$data['categories'] = $this->model_report_adv_customers->getProductsCategories(0);
		if (isset($this->request->get['filter_category'])) {
			$filter_category = explode(',', $this->request->get['filter_category']);
			$this->session->data['filter_category'] = $this->request->get['filter_category'];
		} else {
			$filter_category = array();
			$this->session->data['filter_category'] = '';
		}
		
		$data['manufacturers'] = $this->model_report_adv_customers->getProductsManufacturers(); 
		if (isset($this->request->get['filter_manufacturer'])) {
			$filter_manufacturer = explode(',', $this->request->get['filter_manufacturer']);
			$this->session->data['filter_manufacturer'] = $this->request->get['filter_manufacturer'];
		} else {
			$filter_manufacturer = array();
			$this->session->data['filter_manufacturer'] = '';
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$this->session->data['filter_sku'] = $filter_sku = $this->request->get['filter_sku'];
		} else {
			$this->session->data['filter_sku'] = $filter_sku = '';
		}

		if (isset($this->request->get['filter_product_name'])) {
			$this->session->data['filter_product_name'] = $filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$this->session->data['filter_product_name'] = $filter_product_name = '';
		}
		
		if (isset($this->request->get['filter_model'])) {
			$this->session->data['filter_model'] = $filter_model = $this->request->get['filter_model'];
		} else {
			$this->session->data['filter_model'] = $filter_model = '';
		}

		$data['order_options'] = $this->model_report_adv_customers->getProductOptions();
		if (isset($this->request->get['filter_option'])) {
			$filter_option = explode(',', $this->request->get['filter_option']);
			$this->session->data['filter_option'] = $this->request->get['filter_option'];
		} else {
			$filter_option = array();
			$this->session->data['filter_option'] = '';
		}

		$data['attributes'] = $this->model_report_adv_customers->getProductAttributes();
		if (isset($this->request->get['filter_attribute'])) {
			$filter_attribute = explode(',', $this->request->get['filter_attribute']);
			$this->session->data['filter_attribute'] = $this->request->get['filter_attribute'];
		} else {
			$filter_attribute = array();
			$this->session->data['filter_attribute'] = '';
		}

		$data['locations'] = $this->model_report_adv_customers->getProductLocations();
		if (isset($this->request->get['filter_location'])) {
			$filter_location = explode(',', $this->request->get['filter_location']);
			$this->session->data['filter_location'] = $this->request->get['filter_location'];
		} else {
			$filter_location = array();
			$this->session->data['filter_location'] = '';
		}

		$data['affiliate_names'] = $this->model_report_adv_customers->getOrderAffiliates();
		if (isset($this->request->get['filter_affiliate_name'])) {
			$filter_affiliate_name = explode(',', $this->request->get['filter_affiliate_name']);
			$this->session->data['filter_affiliate_name'] = $this->request->get['filter_affiliate_name'];
		} else {
			$filter_affiliate_name = array();
			$this->session->data['filter_affiliate_name'] = '';
		}

		$data['affiliate_emails'] = $this->model_report_adv_customers->getOrderAffiliates();
		if (isset($this->request->get['filter_affiliate_email'])) {
			$filter_affiliate_email = explode(',', $this->request->get['filter_affiliate_email']);
			$this->session->data['filter_affiliate_email'] = $this->request->get['filter_affiliate_email'];
		} else {
			$filter_affiliate_email = array();
			$this->session->data['filter_affiliate_email'] = '';
		}

		$data['coupon_names'] = $this->model_report_adv_customers->getOrderCouponNames();
		if (isset($this->request->get['filter_coupon_name'])) {
			$filter_coupon_name = explode(',', $this->request->get['filter_coupon_name']);
			$this->session->data['filter_coupon_name'] = $this->request->get['filter_coupon_name'];
		} else {
			$filter_coupon_name = array();
			$this->session->data['filter_coupon_name'] = '';
		}

		if (isset($this->request->get['filter_coupon_code'])) {
			$this->session->data['filter_coupon_code'] = $filter_coupon_code = $this->request->get['filter_coupon_code'];
		} else {
			$this->session->data['filter_coupon_code'] = $filter_coupon_code = '';
		}

		if (isset($this->request->get['filter_voucher_code'])) {
			$this->session->data['filter_voucher_code'] = $filter_voucher_code = $this->request->get['filter_voucher_code'];
		} else {
			$this->session->data['filter_voucher_code'] = $filter_voucher_code = '';
		}

		if (isset($_GET['cron'])) {
			$cron_settings = unserialize($this->config->get('advco' . $this->user->getId() . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$filter_report = $cron_setting['cron_filter_report'];
					$filter_details = $cron_setting['cron_filter_details'];
					$filter_group = $cron_setting['cron_filter_group'];
					$filter_sort = $cron_setting['cron_filter_sort'];
					$filter_order = $cron_setting['cron_filter_order'];
					$filter_limit = $cron_setting['cron_filter_limit'];
					
					$filter_range = $cron_setting['cron_filter_range'];
					$filter_date_start = $cron_setting['cron_date_start'];
					$filter_date_end = $cron_setting['cron_date_end'];
					$filter_status_date_start = $cron_setting['cron_status_date_start'];
					$filter_status_date_end = $cron_setting['cron_status_date_end'];
					$filter_order_status_id = $cron_setting['cron_filter_order_status_id'] != '' ? explode(',', $cron_setting['cron_filter_order_status_id']) : '';
					$filter_order_id_from = $cron_setting['cron_filter_order_id_from'];
					$filter_order_id_to = $cron_setting['cron_filter_order_id_to'];
					$filter_order_value_min = $cron_setting['cron_filter_order_value_min'];
					$filter_order_value_max = $cron_setting['cron_filter_order_value_max'];	
					$filter_store_id = $cron_setting['cron_filter_store_id'] != '' ? explode(',', $cron_setting['cron_filter_store_id']) : '';
					$filter_currency = $cron_setting['cron_filter_currency'] != '' ? explode(',', $cron_setting['cron_filter_currency']) : '';
					$filter_taxes = $cron_setting['cron_filter_taxes'] != '' ? explode(',', $cron_setting['cron_filter_taxes']) : '';	
					$filter_tax_classes = $cron_setting['cron_filter_tax_classes'] != '' ? explode(',', $cron_setting['cron_filter_tax_classes']) : '';	
					$filter_geo_zones = $cron_setting['cron_filter_geo_zones'] != '' ? explode(',', $cron_setting['cron_filter_geo_zones']) : '';	
					$filter_customer_group_id = $cron_setting['cron_filter_customer_group_id'] != '' ? explode(',', $cron_setting['cron_filter_customer_group_id']) : '';
					$filter_customer_status = $cron_setting['cron_filter_customer_status'] != '' ? explode(',', $cron_setting['cron_filter_customer_status']) : '';	
					$filter_customer_name = $cron_setting['cron_filter_customer_name'];	
					$filter_customer_email = $cron_setting['cron_filter_customer_email'];	
					$filter_customer_telephone = $cron_setting['cron_filter_customer_telephone'];	
					$filter_ip = $cron_setting['cron_filter_ip'];	
					$filter_payment_company = $cron_setting['cron_filter_payment_company'];
					$filter_payment_address = $cron_setting['cron_filter_payment_address'];
					$filter_payment_city = $cron_setting['cron_filter_payment_city'];
					$filter_payment_zone = $cron_setting['cron_filter_payment_zone'];
					$filter_payment_postcode = $cron_setting['cron_filter_payment_postcode'];
					$filter_payment_country = $cron_setting['cron_filter_payment_country'];
					$filter_payment_method = $cron_setting['cron_filter_payment_method'] != '' ? explode(',', $cron_setting['cron_filter_payment_method']) : '';	
					$filter_shipping_company = $cron_setting['cron_filter_shipping_company'];
					$filter_shipping_address = $cron_setting['cron_filter_shipping_address'];
					$filter_shipping_city = $cron_setting['cron_filter_shipping_city'];	
					$filter_shipping_zone = $cron_setting['cron_filter_shipping_zone'];
					$filter_shipping_postcode = $cron_setting['cron_filter_shipping_postcode'];
					$filter_shipping_country = $cron_setting['cron_filter_shipping_country'];
					$filter_shipping_method = $cron_setting['cron_filter_shipping_method'] != '' ? explode(',', $cron_setting['cron_filter_shipping_method']) : '';	
					$filter_category = $cron_setting['cron_filter_category'] != '' ? explode(',', $cron_setting['cron_filter_category']) : '';	
					$filter_manufacturer = $cron_setting['cron_filter_manufacturer'] != '' ? explode(',', $cron_setting['cron_filter_manufacturer']) : '';	
					$filter_sku = $cron_setting['cron_filter_sku'];		
					$filter_product_name = $cron_setting['cron_filter_product_name'];
					$filter_model = $cron_setting['cron_filter_model'];
					$filter_option = $cron_setting['cron_filter_option'] != '' ? explode(',', $cron_setting['cron_filter_option']) : '';	
					$filter_attribute = $cron_setting['cron_filter_attribute'] != '' ? explode(',', $cron_setting['cron_filter_attribute']) : '';
					$filter_location = $cron_setting['cron_filter_location'] != '' ? explode(',', $cron_setting['cron_filter_location']) : '';	
					$filter_affiliate_name = $cron_setting['cron_filter_affiliate_name'] != '' ? explode(',', $cron_setting['cron_filter_affiliate_name']) : '';	
					$filter_affiliate_email = $cron_setting['cron_filter_affiliate_email'] != '' ? explode(',', $cron_setting['cron_filter_affiliate_email']) : '';	
					$filter_coupon_name = $cron_setting['cron_filter_coupon_name'] != '' ? explode(',', $cron_setting['cron_filter_coupon_name']) : '';
					$filter_coupon_code = $cron_setting['cron_filter_coupon_code'];
					$filter_voucher_code = $cron_setting['cron_filter_voucher_code'];			
				}
			}
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_range'])) {
			$url .= '&filter_range=' . $this->request->get['filter_range'];
		}

		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}
		
		if (isset($this->request->get['filter_details'])) {
			$url .= '&filter_details=' . $this->request->get['filter_details'];
		}
		
		if (isset($this->request->get['filter_limit'])) {
			$url .= '&filter_limit=' . $this->request->get['filter_limit'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_status_date_start'])) {
			$url .= '&filter_status_date_start=' . $this->request->get['filter_status_date_start'];
		}
		
		if (isset($this->request->get['filter_status_date_end'])) {
			$url .= '&filter_status_date_end=' . $this->request->get['filter_status_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_order_id_from'])) {
			$url .= '&filter_order_id_from=' . $this->request->get['filter_order_id_from'];
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			$url .= '&filter_order_id_to=' . $this->request->get['filter_order_id_to'];
		}

		if (isset($this->request->get['filter_order_value_min'])) {
			$url .= '&filter_order_value_min=' . $this->request->get['filter_order_value_min'];
		}
		
		if (isset($this->request->get['filter_order_value_max'])) {
			$url .= '&filter_order_value_max=' . $this->request->get['filter_order_value_max'];
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_currency'])) {
			$url .= '&filter_currency=' . $this->request->get['filter_currency'];
		}
		
		if (isset($this->request->get['filter_taxes'])) {
			$url .= '&filter_taxes=' . $this->request->get['filter_taxes'];
		}
		
		if (isset($this->request->get['filter_tax_classes'])) {
			$url .= '&filter_tax_classes=' . $this->request->get['filter_tax_classes'];
		}
		
		if (isset($this->request->get['filter_geo_zones'])) {
			$url .= '&filter_geo_zones=' . $this->request->get['filter_geo_zones'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_status'])) {
			$url .= '&filter_customer_status=' . $this->request->get['filter_customer_status'];
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}		

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode(html_entity_decode($this->request->get['filter_customer_email'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_customer_telephone'])) {
			$url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_company'])) {
			$url .= '&filter_payment_company=' . urlencode(html_entity_decode($this->request->get['filter_payment_company'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_address'])) {
			$url .= '&filter_payment_address=' . urlencode(html_entity_decode($this->request->get['filter_payment_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_city'])) {
			$url .= '&filter_payment_city=' . urlencode(html_entity_decode($this->request->get['filter_payment_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$url .= '&filter_payment_zone=' . urlencode(html_entity_decode($this->request->get['filter_payment_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$url .= '&filter_payment_postcode=' . urlencode(html_entity_decode($this->request->get['filter_payment_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_country'])) {
			$url .= '&filter_payment_country=' . urlencode(html_entity_decode($this->request->get['filter_payment_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}	
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$url .= '&filter_shipping_company=' . urlencode(html_entity_decode($this->request->get['filter_shipping_company'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_shipping_address'])) {
			$url .= '&filter_shipping_address=' . urlencode(html_entity_decode($this->request->get['filter_shipping_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_city'])) {
			$url .= '&filter_shipping_city=' . urlencode(html_entity_decode($this->request->get['filter_shipping_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$url .= '&filter_shipping_zone=' . urlencode(html_entity_decode($this->request->get['filter_shipping_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$url .= '&filter_shipping_postcode=' . urlencode(html_entity_decode($this->request->get['filter_shipping_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_country'])) {
			$url .= '&filter_shipping_country=' . urlencode(html_entity_decode($this->request->get['filter_shipping_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}	
		
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}	
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}	
		
		if (isset($this->request->get['filter_attribute'])) {
			$url .= '&filter_attribute=' . $this->request->get['filter_attribute'];
		}			

		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . $this->request->get['filter_location'];
		}	
		
		if (isset($this->request->get['filter_affiliate_name'])) {
			$url .= '&filter_affiliate_name=' . $this->request->get['filter_affiliate_name'];
		}	
		
		if (isset($this->request->get['filter_affiliate_email'])) {
			$url .= '&filter_affiliate_email=' . $this->request->get['filter_affiliate_email'];
		}	
		
		if (isset($this->request->get['filter_coupon_name'])) {
			$url .= '&filter_coupon_name=' . $this->request->get['filter_coupon_name'];
		}	
		
		if (isset($this->request->get['filter_coupon_code'])) {
			$url .= '&filter_coupon_code=' . urlencode(html_entity_decode($this->request->get['filter_coupon_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_voucher_code'])) {
			$url .= '&filter_voucher_code=' . urlencode(html_entity_decode($this->request->get['filter_voucher_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/adv_customers', 'token=' . $this->session->data['token'], true)
   		);

		if (!file_exists(DIR_APPLICATION . 'model/module/adv_settings.php')) {
			$data['module_page'] = $this->response->redirect($this->url->link('extension/module/adv_reports_customers', 'token=' . $this->session->data['token'], true));
		}
		
		if ($this->config->get('advco' . $this->user->getId() . '_date_format')) {
			$data['advco_date_format'] = $this->config->get('advco' . $this->user->getId() . '_date_format');
		} else {
			$data['advco_date_format'] = 'DDMMYYYY';
		}

		if ($this->config->get('advco' . $this->user->getId() . '_hour_format')) {
			$data['advco_hour_format'] = $this->config->get('advco' . $this->user->getId() . '_hour_format');
		} else {
			$data['advco_hour_format'] = '24';
		}
		
		if ($this->config->get('advco' . $this->user->getId() . '_week_days')) {
			$data['advco_week_days'] = $this->config->get('advco' . $this->user->getId() . '_week_days');
		} else {
			$data['advco_week_days'] = 'mon_sun';
		}

		$selected_load_save_reports = unserialize($this->config->get('advco' . $this->user->getId() . 'sr_load_save_report'));
		
		if (isset($this->request->post['advco' . $this->user->getId() . 'sr_load_save_report'])) {
			$data['advco_load_save_reports'] = $this->request->post['advco_load_save_report'];
		} elseif (isset($selected_load_save_reports)) {
			$data['advco_load_save_reports'] = $selected_load_save_reports;
		} else { 	
			$data['advco_load_save_reports'] = array();
		}

		$selected_cron_settings = unserialize($this->config->get('advco' . $this->user->getId() . 'cron_setting'));
		
		if (isset($this->request->post['advco' . $this->user->getId() . 'cron_setting'])) {
			$data['advco_cron_settings'] = $this->request->post['advco_cron_setting'];
		} elseif (isset($selected_cron_settings)) {
			$data['advco_cron_settings'] = $selected_cron_settings;
		} else { 	
			$data['advco_cron_settings'] = array();
		}
		
		$this->load->model('localisation/return_action');
		$data['return_actions'] = $this->model_localisation_return_action->getReturnActions();
		
		$data['auth'] = FALSE;
		$data['ldata'] = FALSE;
		$data['customers'] = array();
		
		$filter_data = array(
			'filter_date_start'	     		=> $filter_date_start, 
			'filter_date_end'	     		=> $filter_date_end,
			'filter_range'           		=> $filter_range,
			'filter_report'           		=> $filter_report,
			'filter_group'           		=> $filter_group,
			'filter_status_date_start'		=> $filter_status_date_start, 
			'filter_status_date_end'		=> $filter_status_date_end, 			
			'filter_order_status_id'		=> $filter_order_status_id,
			'filter_order_id_from'			=> $filter_order_id_from,
			'filter_order_id_to'			=> $filter_order_id_to,	
			'filter_order_value_min'		=> $filter_order_value_min,
			'filter_order_value_max'		=> $filter_order_value_max,				
			'filter_store_id'				=> $filter_store_id,
			'filter_currency'				=> $filter_currency,
			'filter_taxes'					=> $filter_taxes,
			'filter_tax_classes'			=> $filter_tax_classes,
			'filter_geo_zones'				=> $filter_geo_zones,			
			'filter_customer_group_id'		=> $filter_customer_group_id,
			'filter_customer_status'   		=> $filter_customer_status,
			'filter_customer_name'	 	 	=> $filter_customer_name,			
			'filter_customer_email'			=> $filter_customer_email,
			'filter_customer_telephone'		=> $filter_customer_telephone,
			'filter_ip' 	 				=> $filter_ip,			
			'filter_payment_company'		=> $filter_payment_company,
			'filter_payment_address'		=> $filter_payment_address,
			'filter_payment_city'			=> $filter_payment_city,
			'filter_payment_zone'			=> $filter_payment_zone,			
			'filter_payment_postcode'		=> $filter_payment_postcode,
			'filter_payment_country'		=> $filter_payment_country,
			'filter_payment_method'  		=> $filter_payment_method,
			'filter_shipping_company'		=> $filter_shipping_company,
			'filter_shipping_address'		=> $filter_shipping_address,
			'filter_shipping_city'			=> $filter_shipping_city,
			'filter_shipping_zone'			=> $filter_shipping_zone,			
			'filter_shipping_postcode'		=> $filter_shipping_postcode,
			'filter_shipping_country'		=> $filter_shipping_country,
			'filter_shipping_method'  		=> $filter_shipping_method,
			'filter_category'				=> $filter_category,
			'filter_manufacturer'			=> $filter_manufacturer,
			'filter_sku' 	 				=> $filter_sku,
			'filter_product_name'			=> $filter_product_name,
			'filter_model' 	 				=> $filter_model,
			'filter_option'  				=> $filter_option,
			'filter_attribute' 	 		 	=> $filter_attribute,
			'filter_location'  				=> $filter_location,
			'filter_affiliate_name'			=> $filter_affiliate_name,
			'filter_affiliate_email'		=> $filter_affiliate_email,
			'filter_coupon_name'			=> $filter_coupon_name,
			'filter_coupon_code'			=> $filter_coupon_code,
			'filter_voucher_code'			=> $filter_voucher_code,			
			'filter_sort'  					=> $filter_sort,
			'filter_order'  				=> $filter_order,
			'filter_details'  				=> $filter_details,
			'filter_limit'  				=> $filter_limit,			
			'start'                  		=> ($page - 1) * $filter_limit
		);

		$results = $this->model_report_adv_customers->getCustomers($filter_data);
		$totals = $filter_limit != '999999' ? $this->model_report_adv_customers->getCustomersTotal($filter_data) : FALSE;		

		if ($filter_limit != '999999') {
		if ($filter_details == 'all_details_products' or $filter_details == 'all_details_orders') {
		$counter = 0;
		foreach ($totals as $total) {
			$counter += count($total['counter']);
		}
		$total = $counter;
		} else {
		$total = $totals;
		}
		} else {
		$total = '999999';	
		}
		
		foreach ($results as $result) {

		if ($filter_details != 'all_details_products' && $filter_details != 'all_details_orders') {

			// Custom Fields
			$this->load->model('customer/custom_field');

			$account_custom_fields = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC',
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
			$data['custom_fields'] = $this->model_report_adv_customers->getCustomFieldsNames($filter_data);			
			$order_custom_field = json_decode($result['custom_field'], true);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_custom_field[$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_custom_field[$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$account_custom_fields[] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_custom_field[$custom_field['custom_field_id']])) {
						foreach ($order_custom_field[$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$account_custom_fields[] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$account_custom_fields[] = array(
							'name'  => $custom_field['name'],
							'value' => $order_custom_field[$custom_field['custom_field_id']]
						);
					}
				}
			}
			
			if ($filter_report != 'registered_customers_without_orders' && $filter_report != 'customers_shopping_carts' && $filter_report != 'customers_wishlists') {
				
			$orders_total = 0;
			$products_total = 0;
			$total_total = 0;
    		$refunds_total = 0;
			$reward_points_total = 0;
	
			foreach ($results as $totals) {
    			$orders_total += $totals['orders'];
				$products_total += $totals['products'];
				$total_total += $totals['total'];
				$refunds_total += $totals['refunds'];
				$reward_points_total += $totals['reward_points'];
			}
			}
			
		  if ($filter_report == 'all_registered_customers_with_without_orders') {
			
			$customers[] = array(
				'date_added'   						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),	
				'customer_id'   	  				=> $result['id'],
				'cust_name'    						=> $result['name'],
				'cust_company'   					=> $result['company'],
				'cust_email'   						=> $result['email'],
				'cust_telephone'   					=> $result['telephone'],			
				'cust_group' 						=> $result['customer_group'],
				'custom_field' 						=> $result['customer_custom_field'],	
				'custom_fields' 					=> $account_custom_fields,				
				'cust_status'         				=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'cust_first_name'   				=> $result['first_name'],
				'cust_last_name'   					=> $result['last_name'],
				'cust_company'   					=> $result['company'],
				'cust_address_1'   					=> $result['address_1'],
				'cust_address_2'   					=> $result['address_2'],
				'cust_city'   						=> $result['city'],
				'cust_postcode'   					=> $result['postcode'],
				'cust_country_id'   				=> $result['country_id'],
				'cust_country'   					=> $result['country'],
				'cust_country_code'   				=> $result['country_code'],
				'cust_zone_id'   					=> $result['zone_id'],
				'cust_region_state'   				=> $result['region_state'],
				'cust_region_state_code'   			=> $result['region_state_code'],
				'newsletter'        	 			=> ($result['newsletter'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'approved'         					=> ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'safe'         						=> ($result['safe'] ? $this->language->get('text_yes') : $this->language->get('text_no')),				
				'ip'   								=> $result['ip'],
				'total_logins'   					=> ($result['total_logins'] ? $result['total_logins'] : ''),
				'last_login'   						=> ($result['last_login'] ? date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['last_login'])) : ''),	
				'mostrecent'						=> ($result['mostrecent'] ? date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['mostrecent'])) : ''),	
				'orders'      						=> $result['orders'] != '' ? $result['orders'] : '0',
				'products'      					=> $result['products'] != '' ? $result['products'] : '0',				
				'total'      						=> $this->currency->format($result['total'] != '' ? $result['total'] : '0', $this->config->get('config_currency')),
				'total_raw'      					=> ($result['total'] != '' ? $result['total'] : '0.00'),
				'aov'      							=> $this->currency->format(($result['orders'] ? $result['total'] / $result['orders'] : 0), $this->config->get('config_currency')),
				'aov_raw'    			  			=> $result['orders'] ? $result['total'] / $result['orders'] : 0,					
				'refunds'      						=> $this->currency->format($result['refunds'], $this->config->get('config_currency')),
				'refunds_raw'   	   				=> $result['refunds'],
				'reward_points'   	   				=> $result['reward_points'] ? $result['reward_points'] : 0,
				'orders_total'      				=> $orders_total,	
				'products_total'   		   			=> $products_total,
				'total_total'      					=> $this->currency->format($total_total, $this->config->get('config_currency')),
				'aov_total'      					=> $this->currency->format(($total_total ? $total_total / $orders_total : 0), $this->config->get('config_currency')),				
				'refunds_total'    		  			=> $this->currency->format($refunds_total, $this->config->get('config_currency')),
				'reward_points_total'   	   		=> $reward_points_total ? $reward_points_total : 0,
				'href' 								=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true)
			);

		  } else if ($filter_report == 'registered_customers_without_orders') {
			
			$customers[] = array(
				'date_added'   						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),	
				'customer_id'   	  				=> $result['id'],
				'cust_name'    						=> $result['name'],
				'cust_company'   					=> $result['company'],
				'cust_email'   						=> $result['email'],
				'cust_telephone'   					=> $result['telephone'],			
				'cust_group' 						=> $result['customer_group'],
				'custom_field' 						=> $result['customer_custom_field'],	
				'custom_fields' 					=> $account_custom_fields,				
				'cust_status'         				=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'cust_first_name'   				=> $result['first_name'],
				'cust_last_name'   					=> $result['last_name'],
				'cust_company'   					=> $result['company'],
				'cust_address_1'   					=> $result['address_1'],
				'cust_address_2'   					=> $result['address_2'],
				'cust_city'   						=> $result['city'],
				'cust_postcode'   					=> $result['postcode'],
				'cust_country_id'   				=> $result['country_id'],
				'cust_country'   					=> $result['country'],
				'cust_country_code'   				=> $result['country_code'],
				'cust_zone_id'   					=> $result['zone_id'],
				'cust_region_state'   				=> $result['region_state'],
				'cust_region_state_code'   			=> $result['region_state_code'],
				'newsletter'        	 			=> ($result['newsletter'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'approved'         					=> ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'safe'         						=> ($result['safe'] ? $this->language->get('text_yes') : $this->language->get('text_no')),				
				'ip'   								=> $result['ip'],
				'total_logins'   					=> ($result['total_logins'] ? $result['total_logins'] : ''),
				'last_login'   						=> ($result['last_login'] ? date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['last_login'])) : ''),				
				'href' 								=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true)
			);
		  
		  } else if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {

				if ($filter_report == 'customers_shopping_carts') {
					$cart_wishlists_query = $this->db->query("SELECT sc.* FROM " . DB_PREFIX . "cart sc, " . DB_PREFIX . "product p WHERE sc.customer_id = '" . (int)$result['id'] . "' AND sc.product_id = p.product_id");
				} else if ($filter_report == 'customers_wishlists') {
					$cart_wishlists_query = $this->db->query("SELECT cw.* FROM " . DB_PREFIX . "customer_wishlist cw, " . DB_PREFIX . "product p WHERE cw.customer_id = '" . (int)$result['id'] . "' AND cw.product_id = p.product_id");
				}
				
				$product_data = array();
					foreach ($cart_wishlists_query->rows as $products) {
						if ($filter_report == 'customers_shopping_carts') {
							$product_query = $this->db->query("SELECT p.*, sc.*, SUM(sc.quantity) AS cart_quantity, (SELECT pd.name FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = '" . (int)$products['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS name, (SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE p2c.product_id = '" . (int)$products['product_id'] . "' AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0) AS category, (SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE p.product_id = '" . (int)$products['product_id'] . "' AND p.manufacturer_id = m.manufacturer_id) AS manufacturer, (SELECT GROUP_CONCAT(CONCAT(agd.name,' &gt; ',ad.name,' &gt; ',pa.text) ORDER BY agd.name, ad.name, pa.text ASC SEPARATOR '; ') FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.product_id = '" . (int)$products['product_id'] . "' AND pa.product_id = p.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute FROM " . DB_PREFIX . "product p, " . DB_PREFIX . "cart sc WHERE p.product_id = '" . (int)$products['product_id'] . "' AND sc.product_id = p.product_id AND sc.customer_id = '" . (int)$result['id'] . "' AND p.status = '1'");
						} else if ($filter_report == 'customers_wishlists') {
							$product_query = $this->db->query("SELECT p.*, cw.*, COUNT(cw.product_id) AS wishlist_quantity, (SELECT pd.name FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = '" . (int)$products['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS name, (SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE p2c.product_id = '" . (int)$products['product_id'] . "' AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0) AS category, (SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE p.product_id = '" . (int)$products['product_id'] . "' AND p.manufacturer_id = m.manufacturer_id) AS manufacturer, (SELECT GROUP_CONCAT(CONCAT(agd.name,' &gt; ',ad.name,' &gt; ',pa.text) ORDER BY agd.name, ad.name, pa.text ASC SEPARATOR '; ') FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.product_id = '" . (int)$products['product_id'] . "' AND pa.product_id = p.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute FROM " . DB_PREFIX . "product p, " . DB_PREFIX . "customer_wishlist cw WHERE p.product_id = '" . (int)$products['product_id'] . "' AND cw.product_id = p.product_id AND cw.customer_id = '" . (int)$result['id'] . "' AND p.status = '1'");
						}
						
						$price = $product_query->row['price'];	
						
						$this->load->model('catalog/product');
						$product_specials = $this->model_catalog_product->getProductSpecials($products['product_id']);

						foreach ($product_specials  as $product_special) {
							if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
								$price = $product_special['price'];
								break;
							}
						}

						if ($filter_report == 'customers_shopping_carts') {
						// Options
						$option_price = 0;
						$option_data = array();
							foreach (json_decode($product_query->row['option']) as $product_option_id => $value) {
								$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_query->row['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
								if ($option_query->num_rows) {
									if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
										$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
							
										if ($option_value_query->num_rows) {
											if ($option_value_query->row['price_prefix'] == '+') {
												$option_price += $option_value_query->row['price'];
											} elseif ($option_value_query->row['price_prefix'] == '-') {
												$option_price -= $option_value_query->row['price'];
											}
									
											$option_data[] = array(
												'name'                    => $option_query->row['name'],
												'value'            		  => $option_value_query->row['name'],
												'quantity'                => $option_value_query->row['quantity'],										
												'price'                   => $option_value_query->row['price'],
												'price_prefix'            => $option_value_query->row['price_prefix']									
											);
										}
									} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
										foreach ($value as $product_option_value_id) {
											$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
									
											if ($option_value_query->num_rows) {
												if ($option_value_query->row['price_prefix'] == '+') {
													$option_price += $option_value_query->row['price'];
												} elseif ($option_value_query->row['price_prefix'] == '-') {
													$option_price -= $option_value_query->row['price'];
												}
			
												$option_data[] = array(
													'name'                    => $option_query->row['name'],
													'value'            		  => $option_value_query->row['name'],
													'quantity'                => $option_value_query->row['quantity'],											
													'price'                   => $option_value_query->row['price'],
													'price_prefix'            => $option_value_query->row['price_prefix']
												);
											}
										}
									} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							
										$option_data[] = array(
											'name'                    => $option_query->row['name'],
											'value'            		  => $value,
											'quantity'                => '',
											'price'                   => '',
											'price_prefix'            => ''							
										);
									}
								}
							}
					
						}
						
						$product_data[] = array(
							'date_added'   	  		=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($cart_wishlists_query->row['date_added'])),
							'product_id'         	=> $product_query->row['product_id'],
							'sku'         			=> $product_query->row['sku'],
							'name'         			=> $product_query->row['name'],
							'option'     			=> $filter_report == 'customers_shopping_carts' ? $option_data : '',
							'model'         		=> $product_query->row['model'],
							'category'         		=> $product_query->row['category'],
							'manufacturer'         	=> $product_query->row['manufacturer'],
							'attribute'         	=> $product_query->row['attribute'],								
							'price'      			=> $this->currency->format($filter_report == 'customers_shopping_carts' ? ($price+$option_price) : $price, $this->config->get('config_currency')),
							'price_raw'      		=> $filter_report == 'customers_shopping_carts' ? ($price+$option_price) : $price,
							'cart_quantity'         => $filter_report == 'customers_shopping_carts' ? $product_query->row['cart_quantity'] : '',
							'wishlist_quantity'     => $filter_report == 'customers_wishlists' ? $product_query->row['wishlist_quantity'] : '',
							'href' 					=> $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_query->row['product_id'], true)
						);						
					}

			if ($filter_report == 'customers_shopping_carts') {
				$cart_value = 0;
				foreach($product_data as $cart_values) {
					$cart_value += $cart_values['price_raw'];
				}
			} else if ($filter_report == 'customers_wishlists') {
				$wishlist_value = 0;
				foreach($product_data as $wishlist_values) {
					$wishlist_value += $wishlist_values['price_raw'];
				}
			}
						
			$customers[] = array(
				'date'   	  						=> $result['date'],
				'date_start' 						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_start'])),
				'date_end'   						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_end'])),	
				'customer_id'   	  				=> $result['id'],
				'cust_name'    						=> $result['name'],
				'cust_company'   					=> $result['company'],
				'cust_email'   						=> $result['email'],
				'cust_telephone'   					=> $result['telephone'],			
				'cust_group' 						=> $result['customer_group'],
				'custom_field' 						=> $result['customer_custom_field'],	
				'custom_fields' 					=> $account_custom_fields,				
				'cust_status'         				=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'cust_first_name'   				=> $result['first_name'],
				'cust_last_name'   					=> $result['last_name'],
				'cust_company'   					=> $result['company'],
				'cust_address_1'   					=> $result['address_1'],
				'cust_address_2'   					=> $result['address_2'],
				'cust_city'   						=> $result['city'],
				'cust_postcode'   					=> $result['postcode'],
				'cust_country_id'   				=> $result['country_id'],
				'cust_country'   					=> $result['country'],
				'cust_country_code'   				=> $result['country_code'],
				'cust_zone_id'   					=> $result['zone_id'],
				'cust_region_state'   				=> $result['region_state'],
				'cust_region_state_code'   			=> $result['region_state_code'],
				'newsletter'        	 			=> ($result['newsletter'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'approved'         					=> ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'safe'         						=> ($result['safe'] ? $this->language->get('text_yes') : $this->language->get('text_no')),				
				'ip'   								=> $result['ip'],
				'total_logins'   					=> ($result['total_logins'] ? $result['total_logins'] : ''),
				'last_login'   						=> ($result['last_login'] ? date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['last_login'])) : ''),				
				'cart_quantity'     				=> ($filter_report == 'customers_shopping_carts' ? $result['cart_quantity'] : ''),
				'cart_value'     					=> $this->currency->format($filter_report == 'customers_shopping_carts' ? $cart_value : '', $this->config->get('config_currency')),
				'cart_value_raw'     				=> ($filter_report == 'customers_shopping_carts' ? $cart_value : ''),
				'wishlist_quantity'     			=> ($filter_report == 'customers_wishlists' ? $result['wishlist_quantity'] : ''),	
				'wishlist_value'     				=> $this->currency->format($filter_report == 'customers_wishlists' ? $wishlist_value : '', $this->config->get('config_currency')),
				'wishlist_value_raw'     			=> ($filter_report == 'customers_wishlists' ? $wishlist_value : ''),
				'product'     						=> $product_data,	
				'href' 								=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true)
			);
			
		  } else {
			
			$customers[] = array(
				'year'		       					=> $result['year'],
				'quarter'		    	   			=> 'Q' . $result['quarter'],	
				'year_quarter'		    	   		=> 'Q' . $result['quarter']. '<br>' . $result['year'],
				'month'		       					=> $result['month'],
				'year_month'		       			=> $result['month'] . '<br>' . $result['year'],			
				'date_start' 						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_start'])),
				'date_end'   						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_end'])),	
				'order_id'   						=> $result['order_id'],	
				'customer_id'   	  				=> $result['customer_id'],
				'cust_name'    						=> $result['cust_name'],
				'cust_email'   						=> $result['cust_email'],
				'cust_telephone'   					=> $result['cust_telephone'],		
				'cust_group_reg' 					=> $result['cust_group_reg'],
				'cust_group_guest' 					=> $result['cust_group_guest'],
				'custom_field' 						=> $result['customer_custom_field'],	
				'custom_fields' 					=> $account_custom_fields,					
				'cust_status'         				=> ($result['cust_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),	
				'cust_first_name'   				=> $result['customer_id'] != 0 ? $result['first_name'] : $result['guest_firstname'],
				'cust_last_name'   					=> $result['customer_id'] != 0 ? $result['last_name'] : $result['guest_lastname'],
				'cust_company'   					=> $result['customer_id'] != 0 ? $result['company'] : $result['guest_company'],
				'cust_address_1'   					=> $result['customer_id'] != 0 ? $result['address_1'] : $result['guest_address_1'],
				'cust_address_2'   					=> $result['customer_id'] != 0 ? $result['address_2'] : $result['guest_address_2'],
				'cust_city'   						=> $result['customer_id'] != 0 ? $result['city'] : $result['guest_city'],
				'cust_postcode'   					=> $result['customer_id'] != 0 ? $result['postcode'] : $result['guest_postcode'],
				'cust_country_id'   				=> $result['customer_id'] != 0 ? $result['country_id'] : $result['guest_country_id'],
				'cust_country'   					=> $result['customer_id'] != 0 ? $result['country'] : $result['guest_country'],
				'cust_country_code'   				=> $result['customer_id'] != 0 ? $result['country_code'] : $result['guest_country_code'],
				'cust_zone_id'   					=> $result['customer_id'] != 0 ? $result['zone_id'] : $result['guest_zone_id'],
				'cust_region_state'   				=> $result['customer_id'] != 0 ? $result['region_state'] : $result['guest_region_state'],
				'cust_region_state_code'   			=> $result['customer_id'] != 0 ? $result['region_state_code'] : $result['guest_region_state_code'],			
				'newsletter'        	 			=> ($result['customer_id'] != 0 ? ($result['newsletter'] ? $this->language->get('text_yes') : $this->language->get('text_no')) : ''),
				'approved'         					=> ($result['customer_id'] != 0 ? ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')) : ''),
				'safe'         						=> ($result['customer_id'] != 0 ? ($result['safe'] ? $this->language->get('text_yes') : $this->language->get('text_no')) : ''),			
				'ip'   								=> ($result['customer_id'] != 0 ? ($result['ip'] ? $result['ip'] : '') : ''),	
				'total_logins'   					=> ($result['total_logins'] ? ($result['total_logins'] ? $result['total_logins'] : '') : ''),	
				'last_login'   						=> ($result['last_login'] ? date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['last_login'])) : ''),				
				'mostrecent'						=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['mostrecent'])),				
				'orders'  							=> $result['orders'],
				'products'      					=> $result['products'],
				'total'      						=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'total_raw'      					=> $result['total'],
				'aov'      							=> $this->currency->format(($result['orders'] ? $result['total'] / $result['orders'] : 0), $this->config->get('config_currency')),
				'aov_raw'    			  			=> $result['orders'] ? $result['total'] / $result['orders'] : 0,
				'refunds'      						=> $this->currency->format($result['refunds'], $this->config->get('config_currency')),
				'refunds_raw'   	   				=> $result['refunds'],
				'reward_points'   	   				=> $result['reward_points'] ? $result['reward_points'] : 0,
				'gorders'    						=> $result['orders'],
				'gcustomer'    						=> preg_replace('~\(.*?\)~', '', $result['cust_name']),
				'gcompany'    						=> $result['customer_id'] != 0 ? $result['company'] : $result['guest_company'],			
				'gtotal'      						=> round($result['total'], 2),			
				'order_ord_id'     					=> $filter_details == 'basic_details' ? $result['order_ord_id'] : '',
				'order_ord_id_link'   	  			=> $filter_details == 'basic_details' ? $result['order_ord_id_link'] : '',					
				'order_ord_date'    				=> $filter_details == 'basic_details' ? $result['order_ord_date'] : '',
				'order_inv_no'     					=> $filter_details == 'basic_details' ? $result['order_inv_no'] : '',
				'order_name'   						=> $filter_details == 'basic_details' ? $result['order_name'] : '',
				'order_email'   					=> $filter_details == 'basic_details' ? $result['order_email'] : '',
				'order_group'   					=> $filter_details == 'basic_details' ? $result['order_group'] : '',
				'order_shipping_method' 			=> $filter_details == 'basic_details' ? strip_tags($result['order_shipping_method'], '<br>') : '',
				'order_payment_method'  			=> $filter_details == 'basic_details' ? strip_tags($result['order_payment_method'], '<br>') : '',
				'order_status'  					=> $filter_details == 'basic_details' ? $result['order_status'] : '',
				'order_store'      					=> $filter_details == 'basic_details' ? $result['order_store'] : '',	
				'order_currency' 					=> $filter_details == 'basic_details' ? $result['order_currency'] : '',				
				'order_products' 					=> $filter_details == 'basic_details' ? $result['order_products'] : '',
				'order_sub_total'  					=> $filter_details == 'basic_details' ? $result['order_sub_total'] : '',				
				'order_shipping'  					=> $filter_details == 'basic_details' ? $result['order_shipping'] : '',
				'order_tax'  						=> $filter_details == 'basic_details' ? $result['order_tax'] : '',					
				'order_value'  						=> $filter_details == 'basic_details' ? $result['order_value'] : '',			
				'product_ord_id'  					=> $filter_details == 'basic_details' ? $result['product_ord_id'] : '',
				'product_ord_id_link'  				=> $filter_details == 'basic_details' ? $result['product_ord_id_link'] : '',
				'product_ord_date'  				=> $filter_details == 'basic_details' ? $result['product_ord_date'] : '',
				'product_pid'  						=> $filter_details == 'basic_details' ? $result['product_pid'] : '',
				'product_pid_link'  				=> $filter_details == 'basic_details' ? $result['product_pid_link'] : '',	
				'product_sku'  						=> $filter_details == 'basic_details' ? $result['product_sku'] : '',
				'product_model'  					=> $filter_details == 'basic_details' ? $result['product_model'] : '',				
				'product_name'  					=> $filter_details == 'basic_details' ? $result['product_name'] : '',	
				'product_option'  					=> $filter_details == 'basic_details' ? $result['product_option'] : '',					
				'product_attributes'  				=> $filter_details == 'basic_details' ? $result['product_attributes'] : '',
				'product_manu'  					=> $filter_details == 'basic_details' ? $result['product_manu'] : '',
				'product_category'  				=> $filter_details == 'basic_details' ? $result['product_category'] : '',
				'product_currency'  				=> $filter_details == 'basic_details' ? $result['product_currency'] : '',
				'product_price'  					=> $filter_details == 'basic_details' ? $result['product_price'] : '',
				'product_quantity'  				=> $filter_details == 'basic_details' ? $result['product_quantity'] : '',
				'product_total_excl_vat'	  		=> $filter_details == 'basic_details' ? $result['product_total_excl_vat'] : '',				
				'product_tax'  						=> $filter_details == 'basic_details' ? $result['product_tax'] : '',
				'product_total_incl_vat'  			=> $filter_details == 'basic_details' ? $result['product_total_incl_vat'] : '',
				'customer_ord_id' 					=> $filter_details == 'basic_details' ? $result['customer_ord_id'] : '',
				'customer_ord_id_link' 				=> $filter_details == 'basic_details' ? $result['customer_ord_id_link'] : '',
				'customer_ord_date' 				=> $filter_details == 'basic_details' ? $result['customer_ord_date'] : '',
				'customer_cust_id' 					=> $filter_details == 'basic_details' ? $result['customer_cust_id'] : '',
				'customer_cust_id_link' 			=> $filter_details == 'basic_details' ? $result['customer_cust_id_link'] : '',	
				'billing_name' 						=> $filter_details == 'basic_details' ? $result['billing_name'] : '',
				'billing_company' 					=> $filter_details == 'basic_details' ? $result['billing_company'] : '',
				'billing_address_1' 				=> $filter_details == 'basic_details' ? $result['billing_address_1'] : '',
				'billing_address_2' 				=> $filter_details == 'basic_details' ? $result['billing_address_2'] : '',
				'billing_city' 						=> $filter_details == 'basic_details' ? $result['billing_city'] : '',
				'billing_zone' 						=> $filter_details == 'basic_details' ? $result['billing_zone'] : '',
				'billing_postcode' 					=> $filter_details == 'basic_details' ? $result['billing_postcode'] : '',	
				'billing_country' 					=> $filter_details == 'basic_details' ? $result['billing_country'] : '',
				'customer_telephone' 				=> $filter_details == 'basic_details' ? $result['customer_telephone'] : '',
				'shipping_name' 					=> $filter_details == 'basic_details' ? $result['shipping_name'] : '',
				'shipping_company' 					=> $filter_details == 'basic_details' ? $result['shipping_company'] : '',
				'shipping_address_1' 				=> $filter_details == 'basic_details' ? $result['shipping_address_1'] : '',
				'shipping_address_2' 				=> $filter_details == 'basic_details' ? $result['shipping_address_2'] : '',
				'shipping_city' 					=> $filter_details == 'basic_details' ? $result['shipping_city'] : '',
				'shipping_zone' 					=> $filter_details == 'basic_details' ? $result['shipping_zone'] : '',
				'shipping_postcode' 				=> $filter_details == 'basic_details' ? $result['shipping_postcode'] : '',	
				'shipping_country' 					=> $filter_details == 'basic_details' ? $result['shipping_country'] : '',
				'orders_total'      				=> $orders_total,	
				'products_total'   		   			=> $products_total,
				'total_total'      					=> $this->currency->format($total_total, $this->config->get('config_currency')),
				'aov_total' 						=> $this->currency->format(($total_total ? $total_total / $orders_total : 0), $this->config->get('config_currency')),	
				'refunds_total'    		  			=> $this->currency->format($refunds_total, $this->config->get('config_currency')),
				'reward_points_total'   	   		=> $reward_points_total ? $reward_points_total : 0,
				'href' 								=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true)
			);
		  }

		} else {

			// Options
			$option_data = array();
			$options = $this->model_report_adv_customers->getOrderOptions($result['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'checkbox' && $option['type'] != 'text' && $option['type'] != 'textarea' && $option['type'] != 'file' && $option['type'] != 'image' && $option['type'] != 'date' && $option['type'] != 'datetime' && $option['type'] != 'time') {
					$option_data[] = array(
						'name'  	=> $option['name'],
						'value' 	=> $option['value'],
						'type'  	=> $option['type']
					);
				}
			}

			// Taxes
			$tax_data = array();
			$taxes = $this->model_report_adv_customers->getOrderTaxesDivided($result['order_id']);

			foreach ($taxes as $tax) {
				$tax_data[] = array(
						'title'  	=> $tax['title'],
						'value' 	=> $tax['value']
				);
			}
			
			// Custom Fields
			$this->load->model('customer/custom_field');

			$account_custom_fields = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'order' => 'ASC',
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
			$data['custom_fields'] = $this->model_report_adv_customers->getCustomFieldsNames($filter_data);			
			$order_custom_field = json_decode($result['custom_field'], true);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_custom_field[$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_custom_field[$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$account_custom_fields[] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_custom_field[$custom_field['custom_field_id']])) {
						foreach ($order_custom_field[$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$account_custom_fields[] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$account_custom_fields[] = array(
							'name'  => $custom_field['name'],
							'value' => $order_custom_field[$custom_field['custom_field_id']]
						);
					}
				}
			}
			
			$customers[] = array(
				'order_id'   					=> $result['order_id'],	
				'order_id_link'     			=> $result['order_id_link'], 
				'date_added'    				=> date($data['advco_date_format'] == 'DDMMYYYY' ? 'd/m/Y' : 'm/d/Y', strtotime($result['date_added'])),
				'invoice'     					=> $result['invoice_prefix'] . $result['invoice_no'],
				'name'   						=> $result['firstname'] . ' ' . $result['lastname'],
				'email'   						=> $result['email'],
				'cust_group'   					=> $result['cust_group'],
				'product_id'  					=> $result['product_id'],	
				'product_id_link'  				=> $result['product_id_link'],	
				'product_sku'  					=> $result['product_sku'],
				'product_model'  				=> $result['product_model'],				
				'product_name'  				=> $result['product_name'],	
				'product_options'  				=> $result['product_options'],
				'product_option'   		   		=> $option_data,				
				'product_attributes'  			=> $result['product_attributes'],
				'product_category'  			=> $result['product_category'],				
				'product_manu'  				=> $result['product_manu'],
				'currency_code' 				=> $result['currency_code'],
				'product_price'        			=> $this->currency->format($result['product_price'], $this->config->get('config_currency')),
				'product_price_raw'  			=> $result['product_price'],
				'product_quantity'  			=> $result['product_quantity'],
				'product_total_excl_vat'  		=> $this->currency->format($result['product_total_excl_vat'], $this->config->get('config_currency')),
				'product_total_excl_vat_raw'  	=> $result['product_total_excl_vat'],
				'product_tax'  					=> $this->currency->format($result['product_tax'], $this->config->get('config_currency')),
				'product_tax_raw'  				=> $result['product_tax'],
				'product_total_incl_vat'  		=> $this->currency->format($result['product_total_incl_vat'], $this->config->get('config_currency')),
				'product_total_incl_vat_raw'  	=> $result['product_total_incl_vat'],
				'product_qty_refund'  			=> $result['product_qty_refund'],
				'product_refund'  				=> $this->currency->format($result['product_refund'], $this->config->get('config_currency')),
				'product_refund_raw'  			=> $result['product_refund'],
				'product_reward_points'      	=> $result['product_reward_points'] ? $result['product_reward_points'] : 0,
				'order_sub_total'  				=> $this->currency->format($result['order_sub_total'], $this->config->get('config_currency')),
				'order_sub_total_raw'  			=> $result['order_sub_total'],
				'order_handling'  				=> $this->currency->format($result['order_handling'], $this->config->get('config_currency')),
				'order_handling_raw'  			=> $result['order_handling'],
				'order_low_order_fee'  			=> $this->currency->format($result['order_low_order_fee'], $this->config->get('config_currency')),
				'order_low_order_fee_raw'		=> $result['order_low_order_fee'],
				'order_shipping'  				=> $this->currency->format($result['order_shipping'], $this->config->get('config_currency')),
				'order_shipping_raw'  			=> $result['order_shipping'],
				'order_reward'  				=> $this->currency->format($result['order_reward'], $this->config->get('config_currency')),
				'order_reward_raw'  			=> $result['order_reward'],
				'order_earned_points'      		=> $result['order_earned_points'] ? $result['order_earned_points'] : 0,
				'order_used_points'      		=> $result['order_used_points'] ? $result['order_used_points'] : 0,				
				'order_coupon'  				=> $this->currency->format($result['order_coupon'], $this->config->get('config_currency')),
				'order_coupon_raw'  			=> $result['order_coupon'],
				'order_coupon_name'  			=> $result['order_coupon_name'],
				'order_coupon_code'  			=> $result['order_coupon_code'],
				'order_tax'  					=> $this->currency->format($result['order_tax'], $this->config->get('config_currency')),
				'order_tax_raw'  				=> $result['order_tax'],
				'order_taxes'  					=> $tax_data,
				'order_credit'  				=> $this->currency->format($result['order_credit'], $this->config->get('config_currency')),
				'order_credit_raw'  			=> $result['order_credit'],
				'order_voucher'  				=> $this->currency->format($result['order_voucher'], $this->config->get('config_currency')),
				'order_voucher_raw'  			=> $result['order_voucher'],
				'order_voucher_code'  			=> $result['order_voucher_code'],
				'order_commission'   			=> $this->currency->format('-' . ($result['order_commission']), $this->config->get('config_currency')),
				'order_commission_raw'   		=> $result['order_commission'],				
				'order_value'  					=> $this->currency->format($result['order_value'], $this->config->get('config_currency')),
				'order_value_raw'  				=> $result['order_value'],
				'order_refund'      			=> $this->currency->format($result['order_refund'], $this->config->get('config_currency')),
				'order_refund_raw'      		=> $result['order_refund'],
				'shipping_method' 				=> preg_replace('~\(.*?\)~', '', $result['shipping_method']),
				'payment_method'  				=> preg_replace('~\(.*?\)~', '', $result['payment_method']),
				'order_status'  				=> $result['order_status'],
				'store_name'      				=> $result['store_name'],	
				'customer_id' 					=> $result['customer_id'],	
				'customer_id_link' 				=> $result['customer_id_link'],	
				'custom_field' 					=> $result['custom_field'],	
				'custom_fields' 				=> $account_custom_fields,
				'payment_firstname' 			=> $result['payment_firstname'],
				'payment_lastname' 				=> $result['payment_lastname'],
				'payment_company' 				=> $result['payment_company'],
				'payment_address_1' 			=> $result['payment_address_1'],
				'payment_address_2' 			=> $result['payment_address_2'],
				'payment_city' 					=> $result['payment_city'],
				'payment_zone' 					=> $result['payment_zone'],
				'payment_zone_id' 				=> $result['payment_zone_id'],
				'payment_zone_code' 			=> $result['payment_zone_code'],
				'payment_postcode' 				=> $result['payment_postcode'],	
				'payment_country' 				=> $result['payment_country'],
				'payment_country_id' 			=> $result['payment_country_id'],
				'payment_country_code' 			=> $result['payment_country_code'],
				'telephone' 					=> $result['telephone'],
				'shipping_firstname' 			=> $result['shipping_firstname'],
				'shipping_lastname' 			=> $result['shipping_lastname'],
				'shipping_company' 				=> $result['shipping_company'],
				'shipping_address_1' 			=> $result['shipping_address_1'],
				'shipping_address_2' 			=> $result['shipping_address_2'],
				'shipping_city' 				=> $result['shipping_city'],
				'shipping_zone' 				=> $result['shipping_zone'],
				'shipping_zone_id' 				=> $result['shipping_zone_id'],
				'shipping_zone_code' 			=> $result['shipping_zone_code'],
				'shipping_postcode' 			=> $result['shipping_postcode'],	
				'shipping_country' 				=> $result['shipping_country'],
				'shipping_country_id' 			=> $result['shipping_country_id'],
				'shipping_country_code' 		=> $result['shipping_country_code'],
				'order_weight' 					=> $result['order_weight'] . $result['weight_class'],
				'order_comment' 				=> $result['comment']
			);
			
			}
		
		}

		$data['adv_ext_name'] = $this->language->get('adv_ext_name');
		$data['adv_ext_short_name'] = 'adv_customers';
		$data['adv_ext_version'] = $this->language->get('adv_ext_version');	
		$data['adv_ext_url'] = 'https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=3749';
		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		
		$data['text_guest'] = $this->language->get('text_guest');
		$data['text_no_details'] = $this->language->get('text_no_details');
		$data['text_basic_details'] = $this->language->get('text_basic_details');
		$data['text_all_details'] = $this->language->get('text_all_details');			
		$data['text_no_results'] = $this->language->get('text_no_results');	
		$data['text_all'] = $this->language->get('text_all');
		$data['text_for_export'] = $this->language->get('text_for_export');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_all_status'] = $this->language->get('text_all_status');		
		$data['text_all_stores'] = $this->language->get('text_all_stores');
		$data['text_all_currencies'] = $this->language->get('text_all_currencies');
		$data['text_all_taxes'] = $this->language->get('text_all_taxes');	
		$data['text_all_tax_classes'] = $this->language->get('text_all_tax_classes');			
		$data['text_all_zones'] = $this->language->get('text_all_zones');			
		$data['text_all_groups'] = $this->language->get('text_all_groups');
		$data['text_all_payment_methods'] = $this->language->get('text_all_payment_methods');	
		$data['text_all_shipping_methods'] = $this->language->get('text_all_shipping_methods');
		$data['text_all_categories'] = $this->language->get('text_all_categories');
		$data['text_all_manufacturers'] = $this->language->get('text_all_manufacturers');
		$data['text_all_options'] = $this->language->get('text_all_options');
		$data['text_all_attributes'] = $this->language->get('text_all_attributes');
		$data['text_all_locations'] = $this->language->get('text_all_locations');	
		$data['text_all_affiliate_names'] = $this->language->get('text_all_affiliate_names');
		$data['text_all_affiliate_emails'] = $this->language->get('text_all_affiliate_emails');
		$data['text_all_coupon_names'] = $this->language->get('text_all_coupon_names');
		$data['text_selected'] = $this->language->get('text_selected');		
		$data['text_detail'] = $this->language->get('text_detail');	
		$data['text_product_list'] = $this->language->get('text_product_list');
		$data['text_filter_total'] = $this->language->get('text_filter_total');
		$data['text_load_save_options'] = $this->language->get('text_load_save_options');
		$data['text_load_save'] = $this->language->get('text_load_save');		
		$data['text_export_options'] = $this->language->get('text_export_options');
		$data['text_report_type'] = $this->language->get('text_report_type');
		$data['help_report_type'] = $this->language->get('help_report_type');
		$data['text_export_type'] = $this->language->get('text_export_type');
		$data['text_select_export_type'] = $this->language->get('text_select_export_type');
		$data['text_export_logo_criteria'] = $this->language->get('text_export_logo_criteria');
		$data['text_export_csv_delimiter'] = $this->language->get('text_export_csv_delimiter');	
		$data['text_export_no_details'] = $this->language->get('text_export_no_details');
		$data['text_export_all_details'] = $this->language->get('text_export_all_details');
		$data['text_all_details_products'] = $this->language->get('text_all_details_products');
		$data['text_all_details_orders'] = $this->language->get('text_all_details_orders');
		$data['text_export_basic_details'] = $this->language->get('text_export_basic_details');	
		$data['text_local_settings'] = $this->language->get('text_local_settings');
		$data['text_check_all'] = $this->language->get('text_check_all');
		$data['text_uncheck_all'] = $this->language->get('text_uncheck_all');
		$data['text_filtering_options'] = $this->language->get('text_filtering_options');
		$data['text_column_settings'] = $this->language->get('text_column_settings');
		$data['text_mv_columns'] = $this->language->get('text_mv_columns');		
		$data['text_bd_columns'] = $this->language->get('text_bd_columns');	
		$data['text_all_columns'] = $this->language->get('text_all_columns');		
		$data['text_export_note'] = $this->language->get('text_export_note');
		$data['text_report_settings'] = $this->language->get('text_report_settings');
		$data['text_cron_settings'] = $this->language->get('text_cron_settings');
		$data['text_format_date'] = $this->language->get('text_format_date');
		$data['text_format_date_eu'] = $this->language->get('text_format_date_eu');
		$data['text_format_date_us'] = $this->language->get('text_format_date_us');
		$data['text_format_hour'] = $this->language->get('text_format_hour');
		$data['text_format_hour_24'] = $this->language->get('text_format_hour_24');
		$data['text_format_hour_12'] = $this->language->get('text_format_hour_12');		
		$data['text_format_week'] = $this->language->get('text_format_week');
		$data['text_format_week_mon_sun'] = $this->language->get('text_format_week_mon_sun');
		$data['text_format_week_sun_sat'] = $this->language->get('text_format_week_sun_sat');
		$data['text_export_notice1'] = $this->language->get('text_export_notice1');
		$data['text_export_notice2'] = $this->language->get('text_export_notice2');		
		$data['text_export_limit'] = $this->language->get('text_export_limit');
		$data['text_cron'] = $this->language->get('text_cron');
		$data['text_cron_1'] = $this->language->get('text_cron_1');
		$data['text_cron_1_text'] = $this->language->get('text_cron_1_text');
		$data['text_cron_2'] = $this->language->get('text_cron_2');
		$data['text_cron_3'] = $this->language->get('text_cron_3');
		$data['text_cron_3_text'] = $this->language->get('text_cron_3_text');
		$data['text_cron_3_option1'] = $this->language->get('text_cron_3_option1');
		$data['text_cron_3_option2'] = $this->language->get('text_cron_3_option2');
		$data['text_save_path'] = $this->language->get('text_save_path');
		$data['help_save_path'] = $this->language->get('help_save_path');
		$data['text_cron_email'] = $this->language->get('text_cron_email');
		$data['help_cron_email'] = $this->language->get('help_cron_email');
		$data['text_cron_file_name'] = $this->language->get('text_cron_file_name');	
		$data['help_cron_file_name'] = $this->language->get('help_cron_file_name');
		$data['text_cron_4'] = $this->language->get('text_cron_4');
		$data['text_cron_user'] = $this->language->get('text_cron_user');
		$data['help_cron_user'] = $this->language->get('help_cron_user');
		$data['text_cron_pass'] = $this->language->get('text_cron_pass');
		$data['help_cron_pass'] = $this->language->get('help_cron_pass');
		$data['text_cron_token'] = $this->language->get('text_cron_token');
		$data['help_cron_token'] = $this->language->get('help_cron_token');
		$data['text_token_generate'] = $this->language->get('text_token_generate');
		$data['text_cron_5'] = $this->language->get('text_cron_5');
		$data['text_cpanel_setting'] = $this->language->get('text_cpanel_setting');
		$data['text_cpanel_setting_note'] = $this->language->get('text_cpanel_setting_note');
		$data['text_cron_command'] = $this->language->get('text_cron_command');
		$data['text_cron_command_empty'] = $this->language->get('text_cron_command_empty');
		$data['text_cron_6'] = $this->language->get('text_cron_6');
		$data['entry_cron_title'] = $this->language->get('entry_cron_title');
		$data['entry_cron_command'] = $this->language->get('entry_cron_command');		
		$data['text_save_cron'] = $this->language->get('text_save_cron');		
		$data['text_pagin_page'] = $this->language->get('text_pagin_page');
		$data['text_pagin_of'] = $this->language->get('text_pagin_of');
		$data['text_pagin_results'] = $this->language->get('text_pagin_results');	
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_report_date'] = $this->language->get('text_report_date');
		$data['text_report_criteria'] = $this->language->get('text_report_criteria');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_report_title'] = $this->language->get('text_report_title');
		$data['text_cron_title'] = $this->language->get('text_cron_title');	
		
		$data['column_date'] = $this->language->get('column_date');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_id'] = $this->language->get('column_id');
		$data['column_id_guest'] = $this->language->get('column_id_guest');
		$data['column_customer'] = $this->language->get('column_customer');	
		$data['column_email'] = $this->language->get('column_email');
		$data['column_telephone'] = $this->language->get('column_telephone');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_customer_status'] = $this->language->get('column_customer_status');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_first_name'] = $this->language->get('column_first_name');
		$data['column_last_name'] = $this->language->get('column_last_name');
		$data['column_company'] = $this->language->get('column_company');
		$data['column_address_1'] = $this->language->get('column_address_1');
		$data['column_address_2'] = $this->language->get('column_address_2');
		$data['column_city'] = $this->language->get('column_city');
		$data['column_postcode'] = $this->language->get('column_postcode');
		$data['column_country_id'] = $this->language->get('column_country_id');
		$data['column_country'] = $this->language->get('column_country');
		$data['column_country_code'] = $this->language->get('column_country_code');
		$data['column_zone_id'] = $this->language->get('column_zone_id');
		$data['column_region_state'] = $this->language->get('column_region_state');	
		$data['column_region_state_code'] = $this->language->get('column_region_state_code');	
		$data['column_newsletter'] = $this->language->get('column_newsletter');	
		$data['column_approved'] = $this->language->get('column_approved');	
		$data['column_safe'] = $this->language->get('column_safe');	
		$data['column_ip'] = $this->language->get('column_ip');	
		$data['column_total_logins'] = $this->language->get('column_total_logins');	
		$data['column_last_login'] = $this->language->get('column_last_login');	
		$data['column_price'] = $this->language->get('column_price');
		$data['column_cart_quantity'] = $this->language->get('column_cart_quantity');
		$data['column_cart_value'] = $this->language->get('column_cart_value');		
		$data['column_wishlist_quantity'] = $this->language->get('column_wishlist_quantity');
		$data['column_wishlist_value'] = $this->language->get('column_wishlist_value');		
		$data['column_mostrecent'] = $this->language->get('column_mostrecent');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_customers'] = $this->language->get('column_customers');	
		$data['column_total'] = $this->language->get('column_total');
		$data['column_aov'] = $this->language->get('column_aov');
		$data['column_refunds'] = $this->language->get('column_refunds');
		$data['column_customer_reward_points'] = $this->language->get('column_customer_reward_points');	
		$data['column_action'] = $this->language->get('column_action');
		$data['column_sub_total'] = $this->language->get('column_sub_total');
		$data['column_handling'] = $this->language->get('column_handling');	
		$data['column_loworder'] = $this->language->get('column_loworder');
		$data['column_shipping'] = $this->language->get('column_shipping');
		$data['column_reward'] = $this->language->get('column_reward');
		$data['column_earned_reward_points'] = $this->language->get('column_earned_reward_points');
		$data['column_used_reward_points'] = $this->language->get('column_used_reward_points');		
		$data['column_coupon'] = $this->language->get('column_coupon');
		$data['column_coupon_name'] = $this->language->get('column_coupon_name');
		$data['column_coupon_code'] = $this->language->get('column_coupon_code');
		$data['column_taxes'] = $this->language->get('column_taxes');		
		$data['column_credit'] = $this->language->get('column_credit');	
		$data['column_voucher'] = $this->language->get('column_voucher');	
		$data['column_voucher_code'] = $this->language->get('column_voucher_code');
		$data['column_commission'] = $this->language->get('column_commission');	
		$data['column_order_date_added'] = $this->language->get('column_order_date_added');
		$data['column_order_order_id'] = $this->language->get('column_order_order_id');
		$data['column_order_inv_no'] = $this->language->get('column_order_inv_no');
		$data['column_order_customer'] = $this->language->get('column_order_customer');
		$data['column_order_email'] = $this->language->get('column_order_email');		
		$data['column_order_customer_group'] = $this->language->get('column_order_customer_group');		
		$data['column_order_shipping_method'] = $this->language->get('column_order_shipping_method');
		$data['column_order_payment_method'] = $this->language->get('column_order_payment_method');		
		$data['column_order_status'] = $this->language->get('column_order_status');
		$data['column_order_store'] = $this->language->get('column_order_store');
		$data['column_order_currency'] = $this->language->get('column_order_currency');		
		$data['column_order_quantity'] = $this->language->get('column_order_quantity');	
		$data['column_order_sub_total'] = $this->language->get('column_order_sub_total');	
		$data['column_order_shipping'] = $this->language->get('column_order_shipping');
		$data['column_order_tax'] = $this->language->get('column_order_tax');			
		$data['column_order_value'] = $this->language->get('column_order_value');
		$data['column_order_refund'] = $this->language->get('column_order_refund');
		$data['column_order_commission'] = $this->language->get('column_order_commission');	
		$data['column_prod_order_id'] = $this->language->get('column_prod_order_id');
		$data['column_prod_date_added'] = $this->language->get('column_prod_date_added');
		$data['column_prod_inv_no'] = $this->language->get('column_prod_inv_no');
		$data['column_prod_id'] = $this->language->get('column_prod_id');
		$data['column_prod_sku'] = $this->language->get('column_prod_sku');		
		$data['column_prod_model'] = $this->language->get('column_prod_model');		
		$data['column_prod_name'] = $this->language->get('column_prod_name');	
		$data['column_prod_option'] = $this->language->get('column_prod_option');	
		$data['column_prod_attributes'] = $this->language->get('column_prod_attributes');			
		$data['column_prod_manu'] = $this->language->get('column_prod_manu');
		$data['column_prod_category'] = $this->language->get('column_prod_category');	
		$data['column_prod_currency'] = $this->language->get('column_prod_currency');
		$data['column_prod_price'] = $this->language->get('column_prod_price');
		$data['column_prod_quantity'] = $this->language->get('column_prod_quantity');
		$data['column_prod_total_excl_vat'] = $this->language->get('column_prod_total_excl_vat');
		$data['column_prod_tax'] = $this->language->get('column_prod_tax');
		$data['column_prod_total_incl_vat'] = $this->language->get('column_prod_total_incl_vat');
		$data['column_prod_qty_refunded'] = $this->language->get('column_prod_qty_refunded');
		$data['column_prod_refunded'] = $this->language->get('column_prod_refunded');
		$data['column_prod_reward_points'] = $this->language->get('column_prod_reward_points');
		$data['column_customer_order_id'] = $this->language->get('column_customer_order_id');
		$data['column_customer_date_added'] = $this->language->get('column_customer_date_added');		
		$data['column_customer_cust_id'] = $this->language->get('column_customer_cust_id');
		$data['column_custom_fields'] = $this->language->get('column_custom_fields');
		$data['column_billing_name'] = $this->language->get('column_billing_name');
		$data['column_billing_first_name'] = $this->language->get('column_billing_first_name');
		$data['column_billing_last_name'] = $this->language->get('column_billing_last_name');
		$data['column_billing_company'] = $this->language->get('column_billing_company');
		$data['column_billing_address_1'] = $this->language->get('column_billing_address_1');
		$data['column_billing_address_2'] = $this->language->get('column_billing_address_2');
		$data['column_billing_city'] = $this->language->get('column_billing_city');
		$data['column_billing_zone'] = $this->language->get('column_billing_zone');
		$data['column_billing_zone_id'] = $this->language->get('column_billing_zone_id');
		$data['column_billing_zone_code'] = $this->language->get('column_billing_zone_code');
		$data['column_billing_postcode'] = $this->language->get('column_billing_postcode');		
		$data['column_billing_country'] = $this->language->get('column_billing_country');
		$data['column_billing_country_id'] = $this->language->get('column_billing_country_id');
		$data['column_billing_country_code'] = $this->language->get('column_billing_country_code');
		$data['column_customer_telephone'] = $this->language->get('column_customer_telephone');
		$data['column_shipping_name'] = $this->language->get('column_shipping_name');
		$data['column_shipping_first_name'] = $this->language->get('column_shipping_first_name');
		$data['column_shipping_last_name'] = $this->language->get('column_shipping_last_name');
		$data['column_shipping_company'] = $this->language->get('column_shipping_company');
		$data['column_shipping_address_1'] = $this->language->get('column_shipping_address_1');
		$data['column_shipping_address_2'] = $this->language->get('column_shipping_address_2');
		$data['column_shipping_city'] = $this->language->get('column_shipping_city');
		$data['column_shipping_zone'] = $this->language->get('column_shipping_zone');
		$data['column_shipping_zone_id'] = $this->language->get('column_shipping_zone_id');
		$data['column_shipping_zone_code'] = $this->language->get('column_shipping_zone_code');
		$data['column_shipping_postcode'] = $this->language->get('column_shipping_postcode');		
		$data['column_shipping_country'] = $this->language->get('column_shipping_country');
		$data['column_shipping_country_id'] = $this->language->get('column_shipping_country_id');
		$data['column_shipping_country_code'] = $this->language->get('column_shipping_country_code');
		$data['column_order_weight'] = $this->language->get('column_order_weight');
		$data['column_order_comment'] = $this->language->get('column_order_comment');
		
		$data['column_year'] = $this->language->get('column_year');
		$data['column_quarter'] = $this->language->get('column_quarter');
		$data['column_month'] = $this->language->get('column_month');
			
		$data['entry_order_created'] = $this->language->get('entry_order_created');
		$data['entry_added_to_cart'] = $this->language->get('entry_added_to_cart');
		$data['entry_added_to_wishlist'] = $this->language->get('entry_added_to_wishlist');		
		$data['entry_order_abandoned'] = $this->language->get('entry_order_abandoned');
		$data['entry_status_changed'] = $this->language->get('entry_status_changed');	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_range'] = $this->language->get('entry_range');	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_order_id_from'] = $this->language->get('entry_order_id_from');
		$data['entry_order_id_to'] = $this->language->get('entry_order_id_to');	
		$data['entry_order_value'] = $this->language->get('entry_order_value');
		$data['entry_order_value_min'] = $this->language->get('entry_order_value_min');
		$data['entry_order_value_max'] = $this->language->get('entry_order_value_max');			
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_currency'] = $this->language->get('entry_currency');	
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_tax_classes'] = $this->language->get('entry_tax_classes');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');		
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer_status'] = $this->language->get('entry_customer_status');	
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_customer_name'] = $this->language->get('entry_customer_name');		
		$data['entry_customer_email'] = $this->language->get('entry_customer_email'); 
		$data['entry_customer_telephone'] = $this->language->get('entry_customer_telephone');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_payment_company'] = $this->language->get('entry_payment_company');
		$data['entry_payment_address'] = $this->language->get('entry_payment_address');
		$data['entry_payment_city'] = $this->language->get('entry_payment_city');
		$data['entry_payment_zone'] = $this->language->get('entry_payment_zone');		
		$data['entry_payment_postcode'] = $this->language->get('entry_payment_postcode');
		$data['entry_payment_country'] = $this->language->get('entry_payment_country');		
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_shipping_company'] = $this->language->get('entry_shipping_company');
		$data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$data['entry_shipping_city'] = $this->language->get('entry_shipping_city');
		$data['entry_shipping_zone'] = $this->language->get('entry_shipping_zone');		
		$data['entry_shipping_postcode'] = $this->language->get('entry_shipping_postcode');
		$data['entry_shipping_country'] = $this->language->get('entry_shipping_country');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');		
		$data['entry_category'] = $this->language->get('entry_category'); 
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_attributes'] = $this->language->get('entry_attributes');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_affiliate_name'] = $this->language->get('entry_affiliate_name');
		$data['entry_affiliate_email'] = $this->language->get('entry_affiliate_email');
		$data['entry_coupon_name'] = $this->language->get('entry_coupon_name');
		$data['entry_coupon_code'] = $this->language->get('entry_coupon_code');
		$data['entry_voucher_code'] = $this->language->get('entry_voucher_code');		

		$data['entry_report'] = $this->language->get('entry_report');
		$data['entry_group'] = $this->language->get('entry_group');		
		$data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$data['entry_show_details'] = $this->language->get('entry_show_details');	
		$data['entry_limit'] = $this->language->get('entry_limit');
		
		$data['entry_title'] = $this->language->get('entry_title');	
		$data['entry_link'] = $this->language->get('entry_link');			

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_load_save'] = $this->language->get('button_load_save');
		$data['button_load'] = $this->language->get('button_load');
		$data['button_add_report'] = $this->language->get('button_add_report');		
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_filters'] = $this->language->get('button_filters');
		$data['button_chart'] = $this->language->get('button_chart');		
		$data['button_export'] = $this->language->get('button_export');
		$data['button_settings'] = $this->language->get('button_settings');
		$data['button_documentation'] = $this->language->get('button_documentation');
		$data['button_close'] = $this->language->get('button_close');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_expand'] = $this->language->get('button_expand');
		$data['button_collapse'] = $this->language->get('button_collapse');		
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_version'] = $this->language->get('heading_version');		
		
		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_range'])) {
			$url .= '&filter_range=' . $this->request->get['filter_range'];
		}

		if (isset($this->request->get['filter_report'])) {
			$url .= '&filter_report=' . $this->request->get['filter_report'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}
		
		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		if (isset($this->request->get['filter_order'])) {
			$url .= '&filter_order=' . $this->request->get['filter_order'];
		}
		
		if (isset($this->request->get['filter_details'])) {
			$url .= '&filter_details=' . $this->request->get['filter_details'];
		}
		
		if (isset($this->request->get['filter_limit'])) {
			$url .= '&filter_limit=' . $this->request->get['filter_limit'];
		}

		if (isset($this->request->get['filter_status_date_start'])) {
			$url .= '&filter_status_date_start=' . $this->request->get['filter_status_date_start'];
		}
		
		if (isset($this->request->get['filter_status_date_end'])) {
			$url .= '&filter_status_date_end=' . $this->request->get['filter_status_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_order_id_from'])) {
			$url .= '&filter_order_id_from=' . $this->request->get['filter_order_id_from'];
		}
		
		if (isset($this->request->get['filter_order_id_to'])) {
			$url .= '&filter_order_id_to=' . $this->request->get['filter_order_id_to'];
		}

		if (isset($this->request->get['filter_order_value_min'])) {
			$url .= '&filter_order_value_min=' . $this->request->get['filter_order_value_min'];
		}
		
		if (isset($this->request->get['filter_order_value_max'])) {
			$url .= '&filter_order_value_max=' . $this->request->get['filter_order_value_max'];
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}
		
		if (isset($this->request->get['filter_currency'])) {
			$url .= '&filter_currency=' . $this->request->get['filter_currency'];
		}
		
		if (isset($this->request->get['filter_taxes'])) {
			$url .= '&filter_taxes=' . $this->request->get['filter_taxes'];
		}
		
		if (isset($this->request->get['filter_tax_classes'])) {
			$url .= '&filter_tax_classes=' . $this->request->get['filter_tax_classes'];
		}
		
		if (isset($this->request->get['filter_geo_zones'])) {
			$url .= '&filter_geo_zones=' . $this->request->get['filter_geo_zones'];
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_status'])) {
			$url .= '&filter_customer_status=' . $this->request->get['filter_customer_status'];
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}		

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode(html_entity_decode($this->request->get['filter_customer_email'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_customer_telephone'])) {
			$url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_company'])) {
			$url .= '&filter_payment_company=' . urlencode(html_entity_decode($this->request->get['filter_payment_company'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_address'])) {
			$url .= '&filter_payment_address=' . urlencode(html_entity_decode($this->request->get['filter_payment_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_city'])) {
			$url .= '&filter_payment_city=' . urlencode(html_entity_decode($this->request->get['filter_payment_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$url .= '&filter_payment_zone=' . urlencode(html_entity_decode($this->request->get['filter_payment_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$url .= '&filter_payment_postcode=' . urlencode(html_entity_decode($this->request->get['filter_payment_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_country'])) {
			$url .= '&filter_payment_country=' . urlencode(html_entity_decode($this->request->get['filter_payment_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_payment_method'])) {
			$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
		}	
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$url .= '&filter_shipping_company=' . urlencode(html_entity_decode($this->request->get['filter_shipping_company'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_shipping_address'])) {
			$url .= '&filter_shipping_address=' . urlencode(html_entity_decode($this->request->get['filter_shipping_address'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_city'])) {
			$url .= '&filter_shipping_city=' . urlencode(html_entity_decode($this->request->get['filter_shipping_city'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$url .= '&filter_shipping_zone=' . urlencode(html_entity_decode($this->request->get['filter_shipping_zone'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$url .= '&filter_shipping_postcode=' . urlencode(html_entity_decode($this->request->get['filter_shipping_postcode'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_country'])) {
			$url .= '&filter_shipping_country=' . urlencode(html_entity_decode($this->request->get['filter_shipping_country'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}	
		
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}	
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}
		
		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . urlencode(html_entity_decode($this->request->get['filter_sku'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}	
		
		if (isset($this->request->get['filter_attribute'])) {
			$url .= '&filter_attribute=' . $this->request->get['filter_attribute'];
		}			

		if (isset($this->request->get['filter_location'])) {
			$url .= '&filter_location=' . $this->request->get['filter_location'];
		}	
		
		if (isset($this->request->get['filter_affiliate_name'])) {
			$url .= '&filter_affiliate_name=' . $this->request->get['filter_affiliate_name'];
		}	
		
		if (isset($this->request->get['filter_affiliate_email'])) {
			$url .= '&filter_affiliate_email=' . $this->request->get['filter_affiliate_email'];
		}	
		
		if (isset($this->request->get['filter_coupon_name'])) {
			$url .= '&filter_coupon_name=' . $this->request->get['filter_coupon_name'];
		}	
		
		if (isset($this->request->get['filter_coupon_code'])) {
			$url .= '&filter_coupon_code=' . urlencode(html_entity_decode($this->request->get['filter_coupon_code'], ENT_QUOTES, 'UTF-8'));
		}	
		
		if (isset($this->request->get['filter_voucher_code'])) {
			$url .= '&filter_voucher_code=' . urlencode(html_entity_decode($this->request->get['filter_voucher_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (!isset($_GET['cron'])) {
			unset($this->session->data['customers_data']);
			$total > 0 ? $this->session->data['customers_data'] = $data['customers'] = $customers : '';
		}
		
		$data['user'] = $this->user->getId();

		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_no_details'),
			'type'			=> 'export_no_details',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_basic_details'),
			'type'			=> 'export_basic_details',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_all_details')." ".$this->language->get('text_all_details_products'),
			'type' 			=> 'export_all_details_products',
			'icon'			=> 'fa fa-list-alt'
		);
		$data['report_types'][] = array(
			'name'			=> $this->language->get('text_export_all_details')." ".$this->language->get('text_all_details_orders'),
			'type' 			=> 'export_all_details_orders',
			'icon'			=> 'fa fa-list-alt'
		);

		if (isset($this->session->data['report_type'])) {
			$data['report_type'] = $this->session->data['report_type'];
		} else {
			$data['report_type'] = 'export_no_details';
		}
		
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_xlsx'),
			'type'			=> 'export_xlsx',
			'icon'			=> 'icon-green fa fa-file-excel-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_xls'),
			'type'			=> 'export_xls',
			'icon'			=> 'icon-green fa fa-file-excel-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_csv'),
			'type'			=> 'export_csv',
			'icon'			=> 'icon-dark-green fa fa-file-text-o'
		);		
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_pdf'),
			'type'			=> 'export_pdf',
			'icon'			=> 'icon-red fa fa-file-pdf-o'
		);
		$data['export_types'][] = array(
			'name'			=> $this->language->get('text_export_html'),
			'type'			=> 'export_html',
			'icon'			=> 'icon-black fa fa-file-text-o'
		);
		
		if (isset($this->session->data['export_type'])) {
			$data['export_type'] = $this->session->data['export_type'];
		} else {
			$data['export_type'] = '';
		}
		
		if (isset($this->session->data['export_logo_criteria'])) {
			$data['export_logo_criteria'] = $this->session->data['export_logo_criteria'];
		} else {
			$data['export_logo_criteria'] = 0;
		}

		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_comma'),
			'type'			=> 'comma',
		);
		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_semi'),
			'type'			=> 'semi',
		);
		$data['export_csv_delimiters'][] = array(
			'name'			=> $this->language->get('text_csv_delimiter_tab'),
			'type'			=> 'tab',
		);
		
		if (isset($this->session->data['export_csv_delimiter'])) {
			$data['export_csv_delimiter'] = $this->session->data['export_csv_delimiter'];
		} else {
			$data['export_csv_delimiter'] = 'comma';
		}			

		if (isset($_GET['cron'])) {
			$cron_settings = unserialize($this->config->get('advco' . $this->user->getId() . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$data['report_type'] = $cron_setting['cron_report_type'];
					$data['export_type'] = $cron_setting['cron_export_type'];
					$data['export_logo_criteria'] = $cron_setting['cron_export_logo_criteria'];
					$data['export_csv_delimiter'] = $cron_setting['cron_export_csv_delimiter'];
				}
			}
		}
		
		$data['cron_report_type'] = 'export_no_details';
		$data['cron_export_type'] = '';
		$data['cron_export_logo_criteria'] = 0;
		$data['cron_export_csv_delimiter'] = 'comma';
		$data['cron_export_file'] = 'send_to_email';
		$data['cron_file_save_path'] = 'report';
		$data['cron_file_name'] = '';
		$data['cron_email'] = '';
		$data['cron_user_id'] = $this->user->getId();
		$data['root_dir'] = dirname(DIR_APPLICATION) . '/';
		$data['dir_sep'] = '/';
		
		$data['filters'] = array(
			'store'						=> substr($this->language->get('entry_store'),0,-1),			
			'currency'					=> substr($this->language->get('entry_currency'),0,-1),			
			'tax'						=> substr($this->language->get('entry_tax'),0,-1),			
			'tax_class'					=> substr($this->language->get('entry_tax_classes'),0,-1),
			'geo_zone'					=> substr($this->language->get('entry_geo_zone'),0,-1),
			'customer_group'			=> substr($this->language->get('entry_customer_group'),0,-1),
			'customer_status'			=> substr($this->language->get('entry_customer_status'),0,-1),
			'customer_name'				=> substr($this->language->get('entry_customer_name'),0,-1),
			'customer_email'			=> substr($this->language->get('entry_customer_email'),0,-1),
			'customer_telephone'		=> substr($this->language->get('entry_customer_telephone'),0,-1),
			'ip'						=> substr($this->language->get('entry_ip'),0,-1),
			'payment_company'			=> substr($this->language->get('entry_payment_company'),0,-1),			
			'payment_address'			=> substr($this->language->get('entry_payment_address'),0,-1),	
			'payment_city'				=> substr($this->language->get('entry_payment_city'),0,-1),	
			'payment_zone'				=> substr($this->language->get('entry_payment_zone'),0,-1),	
			'payment_postcode'			=> substr($this->language->get('entry_payment_postcode'),0,-1),	
			'payment_country'			=> substr($this->language->get('entry_payment_country'),0,-1),
			'payment_method'			=> substr($this->language->get('entry_payment_method'),0,-1),
			'shipping_company'			=> substr($this->language->get('entry_shipping_company'),0,-1),
			'shipping_address'			=> substr($this->language->get('entry_shipping_address'),0,-1),
			'shipping_city'				=> substr($this->language->get('entry_shipping_city'),0,-1),
			'shipping_zone'				=> substr($this->language->get('entry_shipping_zone'),0,-1),
			'shipping_postcode'			=> substr($this->language->get('entry_shipping_postcode'),0,-1),
			'shipping_country'			=> substr($this->language->get('entry_shipping_country'),0,-1),
			'shipping_method'			=> substr($this->language->get('entry_shipping_method'),0,-1),
			'category'					=> substr($this->language->get('entry_category'),0,-1),
			'manufacturer'				=> substr($this->language->get('entry_manufacturer'),0,-1),
			'product'					=> substr($this->language->get('entry_product'),0,-1),
			'sku'						=> substr($this->language->get('entry_sku'),0,-1),			
			'model'						=> substr($this->language->get('entry_model'),0,-1),
			'option'					=> substr($this->language->get('entry_option'),0,-1),
			'attribute'					=> substr($this->language->get('entry_attributes'),0,-1),
			'location'					=> substr($this->language->get('entry_location'),0,-1),
			'affiliate_name'			=> substr($this->language->get('entry_affiliate_name'),0,-1),
			'affiliate_email'			=> substr($this->language->get('entry_affiliate_email'),0,-1),
			'coupon_name'				=> substr($this->language->get('entry_coupon_name'),0,-1),
			'coupon_code'				=> substr($this->language->get('entry_coupon_code'),0,-1),
			'voucher_code'				=> substr($this->language->get('entry_voucher_code'),0,-1)
		);

		$data['filters_scw_cwo_alc'] = array(
			'store'						=> substr($this->language->get('entry_store'),0,-1),											 
			'customer_group'			=> substr($this->language->get('entry_customer_group'),0,-1),
			'customer_status'			=> substr($this->language->get('entry_customer_status'),0,-1),
			'customer_name'				=> substr($this->language->get('entry_customer_name'),0,-1),
			'customer_email'			=> substr($this->language->get('entry_customer_email'),0,-1),
			'customer_telephone'		=> substr($this->language->get('entry_customer_telephone'),0,-1),
			'ip'						=> substr($this->language->get('entry_ip'),0,-1),
			'payment_company'			=> substr($this->language->get('entry_payment_company'),0,-1),			
			'payment_address'			=> substr($this->language->get('entry_payment_address'),0,-1),	
			'payment_city'				=> substr($this->language->get('entry_payment_city'),0,-1),	
			'payment_zone'				=> substr($this->language->get('entry_payment_zone'),0,-1),	
			'payment_postcode'			=> substr($this->language->get('entry_payment_postcode'),0,-1),	
			'payment_country'			=> substr($this->language->get('entry_payment_country'),0,-1)
		);

		$data['filters_fill'] = array(			
			'currency'					=> substr($this->language->get('entry_currency'),0,-1),			
			'tax'						=> substr($this->language->get('entry_tax'),0,-1),			
			'tax_class'					=> substr($this->language->get('entry_tax_classes'),0,-1),
			'geo_zone'					=> substr($this->language->get('entry_geo_zone'),0,-1),
			'shipping_company'			=> substr($this->language->get('entry_shipping_company'),0,-1),
			'shipping_address'			=> substr($this->language->get('entry_shipping_address'),0,-1),
			'shipping_city'				=> substr($this->language->get('entry_shipping_city'),0,-1),
			'shipping_zone'				=> substr($this->language->get('entry_shipping_zone'),0,-1),
			'shipping_postcode'			=> substr($this->language->get('entry_shipping_postcode'),0,-1),
			'shipping_country'			=> substr($this->language->get('entry_shipping_country'),0,-1),
			'shipping_method'			=> substr($this->language->get('entry_shipping_method'),0,-1),
			'category'					=> substr($this->language->get('entry_category'),0,-1),
			'manufacturer'				=> substr($this->language->get('entry_manufacturer'),0,-1),
			'product'					=> substr($this->language->get('entry_product'),0,-1),
			'sku'						=> substr($this->language->get('entry_sku'),0,-1),			
			'model'						=> substr($this->language->get('entry_model'),0,-1),
			'option'					=> substr($this->language->get('entry_option'),0,-1),
			'attribute'					=> substr($this->language->get('entry_attributes'),0,-1),
			'location'					=> substr($this->language->get('entry_location'),0,-1),
			'affiliate_name'			=> substr($this->language->get('entry_affiliate_name'),0,-1),
			'affiliate_email'			=> substr($this->language->get('entry_affiliate_email'),0,-1),
			'coupon_name'				=> substr($this->language->get('entry_coupon_name'),0,-1),
			'coupon_code'				=> substr($this->language->get('entry_coupon_code'),0,-1),
			'voucher_code'				=> substr($this->language->get('entry_voucher_code'),0,-1)
		);
		
		if ($this->config->get('advco' . $this->user->getId() . '_settings_filters')) {
			$data['advco_settings_filters'] = $this->config->get('advco' . $this->user->getId() . '_settings_filters');
		} else {
			$data['advco_settings_filters'] = array_keys($data['filters']);
		}

		$data['scw_columns'] = array(
			'scw_id'					=> $this->language->get('column_id'),
			'scw_customer'				=> $this->language->get('column_customer'),
			'scw_email'					=> $this->language->get('column_email'),
			'scw_telephone'				=> $this->language->get('column_telephone'),
			'scw_customer_group'		=> $this->language->get('column_customer_group'),
			'scw_custom_fields'			=> $this->language->get('column_custom_fields'),
			'scw_customer_status'		=> $this->language->get('column_customer_status'),
			'scw_first_name'			=> $this->language->get('column_first_name'),
			'scw_last_name'				=> $this->language->get('column_last_name'),
			'scw_company'				=> $this->language->get('column_company'),
			'scw_address_1'				=> $this->language->get('column_address_1'),
			'scw_address_2'				=> $this->language->get('column_address_2'),
			'scw_city'					=> $this->language->get('column_city'),
			'scw_postcode'				=> $this->language->get('column_postcode'),
			'scw_country_id'			=> $this->language->get('column_country_id'),
			'scw_country'				=> $this->language->get('column_country'),
			'scw_country_code'			=> $this->language->get('column_country_code'),
			'scw_zone_id'				=> $this->language->get('column_zone_id'),
			'scw_region_state'			=> $this->language->get('column_region_state'),
			'scw_region_state_code'		=> $this->language->get('column_region_state_code'),
			'scw_newsletter'			=> $this->language->get('column_newsletter'),
			'scw_approved'				=> $this->language->get('column_approved'),
			'scw_safe'					=> $this->language->get('column_safe'),
			'scw_ip'					=> $this->language->get('column_ip'),
			'scw_total_logins'			=> $this->language->get('column_total_logins'),
			'scw_last_login'			=> $this->language->get('column_last_login'),
			'scw_cart_quantity'			=> $this->language->get('column_cart_quantity'),
			'scw_cart_value'			=> $this->language->get('column_cart_value'),			
			'scw_wishlist_quantity'		=> $this->language->get('column_wishlist_quantity'),
			'scw_wishlist_value'		=> $this->language->get('column_wishlist_value'),
			'scw_product_id'			=> $this->language->get('column_prod_id'),
			'scw_date_added'			=> $this->language->get('column_prod_date_added'),
			'scw_sku'					=> $this->language->get('column_prod_sku'),
			'scw_name'					=> $this->language->get('column_prod_name'),
			'scw_options'				=> $this->language->get('column_prod_option'),
			'scw_model'					=> $this->language->get('column_prod_model'),	
			'scw_category'				=> $this->language->get('column_prod_category'),
			'scw_manufacturer'			=> $this->language->get('column_prod_manu'),
			'scw_attribute'				=> $this->language->get('column_prod_attributes'),
			'scw_price'					=> $this->language->get('column_price')		
		);
		
		if ($this->config->get('advco' . $this->user->getId() . '_settings_scw_columns')) {
			$data['advco_settings_scw_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_scw_columns');
		} else {
			$data['advco_settings_scw_columns'] = array('scw_id','scw_customer','scw_email','scw_customer_group','scw_customer_status','scw_total_logins','scw_last_login','scw_cart_quantity','scw_cart_value','scw_wishlist_quantity','scw_wishlist_value', 'scw_product_id', 'scw_date_added', 'scw_sku', 'scw_name', 'scw_options', 'scw_model', 'scw_price');
		}	

		$data['cwo_columns'] = array(
			'cwo_id'					=> $this->language->get('column_id'),
			'cwo_customer'				=> $this->language->get('column_customer'),
			'cwo_email'					=> $this->language->get('column_email'),
			'cwo_telephone'				=> $this->language->get('column_telephone'),
			'cwo_customer_group'		=> $this->language->get('column_customer_group'),
			'cwo_custom_fields'			=> $this->language->get('column_custom_fields'),
			'cwo_customer_status'		=> $this->language->get('column_customer_status'),
			'cwo_first_name'			=> $this->language->get('column_first_name'),
			'cwo_last_name'				=> $this->language->get('column_last_name'),
			'cwo_company'				=> $this->language->get('column_company'),
			'cwo_address_1'				=> $this->language->get('column_address_1'),
			'cwo_address_2'				=> $this->language->get('column_address_2'),
			'cwo_city'					=> $this->language->get('column_city'),
			'cwo_postcode'				=> $this->language->get('column_postcode'),
			'cwo_country_id'			=> $this->language->get('column_country_id'),
			'cwo_country'				=> $this->language->get('column_country'),
			'cwo_country_code'			=> $this->language->get('column_country_code'),
			'cwo_zone_id'				=> $this->language->get('column_zone_id'),
			'cwo_region_state'			=> $this->language->get('column_region_state'),
			'cwo_region_state_code'		=> $this->language->get('column_region_state_code'),
			'cwo_newsletter'			=> $this->language->get('column_newsletter'),
			'cwo_approved'				=> $this->language->get('column_approved'),
			'cwo_safe'					=> $this->language->get('column_safe'),
			'cwo_ip'					=> $this->language->get('column_ip'),
			'cwo_total_logins'			=> $this->language->get('column_total_logins'),
			'cwo_last_login'			=> $this->language->get('column_last_login')
		);
				
		if ($this->config->get('advco' . $this->user->getId() . '_settings_cwo_columns')) {
			$data['advco_settings_cwo_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_cwo_columns');
		} else {
			$data['advco_settings_cwo_columns'] = array('cwo_id','cwo_customer','cwo_email','cwo_customer_group','cwo_customer_status','cwo_total_logins','cwo_last_login');
		}	
		
		$data['mv_columns'] = array(
			'mv_id'						=> (($filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id')),
			'mv_customer'				=> $this->language->get('column_customer'),
			'mv_email'					=> $this->language->get('column_email'),
			'mv_telephone'				=> $this->language->get('column_telephone'),
			'mv_customer_group'			=> $this->language->get('column_customer_group'),	
			'mv_custom_fields'			=> $this->language->get('column_custom_fields'),
			'mv_customer_status'		=> $this->language->get('column_customer_status'),
			'mv_first_name'				=> $this->language->get('column_first_name'),
			'mv_last_name'				=> $this->language->get('column_last_name'),
			'mv_company'				=> $this->language->get('column_company'),
			'mv_address_1'				=> $this->language->get('column_address_1'),
			'mv_address_2'				=> $this->language->get('column_address_2'),
			'mv_city'					=> $this->language->get('column_city'),
			'mv_postcode'				=> $this->language->get('column_postcode'),
			'mv_country_id'				=> $this->language->get('column_country_id'),
			'mv_country'				=> $this->language->get('column_country'),
			'mv_country_code'			=> $this->language->get('column_country_code'),
			'mv_zone_id'				=> $this->language->get('column_zone_id'),
			'mv_region_state'			=> $this->language->get('column_region_state'),
			'mv_region_state_code'		=> $this->language->get('column_region_state_code'),
			'mv_newsletter'				=> $this->language->get('column_newsletter'),
			'mv_approved'				=> $this->language->get('column_approved'),
			'mv_safe'					=> $this->language->get('column_safe'),
			'mv_ip'						=> $this->language->get('column_ip'),
			'mv_total_logins'			=> $this->language->get('column_total_logins'),
			'mv_last_login'				=> $this->language->get('column_last_login'),
			'mv_mostrecent'				=> $this->language->get('column_mostrecent'),			
			'mv_orders'					=> $this->language->get('column_orders'),
			'mv_products'				=> $this->language->get('column_products'),
			'mv_total'					=> $this->language->get('column_total'),
			'mv_aov'					=> $this->language->get('column_aov'),
			'mv_refunds'				=> $this->language->get('column_refunds'),
			'mv_reward_points'			=> $this->language->get('column_customer_reward_points')
		);
				
		if ($this->config->get('advco' . $this->user->getId() . '_settings_mv_columns')) {
			$data['advco_settings_mv_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_mv_columns');
		} else {
			$data['advco_settings_mv_columns'] = array('mv_id','mv_customer','mv_email','mv_customer_group','mv_customer_status','mv_total_logins','mv_last_login','mv_mostrecent','mv_orders','mv_products','mv_total','mv_aov','mv_refunds');
		}	
		
		$data['ol_columns'] = array(
			'ol_order_order_id'			=> $this->language->get('column_order_order_id'),			
			'ol_order_date_added'		=> $this->language->get('column_order_date_added'),			
			'ol_order_inv_no'			=> $this->language->get('column_order_inv_no'),			
			'ol_order_customer'			=> $this->language->get('column_order_customer'),
			'ol_order_email'			=> $this->language->get('column_order_email'),
			'ol_order_customer_group'	=> $this->language->get('column_order_customer_group'),
			'ol_order_shipping_method'	=> $this->language->get('column_order_shipping_method'),
			'ol_order_payment_method'	=> $this->language->get('column_order_payment_method'),
			'ol_order_status'			=> $this->language->get('column_order_status'),
			'ol_order_store'			=> $this->language->get('column_order_store'),
			'ol_order_currency'			=> $this->language->get('column_order_currency'),			
			'ol_order_quantity'			=> $this->language->get('column_order_quantity'),	
			'ol_order_sub_total'		=> $this->language->get('column_order_sub_total'),	
			'ol_order_shipping'			=> $this->language->get('column_order_shipping'),	
			'ol_order_tax'				=> $this->language->get('column_order_tax'),	
			'ol_order_value'			=> $this->language->get('column_order_value')
		);

		if ($this->config->get('advco' . $this->user->getId() . '_settings_ol_columns')) {
			$data['advco_settings_ol_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_ol_columns');
		} else {
			$data['advco_settings_ol_columns'] = array_keys($data['ol_columns']);
		}
		
		$data['pl_columns'] = array(
			'pl_prod_order_id'			=> $this->language->get('column_prod_order_id'),			
			'pl_prod_date_added'		=> $this->language->get('column_prod_date_added'),										
			'pl_prod_id'				=> $this->language->get('column_prod_id'),
			'pl_prod_sku'				=> $this->language->get('column_prod_sku'),
			'pl_prod_model'				=> $this->language->get('column_prod_model'),
			'pl_prod_name'				=> $this->language->get('column_prod_name'),
			'pl_prod_option'			=> $this->language->get('column_prod_option'),
			'pl_prod_attributes'		=> $this->language->get('column_prod_attributes'),
			'pl_prod_category'			=> $this->language->get('column_prod_category'),				
			'pl_prod_manu'				=> $this->language->get('column_prod_manu'),			
			'pl_prod_currency'			=> $this->language->get('column_prod_currency'),	
			'pl_prod_price'				=> $this->language->get('column_prod_price'),	
			'pl_prod_quantity'			=> $this->language->get('column_prod_quantity'),	
			'pl_prod_total_excl_vat'	=> $this->language->get('column_prod_total_excl_vat'),	
			'pl_prod_tax'				=> $this->language->get('column_prod_tax'),	
			'pl_prod_total_incl_vat'	=> $this->language->get('column_prod_total_incl_vat')
		);

		if ($this->config->get('advco' . $this->user->getId() . '_settings_pl_columns')) {
			$data['advco_settings_pl_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_pl_columns');
		} else {
			$data['advco_settings_pl_columns'] = array('pl_prod_order_id','pl_prod_date_added','pl_prod_id','pl_prod_sku','pl_prod_model','pl_prod_name','pl_prod_option','pl_prod_currency','pl_prod_price','pl_prod_quantity','pl_prod_total_excl_vat','pl_prod_tax','pl_prod_total_incl_vat');
		}
		
		$data['cl_columns'] = array(
			'cl_customer_order_id'		=> $this->language->get('column_customer_order_id'),			
			'cl_customer_date_added'	=> $this->language->get('column_customer_date_added'),										
			'cl_customer_cust_id'		=> $this->language->get('column_customer_cust_id'),
			'cl_billing_name'			=> strip_tags($this->language->get('column_billing_name')),
			'cl_billing_company'		=> strip_tags($this->language->get('column_billing_company')),
			'cl_billing_address_1'		=> strip_tags($this->language->get('column_billing_address_1')),
			'cl_billing_address_2'		=> strip_tags($this->language->get('column_billing_address_2')),
			'cl_billing_city'			=> strip_tags($this->language->get('column_billing_city')),
			'cl_billing_zone'			=> strip_tags($this->language->get('column_billing_zone')),
			'cl_billing_postcode'		=> strip_tags($this->language->get('column_billing_postcode')),			
			'cl_billing_country'		=> strip_tags($this->language->get('column_billing_country')),
			'cl_customer_telephone'		=> $this->language->get('column_customer_telephone'),
			'cl_shipping_name'			=> strip_tags($this->language->get('column_shipping_name')),	
			'cl_shipping_company'		=> strip_tags($this->language->get('column_shipping_company')),	
			'cl_shipping_address_1'		=> strip_tags($this->language->get('column_shipping_address_1')),	
			'cl_shipping_address_2'		=> strip_tags($this->language->get('column_shipping_address_2')),
			'cl_shipping_city'			=> strip_tags($this->language->get('column_shipping_city')),
			'cl_shipping_zone'			=> strip_tags($this->language->get('column_shipping_zone')),
			'cl_shipping_postcode'		=> strip_tags($this->language->get('column_shipping_postcode')),
			'cl_shipping_country'		=> strip_tags($this->language->get('column_shipping_country'))
		);

		if ($this->config->get('advco' . $this->user->getId() . '_settings_cl_columns')) {
			$data['advco_settings_cl_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_cl_columns');
		} else {
			$data['advco_settings_cl_columns'] = array('cl_customer_order_id','cl_customer_date_added','cl_customer_cust_id','cl_billing_name','cl_billing_company','cl_billing_address_1','cl_billing_city','cl_billing_zone','cl_billing_postcode','cl_billing_country','cl_customer_telephone','cl_shipping_name','cl_shipping_company','cl_shipping_address_1','cl_shipping_city','cl_shipping_zone','cl_shipping_postcode','cl_shipping_country');
		}
		
		$data['all_columns'] = array(
			'all_order_inv_no'			=> $this->language->get('column_order_inv_no'),			
			'all_order_customer_name'	=> $this->language->get('column_order_customer'),			
			'all_order_email'			=> $this->language->get('column_order_email'),			
			'all_order_customer_group'	=> $this->language->get('column_order_customer_group'),
			'all_prod_id'				=> $this->language->get('column_prod_id'),
			'all_prod_sku'				=> $this->language->get('column_prod_sku'),
			'all_prod_model'			=> $this->language->get('column_prod_model'),
			'all_prod_name'				=> $this->language->get('column_prod_name'),
			'all_prod_option'			=> $this->language->get('column_prod_option'),
			'all_prod_attributes'		=> $this->language->get('column_prod_attributes'),
			'all_prod_category'			=> $this->language->get('column_prod_category'),			
			'all_prod_manu'				=> $this->language->get('column_prod_manu'),
			'all_prod_currency'			=> $this->language->get('column_prod_currency'),
			'all_prod_price'			=> $this->language->get('column_prod_price'),
			'all_prod_quantity'			=> $this->language->get('column_prod_quantity'),
			'all_prod_total_excl_vat'	=> $this->language->get('column_prod_total_excl_vat'),
			'all_prod_tax'				=> $this->language->get('column_prod_tax'),
			'all_prod_total_incl_vat'	=> $this->language->get('column_prod_total_incl_vat'),
			'all_prod_qty_refund'		=> $this->language->get('column_prod_qty_refunded'),
			'all_prod_refund'			=> $this->language->get('column_prod_refunded'),
			'all_prod_reward_points'	=> $this->language->get('column_prod_reward_points'),
			'all_sub_total'				=> $this->language->get('column_sub_total'),
			'all_handling'				=> $this->language->get('column_handling'),
			'all_loworder'				=> $this->language->get('column_loworder'),
			'all_shipping'				=> $this->language->get('column_shipping'),
			'all_reward'				=> $this->language->get('column_reward'),
			'all_reward_points'			=> $this->language->get('column_reward_points'),			
			'all_coupon'				=> $this->language->get('column_coupon'),
			'all_coupon_name'			=> $this->language->get('column_coupon_name'),
			'all_coupon_code'			=> $this->language->get('column_coupon_code'),
			'all_order_tax'				=> $this->language->get('column_order_tax'),
			'all_credit'				=> $this->language->get('column_credit'),
			'all_voucher'				=> $this->language->get('column_voucher'),
			'all_voucher_code'			=> $this->language->get('column_voucher_code'),
			'all_order_commission'		=> $this->language->get('column_order_commission'),
			'all_order_value'			=> $this->language->get('column_order_value'),
			'all_refund'				=> $this->language->get('column_order_refund'),
			'all_order_shipping_method'	=> $this->language->get('column_order_shipping_method'),
			'all_order_payment_method'	=> $this->language->get('column_order_payment_method'),
			'all_order_status'			=> $this->language->get('column_order_status'),
			'all_order_store'			=> $this->language->get('column_order_store'),
			'all_customer_cust_id'		=> $this->language->get('column_customer_cust_id'),
			'all_custom_fields'			=> $this->language->get('column_custom_fields'),
			'all_billing_first_name'	=> strip_tags($this->language->get('column_billing_first_name')),
			'all_billing_last_name'		=> strip_tags($this->language->get('column_billing_last_name')),
			'all_billing_company'		=> strip_tags($this->language->get('column_billing_company')),
			'all_billing_address_1'		=> strip_tags($this->language->get('column_billing_address_1')),
			'all_billing_address_2'		=> strip_tags($this->language->get('column_billing_address_2')),
			'all_billing_city'			=> strip_tags($this->language->get('column_billing_city')),
			'all_billing_zone'			=> strip_tags($this->language->get('column_billing_zone')),
			'all_billing_zone_id'		=> strip_tags($this->language->get('column_billing_zone_id')),
			'all_billing_zone_code'		=> strip_tags($this->language->get('column_billing_zone_code')),
			'all_billing_postcode'		=> strip_tags($this->language->get('column_billing_postcode')),			
			'all_billing_country'		=> strip_tags($this->language->get('column_billing_country')),
			'all_billing_country_id'	=> strip_tags($this->language->get('column_billing_country_id')),
			'all_billing_country_code'	=> strip_tags($this->language->get('column_billing_country_code')),
			'all_customer_telephone'	=> $this->language->get('column_customer_telephone'),
			'all_shipping_first_name'	=> strip_tags($this->language->get('column_shipping_first_name')),
			'all_shipping_last_name'	=> strip_tags($this->language->get('column_shipping_last_name')),
			'all_shipping_company'		=> strip_tags($this->language->get('column_shipping_company')),	
			'all_shipping_address_1'	=> strip_tags($this->language->get('column_shipping_address_1')),	
			'all_shipping_address_2'	=> strip_tags($this->language->get('column_shipping_address_2')),
			'all_shipping_city'			=> strip_tags($this->language->get('column_shipping_city')),
			'all_shipping_zone'			=> strip_tags($this->language->get('column_shipping_zone')),
			'all_shipping_zone_id'		=> strip_tags($this->language->get('column_shipping_zone_id')),
			'all_shipping_zone_code'	=> strip_tags($this->language->get('column_shipping_zone_code')),
			'all_shipping_postcode'		=> strip_tags($this->language->get('column_shipping_postcode')),
			'all_shipping_country'		=> strip_tags($this->language->get('column_shipping_country')),
			'all_shipping_country_id'	=> strip_tags($this->language->get('column_shipping_country_id')),
			'all_shipping_country_code'	=> strip_tags($this->language->get('column_shipping_country_code')),
			'all_order_weight'			=> $this->language->get('column_order_weight'),
			'all_order_comment'			=> $this->language->get('column_order_comment')
		);

		if ($this->config->get('advco' . $this->user->getId() . '_settings_all_columns')) {
			$data['advco_settings_all_columns'] = $this->config->get('advco' . $this->user->getId() . '_settings_all_columns');
		} else {
			$data['advco_settings_all_columns'] = array_keys($data['all_columns']);
		}

		$user = 'advco' . $this->user->getId();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = '" . $user . "'");
		$data['initialise'] = '';
		 if (!$query->rows) {
			$data['text_initialise_use'] = $this->language->get('text_initialise_use');
			$data['initialise'] = $query;
			$settings_data = array(
				'advco' . $this->user->getId() . '_settings_filters' 		=> array_keys($data['filters']),
				'advco' . $this->user->getId() . '_settings_scw_columns' 	=> array('scw_id','scw_customer','scw_email','scw_customer_group','scw_customer_status','scw_total_logins','scw_last_login','scw_cart_quantity','scw_cart_value','scw_wishlist_quantity','scw_wishlist_value', 'scw_product_id', 'scw_date_added', 'scw_sku', 'scw_name', 'scw_options', 'scw_model', 'scw_price'),
				'advco' . $this->user->getId() . '_settings_cwo_columns' 	=> array('cwo_id','cwo_customer','cwo_email','cwo_customer_group','cwo_customer_status','cwo_total_logins','cwo_last_login'),				
				'advco' . $this->user->getId() . '_settings_mv_columns' 	=> array('mv_id','mv_customer','mv_email','mv_customer_group','mv_customer_status','mv_total_logins','mv_last_login','mv_mostrecent','mv_orders','mv_products','mv_total','mv_aov','mv_refunds'),			
				'advco' . $this->user->getId() . '_settings_ol_columns' 	=> array_keys($data['ol_columns']),
				'advco' . $this->user->getId() . '_settings_pl_columns' 	=> array('pl_prod_order_id','pl_prod_date_added','pl_prod_id','pl_prod_sku','pl_prod_model','pl_prod_name','pl_prod_option','pl_prod_currency','pl_prod_price','pl_prod_quantity','pl_prod_total_excl_vat','pl_prod_tax','pl_prod_total_incl_vat'),
				'advco' . $this->user->getId() . '_settings_cl_columns' 	=> array('cl_customer_order_id','cl_customer_date_added','cl_customer_cust_id','cl_billing_name','cl_billing_company','cl_billing_address_1','cl_billing_city','cl_billing_zone','cl_billing_postcode','cl_billing_country','cl_customer_telephone','cl_shipping_name','cl_shipping_company','cl_shipping_address_1','cl_shipping_city','cl_shipping_zone','cl_shipping_postcode','cl_shipping_country'),
				'advco' . $this->user->getId() . '_settings_all_columns' 	=> array_keys($data['all_columns'])
			);
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($user, $settings_data);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $filter_limit;
		$pagination->url = $this->url->link('report/adv_customers', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $filter_limit) + 1 : 0, ((($page - 1) * $filter_limit) > ($total - $filter_limit)) ? $total : ((($page - 1) * $filter_limit) + $filter_limit), $total, ceil($total / $filter_limit));

		$data['report_link'] = $this->url->link('report/adv_customers', 'token=' . $this->session->data['token'], true);
		$data['save_report_link'] = 'index.php?route=report/adv_customers'. $url;
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_range'] = $filter_range;
		$data['filter_report'] = $filter_report;
		$data['filter_group'] = $filter_group;		
		$data['filter_sort'] = $filter_sort;
		$data['filter_order'] = $filter_order;
		$data['filter_details'] = $filter_details;
		$data['filter_limit'] = $filter_limit;		
		$data['filter_status_date_start'] = $filter_status_date_start;
		$data['filter_status_date_end'] = $filter_status_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;		
		$data['filter_order_id_from'] = $filter_order_id_from;
		$data['filter_order_id_to'] = $filter_order_id_to;
		$data['filter_order_value_min'] = $filter_order_value_min;
		$data['filter_order_value_max'] = $filter_order_value_max;			
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_currency'] = $filter_currency;
		$data['filter_taxes'] = $filter_taxes;
		$data['filter_tax_classes'] = $filter_tax_classes;		
		$data['filter_geo_zones'] = $filter_geo_zones;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_customer_status'] = $filter_customer_status;
		$data['filter_customer_name'] = $filter_customer_name; 
		$data['filter_customer_email'] = $filter_customer_email; 		
		$data['filter_customer_telephone'] = $filter_customer_telephone;
		$data['filter_ip'] = $filter_ip;
		$data['filter_payment_company'] = $filter_payment_company; 
		$data['filter_payment_address'] = $filter_payment_address; 
		$data['filter_payment_city'] = $filter_payment_city; 
		$data['filter_payment_postcode'] = $filter_payment_postcode; 
		$data['filter_payment_zone'] = $filter_payment_zone; 
		$data['filter_payment_country'] = $filter_payment_country; 
		$data['filter_payment_method'] = $filter_payment_method; 		
		$data['filter_shipping_company'] = $filter_shipping_company; 
		$data['filter_shipping_address'] = $filter_shipping_address; 
		$data['filter_shipping_city'] = $filter_shipping_city; 
		$data['filter_shipping_postcode'] = $filter_shipping_postcode; 
		$data['filter_shipping_zone'] = $filter_shipping_zone; 
		$data['filter_shipping_country'] = $filter_shipping_country; 
		$data['filter_shipping_method'] = $filter_shipping_method; 
		$data['filter_manufacturer'] = $filter_manufacturer; 
		$data['filter_category'] = $filter_category; 
		$data['filter_sku'] = $filter_sku; 
		$data['filter_product_name'] = $filter_product_name; 
		$data['filter_model'] = $filter_model; 
		$data['filter_option'] = $filter_option;
		$data['filter_attribute'] = $filter_attribute;
		$data['filter_location'] = $filter_location;
		$data['filter_affiliate_name'] = $filter_affiliate_name; 
		$data['filter_affiliate_email'] = $filter_affiliate_email; 
		$data['filter_coupon_name'] = $filter_coupon_name; 
		$data['filter_coupon_code'] = $filter_coupon_code; 
		$data['filter_voucher_code'] = $filter_voucher_code;		
		
		$data['url'] = $this->url->link('report/adv_customers', 'token=' . $this->session->data['token'] . $url . '&page='. $page, true);
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (!isset($_GET['cron'])) {
			$this->response->setOutput($this->load->view('report/adv_customers', $data));
		} else {	
			$export_data = array(
				'results'		=> $total > 0 ? $data['customers'] = $customers : ''
			);

			$user_id = $_GET['user_id'];

			if ($this->config->get('advco' . $user_id . '_settings_scw_columns')) {
				$advco_settings_scw_columns = $this->config->get('advco' . $user_id . '_settings_scw_columns');
			} else {
				$advco_settings_scw_columns = array();
			}
			
			if ($this->config->get('advco' . $user_id . '_settings_cwo_columns')) {
				$advco_settings_cwo_columns = $this->config->get('advco' . $user_id . '_settings_cwo_columns');
			} else {
				$advco_settings_cwo_columns = array();
			}
			
			if ($this->config->get('advco' . $user_id . '_settings_mv_columns')) {
				$advco_settings_mv_columns = $this->config->get('advco' . $user_id . '_settings_mv_columns');
			} else {
				$advco_settings_mv_columns = array();
			}	

			if ($this->config->get('advco' . $user_id . '_settings_ol_columns')) {
				$advco_settings_ol_columns = $this->config->get('advco' . $user_id . '_settings_ol_columns');
			} else {
				$advco_settings_ol_columns = array();
			}
		
			if ($this->config->get('advco' . $user_id . '_settings_pl_columns')) {
				$advco_settings_pl_columns = $this->config->get('advco' . $user_id . '_settings_pl_columns');
			} else {
				$advco_settings_pl_columns = array();
			}
		
			if ($this->config->get('advco' . $user_id . '_settings_cl_columns')) {
				$advco_settings_cl_columns = $this->config->get('advco' . $user_id . '_settings_cl_columns');
			} else {
				$advco_settings_cl_columns = array();
			}
		
			if ($this->config->get('advco' . $user_id . '_settings_all_columns')) {
				$advco_settings_all_columns = $this->config->get('advco' . $user_id . '_settings_all_columns');
			} else {
				$advco_settings_all_columns = array();
			}

			$cron_settings = unserialize($this->config->get('advco' . $user_id . 'cron_setting'));
			foreach ($cron_settings as $cron_setting) {
				if (isset($_GET['cron_id']) && ($_GET['cron_id'] == $cron_setting['cron_id'])) {
					$report_type = $cron_setting['cron_report_type'];
					$export_type = $cron_setting['cron_export_type'];
					$export_logo_criteria = $cron_setting['cron_export_logo_criteria'];
					$export_csv_delimiter = $cron_setting['cron_export_csv_delimiter'];
			
					$filter_report = $cron_setting['cron_filter_report'];
					$filter_details = $cron_setting['cron_filter_details'];
					$filter_group = $cron_setting['cron_filter_group'];
					$filter_sort = $cron_setting['cron_filter_sort'];
					$filter_order = $cron_setting['cron_filter_order'];
					$filter_limit = $cron_setting['cron_filter_limit'];
					
					$filter_range = $cron_setting['cron_filter_range'];
					$filter_date_start = $cron_setting['cron_date_start'];
					$filter_date_end = $cron_setting['cron_date_end'];
					$filter_order_status_id = $cron_setting['cron_filter_order_status_id'];
					$filter_status_date_start = $cron_setting['cron_status_date_start'];
					$filter_status_date_end = $cron_setting['cron_status_date_end'];
					$filter_order_id_from = $cron_setting['cron_filter_order_id_from'];
					$filter_order_id_to = $cron_setting['cron_filter_order_id_to'];
					$filter_order_value_min = $cron_setting['cron_filter_order_value_min'];
					$filter_order_value_max = $cron_setting['cron_filter_order_value_max'];
		
					$filter_store_id = $cron_setting['cron_filter_store_id'];
					$filter_currency = $cron_setting['cron_filter_currency'];
					$filter_taxes = $cron_setting['cron_filter_taxes'];
					$filter_tax_classes = $cron_setting['cron_filter_tax_classes'];
					$filter_geo_zones = $cron_setting['cron_filter_geo_zones'];
					$filter_customer_group_id = $cron_setting['cron_filter_customer_group_id'];
					$filter_customer_status = $cron_setting['cron_filter_customer_status'];
					$filter_customer_name = $cron_setting['cron_filter_customer_name'];
					$filter_customer_email = $cron_setting['cron_filter_customer_email'];
					$filter_customer_telephone = $cron_setting['cron_filter_customer_telephone'];
					$filter_ip = $cron_setting['cron_filter_ip'];
					$filter_payment_company = $cron_setting['cron_filter_payment_company'];
					$filter_payment_address = $cron_setting['cron_filter_payment_address'];
					$filter_payment_city = $cron_setting['cron_filter_payment_city'];
					$filter_payment_zone = $cron_setting['cron_filter_payment_zone'];
					$filter_payment_postcode = $cron_setting['cron_filter_payment_postcode'];
					$filter_payment_country = $cron_setting['cron_filter_payment_country'];
					$filter_payment_method = $cron_setting['cron_filter_payment_method'];
					$filter_shipping_company = $cron_setting['cron_filter_shipping_company'];
					$filter_shipping_address = $cron_setting['cron_filter_shipping_address'];
					$filter_shipping_city = $cron_setting['cron_filter_shipping_city'];
					$filter_shipping_zone = $cron_setting['cron_filter_shipping_zone'];
					$filter_shipping_postcode = $cron_setting['cron_filter_shipping_postcode'];
					$filter_shipping_country = $cron_setting['cron_filter_shipping_country'];
					$filter_shipping_method = $cron_setting['cron_filter_shipping_method'];
					$filter_category = $cron_setting['cron_filter_category'];
					$filter_manufacturer = $cron_setting['cron_filter_manufacturer'];
					$filter_sku = $cron_setting['cron_filter_sku'];
					$filter_product_name = $cron_setting['cron_filter_product_name'];
					$filter_model = $cron_setting['cron_filter_model'];
					$filter_option = $cron_setting['cron_filter_option'];
					$filter_attribute = $cron_setting['cron_filter_attribute'];
					$filter_location = $cron_setting['cron_filter_location'];
					$filter_affiliate_name = $cron_setting['cron_filter_affiliate_name'];
					$filter_affiliate_email = $cron_setting['cron_filter_affiliate_email'];
					$filter_coupon_name = $cron_setting['cron_filter_coupon_name'];
					$filter_coupon_code = $cron_setting['cron_filter_coupon_code'];
					$filter_voucher_code = $cron_setting['cron_filter_voucher_code'];	
					
					$file_save_path = $cron_setting['cron_file_save_path'];
					$file_name = $cron_setting['cron_file_name'];
					$export_file = $cron_setting['cron_export_file'];
					$email = $cron_setting['cron_email'];
				}
			}
			
			if ($export_type == 'export_xlsx') {
				$logo = str_replace('\\', '/', DIR_IMAGE . $this->config->get('config_logo'));
			} else {
				$this->load->model('tool/image');
				$logo = $this->model_tool_image->resize($this->config->get('config_logo'), 268, 50);
			}
		
			if ($report_type == 'export_no_details' && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx.inc.php');
				exit();			
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_pdf') {
				require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_pdf.inc.php');
				exit();
			} elseif ($report_type == 'export_no_details' && $export_type == 'export_html') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_html.inc.php');
				exit();	
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls_basic_details.inc.php');
				exit();
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx_basic_details.inc.php');
				exit();	
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv_basic_details.inc.php');
				exit();				
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_pdf') {
				require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_pdf_basic_details.inc.php');	
				exit();
			} elseif ($report_type == 'export_basic_details' && $export_type == 'export_html') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_html_basic_details.inc.php');
				exit();	
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xls') {
				$cwd = getcwd();			
				chdir(DIR_SYSTEM . 'library/pear');
				require_once('Spreadsheet/Excel/Writer.php');
				chdir($cwd);			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls_all_details.inc.php');
				exit();
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xlsx') {
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
				require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx_all_details.inc.php');
				exit();	
			} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_csv') {
				include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv_all_details.inc.php');
				exit();			
			} else {
				exit();
			}		
		}
	}
	
	public function customer_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_customer_name']) or isset($this->request->get['filter_customer_email']) or isset($this->request->get['filter_customer_telephone']) or isset($this->request->get['filter_ip']) or isset($this->request->get['filter_payment_company']) or isset($this->request->get['filter_payment_address']) or isset($this->request->get['filter_payment_city']) or isset($this->request->get['filter_payment_zone']) or isset($this->request->get['filter_payment_postcode']) or isset($this->request->get['filter_payment_country']) or isset($this->request->get['filter_shipping_company']) or isset($this->request->get['filter_shipping_address']) or isset($this->request->get['filter_shipping_city']) or isset($this->request->get['filter_shipping_zone']) or isset($this->request->get['filter_shipping_postcode']) or isset($this->request->get['filter_shipping_country'])) {
			
		$this->load->model('report/adv_customers');
		
		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = '';
		}	

		if (isset($this->request->get['filter_customer_telephone'])) {
			$filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$filter_payment_zone = '';
		}
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$filter_payment_country = '';
		}
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$filter_shipping_company = $this->request->get['filter_shipping_company'];
		} else {
			$filter_shipping_company = '';
		}
		
		if (isset($this->request->get['filter_shipping_address'])) {
			$filter_shipping_address = $this->request->get['filter_shipping_address'];
		} else {
			$filter_shipping_address = '';
		}

		if (isset($this->request->get['filter_shipping_city'])) {
			$filter_shipping_city = $this->request->get['filter_shipping_city'];
		} else {
			$filter_shipping_city = '';
		}
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$filter_shipping_zone = $this->request->get['filter_shipping_zone'];
		} else {
			$filter_shipping_zone = '';
		}
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$filter_shipping_postcode = $this->request->get['filter_shipping_postcode'];
		} else {
			$filter_shipping_postcode = '';
		}

		if (isset($this->request->get['filter_shipping_country'])) {
			$filter_shipping_country = $this->request->get['filter_shipping_country'];
		} else {
			$filter_shipping_country = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_customer_name' 	 		=> $filter_customer_name,
			'filter_customer_email' 	 	=> $filter_customer_email,			
			'filter_customer_telephone' 	=> $filter_customer_telephone,
			'filter_ip' 					=> $filter_ip,			
			'filter_payment_company' 		=> $filter_payment_company,
			'filter_payment_address' 		=> $filter_payment_address,
			'filter_payment_city' 			=> $filter_payment_city,
			'filter_payment_zone' 			=> $filter_payment_zone,			
			'filter_payment_postcode' 		=> $filter_payment_postcode,
			'filter_payment_country' 		=> $filter_payment_country,			
			'filter_shipping_company' 		=> $filter_shipping_company,
			'filter_shipping_address' 		=> $filter_shipping_address,
			'filter_shipping_city' 			=> $filter_shipping_city,
			'filter_shipping_zone' 			=> $filter_shipping_zone,			
			'filter_shipping_postcode' 		=> $filter_shipping_postcode,
			'filter_shipping_country' 		=> $filter_shipping_country,
			'start'        					=> 0,
			'limit'        					=> $limit
		);
						
		$results = $this->model_report_adv_customers->getCustomerAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'     		=> $result['customer_id'],				
					'cust_name'     		=> html_entity_decode($result['cust_name'], ENT_QUOTES, 'UTF-8'),
					'cust_email'     		=> $result['cust_email'],
					'cust_telephone'     	=> $result['cust_telephone'],
					'cust_ip'     			=> $result['cust_ip'],
					'payment_company'     	=> html_entity_decode($result['payment_company'], ENT_QUOTES, 'UTF-8'),	
					'payment_address'     	=> html_entity_decode($result['payment_address'], ENT_QUOTES, 'UTF-8'),	
					'payment_city'     		=> html_entity_decode($result['payment_city'], ENT_QUOTES, 'UTF-8'),	
					'payment_zone'     		=> html_entity_decode($result['payment_zone'], ENT_QUOTES, 'UTF-8'),						
					'payment_postcode'     	=> $result['payment_postcode'],
					'payment_country'     	=> html_entity_decode($result['payment_country'], ENT_QUOTES, 'UTF-8'),					
					'shipping_company'     	=> html_entity_decode($result['shipping_company'], ENT_QUOTES, 'UTF-8'),	
					'shipping_address'     	=> html_entity_decode($result['shipping_address'], ENT_QUOTES, 'UTF-8'),
					'shipping_city'     	=> html_entity_decode($result['shipping_city'], ENT_QUOTES, 'UTF-8'),
					'shipping_zone'     	=> html_entity_decode($result['shipping_zone'], ENT_QUOTES, 'UTF-8'),					
					'shipping_postcode'     => $result['shipping_postcode'],
					'shipping_country'     	=> html_entity_decode($result['shipping_country'], ENT_QUOTES, 'UTF-8')			
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customer_registered_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_customer_name']) or isset($this->request->get['filter_customer_email']) or isset($this->request->get['filter_customer_telephone']) or isset($this->request->get['filter_ip']) or isset($this->request->get['filter_payment_company']) or isset($this->request->get['filter_payment_address']) or isset($this->request->get['filter_payment_city']) or isset($this->request->get['filter_payment_zone']) or isset($this->request->get['filter_payment_postcode']) or isset($this->request->get['filter_payment_country'])) {
			
		$this->load->model('report/adv_customers');
		
		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = '';
		}	

		if (isset($this->request->get['filter_customer_telephone'])) {
			$filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$filter_payment_zone = '';
		}
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$filter_payment_country = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_customer_name' 	 		=> $filter_customer_name,
			'filter_customer_email' 	 	=> $filter_customer_email,			
			'filter_customer_telephone' 	=> $filter_customer_telephone,
			'filter_ip' 					=> $filter_ip,			
			'filter_payment_company' 		=> $filter_payment_company,
			'filter_payment_address' 		=> $filter_payment_address,
			'filter_payment_city' 			=> $filter_payment_city,
			'filter_payment_zone' 			=> $filter_payment_zone,			
			'filter_payment_postcode' 		=> $filter_payment_postcode,
			'filter_payment_country' 		=> $filter_payment_country,
			'start'        					=> 0,
			'limit'        					=> $limit
		);
						
		$results = $this->model_report_adv_customers->getCustomerAutocompleteAllRegistered($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'     		=> $result['customer_id'],				
					'cust_name'     		=> html_entity_decode($result['cust_name'], ENT_QUOTES, 'UTF-8'),
					'cust_email'     		=> $result['cust_email'],
					'cust_telephone'     	=> $result['cust_telephone'],
					'cust_ip'     			=> $result['cust_ip'],
					'payment_company'     	=> html_entity_decode($result['payment_company'], ENT_QUOTES, 'UTF-8'),	
					'payment_address'     	=> html_entity_decode($result['payment_address'], ENT_QUOTES, 'UTF-8'),	
					'payment_city'     		=> html_entity_decode($result['payment_city'], ENT_QUOTES, 'UTF-8'),	
					'payment_zone'     		=> html_entity_decode($result['payment_zone'], ENT_QUOTES, 'UTF-8'),						
					'payment_postcode'     	=> $result['payment_postcode'],
					'payment_country'     	=> html_entity_decode($result['payment_country'], ENT_QUOTES, 'UTF-8')		
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function product_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_sku']) or isset($this->request->get['filter_product_name']) or isset($this->request->get['filter_model'])) {
		
		$this->load->model('report/adv_customers');
					
		if (isset($this->request->get['filter_sku'])) {
			$filter_sku = $this->request->get['filter_sku'];
		} else {
			$filter_sku = '';
		}

		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = '';
		}
		
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(				
			'filter_sku' 	 				=> $filter_sku,
			'filter_product_name' 	 		=> $filter_product_name,
			'filter_model' 	 				=> $filter_model,
			'start'        					=> 0,
			'limit'        					=> $limit	
		);
						
		$results = $this->model_report_adv_customers->getProductAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'product_id'     		=> $result['product_id'],
					'prod_sku'     			=> html_entity_decode($result['prod_sku'], ENT_QUOTES, 'UTF-8'),					
					'prod_name'     		=> html_entity_decode($result['prod_name'], ENT_QUOTES, 'UTF-8'),
					'prod_model'     		=> html_entity_decode($result['prod_model'], ENT_QUOTES, 'UTF-8')				
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function coupon_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_coupon_code'])) {
			
		$this->load->model('report/adv_customers');

		if (isset($this->request->get['filter_coupon_code'])) {
			$filter_coupon_code = $this->request->get['filter_coupon_code'];
		} else {
			$filter_coupon_code = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_coupon_code' 	 		=> $filter_coupon_code,
			'start'        					=> 0,
			'limit'        					=> $limit			
		);
						
		$results = $this->model_report_adv_customers->getCouponAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'coupon_id'     		=> $result['coupon_id'],
					'coupon_code'     		=> html_entity_decode($result['coupon_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function voucher_autocomplete() {
		$json = array();

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_voucher_code'])) {
			
		$this->load->model('report/adv_customers');

		if (isset($this->request->get['filter_voucher_code'])) {
			$filter_voucher_code = $this->request->get['filter_voucher_code'];
		} else {
			$filter_voucher_code = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(		
			'filter_voucher_code' 	 		=> $filter_voucher_code,
			'start'        					=> 0,
			'limit'        					=> $limit
		);
						
		$results = $this->model_report_adv_customers->getVoucherAutocomplete($filter_data);
			
			foreach ($results as $result) {
				$json[] = array(
					'voucher_id'     		=> $result['voucher_id'],
					'voucher_code'     		=> html_entity_decode($result['voucher_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function settings($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_customers');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_customers')) {
				$json['error'] = $this->language->get('error_permission');
			} else {			
				$user = 'advco' . $this->user->getId();
				$this->model_setting_setting->editSetting($user, $this->request->post);
				$json['success'] = $this->language->get('text_success_settings');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	

	public function load_save_report($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_customers');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_customers')) {
				$json['error'] = $this->language->get('error_permission');
			} else {			
				if (isset($this->request->post['advco_load_save_report'])) {
					$this->request->post['advco' . $this->user->getId() . 'sr_load_save_report'] = serialize($this->request->post['advco_load_save_report']);
				}			
				$this->model_setting_setting->editSetting('advco' . $this->user->getId() . 'sr', $this->request->post);
				$json['success'] = $this->language->get('text_success_save_report');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cron($filter_data = array()) {
		$json = array();
		
		$this->load->model('setting/setting');
		$this->load->language('report/adv_customers');
		
		if (!$json) {
			if (!$this->user->hasPermission('modify', 'report/adv_customers')) {
				$json['error'] = $this->language->get('error_permission');
			} else {	
				if ($this->request->post['cron_export_type'] == '') {
					$json['error'] = $this->language->get('error_export_type');
				} else if ((utf8_strlen($this->request->post['cron_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['cron_email'])) {
					$json['error'] = $this->language->get('error_export_email');					
				} else if ($this->request->post['cron_file_name'] == '') {
					$json['error'] = $this->language->get('error_file_name');
				} else if ($this->request->post['cron_user'] == '') {
					$json['error'] = $this->language->get('error_admin_username');
				} else if ($this->request->post['cron_pass'] == '') {
					$json['error'] = $this->language->get('error_admin_password');
				} else if ($this->request->post['cron_token'] == '') {
					$json['error'] = $this->language->get('error_generate_token');
				} else {
					if (isset($this->request->post['advco_cron_setting'])) {
						$this->request->post['advco' . $this->user->getId() . 'cron_setting'] = serialize($this->request->post['advco_cron_setting']);
					}			
					$this->model_setting_setting->editSetting('advco' . $this->user->getId() . 'cron', $this->request->post);
					$json['success'] = $this->language->get('text_success_save_cron');
				}			
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function export($filter_data = array()) {
		$this->load->language('report/adv_customers');

		$export_data = array(
			'results'		=> $this->session->data['customers_data']
		);
		
		$user_id = $this->user->getId();

		if ($this->config->get('advco' . $user_id . '_settings_scw_columns')) {
			$advco_settings_scw_columns = $this->config->get('advco' . $user_id . '_settings_scw_columns');
		} else {
			$advco_settings_scw_columns = array();
		}
			
		if ($this->config->get('advco' . $user_id . '_settings_cwo_columns')) {
			$advco_settings_cwo_columns = $this->config->get('advco' . $user_id . '_settings_cwo_columns');
		} else {
			$advco_settings_cwo_columns = array();
		}
		
		if ($this->config->get('advco' . $user_id . '_settings_mv_columns')) {
			$advco_settings_mv_columns = $this->config->get('advco' . $user_id . '_settings_mv_columns');
		} else {
			$advco_settings_mv_columns = array();
		}	

		if ($this->config->get('advco' . $user_id . '_settings_ol_columns')) {
			$advco_settings_ol_columns = $this->config->get('advco' . $user_id . '_settings_ol_columns');
		} else {
			$advco_settings_ol_columns = array();
		}
		
		if ($this->config->get('advco' . $user_id . '_settings_pl_columns')) {
			$advco_settings_pl_columns = $this->config->get('advco' . $user_id . '_settings_pl_columns');
		} else {
			$advco_settings_pl_columns = array();
		}
		
		if ($this->config->get('advco' . $user_id . '_settings_cl_columns')) {
			$advco_settings_cl_columns = $this->config->get('advco' . $user_id . '_settings_cl_columns');
		} else {
			$advco_settings_cl_columns = array();
		}
		
		if ($this->config->get('advco' . $user_id . '_settings_all_columns')) {
			$advco_settings_all_columns = $this->config->get('advco' . $user_id . '_settings_all_columns');
		} else {
			$advco_settings_all_columns = array();
		}
		
		$this->session->data['report_type'] = $report_type = $this->request->get['report_type'];		
		$this->session->data['export_type'] = $export_type = $this->request->get['export_type'];
		$this->session->data['export_logo_criteria'] = $export_logo_criteria = $this->request->get['export_logo_criteria'];
		$this->session->data['export_csv_delimiter'] = $export_csv_delimiter = $this->request->get['export_csv_delimiter'];
			
		$filter_report = $this->session->data['filter_report'];
		$filter_details = $this->session->data['filter_details'];
		$filter_group = $this->session->data['filter_group'];
		$filter_sort = $this->session->data['filter_sort'];
		$filter_order = $this->session->data['filter_order'];
		$filter_limit = $this->session->data['filter_limit'];
		
		$filter_range = $this->session->data['filter_range'];
		$filter_date_start = $this->session->data['filter_date_start'];
		$filter_date_end = $this->session->data['filter_date_end'];
		$filter_order_status_id = $this->session->data['filter_order_status_id'];
		$filter_status_date_start = $this->session->data['filter_status_date_start'];
		$filter_status_date_end = $this->session->data['filter_status_date_end'];
		$filter_order_id_from = $this->session->data['filter_order_id_from'];
		$filter_order_id_to = $this->session->data['filter_order_id_to'];
		$filter_order_value_min = $this->session->data['filter_order_value_min'];
		$filter_order_value_max = $this->session->data['filter_order_value_max'];			
		
		$filter_store_id = $this->session->data['filter_store_id'];
		$filter_currency = $this->session->data['filter_currency'];
		$filter_taxes = $this->session->data['filter_taxes'];
		$filter_tax_classes = $this->session->data['filter_tax_classes'];
		$filter_geo_zones = $this->session->data['filter_geo_zones'];
		$filter_customer_group_id = $this->session->data['filter_customer_group_id'];
		$filter_customer_status = $this->session->data['filter_customer_status'];
		$filter_customer_name = $this->session->data['filter_customer_name'];
		$filter_customer_email = $this->session->data['filter_customer_email'];
		$filter_customer_telephone = $this->session->data['filter_customer_telephone'];
		$filter_ip = $this->session->data['filter_ip'];
		$filter_payment_company = $this->session->data['filter_payment_company'];
		$filter_payment_address = $this->session->data['filter_payment_address'];
		$filter_payment_city = $this->session->data['filter_payment_city'];
		$filter_payment_zone = $this->session->data['filter_payment_zone'];
		$filter_payment_postcode = $this->session->data['filter_payment_postcode'];
		$filter_payment_country = $this->session->data['filter_payment_country'];
		$filter_payment_method = $this->session->data['filter_payment_method'];
		$filter_shipping_company = $this->session->data['filter_shipping_company'];
		$filter_shipping_address = $this->session->data['filter_shipping_address'];
		$filter_shipping_city = $this->session->data['filter_shipping_city'];
		$filter_shipping_zone = $this->session->data['filter_shipping_zone'];
		$filter_shipping_postcode = $this->session->data['filter_shipping_postcode'];
		$filter_shipping_country = $this->session->data['filter_shipping_country'];
		$filter_shipping_method = $this->session->data['filter_shipping_method'];
		$filter_category = $this->session->data['filter_category'];
		$filter_manufacturer = $this->session->data['filter_manufacturer'];
		$filter_sku = $this->session->data['filter_sku'];
		$filter_product_name = $this->session->data['filter_product_name'];
		$filter_model = $this->session->data['filter_model'];
		$filter_option = $this->session->data['filter_option'];
		$filter_attribute = $this->session->data['filter_attribute'];
		$filter_location = $this->session->data['filter_location'];
		$filter_affiliate_name = $this->session->data['filter_affiliate_name'];
		$filter_affiliate_email = $this->session->data['filter_affiliate_email'];
		$filter_coupon_name = $this->session->data['filter_coupon_name'];
		$filter_coupon_code = $this->session->data['filter_coupon_code'];
		$filter_voucher_code = $this->session->data['filter_voucher_code'];
		
		if ($export_type == 'export_xlsx') {
			$logo = str_replace('\\', '/', DIR_IMAGE . $this->config->get('config_logo'));
		} else {
			$this->load->model('tool/image');
			$logo = $this->model_tool_image->resize($this->config->get('config_logo'), 268, 50);
		}
		
		unset($this->session->data['error_export_type']);
		
		if ($report_type == 'export_no_details' && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx.inc.php');
			exit();			
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_pdf') {
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_pdf.inc.php');
			exit();
		} elseif ($report_type == 'export_no_details' && $export_type == 'export_html') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_html.inc.php');
			exit();	
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls_basic_details.inc.php');
			exit();
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx_basic_details.inc.php');
			exit();	
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv_basic_details.inc.php');
			exit();				
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_pdf') {
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_pdf_basic_details.inc.php');	
			exit();
		} elseif ($report_type == 'export_basic_details' && $export_type == 'export_html') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_html_basic_details.inc.php');
			exit();	
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xls') {
			$cwd = getcwd();			
			chdir(DIR_SYSTEM . 'library/pear');
			require_once('Spreadsheet/Excel/Writer.php');
			chdir($cwd);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xls_all_details.inc.php');
			exit();
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_xlsx') {
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel.php');
			require_once(DIR_SYSTEM . 'library/PHPExcel/Classes/PHPExcel/IOFactory.php');			
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_xlsx_all_details.inc.php');
			exit();	
		} elseif (($report_type == 'export_all_details_products' or $report_type == 'export_all_details_orders') && $export_type == 'export_csv') {
			include(DIR_APPLICATION . 'controller/report/adv_reports/co_export_csv_all_details.inc.php');
			exit();			
		} else {
			exit();
		}	
	}
	
	public function export_validate () {
		$json = array();
		
		$this->load->language('report/adv_customers');
				
		if (!empty($this->session->data['customers_data'])) {
			if ($this->request->post['export_type'] == '') {
				$json['error'] = $this->language->get('error_export_type');
			}
		} else {
			$json['error'] = $this->language->get('error_no_data');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}		
}