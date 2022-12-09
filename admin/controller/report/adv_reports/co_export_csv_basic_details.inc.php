<?php
	ini_set("memory_limit","256M");
	
	$results = $export_data['results'];
	if ($results) {

	// Custom Fields
	$this->load->model('report/adv_customers');
	$filter_data = array(
		'sort'  => 'cf.sort_order',
		'order' => 'ASC',
	);
	$custom_fields_name = $this->model_report_adv_customers->getCustomFieldsNames($filter_data);
	
	$csv_delimiter = strtr($export_csv_delimiter, array(
		'comma'			=> ",",
		'semi'			=> ";",
		'tab'			=> "\t"
	));
	$csv_enclosed = '"';
	$csv_row = "\n";

	if ($filter_group == 'year') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_quarter') . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv = $csv_enclosed . $this->language->get('column_year') . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_month') . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv = $csv_enclosed . $this->language->get('column_date') . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv = $csv_enclosed . $this->language->get('column_order_order_id') . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_date_added') . $csv_enclosed;	
	} else {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;	
	}
	
	in_array('mv_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . (($filter_report == 'registered_and_guest_customers' or $filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id')) . $csv_enclosed : '';
	in_array('mv_customer', $advco_settings_mv_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer')." / ".$this->language->get('column_company') . $csv_enclosed : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_email') . $csv_enclosed : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_telephone') . $csv_enclosed : '';
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_group') . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field_name['name'] . $csv_enclosed : '';
    }
    }		
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_status') . $csv_enclosed : '';	
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_first_name') . $csv_enclosed : '';	
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_name') . $csv_enclosed : '';	
	in_array('mv_company', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_company') . $csv_enclosed : '';	
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_1') . $csv_enclosed : '';	
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_2') . $csv_enclosed : '';	
	in_array('mv_city', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_city') . $csv_enclosed : '';	
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed : '';	
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_id') . $csv_enclosed : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country') . $csv_enclosed : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_code') . $csv_enclosed : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_zone_id') . $csv_enclosed : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state_code') . $csv_enclosed : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_newsletter') . $csv_enclosed : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_approved') . $csv_enclosed : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_safe') . $csv_enclosed : '';
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_ip') . $csv_enclosed : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_logins') . $csv_enclosed : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_login') . $csv_enclosed : '';	
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_mostrecent') . $csv_enclosed : '';	
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	in_array('mv_products', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_products') . $csv_enclosed : '';
	in_array('mv_total', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total') . $csv_enclosed : '';
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_aov') . $csv_enclosed : '';
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_refunds') . $csv_enclosed : '';
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_reward_points') . $csv_enclosed : '';
	
	in_array('ol_order_order_id', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_order_id') . $csv_enclosed : '';
	in_array('ol_order_date_added', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_date_added') . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_inv_no') . $csv_enclosed : '';
	in_array('ol_order_customer', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer') . $csv_enclosed : '';
	in_array('ol_order_email', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_email') . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer_group') . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_shipping_method') . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_payment_method') . $csv_enclosed : '';
	in_array('ol_order_status', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_status') . $csv_enclosed : '';
	in_array('ol_order_store', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_store') . $csv_enclosed : '';
	in_array('ol_order_currency', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_currency') . $csv_enclosed : '';
	in_array('ol_order_quantity', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_quantity') . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_sub_total') . $csv_enclosed : '';
	in_array('ol_order_shipping', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_shipping') . $csv_enclosed : '';
	in_array('ol_order_tax', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_tax') . $csv_enclosed : '';
	in_array('ol_order_value', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_value') . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_cust_id') . $csv_enclosed : '';
	in_array('cl_billing_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_name')) . $csv_enclosed : '';
	in_array('cl_billing_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_company')) . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_address_1')) . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_address_2')) . $csv_enclosed : '';
	in_array('cl_billing_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_city')) . $csv_enclosed : '';
	in_array('cl_billing_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_zone')) . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_postcode')) . $csv_enclosed : '';
	in_array('cl_billing_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_country')) . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_telephone') . $csv_enclosed : '';
	in_array('cl_shipping_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_name')) . $csv_enclosed : '';
	in_array('cl_shipping_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_company')) . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_address_1')) . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_address_2')) . $csv_enclosed : '';
	in_array('cl_shipping_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_city')) . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_zone')) . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_postcode')) . $csv_enclosed : '';
	in_array('cl_shipping_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_country')) . $csv_enclosed : '';

	in_array('pl_prod_order_id', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_order_id') . $csv_enclosed : '';
	in_array('pl_prod_date_added', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_date_added') . $csv_enclosed : '';
	in_array('pl_prod_id', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_id') . $csv_enclosed : '';
	in_array('pl_prod_sku', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_sku') . $csv_enclosed : '';
	in_array('pl_prod_model', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_model') . $csv_enclosed : '';
	in_array('pl_prod_name', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_name') . $csv_enclosed : '';
	in_array('pl_prod_option', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_option') . $csv_enclosed : '';
	in_array('pl_prod_attributes', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_attributes') . $csv_enclosed : '';
	in_array('pl_prod_category', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_category') . $csv_enclosed : '';
	in_array('pl_prod_manu', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_manu') . $csv_enclosed : '';
	in_array('pl_prod_currency', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_currency') . $csv_enclosed : '';
	in_array('pl_prod_price', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_price') . $csv_enclosed : '';
	in_array('pl_prod_quantity', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_quantity') . $csv_enclosed : '';
	in_array('pl_prod_total_excl_vat', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_excl_vat') . $csv_enclosed : '';
	in_array('pl_prod_tax', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_tax') . $csv_enclosed : '';
	in_array('pl_prod_total_incl_vat', $advco_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_incl_vat') . $csv_enclosed : '';
	
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_group == 'year') {				
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . 'Q' . $result['quarter'] . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['month'] . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv .= $csv_enclosed . $result['order_id'] . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_start'] . $csv_enclosed;	
	} else {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;	
	}
	
	in_array('mv_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['customer_id'] > 0 ? $result['customer_id'] : $this->language->get('text_guest')) . $csv_enclosed : '';
	in_array('mv_customer', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name']) . $csv_enclosed : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_email'] . $csv_enclosed : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_telephone'] . $csv_enclosed : '';	
	if ($result['customer_id'] == 0) {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group_guest'] . $csv_enclosed : '';
	} else {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group_reg'] . $csv_enclosed : '';
	}
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field['value'] . $csv_enclosed : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	}	
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_status'] . $csv_enclosed : '';
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_first_name'] . $csv_enclosed : '';	
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_last_name'] . $csv_enclosed : '';	
	in_array('mv_company', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_company'] . $csv_enclosed : '';	
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_1'] . $csv_enclosed : '';	
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_2'] . $csv_enclosed : '';	
	in_array('mv_city', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_city'] . $csv_enclosed : '';	
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_postcode'] . $csv_enclosed : '';	
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_id'] . $csv_enclosed : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country'] . $csv_enclosed : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_code'] . $csv_enclosed : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_zone_id'] . $csv_enclosed : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state'] . $csv_enclosed : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state_code'] . $csv_enclosed : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['newsletter'] . $csv_enclosed : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['approved'] . $csv_enclosed : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['safe'] . $csv_enclosed : '';	
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['ip'] . $csv_enclosed : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_logins'] . $csv_enclosed : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['last_login'] . $csv_enclosed : '';	
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['mostrecent'] . $csv_enclosed : '';	
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['orders'] . $csv_enclosed : '';
	in_array('mv_products', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['products'] . $csv_enclosed : '';	
	in_array('mv_total', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_raw'], 2) . $csv_enclosed : '';
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['aov_raw'], 2) . $csv_enclosed : '';
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['refunds_raw'], 2) . $csv_enclosed : '';
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['reward_points'] . $csv_enclosed : '';	
	
	$order_ord_id = explode('<br>', $result['order_ord_id']);		
	
	foreach ($order_ord_id as $index => $value) {	

	if (in_array('ol_order_order_id', $advco_settings_ol_columns)) {		
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('ol_order_date_added', $advco_settings_ol_columns)) {	
	$order_ord_date = explode('<br>', $result['order_ord_date']);		
	$value = $order_ord_date[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('ol_order_inv_no', $advco_settings_ol_columns)) {	
	$order_inv_no = explode('<br>', $result['order_inv_no']);		
	$value = $order_inv_no[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_customer', $advco_settings_ol_columns)) {	
	$order_name = explode('<br>', $result['order_name']);		
	$value = $order_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		
	if (in_array('ol_order_email', $advco_settings_ol_columns)) {	
	$order_email = explode('<br>', $result['order_email']);		
	$value = $order_email[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_customer_group', $advco_settings_ol_columns)) {	
	$order_group = explode('<br>', $result['order_group']);		
	$value = $order_group[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_shipping_method', $advco_settings_ol_columns)) {	
	$order_shipping_method = explode('<br>', $result['order_shipping_method']);		
	$value = $order_shipping_method[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_payment_method', $advco_settings_ol_columns)) {	
	$order_payment_method = explode('<br>', $result['order_payment_method']);		
	$value = $order_payment_method[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_status', $advco_settings_ol_columns)) {	
	$order_status = explode('<br>', $result['order_status']);		
	$value = $order_status[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_store', $advco_settings_ol_columns)) {	
	$order_store = explode('<br>', $result['order_store']);		
	$value = $order_store[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_currency', $advco_settings_ol_columns)) {	
	$order_currency = explode('<br>', $result['order_currency']);		
	$value = $order_currency[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_quantity', $advco_settings_ol_columns)) {	
	$order_products = explode('<br>', $result['order_products']);		
	$value = $order_products[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_sub_total', $advco_settings_ol_columns)) {	
	$order_sub_total = explode('<br>', $result['order_sub_total']);		
	$value = $order_sub_total[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		
	if (in_array('ol_order_shipping', $advco_settings_ol_columns)) {	
	$order_shipping = explode('<br>', $result['order_shipping']);		
	$value = $order_shipping[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_tax', $advco_settings_ol_columns)) {	
	$order_tax = explode('<br>', $result['order_tax']);		
	$value = $order_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_value', $advco_settings_ol_columns)) {	
	$order_value = explode('<br>', $result['order_value']);		
	$value = $order_value[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		

	if (in_array('cl_customer_cust_id', $advco_settings_cl_columns)) {	
	$customer_cust_id = explode('<br>', $result['customer_cust_id']);		
	$value = $customer_cust_id[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('cl_billing_name', $advco_settings_cl_columns)) {	
	$billing_name = explode('<br>', $result['billing_name']);		
	$value = $billing_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_billing_company', $advco_settings_cl_columns)) {	
	$billing_company = explode('<br>', $result['billing_company']);		
	$value = $billing_company[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_address_1', $advco_settings_cl_columns)) {	
	$billing_address_1 = explode('<br>', $result['billing_address_1']);		
	$value = $billing_address_1[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}		
	if (in_array('cl_billing_address_2', $advco_settings_cl_columns)) {	
	$billing_address_2 = explode('<br>', $result['billing_address_2']);		
	$value = $billing_address_2[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_city', $advco_settings_cl_columns)) {	
	$billing_city = explode('<br>', $result['billing_city']);		
	$value = $billing_city[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_zone', $advco_settings_cl_columns)) {	
	$billing_zone = explode('<br>', $result['billing_zone']);		
	$value = $billing_zone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_postcode', $advco_settings_cl_columns)) {	
	$billing_postcode = explode('<br>', $result['billing_postcode']);		
	$value = $billing_postcode[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_country', $advco_settings_cl_columns)) {	
	$billing_country = explode('<br>', $result['billing_country']);		
	$value = $billing_country[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_customer_telephone', $advco_settings_cl_columns)) {	
	$customer_telephone = explode('<br>', $result['customer_telephone']);		
	$value = $customer_telephone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_name', $advco_settings_cl_columns)) {	
	$shipping_name = explode('<br>', $result['shipping_name']);		
	$value = $shipping_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_company', $advco_settings_cl_columns)) {	
	$shipping_company = explode('<br>', $result['shipping_company']);		
	$value = $shipping_company[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_address_1', $advco_settings_cl_columns)) {	
	$shipping_address_1 = explode('<br>', $result['shipping_address_1']);		
	$value = $shipping_address_1[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_address_2', $advco_settings_cl_columns)) {	
	$shipping_address_2 = explode('<br>', $result['shipping_address_2']);		
	$value = $shipping_address_2[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_city', $advco_settings_cl_columns)) {	
	$shipping_city = explode('<br>', $result['shipping_city']);		
	$value = $shipping_city[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_zone', $advco_settings_cl_columns)) {	
	$shipping_zone = explode('<br>', $result['shipping_zone']);		
	$value = $shipping_zone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_shipping_postcode', $advco_settings_cl_columns)) {	
	$shipping_postcode = explode('<br>', $result['shipping_postcode']);		
	$value = $shipping_postcode[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_shipping_country', $advco_settings_cl_columns)) {	
	$shipping_country = explode('<br>', $result['shipping_country']);		
	$value = $shipping_country[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	
	$export_csv .= $csv_row;		
	if ($filter_group == 'year') {				
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	} else {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	}
	in_array('mv_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customer', $advco_settings_mv_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    }
    }		
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_company', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_city', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_products', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	}

	in_array('ol_order_order_id', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_date_added', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_email', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_status', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_store', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_currency', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_quantity', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_tax', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_value', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	$product_ord_id = explode('<br>', $result['product_ord_id']);		
	
	foreach ($product_ord_id as $index => $value) {	

	if (in_array('pl_prod_order_id', $advco_settings_pl_columns)) {	
	$product_ord_id = explode('<br>', $result['product_ord_id']);		
	$value = $product_ord_id[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('pl_prod_date_added', $advco_settings_pl_columns)) {	
	$product_ord_date = explode('<br>', $result['product_ord_date']);		
	$value = $product_ord_date[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('pl_prod_id', $advco_settings_pl_columns)) {	
	$product_pid = explode('<br>', $result['product_pid']);		
	$value = $product_pid[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_sku', $advco_settings_pl_columns)) {	
	$product_sku = explode('<br>', $result['product_sku']);		
	$value = $product_sku[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}		
	if (in_array('pl_prod_model', $advco_settings_pl_columns)) {	
	$product_model = explode('<br>', $result['product_model']);		
	$value = $product_model[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_name', $advco_settings_pl_columns)) {	
	$product_name = explode('<br>', $result['product_name']);		
	$value = $product_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($value, ENT_NOQUOTES, 'UTF-8') . $csv_enclosed;		
	}	
	if (in_array('pl_prod_option', $advco_settings_pl_columns)) {	
	$product_option = explode('<br>', $result['product_option']);		
	$value = $product_option[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}	
	if (in_array('pl_prod_attributes', $advco_settings_pl_columns)) {	
	$product_attributes = explode('<br>', $result['product_attributes']);		
	$value = $product_attributes[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}		
	if (in_array('pl_prod_category', $advco_settings_pl_columns)) {	
	$product_category = explode('<br>', $result['product_category']);		
	$value = $product_category[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}
	if (in_array('pl_prod_manu', $advco_settings_pl_columns)) {	
	$product_manu = explode('<br>', $result['product_manu']);		
	$value = $product_manu[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}		
	if (in_array('pl_prod_currency', $advco_settings_pl_columns)) {	
	$product_currency = explode('<br>', $result['product_currency']);		
	$value = $product_currency[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_price', $advco_settings_pl_columns)) {	
	$product_price = explode('<br>', $result['product_price']);		
	$value = $product_price[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_quantity', $advco_settings_pl_columns)) {	
	$product_quantity = explode('<br>', $result['product_quantity']);		
	$value = $product_quantity[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_total_excl_vat', $advco_settings_pl_columns)) {	
	$product_total_excl_vat = explode('<br>', $result['product_total_excl_vat']);		
	$value = $product_total_excl_vat[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_tax', $advco_settings_pl_columns)) {	
	$product_tax = explode('<br>', $result['product_tax']);		
	$value = $product_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_total_incl_vat', $advco_settings_pl_columns)) {	
	$product_total_incl_vat = explode('<br>', $result['product_total_incl_vat']);		
	$value = $product_total_incl_vat[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	

	$export_csv .= $csv_row;		
	if ($filter_group == 'year') {				
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;			
	} elseif ($filter_group == 'month') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;			
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	} elseif ($filter_group == 'day') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_group == 'order') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	} else {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	}
	in_array('mv_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customer', $advco_settings_mv_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    }
    }		
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_company', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_city', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_products', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';

	in_array('ol_order_order_id', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_date_added', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_email', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_status', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_store', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_currency', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_quantity', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_tax', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_value', $advco_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_name', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_company', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_city', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_country', $advco_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	}
	
	$export_csv .= $csv_row;
	}

	if (!isset($_GET['cron'])) {
		$filename = "customers_report_basic_details_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
		header('Content-Disposition: attachment; filename='.$filename.".csv");
		print chr(255) . chr(254) . mb_convert_encoding($export_csv, 'UTF-16LE', 'UTF-8');			
	} else if (isset($_GET['cron'])) {
		$file_path_parts = explode('/', $file_save_path);
		$file_path = dirname(DIR_APPLICATION);
		foreach ($file_path_parts as $file_path_part) {
			$file_path .= '/' . $file_path_part;
			if (!file_exists($file_path)) {
				mkdir($file_path, 0755);
				if (file_exists(DIR_DOWNLOAD . 'index.html')) {
					copy(DIR_DOWNLOAD  . 'index.html', $file_path . DIRECTORY_SEPARATOR . 'index.html');
				}
			}
		}
		
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
		
		$filename = $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";		
		
		file_put_contents($file, $export_csv);
		
		$message  = '<html dir="ltr" lang="en">' . "\n";
		$message .= '  <head>' . "\n";
		$message .= '    <title>' . $this->language->get('text_email_subject') . '</title>' . "\n";
		$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$message .= '  </head>' . "\n";
		$message .= '  <body>' . "\n";
		if ($export_file == 'save_on_server') {			
			$message .= '<p>' . $this->language->get('text_email_message_save_file') . ' <a href="' . $file_to_download . '">' . $filename . '</a>.</p><br />' . "\n";
		} else if ($export_file == 'send_to_email') {
			$message .= '<p>' . $this->language->get('text_email_message_send_file') . '</p><br />' . "\n";
		}
		$message .= '<p><b>' . $this->config->get('config_name') . '</b><br />' . "\n";
		$message .= $this->config->get('config_address') . '</p>' . "\n";			
		$message .= '</body>' . "\n";
		$message .= '</html>' . "\n";

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($this->language->get('text_email_subject'));
		if ($export_file == 'save_on_server') {			
			$mail->setHtml($message);
		} else if ($export_file == 'send_to_email') {
		$mail->setHtml($message);
		$mail->addAttachment($file);
		}			
		$mail->send();
	}			
	exit;
	}
?>