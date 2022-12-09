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

	if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {
	$export_csv = $csv_enclosed . $this->language->get('column_date_added') . $csv_enclosed;
	} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;		
	} else {
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
	}
	
	if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {

	in_array('scw_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_id') . $csv_enclosed : '';
	in_array('scw_customer', $advco_settings_scw_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer')." / ".$this->language->get('column_company') . $csv_enclosed : '';
	in_array('scw_email', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_email') . $csv_enclosed : '';
	in_array('scw_telephone', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_telephone') . $csv_enclosed : '';
	in_array('scw_customer_group', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_group') . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field_name['name'] . $csv_enclosed : '';
    }
    }
	in_array('scw_customer_status', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_status') . $csv_enclosed : '';	
	in_array('scw_first_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_first_name') . $csv_enclosed : '';
	in_array('scw_last_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_name') . $csv_enclosed : '';
	in_array('scw_company', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_company') . $csv_enclosed : '';	
	in_array('scw_address_1', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_1') . $csv_enclosed : '';	
	in_array('scw_address_2', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_2') . $csv_enclosed : '';	
	in_array('scw_city', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_city') . $csv_enclosed : '';	
	in_array('scw_postcode', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed : '';	
	in_array('scw_country_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_id') . $csv_enclosed : '';	
	in_array('scw_country', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country') . $csv_enclosed : '';	
	in_array('scw_country_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_code') . $csv_enclosed : '';
	in_array('scw_zone_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_zone_id') . $csv_enclosed : '';
	in_array('scw_region_state', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed : '';
	in_array('scw_region_state_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state_code') . $csv_enclosed : '';
	in_array('scw_newsletter', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_newsletter') . $csv_enclosed : '';
	in_array('scw_approved', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_approved') . $csv_enclosed : '';
	in_array('scw_safe', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_safe') . $csv_enclosed : '';
	in_array('scw_ip', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_ip') . $csv_enclosed : '';
	in_array('scw_total_logins', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_logins') . $csv_enclosed : '';
	in_array('scw_last_login', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_login') . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cart_quantity') . $csv_enclosed : '';
	in_array('scw_cart_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cart_value') . $csv_enclosed : '';
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_wishlist_quantity') . $csv_enclosed : '';
	in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_wishlist_value') . $csv_enclosed : '';
	}
	in_array('scw_product_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_id') . $csv_enclosed : '';
	in_array('scw_date_added', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_date_added') . $csv_enclosed : '';
	in_array('scw_sku', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_sku') . $csv_enclosed : '';
	in_array('scw_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_name') . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_options', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_option') . $csv_enclosed : '';
	}
	in_array('scw_model', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_model') . $csv_enclosed : '';
	in_array('scw_category', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_category') . $csv_enclosed : '';
	in_array('scw_manufacturer', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_manu') . $csv_enclosed : '';
	in_array('scw_attribute', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_attributes') . $csv_enclosed : '';
	in_array('scw_price', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_price') . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_cart_quantity') . $csv_enclosed : '';
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_wishlist_quantity') . $csv_enclosed : '';
	}

	} elseif ($filter_report == 'registered_customers_without_orders') {

	in_array('cwo_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_id') . $csv_enclosed : '';
	in_array('cwo_customer', $advco_settings_cwo_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer')." / ".$this->language->get('column_company') . $csv_enclosed : '';
	in_array('cwo_email', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_email') . $csv_enclosed : '';
	in_array('cwo_telephone', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_telephone') . $csv_enclosed : '';
	in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_group') . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field_name['name'] . $csv_enclosed : '';
    }
    }	
	in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_status') . $csv_enclosed : '';	
	in_array('cwo_first_name', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_first_name') . $csv_enclosed : '';	
	in_array('cwo_last_name', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_name') . $csv_enclosed : '';	
	in_array('cwo_company', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_company') . $csv_enclosed : '';	
	in_array('cwo_address_1', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_1') . $csv_enclosed : '';	
	in_array('cwo_address_2', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_address_2') . $csv_enclosed : '';	
	in_array('cwo_city', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_city') . $csv_enclosed : '';	
	in_array('cwo_postcode', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed : '';	
	in_array('cwo_country_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_id') . $csv_enclosed : '';
	in_array('cwo_country', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country') . $csv_enclosed : '';
	in_array('cwo_country_code', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_country_code') . $csv_enclosed : '';
	in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_zone_id') . $csv_enclosed : '';
	in_array('cwo_region_state', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed : '';
	in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_region_state_code') . $csv_enclosed : '';
	in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_newsletter') . $csv_enclosed : '';
	in_array('cwo_approved', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_approved') . $csv_enclosed : '';
	in_array('cwo_safe', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_safe') . $csv_enclosed : '';
	in_array('cwo_ip', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_ip') . $csv_enclosed : '';
	in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total_logins') . $csv_enclosed : '';
	in_array('cwo_last_login', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_last_login') . $csv_enclosed : '';
	
	} else {
		
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
	}
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {
	$export_csv .= $csv_enclosed . $result['date_added'] . $csv_enclosed;
	} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;
	} else {
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
	}	
	
	if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {

	in_array('scw_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['customer_id'] . $csv_enclosed : '';
	in_array('scw_customer', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name']) . $csv_enclosed : '';
	in_array('scw_email', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_email'] . $csv_enclosed : '';
	in_array('scw_telephone', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_telephone'] . $csv_enclosed : '';
	in_array('scw_customer_group', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group'] . $csv_enclosed : '';
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field['value'] . $csv_enclosed : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	}	
	in_array('scw_customer_status', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_status'] . $csv_enclosed : '';
	in_array('scw_first_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_first_name'] . $csv_enclosed : '';	
	in_array('scw_last_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_last_name'] . $csv_enclosed : '';	
	in_array('scw_company', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_company'] . $csv_enclosed : '';	
	in_array('scw_address_1', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_1'] . $csv_enclosed : '';	
	in_array('scw_address_2', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_2'] . $csv_enclosed : '';	
	in_array('scw_city', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_city'] . $csv_enclosed : '';	
	in_array('scw_postcode', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_postcode'] . $csv_enclosed : '';	
	in_array('scw_country_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_id'] . $csv_enclosed : '';
	in_array('scw_country', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country'] . $csv_enclosed : '';
	in_array('scw_country_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_code'] . $csv_enclosed : '';
	in_array('scw_zone_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_zone_id'] . $csv_enclosed : '';
	in_array('scw_region_state', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state'] . $csv_enclosed : '';
	in_array('scw_region_state_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state_code'] . $csv_enclosed : '';
	in_array('scw_newsletter', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['newsletter'] . $csv_enclosed : '';
	in_array('scw_approved', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['approved'] . $csv_enclosed : '';
	in_array('scw_safe', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['safe'] . $csv_enclosed : '';	
	in_array('scw_ip', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['ip'] . $csv_enclosed : '';
	in_array('scw_total_logins', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_logins'] . $csv_enclosed : '';
	in_array('scw_last_login', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['last_login'] . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cart_quantity'] . $csv_enclosed : '';	
	in_array('scw_cart_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cart_value_raw'] . $csv_enclosed : '';	
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['wishlist_quantity'] . $csv_enclosed : '';	
	in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['wishlist_value_raw'] . $csv_enclosed : '';	
	}	
	if ($result['product']) {	
	foreach ($result['product'] as $product) {
	in_array('scw_product_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['product_id'] . $csv_enclosed : '';
	in_array('scw_date_added', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['date_added'] . $csv_enclosed : '';
	in_array('scw_sku', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['sku'] . $csv_enclosed : '';
	in_array('scw_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['name'] . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	if ($product['option']) {
	$resultstr = array();
	foreach ($product['option'] as $option) {
	$resultstr[] = $option['name'].': '.$option['value'];
	}
	$result_options = implode("; ",$resultstr);
	in_array('scw_options', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result_options . $csv_enclosed : '';
	} else {
	in_array('scw_options', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	}
	in_array('scw_model', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['model'] . $csv_enclosed : '';
	in_array('scw_category', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($product['category']) . $csv_enclosed : '';
	in_array('scw_manufacturer', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($product['manufacturer']) . $csv_enclosed : '';
	in_array('scw_attribute', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($product['attribute']) . $csv_enclosed : '';
	in_array('scw_price', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($product['price_raw'], 2) . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['cart_quantity'] . $csv_enclosed : '';	
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $product['wishlist_quantity'] . $csv_enclosed : '';
	}		
	$export_csv .= $csv_row;
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;	
	in_array('scw_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_customer', $advco_settings_scw_columns) ?  $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_email', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_telephone', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_customer_group', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
    }
    }
	in_array('scw_customer_status', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_first_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_last_name', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_company', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_address_1', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_address_2', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_city', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_postcode', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_country_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_country', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('scw_country_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_zone_id', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_region_state', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_region_state_code', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_newsletter', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_approved', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_safe', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_ip', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_total_logins', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_last_login', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_cart_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}	
	}
	}
	
	} elseif ($filter_report == 'registered_customers_without_orders') {

	in_array('cwo_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['customer_id'] . $csv_enclosed : '';
	in_array('cwo_customer', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name']) . $csv_enclosed : '';
	in_array('cwo_email', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_email'] . $csv_enclosed : '';
	in_array('cwo_telephone', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_telephone'] . $csv_enclosed : '';
	in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group'] . $csv_enclosed : '';
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $custom_field['value'] . $csv_enclosed : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	}	
	in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_status'] . $csv_enclosed : '';
	in_array('cwo_first_name', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_first_name'] . $csv_enclosed : '';	
	in_array('cwo_last_name', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_last_name'] . $csv_enclosed : '';	
	in_array('cwo_company', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_company'] . $csv_enclosed : '';	
	in_array('cwo_address_1', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_1'] . $csv_enclosed : '';	
	in_array('cwo_address_2', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_address_2'] . $csv_enclosed : '';	
	in_array('cwo_city', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_city'] . $csv_enclosed : '';	
	in_array('cwo_postcode', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_postcode'] . $csv_enclosed : '';	
	in_array('cwo_country_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_id'] . $csv_enclosed : '';
	in_array('cwo_country', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country'] . $csv_enclosed : '';
	in_array('cwo_country_code', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_country_code'] . $csv_enclosed : '';
	in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_zone_id'] . $csv_enclosed : '';
	in_array('cwo_region_state', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state'] . $csv_enclosed : '';
	in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_region_state_code'] . $csv_enclosed : '';
	in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['newsletter'] . $csv_enclosed : '';
	in_array('cwo_approved', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['approved'] . $csv_enclosed : '';
	in_array('cwo_safe', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['safe'] . $csv_enclosed : '';	
	in_array('cwo_ip', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['ip'] . $csv_enclosed : '';
	in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['total_logins'] . $csv_enclosed : '';
	in_array('cwo_last_login', $advco_settings_cwo_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['last_login'] . $csv_enclosed : '';
	
	} else {
		
	in_array('mv_id', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['customer_id'] > 0 ? $result['customer_id'] : $this->language->get('text_guest')) . $csv_enclosed : '';
	in_array('mv_customer', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name']) . $csv_enclosed : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_email'] . $csv_enclosed : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_telephone'] . $csv_enclosed : '';
	if ($filter_report == 'all_registered_customers_with_without_orders') {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group'] . $csv_enclosed : '';
	} else {	
	if ($result['customer_id'] == 0) {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group_guest'] . $csv_enclosed : '';
	} else {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['cust_group_reg'] . $csv_enclosed : '';
	}
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
	}
	$export_csv .= $csv_row;
	}

	if (!isset($_GET['cron'])) {
		$filename = "customers_report_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
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