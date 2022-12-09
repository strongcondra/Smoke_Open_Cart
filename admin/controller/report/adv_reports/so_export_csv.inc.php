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

	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders' or $filter_report == 'tax') {
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
	
	if ($filter_report == 'tax') {
		
	in_array('tr_tax_name', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_title') . $csv_enclosed : '';
	in_array('tr_tax_rate', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_rate') . $csv_enclosed : '';
	in_array('tr_tax_orders', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_orders') . $csv_enclosed : '';
	in_array('tr_tax_total', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $this->language->get('column_tax_total') . $csv_enclosed : '';
	
	} elseif ($filter_report == 'coupon') {
		
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
	$export_csv .= $csv_row;

	foreach ($results as $result) {
	if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders' or $filter_report == 'tax') {
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
	
	if ($filter_report == 'tax') {
		
	in_array('tr_tax_name', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['tax_title'] . $csv_enclosed : '';
	in_array('tr_tax_rate', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['tax_rate'] . $csv_enclosed : '';
	in_array('tr_tax_orders', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . $result['orders'] . $csv_enclosed : '';
	in_array('tr_tax_total', $advso_settings_tr_columns) ? $export_csv .= $csv_delimiter . $csv_enclosed . round($result['total_tax_raw'], 2) . $csv_enclosed : '';
	
	} elseif ($filter_report == 'coupon') {
		
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
	$export_csv .= $csv_row;
	}

	if (!isset($_GET['cron'])) {
		$filename = "sales_report_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
		header('Content-Disposition: attachment; filename='.$filename.".csv");
		print $export_csv;		
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