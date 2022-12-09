<?php
	ini_set("memory_limit","256M");	
		
	$results = $export_data['results'];
	if ($results) {

		$this->load->model('report/adv_sales');	
		if ($filter_details == 'all_details_products') {
		$option_names = $this->model_report_adv_sales->getOrderOptionsNames();
		}
		$tax_names = $this->model_report_adv_sales->getOrderTaxNames();

		// Custom Fields
		$this->load->model('report/adv_sales');
		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC',
		);
		$custom_fields_name = $this->model_report_adv_sales->getCustomFieldsNames($filter_data);
	
		// we use our own error handler
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		
		if (!isset($_GET['cron'])) {
			// Creating a workbook
			$workbook = new Spreadsheet_Excel_Writer();
			$workbook->setTempDir(DIR_CACHE);
			$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format
		
			// sending HTTP headers
			$workbook->send('sales_report_all_details_'.date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").'.xls');
			$worksheet =& $workbook->addWorksheet('ADV Sales Report');
			$worksheet->setInputEncoding('UTF-8');
			$worksheet->setZoom(90);	
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
		
			$filename = $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";
			$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";
			$file = $file_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";		
		
			// Creating a workbook
			$workbook = new Spreadsheet_Excel_Writer($file);
			$workbook->setTempDir(DIR_CACHE);
			$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format
		
			// sending HTTP headers
			$worksheet =& $workbook->addWorksheet('ADV Sales Report');
			$worksheet->setInputEncoding('UTF-8');
			$worksheet->setZoom(90);
		}

		// Formating a workbook
		if ($export_logo_criteria) {
		$workbook->setCustomColor(43, 219, 229, 241);
		$criteriaDateFormat =& $workbook->addFormat(array('Align' => 'left', 'FgColor' => '43'));	
		$criteriaTitleFormat =& $workbook->addFormat(array('Align' => 'left', 'FgColor' => '43', 'bold' => 1));
		$criteriaTitleFormat->setSize(24);
		$criteriaTitleFormat->setVAlign('vcenter');
		$criteriaFilterFormat1 =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '43', 'bold' => 1));
		$criteriaFilterFormat1->setVAlign('top');
		$criteriaFilterFormat2 =& $workbook->addFormat(array('Align' => 'left', 'FgColor' => '43'));
		$criteriaFilterFormat2->setVAlign('top');
		$criteriaFilterFormat2->setTextWrap();
		$criteriaAddressFormat =& $workbook->addFormat(array('Align' => 'left', 'FgColor' => '43'));
		$criteriaAddressFormat->setSize(14);
		$criteriaAddressFormat->setVAlign('vcenter');
		$criteriaAddressFormat->setTextWrap();		
		}
		
		$textFormat =& $workbook->addFormat(array('Align' => 'left', 'NumFormat' => "@"));
		if ($filter_report == 'abandoned_orders') {
		$abtextFormat =& $workbook->addFormat(array('Align' => 'left', 'NumFormat' => "@"));			
		$abtextFormat->setStrikeOut();
		}
		
		$numberFormat =& $workbook->addFormat(array('Align' => 'right'));
		if ($filter_report == 'abandoned_orders') {
		$abnumberFormat =& $workbook->addFormat(array('Align' => 'right'));
		$abnumberFormat->setStrikeOut();
		}
		
		$priceFormat =& $workbook->addFormat(array('Align' => 'right'));
		$priceFormat->setNumFormat('0.00');
		if ($filter_report == 'abandoned_orders') {
		$priceFormat->setStrikeOut();
		$abpriceFormat =& $workbook->addFormat(array('Align' => 'right'));
		$abpriceFormat->setNumFormat('0.00');
		}

		$workbook->setCustomColor(27, 255, 255, 204);
		$soldColumnFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '27', 'bordercolor' => 'silver'));
		$soldColumnFormat->setBorder(1);
		if ($filter_report == 'abandoned_orders') {
		$soldColumnFormat->setStrikeOut();
		}		
		$percentFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '27', 'bordercolor' => 'silver'));
		$percentFormat->setBorder(1);	
		$percentFormat->setNumFormat('0.00%');
		if ($filter_report == 'abandoned_orders') {
		$percentFormat->setStrikeOut();
		}
		
		$boxFormatText =& $workbook->addFormat(array('bold' => 1));
		$boxFormatNumber =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));	
			
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,13);
		in_array('all_order_inv_no', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_order_customer_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_order_email', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_order_customer_group', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		if ($filter_details == 'all_details_products') {
		in_array('all_prod_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('all_prod_sku', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_model', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('all_prod_option', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		if ($option_names) {
		foreach ($option_names as $option_name) {
		in_array('all_prod_option', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		}
		}
		in_array('all_prod_attributes', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_prod_category', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';		
		in_array('all_prod_manu', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_prod_currency', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('all_prod_price', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_quantity', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_prod_total_excl_vat', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_tax', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_total_incl_vat', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_qty_refund', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_refund', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_prod_reward_points', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,14) : '';
		}
		in_array('all_sub_total', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_handling', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_loworder', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,14) : '';
		in_array('all_shipping', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_reward', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,19) : '';
		in_array('all_coupon', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_coupon_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_coupon_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_order_tax', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		if ($tax_names) {
		foreach ($tax_names as $tax_name) {
		in_array('all_order_tax', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		}
		}		
		in_array('all_credit', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_voucher', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_voucher_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_order_commission', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,19) : '';
		in_array('all_order_value', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_refund', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_order_shipping_method', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_order_payment_method', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_order_status', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('all_order_store', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_customer_cust_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,11) : '';
		if ($custom_fields_name) {
		foreach ($custom_fields_name as $custom_field_name) {
		in_array('all_custom_fields', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,16) : '';
		}
		}
		in_array('all_billing_first_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,16) : '';
		in_array('all_billing_last_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,16) : '';
		in_array('all_billing_company', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_billing_address_1', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_billing_address_2', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_billing_city', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_billing_zone', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_billing_zone_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_billing_zone_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_billing_postcode', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,17) : '';
		in_array('all_billing_country', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_billing_country_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_billing_country_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_customer_telephone', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_shipping_first_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_shipping_last_name', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('all_shipping_company', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_address_1', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_address_2', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_city', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_zone', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_shipping_zone_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_shipping_zone_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,16) : '';
		in_array('all_shipping_postcode', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,17) : '';
		in_array('all_shipping_country', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_country_id', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_shipping_country_code', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('all_order_weight', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('all_order_comment', $advso_settings_all_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		
		if ($export_logo_criteria) {
		$worksheet->setMerge(0, 0, 0, 1);
		$worksheet->writeString(0, 0, '', $criteriaDateFormat);			
		$worksheet->setMerge(0, 2, 0, $j-1);
		$worksheet->writeString(0, 2, $this->language->get('text_report_date').": ".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A"), $criteriaDateFormat);
		$worksheet->setRow(1, 50);	
		$worksheet->setMerge(1, 0, 1, 1);
		$worksheet->writeString(1, 0, '', $criteriaTitleFormat);			
		$worksheet->setMerge(1, 2, 1, $j-1);
		$worksheet->writeString(1, 2, $this->language->get('adv_ext_name'), $criteriaTitleFormat);
		$worksheet->setRow(2, 30);
		$worksheet->setMerge(2, 0, 2, 1);
		$worksheet->writeString(2, 0, '', $criteriaAddressFormat);			
		$worksheet->setMerge(2, 2, 2, $j-1);
		$worksheet->writeString(2, 2, $this->config->get('config_name').", ".$this->config->get('config_address').", ".$this->language->get('text_email')."".$this->config->get('config_email').", ".$this->language->get('text_telephone')."".$this->config->get('config_telephone'), $criteriaAddressFormat);		
		$worksheet->setRow(3, 40);		
		$worksheet->setMerge(3, 0, 3, 1);
		$worksheet->writeString(3, 0, $this->language->get('text_report_criteria'), $criteriaFilterFormat1);		
		$worksheet->setMerge(3, 2, 3, $j-1);
			$filter_criteria = "";
			if ($filter_report) {	
				if ($filter_report == 'sales_summary') {
					$filter_report_name = $this->language->get('text_sales_summary');
				} elseif ($filter_report == 'day_of_week') {
					$filter_report_name = $this->language->get('text_day_of_week');
				} elseif ($filter_report == 'hour') {
					$filter_report_name = $this->language->get('text_hour');
				} elseif ($filter_report == 'store') {
					$filter_report_name = $this->language->get('text_store');
				} elseif ($filter_report == 'currency') {
					$filter_report_name = $this->language->get('text_currency');
				} elseif ($filter_report == 'customer_group') {
					$filter_report_name = $this->language->get('text_customer_group');
				} elseif ($filter_report == 'tax') {
					$filter_report_name = $this->language->get('text_tax');
				} elseif ($filter_report == 'country') {
					$filter_report_name = $this->language->get('text_country');
				} elseif ($filter_report == 'postcode') {
					$filter_report_name = $this->language->get('text_postcode');
				} elseif ($filter_report == 'region_state') {
					$filter_report_name = $this->language->get('text_region_state');
				} elseif ($filter_report == 'city') {
					$filter_report_name = $this->language->get('text_city');
				} elseif ($filter_report == 'payment_method') {
					$filter_report_name = $this->language->get('text_payment_method');
				} elseif ($filter_report == 'shipping_method') {
					$filter_report_name = $this->language->get('text_shipping_method');					
				} elseif ($filter_report == 'coupon') {
					$filter_report_name = $this->language->get('text_coupons');					
				} elseif ($filter_report == 'voucher') {
					$filter_report_name = $this->language->get('text_gift_vouchers');					
				} elseif ($filter_report == 'abandoned_orders') {
					$filter_report_name = $this->language->get('text_abandoned_orders');			
				}	
				$filter_criteria .= $this->language->get('entry_report')." ".$filter_report_name."; ";
			}
			if ($filter_details) {
				if ($filter_details == 'no_details') {
					$filter_details_name = $this->language->get('text_no_details');
				} elseif ($filter_details == 'basic_details') {
					$filter_details_name = $this->language->get('text_basic_details');
				} elseif ($filter_details == 'all_details_products') {
					$filter_details_name = $this->language->get('text_all_details')." ".$this->language->get('text_all_details_products');				
				} elseif ($filter_details == 'all_details_orders') {
					$filter_details_name = $this->language->get('text_all_details')." ".$this->language->get('text_all_details_orders');		
				}					
				$filter_criteria .= $this->language->get('entry_show_details')." ".$filter_details_name."; ";
			}
			if ($filter_group) {
				if ($filter_group == 'year') {
					$filter_group_name = $this->language->get('text_year');
				} elseif ($filter_group == 'quarter') {
					$filter_group_name = $this->language->get('text_quarter');	
				} elseif ($filter_group == 'month') {
					$filter_group_name = $this->language->get('text_month');
				} elseif ($filter_group == 'week') {
					$filter_group_name = $this->language->get('text_week');
				} elseif ($filter_group == 'day') {
					$filter_group_name = $this->language->get('text_day');
				} elseif ($filter_group == 'order') {
					$filter_group_name = $this->language->get('text_order');					
				}				
				$filter_criteria .= $this->language->get('entry_group')." ".$filter_group_name."; ";
			}
			if ($filter_sort) {	
				if ($filter_sort == 'report_type') {			
					if ($filter_report == 'sales_summary' or $filter_report == 'abandoned_orders' or $filter_report == 'tax' or $filter_report == 'coupon' or $filter_report == 'voucher') {
						$filter_sort_name = $this->language->get('column_date');
					} elseif ($filter_report == 'day_of_week') {
						$filter_sort_name = $this->language->get('column_day_of_week');
					} elseif ($filter_report == 'hour') {
						$filter_sort_name = $this->language->get('column_hour');
					} elseif ($filter_report == 'store') {
						$filter_sort_name = $this->language->get('column_store');
					} elseif ($filter_report == 'currency') {
						$filter_sort_name = $this->language->get('column_currency');
					} elseif ($filter_report == 'customer_group') {
						$filter_sort_name = $this->language->get('column_customer_group');
					} elseif ($filter_report == 'country') {
						$filter_sort_name = $this->language->get('column_country');
					} elseif ($filter_report == 'postcode') {
						$filter_sort_name = $this->language->get('column_postcode');
					} elseif ($filter_report == 'region_state') {
						$filter_sort_name = $this->language->get('column_region_state');
					} elseif ($filter_report == 'city') {
						$filter_sort_name = $this->language->get('column_city');
					} elseif ($filter_report == 'payment_method') {
						$filter_sort_name = $this->language->get('column_payment_method');
					} elseif ($filter_report == 'shipping_method') {
						$filter_sort_name = $this->language->get('column_shipping_method');
					}
					
				}
				if ($filter_report == 'tax') {
				if ($filter_sort == 'tax_name') {
					$filter_sort_name = $this->language->get('column_tax_title');
				} elseif ($filter_sort == 'tax_rate') {
					$filter_sort_name = $this->language->get('column_tax_rate');
				} elseif ($filter_sort == 'tax_orders') {
					$filter_sort_name = $this->language->get('column_orders');
				} elseif ($filter_sort == 'tax_total') {
					$filter_sort_name = $this->language->get('column_tax_total');
				}					
				} elseif ($filter_report == 'coupon') {
				if ($filter_sort == 'coupon_name') {
					$filter_sort_name = $this->language->get('column_coupon_name');
				} elseif ($filter_sort == 'coupon_code') {
					$filter_sort_name = $this->language->get('column_coupon_code');
				} elseif ($filter_sort == 'coupon_discount') {
					$filter_sort_name = $this->language->get('column_coupon_discount');
				} elseif ($filter_sort == 'coupon_type') {
					$filter_sort_name = $this->language->get('column_coupon_type');
				} elseif ($filter_sort == 'coupon_status') {
					$filter_sort_name = $this->language->get('column_coupon_status');
				} elseif ($filter_sort == 'coupon_date_added') {
					$filter_sort_name = $this->language->get('column_coupon_date_added');
				} elseif ($filter_sort == 'coupon_last_used') {
					$filter_sort_name = $this->language->get('column_coupon_last_used');
				} elseif ($filter_sort == 'coupon_amount') {
					$filter_sort_name = $this->language->get('column_coupon_amount');	
				} elseif ($filter_sort == 'coupon_orders') {
					$filter_sort_name = $this->language->get('column_orders');
				} elseif ($filter_sort == 'coupon_total') {
					$filter_sort_name = $this->language->get('column_total');					
				}	
				} elseif ($filter_report == 'voucher') {
				if ($filter_sort == 'voucher_code') {
					$filter_sort_name = $this->language->get('column_voucher_code');
				} elseif ($filter_sort == 'voucher_from_name') {
					$filter_sort_name = $this->language->get('column_voucher_from_name');
				} elseif ($filter_sort == 'voucher_from_email') {
					$filter_sort_name = $this->language->get('column_voucher_from_email');
				} elseif ($filter_sort == 'voucher_to_name') {
					$filter_sort_name = $this->language->get('column_voucher_to_name');
				} elseif ($filter_sort == 'voucher_to_email') {
					$filter_sort_name = $this->language->get('column_voucher_to_email');
				} elseif ($filter_sort == 'voucher_theme') {
					$filter_sort_name = $this->language->get('column_voucher_theme');
				} elseif ($filter_sort == 'voucher_status') {
					$filter_sort_name = $this->language->get('column_voucher_status');
				} elseif ($filter_sort == 'voucher_date_added') {
					$filter_sort_name = $this->language->get('column_voucher_date_added');
				} elseif ($filter_sort == 'voucher_amount') {
					$filter_sort_name = $this->language->get('column_voucher_amount');	
				} elseif ($filter_sort == 'voucher_used_value') {
					$filter_sort_name = $this->language->get('column_voucher_used_value');	
				} elseif ($filter_sort == 'voucher_remaining_value') {
					$filter_sort_name = $this->language->get('column_voucher_remaining_value');						
				} elseif ($filter_sort == 'voucher_orders') {
					$filter_sort_name = $this->language->get('column_orders');
				} elseif ($filter_sort == 'voucher_total') {
					$filter_sort_name = $this->language->get('column_total');					
				}
				} else {
				if ($filter_sort == 'orders') {
					$filter_sort_name = $this->language->get('column_orders');
				} elseif ($filter_sort == 'customers') {
					$filter_sort_name = $this->language->get('column_customers');
				} elseif ($filter_sort == 'products') {
					$filter_sort_name = $this->language->get('column_products');	
				} elseif ($filter_sort == 'sub_total') {
					$filter_sort_name = $this->language->get('column_sub_total');
				} elseif ($filter_sort == 'shipping') {
					$filter_sort_name = $this->language->get('column_shipping');
				} elseif ($filter_sort == 'reward') {
					$filter_sort_name = $this->language->get('column_reward');
				} elseif ($filter_sort == 'earned_reward_points') {
					$filter_sort_name = $this->language->get('column_earned_reward_points');	
				} elseif ($filter_sort == 'used_reward_points') {
					$filter_sort_name = $this->language->get('column_used_reward_points');
				} elseif ($filter_sort == 'coupon') {
					$filter_sort_name = $this->language->get('column_coupon');	
				} elseif ($filter_sort == 'tax') {
					$filter_sort_name = $this->language->get('column_taxes');
				} elseif ($filter_sort == 'credit') {
					$filter_sort_name = $this->language->get('column_credit');
				} elseif ($filter_sort == 'voucher') {
					$filter_sort_name = $this->language->get('column_voucher');
				} elseif ($filter_sort == 'commission') {
					$filter_sort_name = $this->language->get('column_commission');	
				} elseif ($filter_sort == 'total') {
					$filter_sort_name = $this->language->get('column_total');
				} elseif ($filter_sort == 'aov') {
					$filter_sort_name = $this->language->get('column_aov');	
				} elseif ($filter_sort == 'refunds') {
					$filter_sort_name = $this->language->get('column_refunds');					
				}
				}
				if ($filter_order == 'asc') {
					$filter_order_name = $this->language->get('text_asc');
				} elseif ($filter_order == 'desc') {
					$filter_order_name = $this->language->get('text_desc');
				}				
				$filter_criteria .= $this->language->get('entry_sort_by')." ".$filter_sort_name." ".$filter_order_name."; ";
			}
			if ($filter_limit) {	
				$filter_criteria .= $this->language->get('entry_limit')." ".$filter_limit."; ";
			}	
			$filter_criteria .= "\n";
			if ($filter_range) {	
				if ($filter_range == 'custom') {
					$filter_range_name = $this->language->get('stat_custom');
				} elseif ($filter_range == 'today') {
					$filter_range_name = $this->language->get('stat_today');
				} elseif ($filter_range == 'yesterday') {
					$filter_range_name = $this->language->get('stat_yesterday');	
				} elseif ($filter_range == 'week') {
					$filter_range_name = $this->language->get('stat_week');
				} elseif ($filter_range == 'month') {
					$filter_range_name = $this->language->get('stat_month');
				} elseif ($filter_range == 'quarter') {
					$filter_range_name = $this->language->get('stat_quarter');
				} elseif ($filter_range == 'year') {
					$filter_range_name = $this->language->get('stat_year');	
				} elseif ($filter_range == 'current_week') {
					$filter_range_name = $this->language->get('stat_current_week');
				} elseif ($filter_range == 'current_month') {
					$filter_range_name = $this->language->get('stat_current_month');	
				} elseif ($filter_range == 'current_quarter') {
					$filter_range_name = $this->language->get('stat_current_quarter');
				} elseif ($filter_range == 'current_year') {
					$filter_range_name = $this->language->get('stat_current_year');
				} elseif ($filter_range == 'last_week') {
					$filter_range_name = $this->language->get('stat_last_week');
				} elseif ($filter_range == 'last_month') {
					$filter_range_name = $this->language->get('stat_last_month');	
				} elseif ($filter_range == 'last_quarter') {
					$filter_range_name = $this->language->get('stat_last_quarter');
				} elseif ($filter_range == 'last_year') {
					$filter_range_name = $this->language->get('stat_last_year');
				} elseif ($filter_range == 'all_time') {
					$filter_range_name = $this->language->get('stat_all_time');					
				}				
				$filter_criteria .= $this->language->get('entry_range')." ".$filter_range_name;
				if ($filter_date_start) {	
					$filter_criteria .= " [".$this->language->get('entry_date_start')." ".$filter_date_start;
				}
				if ($filter_date_end) {	
					$filter_criteria .= ", ".$this->language->get('entry_date_end')." ".$filter_date_end."]";
				}
				$filter_criteria .= "; ";
			}
			if ($filter_order_status_id) {	
				$filter_criteria .= $this->language->get('entry_status')." ".$filter_order_status_id;
				if ($filter_status_date_start) {	
					$filter_criteria .= " [".$this->language->get('entry_date_start')." ".$filter_status_date_start;
				}
				if ($filter_status_date_end) {	
					$filter_criteria .= ", ".$this->language->get('entry_date_end')." ".$filter_status_date_end."]";
				}
				$filter_criteria .= "; ";				
			}
			if ($filter_order_id_from or $filter_order_id_to) {
				$filter_criteria .= $this->language->get('entry_order_id').": ".$filter_order_id_from."-".$filter_order_id_to."; ";
			}
			if ($filter_order_value_min or $filter_order_value_max) {
				$filter_criteria .= $this->language->get('entry_order_value').": ".$filter_order_value_min."-".$filter_order_value_max."; ";
			}				
			$filter_criteria .= "\n";
			if ($filter_store_id) {	
				$filter_criteria .= $this->language->get('entry_store')." ".$filter_store_id."; ";
			}
			if ($filter_currency) {	
				$filter_criteria .= $this->language->get('entry_currency')." ".$filter_currency."; ";
			}
			if ($filter_taxes) {	
				$filter_criteria .= $this->language->get('entry_tax')." ".$filter_taxes."; ";
			}
			if ($filter_tax_classes) {	
				$filter_criteria .= $this->language->get('entry_tax_classes')." ".$filter_tax_classes."; ";
			}
			if ($filter_geo_zones) {	
				$filter_criteria .= $this->language->get('entry_geo_zone')." ".$filter_geo_zones."; ";
			}
			if ($filter_customer_group_id) {	
				$filter_criteria .= $this->language->get('entry_customer_group')." ".$filter_customer_group_id."; ";
			}
			if ($filter_customer_name) {	
				$filter_criteria .= $this->language->get('entry_customer_name')." ".$filter_customer_name."; ";
			}
			if ($filter_customer_email) {	
				$filter_criteria .= $this->language->get('entry_customer_email')." ".$filter_customer_email."; ";
			}
			if ($filter_customer_telephone) {	
				$filter_criteria .= $this->language->get('entry_customer_telephone')." ".$filter_customer_telephone."; ";
			}
			if ($filter_ip) {	
				$filter_criteria .= $this->language->get('entry_ip')." ".$filter_ip."; ";
			}
			if ($filter_payment_company) {	
				$filter_criteria .= $this->language->get('entry_payment_company')." ".$filter_payment_company."; ";
			}
			if ($filter_payment_address) {	
				$filter_criteria .= $this->language->get('entry_payment_address')." ".$filter_payment_address."; ";
			}
			if ($filter_payment_city) {	
				$filter_criteria .= $this->language->get('entry_payment_city')." ".$filter_payment_city."; ";
			}
			if ($filter_payment_zone) {	
				$filter_criteria .= $this->language->get('entry_payment_zone')." ".$filter_payment_zone."; ";
			}
			if ($filter_payment_postcode) {	
				$filter_criteria .= $this->language->get('entry_payment_postcode')." ".$filter_payment_postcode."; ";
			}
			if ($filter_payment_country) {	
				$filter_criteria .= $this->language->get('entry_payment_country')." ".$filter_payment_country."; ";
			}
			if ($filter_payment_method) {	
				$filter_criteria .= $this->language->get('entry_payment_method')." ".$filter_payment_method."; ";
			}
			if ($filter_shipping_company) {	
				$filter_criteria .= $this->language->get('entry_shipping_company')." ".$filter_shipping_company."; ";
			}
			if ($filter_shipping_address) {	
				$filter_criteria .= $this->language->get('entry_shipping_address')." ".$filter_shipping_address."; ";
			}
			if ($filter_shipping_city) {	
				$filter_criteria .= $this->language->get('entry_shipping_city')." ".$filter_shipping_city."; ";
			}
			if ($filter_shipping_zone) {	
				$filter_criteria .= $this->language->get('entry_shipping_zone')." ".$filter_shipping_zone."; ";
			}
			if ($filter_shipping_postcode) {	
				$filter_criteria .= $this->language->get('entry_shipping_postcode')." ".$filter_shipping_postcode."; ";
			}
			if ($filter_shipping_country) {	
				$filter_criteria .= $this->language->get('entry_shipping_country')." ".$filter_shipping_country."; ";
			}
			if ($filter_shipping_method) {	
				$filter_criteria .= $this->language->get('entry_shipping_method')." ".$filter_shipping_method."; ";
			}
			if ($filter_category) {	
				$filter_criteria .= $this->language->get('entry_category')." ".$filter_category."; ";
			}
			if ($filter_manufacturer) {	
				$filter_criteria .= $this->language->get('entry_manufacturer')." ".$filter_manufacturer."; ";
			}		
			if ($filter_sku) {	
				$filter_criteria .= $this->language->get('entry_sku')." ".$filter_sku."; ";
			}
			if ($filter_product_name) {	
				$filter_criteria .= $this->language->get('entry_product')." ".$filter_product_name."; ";
			}
			if ($filter_model) {	
				$filter_criteria .= $this->language->get('entry_model')." ".$filter_model."; ";
			}
			if ($filter_option) {	
				$filter_criteria .= $this->language->get('entry_option')." ".$filter_option."; ";
			}	
			if ($filter_attribute) {	
				$filter_criteria .= $this->language->get('entry_attributes')." ".$filter_attribute."; ";
			}		
			if ($filter_location) {	
				$filter_criteria .= $this->language->get('entry_location')." ".$filter_location."; ";
			}		
			if ($filter_affiliate_name) {	
				$filter_criteria .= $this->language->get('entry_affiliate_name')." ".$filter_affiliate_name."; ";
			}		
			if ($filter_affiliate_email) {	
				$filter_criteria .= $this->language->get('entry_affiliate_email')." ".$filter_affiliate_email."; ";
			}		
			if ($filter_coupon_name) {	
				$filter_criteria .= $this->language->get('entry_coupon_name')." ".$filter_coupon_name."; ";
			}		
			if ($filter_coupon_code) {	
				$filter_criteria .= $this->language->get('entry_coupon_code')." ".$filter_coupon_code."; ";
			}	
			if ($filter_voucher_code) {	
				$filter_criteria .= $this->language->get('entry_voucher_code')." ".$filter_voucher_code."; ";
			}
		$worksheet->writeString(3, 2, $filter_criteria, $criteriaFilterFormat2);			
		}
		
		// The order headings row
		$export_logo_criteria ? $i = 4 : $i = 0;
		$j = 0;	
		$worksheet->writeString($i, $j++, $this->language->get('column_order_order_id'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_order_date_added'), $boxFormatText);
		in_array('all_order_inv_no', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_inv_no'), $boxFormatText) : '';
		in_array('all_order_customer_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_customer'), $boxFormatText) : '';
		in_array('all_order_email', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_email'), $boxFormatText) : '';
		in_array('all_order_customer_group', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_customer_group'), $boxFormatText) : '';
		if ($filter_details == 'all_details_products') {
		in_array('all_prod_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_id'), $boxFormatText) : '';
		in_array('all_prod_sku', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_sku'), $boxFormatText) : '';
		in_array('all_prod_model', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_model'), $boxFormatText) : '';
		in_array('all_prod_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_name'), $boxFormatText) : '';
		in_array('all_prod_option', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_option'), $boxFormatText) : '';
		$n = 0;
		if ($option_names) {
		foreach ($option_names as $option_name) {
		in_array('all_prod_option', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $option_name['name'], $boxFormatText) : '';
		$n++;
		}		 
		}	
		in_array('all_prod_attributes', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_attributes'), $boxFormatText) : '';
		in_array('all_prod_category', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_category'), $boxFormatText) : '';		
		in_array('all_prod_manu', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_manu'), $boxFormatText) : '';
		in_array('all_prod_currency', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_currency'), $boxFormatText) : ''; 
		in_array('all_prod_price', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_price'), $boxFormatNumber) : '';
		in_array('all_prod_quantity', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_quantity'), $boxFormatNumber) : '';
		in_array('all_prod_total_excl_vat', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_total_excl_vat'), $boxFormatNumber) : '';
		in_array('all_prod_tax', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_tax'), $boxFormatNumber) : '';
		in_array('all_prod_total_incl_vat', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_total_incl_vat'), $boxFormatNumber) : '';
		in_array('all_prod_qty_refund', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_qty_refunded'), $boxFormatNumber) : '';
		in_array('all_prod_refund', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_refunded'), $boxFormatNumber) : '';
		in_array('all_prod_reward_points', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_reward_points'), $boxFormatNumber) : '';
		}
		in_array('all_sub_total', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_sub_total'), $boxFormatNumber) : '';
		in_array('all_handling', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_handling'), $boxFormatNumber) : '';
		in_array('all_loworder', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_loworder'), $boxFormatNumber) : '';
		in_array('all_shipping', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_shipping'), $boxFormatNumber) : '';
		in_array('all_reward', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_reward'), $boxFormatNumber) : '';
		in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_earned_reward_points'), $boxFormatNumber) : '';
		in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_used_reward_points'), $boxFormatNumber) : '';
		in_array('all_coupon', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon'), $boxFormatNumber) : '';
		in_array('all_coupon_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon_name'), $boxFormatText) : '';
		in_array('all_coupon_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_coupon_code'), $boxFormatText) : '';
		in_array('all_order_tax', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_tax'), $boxFormatNumber) : '';
		$t = 0;
		if ($tax_names) {
		foreach ($tax_names as $tax_name) {
		in_array('all_order_tax', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $tax_name['title'], $boxFormatNumber) : '';
		$t++;
		}		 
		}			
		in_array('all_credit', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_credit'), $boxFormatNumber) : '';
		in_array('all_voucher', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_voucher'), $boxFormatNumber) : '';
		in_array('all_voucher_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_voucher_code'), $boxFormatText) : '';
		in_array('all_order_commission', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_commission'), $boxFormatNumber) : '';
		in_array('all_order_value', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_value'), $boxFormatNumber) : '';
		in_array('all_refund', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_refund'), $boxFormatNumber) : '';
		in_array('all_order_shipping_method', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_shipping_method'), $boxFormatText) : '';
		in_array('all_order_payment_method', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_payment_method'), $boxFormatText) : '';
		in_array('all_order_status', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_status'), $boxFormatText) : '';
		in_array('all_order_store', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_store'), $boxFormatText) : '';
		in_array('all_customer_cust_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_cust_id'), $boxFormatText) : '';
		if ($custom_fields_name) {
		foreach ($custom_fields_name as $custom_field_name) {
		in_array('all_custom_fields', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $custom_field_name['name'], $boxFormatText) : '';
		}
		}		
		in_array('all_billing_first_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_first_name')), $boxFormatText) : '';
		in_array('all_billing_last_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_last_name')), $boxFormatText) : '';
		in_array('all_billing_company', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_company')), $boxFormatText) : '';
		in_array('all_billing_address_1', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_address_1')), $boxFormatText) : '';
		in_array('all_billing_address_2', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_address_2')), $boxFormatText) : '';
		in_array('all_billing_city', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_city')), $boxFormatText) : '';
		in_array('all_billing_zone', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_zone')), $boxFormatText) : '';
		in_array('all_billing_zone_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_zone_id')), $boxFormatText) : '';
		in_array('all_billing_zone_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_zone_code')), $boxFormatText) : '';
		in_array('all_billing_postcode', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_postcode')), $boxFormatText) : '';
		in_array('all_billing_country', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_country')), $boxFormatText) : '';
		in_array('all_billing_country_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_country_id')), $boxFormatText) : '';
		in_array('all_billing_country_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_billing_country_code')), $boxFormatText) : '';
		in_array('all_customer_telephone', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_telephone'), $boxFormatText) : '';
		in_array('all_shipping_first_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_first_name')), $boxFormatText) : '';
		in_array('all_shipping_last_name', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_last_name')), $boxFormatText) : '';
		in_array('all_shipping_company', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_company')), $boxFormatText) : '';
		in_array('all_shipping_address_1', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_address_1')), $boxFormatText) : '';
		in_array('all_shipping_address_2', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_address_2')), $boxFormatText) : '';
		in_array('all_shipping_city', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_city')), $boxFormatText) : '';
		in_array('all_shipping_zone', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_zone')), $boxFormatText) : '';
		in_array('all_shipping_zone_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_zone_id')), $boxFormatText) : '';
		in_array('all_shipping_zone_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_zone_code')), $boxFormatText) : '';
		in_array('all_shipping_postcode', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_postcode')), $boxFormatText) : '';
		in_array('all_shipping_country', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_country')), $boxFormatText) : '';
		in_array('all_shipping_country_id', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_country_id')), $boxFormatText) : '';
		in_array('all_shipping_country_code', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, strip_tags($this->language->get('column_shipping_country_code')), $boxFormatText) : '';
		in_array('all_order_weight', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_weight'), $boxFormatNumber) : '';
		in_array('all_order_comment', $advso_settings_all_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_order_comment'), $boxFormatText) : '';

		// The actual orders data
		$i += 1;
		$j = 0;
		
			foreach ($results as $result) {
			$excelRow = $i+1;			
				$worksheet->write($i, $j++, $result['order_id'], $textFormat);
				$worksheet->write($i, $j++, $result['date_added'], $textFormat);
				in_array('all_order_inv_no', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['invoice'], $textFormat) : '';
				in_array('all_order_customer_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['name'], $textFormat) : '';
				in_array('all_order_email', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['email'], $textFormat) : '';
				in_array('all_order_customer_group', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['cust_group'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				if ($filter_details == 'all_details_products') {
				in_array('all_prod_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_id'], $textFormat) : '';
				in_array('all_prod_sku', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_sku'], $textFormat) : '';
				in_array('all_prod_model', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_model'], $textFormat) : '';
				in_array('all_prod_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['product_name'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				in_array('all_prod_option', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['product_options'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				if (in_array('all_prod_option', $advso_settings_all_columns)) {
				$o = $j;	
				if ($result['product_option']) {
				foreach ($result['product_option'] as $index => $product_option) {
				if ($product_option['name'] == $option_names[$index]['name']) {				
				$worksheet->write($i, $j++, $product_option['value'], $textFormat);	
				//$worksheet->write($i, $j++, $product_option['name'].': '.$product_option['value'], $textFormat);					
				} else {
				foreach ($result['product_option'] as $product_option) {
				foreach ($option_names as $option_name) {			
				if ($product_option['name'] == $option_name['name']) {		
				$worksheet->write($i, $j++, $product_option['value'], $textFormat);	
				//$worksheet->write($i, $j++, $product_option['name'].': '.$product_option['value'], $textFormat);	
				} else {
				$worksheet->write($i, $j++, '', $textFormat);
				}			
				}
				}			
				}
				}	
				} else {
				foreach ($option_names as $option_name) {
				$worksheet->write($i, $j++, '', $textFormat);
				}
				}
				$j=$o+$n;
				}
				in_array('all_prod_attributes', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['product_attributes'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				in_array('all_prod_category', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['product_category'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';				
				in_array('all_prod_manu', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['product_manu'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				in_array('all_prod_currency', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['currency_code'], $textFormat) : '';
				in_array('all_prod_price', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_price_raw'], $priceFormat) : '';
				in_array('all_prod_quantity', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_quantity'], $filter_report != 'abandoned_orders' ? '' : $abnumberFormat) : '';
				in_array('all_prod_total_excl_vat', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_total_excl_vat_raw'], $priceFormat) : '';
				in_array('all_prod_tax', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_tax_raw'], $priceFormat) : '';
				in_array('all_prod_total_incl_vat', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_total_incl_vat_raw'], $priceFormat) : '';
				in_array('all_prod_qty_refund', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_qty_refund'], $filter_report != 'abandoned_orders' ? '' : $abnumberFormat) : '';
				in_array('all_prod_refund', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_refund_raw'] != NULL ? $result['product_refund_raw'] : '0.00', $priceFormat) : '';
				in_array('all_prod_reward_points', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['product_reward_points'], $filter_report != 'abandoned_orders' ? '' : $abnumberFormat) : '';
				}
				in_array('all_sub_total', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_sub_total_raw'], $priceFormat) : '';
				in_array('all_handling', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_handling_raw'] != NULL ? $result['order_handling_raw'] : '0.00', $priceFormat) : '';
				in_array('all_loworder', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_low_order_fee_raw'] != NULL ? $result['order_low_order_fee_raw'] : '0.00', $priceFormat) : '';
				in_array('all_shipping', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_shipping_raw'] != NULL ? $result['order_shipping_raw'] : '0.00', $priceFormat) : '';
				in_array('all_reward', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_reward_raw'] != NULL ? $result['order_reward_raw'] : '0.00', $priceFormat) : '';
				in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_earned_points'], $filter_report != 'abandoned_orders' ? '' : $abnumberFormat) : '';
				in_array('all_reward_points', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_used_points'], $filter_report != 'abandoned_orders' ? '' : $abnumberFormat) : '';
				in_array('all_coupon', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_coupon_raw'] != NULL ? $result['order_coupon_raw'] : '0.00', $priceFormat) : '';
				in_array('all_coupon_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_coupon_name'], $filter_report != 'abandoned_orders' ? $textFormat : $abtextFormat) : '';
				in_array('all_coupon_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_coupon_code'], $filter_report != 'abandoned_orders' ? $textFormat : $abtextFormat) : '';
				if (in_array('all_order_tax', $advso_settings_all_columns)) {
				$worksheet->write($i, $j++, $result['order_tax_raw'] != NULL ? $result['order_tax_raw'] : '0.00', $priceFormat);
				$x = $j;	
				if ($result['order_taxes']) {
				foreach ($result['order_taxes'] as $index => $order_taxes) {
				if ($order_taxes['title'] == $tax_names[$index]['title']) {				
				$worksheet->write($i, $j++, $order_taxes['value'] != NULL ? $order_taxes['value'] : '0.00', $priceFormat);	
				} else {
				foreach ($result['order_taxes'] as $order_taxes) {
				foreach ($tax_names as $tax_name) {		
				if ($order_taxes['title'] == $tax_name['title']) {		
				$worksheet->write($i, $j++, $order_taxes['value'] != NULL ? $order_taxes['value'] : '0.00', $priceFormat);	
				} else {
				$worksheet->write($i, $j++, '', $priceFormat);
				}			
				}
				}			
				}
				}	
				} else {
				foreach ($tax_names as $tax_name) {
				$worksheet->write($i, $j++, '', $priceFormat);
				}
				}
				$j=$x+$t;
				}				
				in_array('all_credit', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_credit_raw'] != NULL ? $result['order_credit_raw'] : '0.00', $priceFormat) : '';
				in_array('all_voucher', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_voucher_raw'] != NULL ? $result['order_voucher_raw'] : '0.00', $priceFormat) : '';
				in_array('all_voucher_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_voucher_code'], $filter_report != 'abandoned_orders' ? $textFormat : $abtextFormat) : '';
				in_array('all_order_commission', $advso_settings_all_columns) ? $worksheet->write($i, $j++, -$result['order_commission_raw'], $priceFormat) : '';
				in_array('all_order_value', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_value_raw'], $priceFormat) : '';
				in_array('all_refund', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_refund_raw'] != NULL ? $result['order_refund_raw'] : '0.00', $priceFormat) : '';
				in_array('all_order_shipping_method', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_method'], $textFormat) : '';
				in_array('all_order_payment_method', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_method'], $textFormat) : '';
				in_array('all_order_status', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_status'], $textFormat) : '';
				in_array('all_order_store', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['store_name'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';
				in_array('all_customer_cust_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['customer_id'], $textFormat) : '';
				if ($result['custom_fields']) {
				foreach ($result['custom_fields'] as $custom_field) {
				in_array('all_custom_fields', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $custom_field['value'], $textFormat) : '';
				}
				} else {
				foreach ($custom_fields_name as $custom_field_name) {
				in_array('all_custom_fields', $advso_settings_all_columns) ? $worksheet->write($i, $j++, '', $textFormat) : '';
				}
				}
				in_array('all_billing_first_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_firstname'], $textFormat) : '';
				in_array('all_billing_last_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_lastname'], $textFormat) : '';
				in_array('all_billing_company', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_company'], $textFormat) : '';
				in_array('all_billing_address_1', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_address_1'], $textFormat) : '';
				in_array('all_billing_address_2', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_address_2'], $textFormat) : '';
				in_array('all_billing_city', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_city'], $textFormat) : '';
				in_array('all_billing_zone', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_zone'], $textFormat) : '';
				in_array('all_billing_zone_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_zone_id'], $textFormat) : '';
				in_array('all_billing_zone_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_zone_code'], $textFormat) : '';
				in_array('all_billing_postcode', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_postcode'], $textFormat) : '';
				in_array('all_billing_country', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_country'], $textFormat) : '';
				in_array('all_billing_country_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_country_id'], $textFormat) : '';
				in_array('all_billing_country_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['payment_country_code'], $textFormat) : '';
				in_array('all_customer_telephone', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['telephone'], $textFormat) : '';
				in_array('all_shipping_first_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_firstname'], $textFormat) : '';
				in_array('all_shipping_last_name', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_lastname'], $textFormat) : '';
				in_array('all_shipping_company', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_company'], $textFormat) : '';
				in_array('all_shipping_address_1', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_address_1'], $textFormat) : '';
				in_array('all_shipping_address_2', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_address_2'], $textFormat) : '';
				in_array('all_shipping_city', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_city'], $textFormat) : '';
				in_array('all_shipping_zone', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_zone'], $textFormat) : '';
				in_array('all_shipping_zone_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_zone_id'], $textFormat) : '';
				in_array('all_shipping_zone_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_zone_code'], $textFormat) : '';
				in_array('all_shipping_postcode', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_postcode'], $textFormat) : '';
				in_array('all_shipping_country', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_country'], $textFormat) : '';
				in_array('all_shipping_country_id', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_country_id'], $textFormat) : '';
				in_array('all_shipping_country_code', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['shipping_country_code'], $textFormat) : '';
				in_array('all_order_weight', $advso_settings_all_columns) ? $worksheet->write($i, $j++, $result['order_weight'], $filter_report != 'abandoned_orders' ? $priceFormat : $abpriceFormat) : '';
				in_array('all_order_comment', $advso_settings_all_columns) ? $worksheet->write($i, $j++, html_entity_decode($result['order_comment'], ENT_COMPAT, 'UTF-8'), $textFormat) : '';

				$i += 1;
				$j = 0;
			}
		
		$freeze = ($export_logo_criteria ? array(5, 2, 5, 2) : array(1, 2, 1, 2));
		$worksheet->freezePanes($freeze);
		
		// Let's send the file		
		$workbook->close();
		
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		
		if (isset($_GET['cron'])) {	
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