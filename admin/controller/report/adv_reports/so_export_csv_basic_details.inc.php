<?php
	ini_set("memory_limit","256M");
	
	$results = $export_data['results'];
	if ($results) {
	
	$csv_delimiter = strtr($export_csv_delimiter, array(
		'comma'			=> ",",
		'semi'			=> ";",
		'tab'			=> "\t"
	));
	$csv_enclosed = '"';
	$csv_row = "\n";

	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders') {
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
	} elseif ($filter_report == 'day_of_week') {
	$export_csv = $csv_enclosed . $this->language->get('column_day_of_week') . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv = $csv_enclosed . $this->language->get('column_hour') . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv = $csv_enclosed . $this->language->get('column_store') . $csv_enclosed;
	} elseif ($filter_report == 'currency') {
	$export_csv = $csv_enclosed . $this->language->get('column_currency') . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv = $csv_enclosed . $this->language->get('column_customer_group') . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv = $csv_enclosed . $this->language->get('column_country') . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv = $csv_enclosed . $this->language->get('column_postcode') . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv = $csv_enclosed . $this->language->get('column_region_state') . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv = $csv_enclosed . $this->language->get('column_city') . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv = $csv_enclosed . $this->language->get('column_payment_method') . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv = $csv_enclosed . $this->language->get('column_shipping_method') . $csv_enclosed;	
	} elseif ($filter_report == 'coupon') {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;
	} elseif ($filter_report == 'voucher') {
	$export_csv = $csv_enclosed . $this->language->get('column_date_start') . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_date_end') . $csv_enclosed;
	}
	
	if ($filter_report == 'coupon') {
		
	in_array('cr_coupon_name', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_name') . $csv_enclosed : '';
	in_array('cr_coupon_code', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_code') . $csv_enclosed : '';
	in_array('cr_coupon_discount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_discount') . $csv_enclosed : '';
	in_array('cr_coupon_type', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_type') . $csv_enclosed : '';
	in_array('cr_coupon_status', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_status') . $csv_enclosed : '';
	in_array('cr_coupon_date_added', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_date_added') . $csv_enclosed : '';
	in_array('cr_coupon_last_used', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_last_used') . $csv_enclosed : '';
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon_amount') . $csv_enclosed : '';
	in_array('cr_coupon_orders', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	in_array('cr_coupon_total', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total') . $csv_enclosed : '';
	
	} elseif ($filter_report == 'voucher') {
		
	in_array('vr_voucher_code', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_code') . $csv_enclosed : '';
	in_array('vr_voucher_from_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_from_name') . $csv_enclosed : '';
	in_array('vr_voucher_from_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_from_email') . $csv_enclosed : '';
	in_array('vr_voucher_to_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_to_name') . $csv_enclosed : '';
	in_array('vr_voucher_to_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_to_email') . $csv_enclosed : '';
	in_array('vr_voucher_theme', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_theme') . $csv_enclosed : '';
	in_array('vr_voucher_status', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_status') . $csv_enclosed : '';
	in_array('vr_voucher_date_added', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_date_added') . $csv_enclosed : '';
	in_array('vr_voucher_amount', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_amount') . $csv_enclosed : '';	
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_used_value') . $csv_enclosed : '';	
	in_array('vr_voucher_remaining_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher_remaining_value') . $csv_enclosed : '';	
	in_array('vr_voucher_orders', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	in_array('vr_voucher_total', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total') . $csv_enclosed : '';
	
	} else {
		
	in_array('mv_orders', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	in_array('mv_customers', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customers') . $csv_enclosed : '';
	in_array('mv_products', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_products') . $csv_enclosed : '';
	in_array('mv_sub_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_sub_total') . $csv_enclosed : '';
	in_array('mv_handling', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_handling') . $csv_enclosed : '';
	in_array('mv_loworder', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_loworder') . $csv_enclosed : '';
	in_array('mv_shipping', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_shipping') . $csv_enclosed : '';	
	in_array('mv_reward', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_reward') . $csv_enclosed : '';
	in_array('mv_earned_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_earned_reward_points') . $csv_enclosed : '';
	in_array('mv_used_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_used_reward_points') . $csv_enclosed : '';
	in_array('mv_coupon', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon') . $csv_enclosed : '';
	in_array('mv_tax', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_taxes') . $csv_enclosed : '';
	in_array('mv_credit', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_credit') . $csv_enclosed : '';
	in_array('mv_voucher', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher') . $csv_enclosed : '';
	in_array('mv_commission', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_commission') . $csv_enclosed : '';
	in_array('mv_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_total') . $csv_enclosed : '';
	in_array('mv_aov', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_aov') . $csv_enclosed : '';
	in_array('mv_refunds', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_refunds') . $csv_enclosed : '';
	}	
	
	in_array('ol_order_order_id', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_order_id') . $csv_enclosed : '';
	in_array('ol_order_date_added', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_date_added') . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_inv_no') . $csv_enclosed : '';
	in_array('ol_order_customer', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer') . $csv_enclosed : '';
	in_array('ol_order_email', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_email') . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_customer_group') . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_shipping_method') . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_payment_method') . $csv_enclosed : '';
	in_array('ol_order_status', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_status') . $csv_enclosed : '';
	in_array('ol_order_store', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_store') . $csv_enclosed : '';
	in_array('ol_order_currency', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_currency') . $csv_enclosed : '';
	in_array('ol_order_quantity', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_quantity') . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_sub_total') . $csv_enclosed : '';
	in_array('ol_order_shipping', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_shipping') . $csv_enclosed : '';
	in_array('ol_order_tax', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_tax') . $csv_enclosed : '';
	if ($filter_report == 'coupon') {
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_coupon') . $csv_enclosed : '';
	} elseif ($filter_report == 'voucher') {
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_voucher') . $csv_enclosed : '';
	}	
	in_array('ol_order_value', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_order_value') . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_cust_id') . $csv_enclosed : '';
	in_array('cl_billing_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_name')) . $csv_enclosed : '';
	in_array('cl_billing_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_company')) . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_address_1')) . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_address_2')) . $csv_enclosed : '';
	in_array('cl_billing_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_city')) . $csv_enclosed : '';
	in_array('cl_billing_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_zone')) . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_postcode')) . $csv_enclosed : '';
	in_array('cl_billing_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_billing_country')) . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_customer_telephone') . $csv_enclosed : '';
	in_array('cl_shipping_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_name')) . $csv_enclosed : '';
	in_array('cl_shipping_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_company')) . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_address_1')) . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_address_2')) . $csv_enclosed : '';
	in_array('cl_shipping_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_city')) . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_zone')) . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_postcode')) . $csv_enclosed : '';
	in_array('cl_shipping_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . strip_tags($this->language->get('column_shipping_country')) . $csv_enclosed : '';

	in_array('pl_prod_order_id', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_order_id') . $csv_enclosed : '';
	in_array('pl_prod_date_added', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_date_added') . $csv_enclosed : '';
	in_array('pl_prod_id', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_id') . $csv_enclosed : '';
	in_array('pl_prod_sku', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_sku') . $csv_enclosed : '';
	in_array('pl_prod_model', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_model') . $csv_enclosed : '';
	in_array('pl_prod_name', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_name') . $csv_enclosed : '';
	in_array('pl_prod_option', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_option') . $csv_enclosed : '';
	in_array('pl_prod_attributes', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_attributes') . $csv_enclosed : '';
	in_array('pl_prod_category', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_category') . $csv_enclosed : '';
	in_array('pl_prod_manu', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_manu') . $csv_enclosed : '';
	in_array('pl_prod_currency', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_currency') . $csv_enclosed : '';
	in_array('pl_prod_price', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_price') . $csv_enclosed : '';
	in_array('pl_prod_quantity', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_quantity') . $csv_enclosed : '';
	in_array('pl_prod_total_excl_vat', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_excl_vat') . $csv_enclosed : '';
	in_array('pl_prod_tax', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_tax') . $csv_enclosed : '';
	in_array('pl_prod_total_incl_vat', $advso_settings_pl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_prod_total_incl_vat') . $csv_enclosed : '';
	
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders') {
	if ($filter_group == 'year') {				
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;
	} elseif ($filter_group == 'quarter') {
	$export_csv .= $csv_enclosed . $result['year'] . $csv_enclosed;				
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['quarter'] . $csv_enclosed;			
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
	} elseif ($filter_report == 'day_of_week') {
	$export_csv .= $csv_enclosed . $result['day_of_week'] . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv .= $csv_enclosed . $result['hour'] . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv .= $csv_enclosed . $result['store_name'] . $csv_enclosed;
	} elseif ($filter_report == 'currency') {
	$export_csv .= $csv_enclosed . $result['currency_code'] . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv .= $csv_enclosed . $result['customer_group'] . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv .= $csv_enclosed . $result['payment_country'] . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv .= $csv_enclosed . $result['payment_postcode'] . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv .= $csv_enclosed . $result['payment_zone'] . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv .= $csv_enclosed . $result['payment_city'] . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv .= $csv_enclosed . $result['payment_method'] . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv .= $csv_enclosed . $result['shipping_method'] . $csv_enclosed;
	} elseif ($filter_report == 'coupon') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;
	} elseif ($filter_report == 'voucher') {
	$export_csv .= $csv_enclosed . $result['date_start'] . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . $result['date_end'] . $csv_enclosed;
	}
	
	if ($filter_report == 'coupon') {
		
	in_array('cr_coupon_name', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_name'] . $csv_enclosed : '';
	in_array('cr_coupon_code', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_code'] . $csv_enclosed : '';
	in_array('cr_coupon_discount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_discount'] . $csv_enclosed : '';
	in_array('cr_coupon_type', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_type'] . $csv_enclosed : '';
	in_array('cr_coupon_status', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_status'] . $csv_enclosed : '';
	in_array('cr_coupon_date_added', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_date_added'] . $csv_enclosed : '';
	in_array('cr_coupon_last_used', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_last_used'] . $csv_enclosed : '';
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['coupon_amount_raw'], 2) . $csv_enclosed : '';
	in_array('cr_coupon_orders', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['coupon_orders'] . $csv_enclosed : '';
	in_array('cr_coupon_total', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['coupon_total_raw'], 2) . $csv_enclosed : '';
	
	} elseif ($filter_report == 'voucher') {
		
	in_array('vr_voucher_code', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_code'] . $csv_enclosed : '';
	in_array('vr_voucher_from_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_from_name'] . $csv_enclosed : '';
	in_array('vr_voucher_from_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_from_email'] . $csv_enclosed : '';
	in_array('vr_voucher_to_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_to_name'] . $csv_enclosed : '';
	in_array('vr_voucher_to_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_to_email'] . $csv_enclosed : '';
	in_array('vr_voucher_theme', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_theme'] . $csv_enclosed : '';
	in_array('vr_voucher_status', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_status'] . $csv_enclosed : '';
	in_array('vr_voucher_date_added', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_date_added'] . $csv_enclosed : '';
	in_array('vr_voucher_amount', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['voucher_amount_raw'], 2) . $csv_enclosed : '';	
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['voucher_used_value_raw'], 2) . $csv_enclosed : '';	
	in_array('vr_voucher_remaining_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['voucher_remaining_value_raw'], 2) . $csv_enclosed : '';	
	in_array('vr_voucher_orders', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['voucher_orders'] . $csv_enclosed : '';
	in_array('vr_voucher_total', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['voucher_total_raw'], 2) . $csv_enclosed : '';
	
	} else {
		
	in_array('mv_orders', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['orders'] . $csv_enclosed : '';
	in_array('mv_customers', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['customers'] . $csv_enclosed : '';
	in_array('mv_products', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['products'] . $csv_enclosed : '';
	in_array('mv_sub_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['sub_total_raw'], 2) . $csv_enclosed : '';
	in_array('mv_handling', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['handling_raw'], 2) . $csv_enclosed : '';
	in_array('mv_loworder', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['low_order_fee_raw'], 2) . $csv_enclosed : '';
	in_array('mv_shipping', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['shipping_raw'], 2) . $csv_enclosed : '';
	in_array('mv_reward', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['reward_raw'], 2) . $csv_enclosed : '';
	in_array('mv_earned_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['earned_reward_points'] . $csv_enclosed : '';
	in_array('mv_used_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['used_reward_points'] . $csv_enclosed : '';	
	in_array('mv_coupon', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['coupon_raw'], 2) . $csv_enclosed : '';
	in_array('mv_tax', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['taxes_raw'], 2) . $csv_enclosed : '';
	in_array('mv_credit', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['credit_raw'], 2) . $csv_enclosed : '';
	in_array('mv_voucher', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['voucher_raw'], 2) . $csv_enclosed : '';
	in_array('mv_commission', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(-$result['commission_raw'], 2) . $csv_enclosed : '';
	in_array('mv_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_raw'], 2) . $csv_enclosed : '';
	in_array('mv_aov', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['aov_raw'], 2) . $csv_enclosed : '';
	in_array('mv_refunds', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round(($result['refunds_raw'] != NULL ? $result['refunds_raw'] : '0.0000'), 2) . $csv_enclosed : '';
	}
	
	$order_ord_id = explode('<br>', $result['order_ord_id']);		
	
	foreach ($order_ord_id as $index => $value) {	

	if (in_array('ol_order_order_id', $advso_settings_ol_columns)) {		
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('ol_order_date_added', $advso_settings_ol_columns)) {	
	$order_ord_date = explode('<br>', $result['order_ord_date']);		
	$value = $order_ord_date[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('ol_order_inv_no', $advso_settings_ol_columns)) {	
	$order_inv_no = explode('<br>', $result['order_inv_no']);		
	$value = $order_inv_no[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_customer', $advso_settings_ol_columns)) {	
	$order_name = explode('<br>', $result['order_name']);		
	$value = $order_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		
	if (in_array('ol_order_email', $advso_settings_ol_columns)) {	
	$order_email = explode('<br>', $result['order_email']);		
	$value = $order_email[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_customer_group', $advso_settings_ol_columns)) {	
	$order_group = explode('<br>', $result['order_group']);		
	$value = $order_group[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_shipping_method', $advso_settings_ol_columns)) {	
	$order_shipping_method = explode('<br>', $result['order_shipping_method']);		
	$value = $order_shipping_method[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_payment_method', $advso_settings_ol_columns)) {	
	$order_payment_method = explode('<br>', $result['order_payment_method']);		
	$value = $order_payment_method[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_status', $advso_settings_ol_columns)) {	
	$order_status = explode('<br>', $result['order_status']);		
	$value = $order_status[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_store', $advso_settings_ol_columns)) {	
	$order_store = explode('<br>', $result['order_store']);		
	$value = $order_store[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($value) . $csv_enclosed;		
	}	
	if (in_array('ol_order_currency', $advso_settings_ol_columns)) {	
	$order_currency = explode('<br>', $result['order_currency']);		
	$value = $order_currency[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_quantity', $advso_settings_ol_columns)) {	
	$order_products = explode('<br>', $result['order_products']);		
	$value = $order_products[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_sub_total', $advso_settings_ol_columns)) {	
	$order_sub_total = explode('<br>', $result['order_sub_total']);		
	$value = $order_sub_total[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		
	if (in_array('ol_order_shipping', $advso_settings_ol_columns)) {	
	$order_shipping = explode('<br>', $result['order_shipping']);		
	$value = $order_shipping[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('ol_order_tax', $advso_settings_ol_columns)) {	
	$order_tax = explode('<br>', $result['order_tax']);		
	$value = $order_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if ($filter_report == 'coupon') {
	if (in_array('cr_coupon_amount', $advso_settings_cr_columns)) {	
	$order_tax = explode('<br>', $result['order_coupon']);		
	$value = $order_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	} elseif ($filter_report == 'voucher') {
	if (in_array('vr_voucher_used_value', $advso_settings_vr_columns)) {	
	$order_tax = explode('<br>', $result['order_voucher']);		
	$value = $order_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	}	
	if (in_array('ol_order_value', $advso_settings_ol_columns)) {	
	$order_value = explode('<br>', $result['order_value']);		
	$value = $order_value[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}		

	if (in_array('cl_customer_cust_id', $advso_settings_cl_columns)) {	
	$customer_cust_id = explode('<br>', $result['customer_cust_id']);		
	$value = $customer_cust_id[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('cl_billing_name', $advso_settings_cl_columns)) {	
	$billing_name = explode('<br>', $result['billing_name']);		
	$value = $billing_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_billing_company', $advso_settings_cl_columns)) {	
	$billing_company = explode('<br>', $result['billing_company']);		
	$value = $billing_company[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_address_1', $advso_settings_cl_columns)) {	
	$billing_address_1 = explode('<br>', $result['billing_address_1']);		
	$value = $billing_address_1[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}		
	if (in_array('cl_billing_address_2', $advso_settings_cl_columns)) {	
	$billing_address_2 = explode('<br>', $result['billing_address_2']);		
	$value = $billing_address_2[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_city', $advso_settings_cl_columns)) {	
	$billing_city = explode('<br>', $result['billing_city']);		
	$value = $billing_city[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_zone', $advso_settings_cl_columns)) {	
	$billing_zone = explode('<br>', $result['billing_zone']);		
	$value = $billing_zone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_postcode', $advso_settings_cl_columns)) {	
	$billing_postcode = explode('<br>', $result['billing_postcode']);		
	$value = $billing_postcode[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_billing_country', $advso_settings_cl_columns)) {	
	$billing_country = explode('<br>', $result['billing_country']);		
	$value = $billing_country[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_customer_telephone', $advso_settings_cl_columns)) {	
	$customer_telephone = explode('<br>', $result['customer_telephone']);		
	$value = $customer_telephone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_name', $advso_settings_cl_columns)) {	
	$shipping_name = explode('<br>', $result['shipping_name']);		
	$value = $shipping_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_company', $advso_settings_cl_columns)) {	
	$shipping_company = explode('<br>', $result['shipping_company']);		
	$value = $shipping_company[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_address_1', $advso_settings_cl_columns)) {	
	$shipping_address_1 = explode('<br>', $result['shipping_address_1']);		
	$value = $shipping_address_1[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_address_2', $advso_settings_cl_columns)) {	
	$shipping_address_2 = explode('<br>', $result['shipping_address_2']);		
	$value = $shipping_address_2[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_city', $advso_settings_cl_columns)) {	
	$shipping_city = explode('<br>', $result['shipping_city']);		
	$value = $shipping_city[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}	
	if (in_array('cl_shipping_zone', $advso_settings_cl_columns)) {	
	$shipping_zone = explode('<br>', $result['shipping_zone']);		
	$value = $shipping_zone[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_shipping_postcode', $advso_settings_cl_columns)) {	
	$shipping_postcode = explode('<br>', $result['shipping_postcode']);		
	$value = $shipping_postcode[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	if (in_array('cl_shipping_country', $advso_settings_cl_columns)) {	
	$shipping_country = explode('<br>', $result['shipping_country']);		
	$value = $shipping_country[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}
	
	$export_csv .= $csv_row;		
	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders') {
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
	} elseif ($filter_report == 'day_of_week') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'currency') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;	
	} elseif ($filter_report == 'coupon') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'voucher') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;
	}
	
	if ($filter_report == 'coupon') {
		
	in_array('cr_coupon_name', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_code', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_discount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_type', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_status', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_date_added', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_last_used', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_orders', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_total', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	} elseif ($filter_report == 'voucher') {
		
	in_array('vr_voucher_code', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_from_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_from_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_to_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_to_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_theme', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_status', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_date_added', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_amount', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('vr_voucher_remaining_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_orders', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_total', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	} else {
			
	in_array('mv_orders', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customers', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_products', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_sub_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_handling', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_loworder', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_shipping', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_reward', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_earned_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_used_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_coupon', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_tax', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_credit', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_voucher', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_commission', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_aov', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_refunds', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	
	}

	in_array('ol_order_order_id', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_date_added', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_email', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_status', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_store', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_currency', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_quantity', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_tax', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	if ($filter_report == 'coupon') {
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	} elseif ($filter_report == 'voucher') {
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}	
	in_array('ol_order_value', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	$product_ord_id = explode('<br>', $result['product_ord_id']);		
	
	foreach ($product_ord_id as $index => $value) {	

	if (in_array('pl_prod_order_id', $advso_settings_pl_columns)) {	
	$product_ord_id = explode('<br>', $result['product_ord_id']);		
	$value = $product_ord_id[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('pl_prod_date_added', $advso_settings_pl_columns)) {	
	$product_ord_date = explode('<br>', $result['product_ord_date']);		
	$value = $product_ord_date[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}
	if (in_array('pl_prod_id', $advso_settings_pl_columns)) {	
	$product_pid = explode('<br>', $result['product_pid']);		
	$value = $product_pid[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_sku', $advso_settings_pl_columns)) {	
	$product_sku = explode('<br>', $result['product_sku']);		
	$value = $product_sku[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . str_replace('&nbsp;&nbsp;','',$value) . $csv_enclosed;		
	}		
	if (in_array('pl_prod_model', $advso_settings_pl_columns)) {	
	$product_model = explode('<br>', $result['product_model']);		
	$value = $product_model[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_name', $advso_settings_pl_columns)) {	
	$product_name = explode('<br>', $result['product_name']);		
	$value = $product_name[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode($value, ENT_NOQUOTES, 'UTF-8') . $csv_enclosed;		
	}	
	if (in_array('pl_prod_option', $advso_settings_pl_columns)) {	
	$product_option = explode('<br>', $result['product_option']);		
	$value = $product_option[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}	
	if (in_array('pl_prod_attributes', $advso_settings_pl_columns)) {	
	$product_attributes = explode('<br>', $result['product_attributes']);		
	$value = $product_attributes[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}		
	if (in_array('pl_prod_category', $advso_settings_pl_columns)) {	
	$product_category = explode('<br>', $result['product_category']);		
	$value = $product_category[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}
	if (in_array('pl_prod_manu', $advso_settings_pl_columns)) {	
	$product_manu = explode('<br>', $result['product_manu']);		
	$value = $product_manu[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . html_entity_decode(str_replace('&nbsp;','',$value)) . $csv_enclosed;		
	}	
	if (in_array('pl_prod_currency', $advso_settings_pl_columns)) {	
	$product_currency = explode('<br>', $result['product_currency']);		
	$value = $product_currency[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_price', $advso_settings_pl_columns)) {	
	$product_price = explode('<br>', $result['product_price']);		
	$value = $product_price[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_quantity', $advso_settings_pl_columns)) {	
	$product_quantity = explode('<br>', $result['product_quantity']);		
	$value = $product_quantity[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_total_excl_vat', $advso_settings_pl_columns)) {	
	$product_total_excl_vat = explode('<br>', $result['product_total_excl_vat']);		
	$value = $product_total_excl_vat[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_tax', $advso_settings_pl_columns)) {	
	$product_tax = explode('<br>', $result['product_tax']);		
	$value = $product_tax[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	
	if (in_array('pl_prod_total_incl_vat', $advso_settings_pl_columns)) {	
	$product_total_incl_vat = explode('<br>', $result['product_total_incl_vat']);		
	$value = $product_total_incl_vat[$index];			
	$export_csv .= $csv_delimiter . $csv_enclosed . $value . $csv_enclosed;		
	}	

	$export_csv .= $csv_row;		
	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders') {
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
	} elseif ($filter_report == 'day_of_week') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'hour') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'store') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'currency') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'customer_group') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'country') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'postcode') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'region_state') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'city') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'payment_method') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'shipping_method') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;	
	} elseif ($filter_report == 'coupon') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;
	} elseif ($filter_report == 'voucher') {
	$export_csv .= $csv_enclosed . '' . $csv_enclosed;					
	$export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed;
	}
	
	if ($filter_report == 'coupon') {
		
	in_array('cr_coupon_name', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_code', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_discount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_type', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_status', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_date_added', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_last_used', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_orders', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cr_coupon_total', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	} elseif ($filter_report == 'voucher') {
		
	in_array('vr_voucher_code', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_from_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_from_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_to_name', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_to_email', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_theme', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_status', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_date_added', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_amount', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('vr_voucher_remaining_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_orders', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('vr_voucher_total', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	} else {
			
	in_array('mv_orders', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_customers', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_products', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_sub_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_handling', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_loworder', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_shipping', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';	
	in_array('mv_reward', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_earned_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_used_points', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_coupon', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_tax', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_credit', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_voucher', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_commission', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_total', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_aov', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('mv_refunds', $advso_settings_mv_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}
	
	in_array('ol_order_order_id', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_date_added', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_inv_no', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_email', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_customer_group', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_payment_method', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_status', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_store', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_currency', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_quantity', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_sub_total', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_shipping', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('ol_order_tax', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	if ($filter_report == 'coupon') {
	in_array('cr_coupon_amount', $advso_settings_cr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	} elseif ($filter_report == 'voucher') {
	in_array('vr_voucher_used_value', $advso_settings_vr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	}	
	in_array('ol_order_value', $advso_settings_ol_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';

	in_array('cl_customer_cust_id', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_billing_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_customer_telephone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_name', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_company', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_1', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_address_2', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_city', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_zone', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_postcode', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	in_array('cl_shipping_country', $advso_settings_cl_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . '' . $csv_enclosed : '';
	
	}
	
	$export_csv .= $csv_row;
	}

	if (!isset($_GET['cron'])) {
		$filename = "sales_report_basic_details_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
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
		
		$filename = $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".csv";		
		
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