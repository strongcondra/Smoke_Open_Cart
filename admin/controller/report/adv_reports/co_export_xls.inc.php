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
			$workbook->send('customers_report_'.date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").'.xls');
			$worksheet =& $workbook->addWorksheet('ADV Customers Report');
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
		
			$filename = $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";
			$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";
			$file = $file_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xls";		
		
			// Creating a workbook
			$workbook = new Spreadsheet_Excel_Writer($file);
			$workbook->setTempDir(DIR_CACHE);
			$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format
		
			// sending HTTP headers
			$worksheet =& $workbook->addWorksheet('ADV Customers Report');
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
		if ($filter_report == 'customers_abandoned_orders') {
		$abtextFormat =& $workbook->addFormat(array('Align' => 'left', 'NumFormat' => "@"));
		$abtextFormat->setStrikeOut();
		}		
		
		$numberFormat =& $workbook->addFormat(array('Align' => 'right'));
		if ($filter_report == 'customers_abandoned_orders') {
		$abnumberFormat =& $workbook->addFormat(array('Align' => 'right'));
		$abnumberFormat->setStrikeOut();
		}
		
		$priceFormat =& $workbook->addFormat(array('Align' => 'right'));
		$priceFormat->setNumFormat('0.00');
		if ($filter_report == 'customers_abandoned_orders') {
		$priceFormat->setStrikeOut();	
		}

		$workbook->setCustomColor(27, 255, 255, 204);
		$soldColumnFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '27', 'bordercolor' => 'silver'));
		$soldColumnFormat->setBorder(1);
		if ($filter_report == 'customers_abandoned_orders') {
		$soldColumnFormat->setStrikeOut();
		}		
		$percentFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '27', 'bordercolor' => 'silver'));
		$percentFormat->setBorder(1);	
		$percentFormat->setNumFormat('0.00%');
		if ($filter_report == 'customers_abandoned_orders') {
		$percentFormat->setStrikeOut();
		}
				
		$boxFormatText =& $workbook->addFormat(array('bold' => 1));
		$boxFormatNumber =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		
		// Set the column widths
		$j = 0;
		if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {		
		$worksheet->setColumn($j,$j++,13);
		} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
		$worksheet->setColumn($j,$j++,13);
		$worksheet->setColumn($j,$j++,13);
		} else {
		if ($filter_group == 'year') {			
		$worksheet->setColumn($j,$j++,10);
		} elseif ($filter_group == 'quarter') {
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,10);	
		} elseif ($filter_group == 'month') {
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,13);
		} elseif ($filter_group == 'day') {
		$worksheet->setColumn($j,$j++,13);
		} elseif ($filter_group == 'order') {
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,13);
		} else {
		$worksheet->setColumn($j,$j++,13);
		$worksheet->setColumn($j,$j++,13);
		}
		}		
		if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {	
		in_array('scw_id', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_customer', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('scw_email', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('scw_telephone', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_customer_group', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	if ($custom_fields_name) {
	    foreach ($custom_fields_name as $custom_field_name) {
		in_array('scw_custom_fields', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	}
	    }		
		in_array('scw_customer_status', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_first_name', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_last_name', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_company', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_address_1', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('scw_address_2', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('scw_city', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_postcode', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_country_id', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_country', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_country_code', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_zone_id', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_region_state', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_region_state_code', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_newsletter', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_approved', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_safe', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_ip', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('scw_total_logins', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_last_login', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_cart_value', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		}
		in_array('scw_product_id', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_date_added', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_sku', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('scw_name', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_options', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		}
		in_array('scw_model', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('scw_category', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('scw_manufacturer', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,18) : '';
		in_array('scw_attribute', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('scw_price', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		}		
		} elseif ($filter_report == 'registered_customers_without_orders') {
		in_array('cwo_id', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('cwo_customer', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('cwo_email', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('cwo_telephone', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	if ($custom_fields_name) {
	    foreach ($custom_fields_name as $custom_field_name) {
		in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	}
	    }		
		in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_first_name', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_last_name', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_company', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_address_1', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('cwo_address_2', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('cwo_city', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_postcode', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('cwo_country_id', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('cwo_country', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_country_code', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('cwo_region_state', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('cwo_approved', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('cwo_safe', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('cwo_ip', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('cwo_last_login', $advco_settings_cwo_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		} else {
		in_array('mv_id', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_customer', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('mv_email', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,25) : '';
		in_array('mv_telephone', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_customer_group', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	if ($custom_fields_name) {
	    foreach ($custom_fields_name as $custom_field_name) {
		in_array('mv_custom_fields', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
    	}
	    }			
		in_array('mv_customer_status', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_first_name', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_last_name', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_company', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_address_1', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('mv_address_2', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,20) : '';
		in_array('mv_city', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_postcode', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_country_id', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_country', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_country_code', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_zone_id', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_region_state', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_region_state_code', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_newsletter', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_approved', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_safe', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_ip', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_total_logins', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_last_login', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';		
		in_array('mv_mostrecent', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_orders', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_products', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,10) : '';
		in_array('mv_total', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_aov', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,15) : '';
		in_array('mv_refunds', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		in_array('mv_reward_points', $advco_settings_mv_columns) ? $worksheet->setColumn($j,$j++,13) : '';
		}

		if ($export_logo_criteria) {
		$worksheet->setMerge(0, 0, 0, 1);
		$worksheet->writeString(0, 0, '', $criteriaDateFormat);			
		$worksheet->setMerge(0, 2, 0, $j-1);
		$worksheet->writeString(0, 2, $this->language->get('text_report_date').": ".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A"), $criteriaDateFormat);
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
				if ($filter_report == 'all_registered_customers_with_without_orders') {
					$filter_report_name = $this->language->get('text_all_registered_customers')." ".$this->language->get('text_with_without_orders');
				} elseif ($filter_report == 'registered_customers_with_orders') {
					$filter_report_name = $this->language->get('text_registered_customers')." ".$this->language->get('text_with_orders');
				} elseif ($filter_report == 'registered_customers_without_orders') {
					$filter_report_name = $this->language->get('text_registered_customers')." ".$this->language->get('text_without_orders');
				} elseif ($filter_report == 'registered_and_guest_customers') {
					$filter_report_name = html_entity_decode($this->language->get('text_registered_guest_customers'))." ".$this->language->get('text_with_orders');					
				} elseif ($filter_report == 'guest_customers') {
					$filter_report_name = $this->language->get('text_guest_customers')." ".$this->language->get('text_with_orders');
				} elseif ($filter_report == 'new_customers') {
					$filter_report_name = $this->language->get('text_new_customers');
				} elseif ($filter_report == 'old_customers') {
					$filter_report_name = $this->language->get('text_old_customers');		
				} elseif ($filter_report == 'customers_abandoned_orders') {
					$filter_report_name = $this->language->get('text_customers_abandoned_orders');				
				} elseif ($filter_report == 'customers_shopping_carts') {
					$filter_report_name = $this->language->get('text_customers_shopping_carts');					
				} elseif ($filter_report == 'customers_wishlists') {
					$filter_report_name = $this->language->get('text_customers_wishlists');					
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
				if ($filter_group == 'no_group') {
					$filter_group_name = $this->language->get('text_no_group');
				} elseif ($filter_group == 'year') {
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
				if ($filter_sort == 'date') {
					$filter_sort_name = $this->language->get('column_date');
				} elseif ($filter_sort == 'id') {
					$filter_sort_name = ($filter_report == 'registered_and_guest_customers' or $filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id');
				} elseif ($filter_sort == 'customer') {
					$filter_sort_name = $this->language->get('column_customer');	
				} elseif ($filter_sort == 'email') {
					$filter_sort_name = $this->language->get('column_email');
				} elseif ($filter_sort == 'telephone') {
					$filter_sort_name = $this->language->get('column_telephone');
				} elseif ($filter_sort == 'customer_group') {
					$filter_sort_name = $this->language->get('column_customer_group');
				} elseif ($filter_sort == 'customer_status') {
					$filter_sort_name = $this->language->get('column_customer_status');	
				} elseif ($filter_sort == 'first_name') {
					$filter_sort_name = $this->language->get('column_first_name');
				} elseif ($filter_sort == 'last_name') {
					$filter_sort_name = $this->language->get('column_last_name');	
				} elseif ($filter_sort == 'company') {
					$filter_sort_name = $this->language->get('column_company');
				} elseif ($filter_sort == 'address_1') {
					$filter_sort_name = $this->language->get('column_address_1');
				} elseif ($filter_sort == 'address_2') {
					$filter_sort_name = $this->language->get('column_address_2');
				} elseif ($filter_sort == 'city') {
					$filter_sort_name = $this->language->get('column_city');	
				} elseif ($filter_sort == 'postcode') {
					$filter_sort_name = $this->language->get('column_postcode');
				} elseif ($filter_sort == 'country_id') {
					$filter_sort_name = $this->language->get('column_country_id');	
				} elseif ($filter_sort == 'country') {
					$filter_sort_name = $this->language->get('column_country');
				} elseif ($filter_sort == 'country_code') {
					$filter_sort_name = $this->language->get('column_country_code');
				} elseif ($filter_sort == 'zone_id') {
					$filter_sort_name = $this->language->get('column_zone_id');
				} elseif ($filter_sort == 'region_state') {
					$filter_sort_name = $this->language->get('column_region_state');	
				} elseif ($filter_sort == 'region_state_code') {
					$filter_sort_name = $this->language->get('column_region_state_code');
				} elseif ($filter_sort == 'newsletter') {
					$filter_sort_name = $this->language->get('column_newsletter');	
				} elseif ($filter_sort == 'approved') {
					$filter_sort_name = $this->language->get('column_approved');
				} elseif ($filter_sort == 'safe') {
					$filter_sort_name = $this->language->get('column_safe');
				} elseif ($filter_sort == 'ip') {
					$filter_sort_name = $this->language->get('column_ip');
				} elseif ($filter_sort == 'total_logins') {
					$filter_sort_name = $this->language->get('column_total_logins');
				} elseif ($filter_sort == 'last_login') {
					$filter_sort_name = $this->language->get('column_last_login');		
				} elseif ($filter_sort == 'cart_quantity') {
					$filter_sort_name = $this->language->get('column_cart_quantity');
				} elseif ($filter_sort == 'wishlist_quantity') {
					$filter_sort_name = $this->language->get('column_wishlist_quantity');						
				} elseif ($filter_sort == 'mostrecent') {
					$filter_sort_name = $this->language->get('column_mostrecent');	
				} elseif ($filter_sort == 'orders') {
					$filter_sort_name = $this->language->get('column_orders');
				} elseif ($filter_sort == 'products') {
					$filter_sort_name = $this->language->get('column_products');
				} elseif ($filter_sort == 'total') {
					$filter_sort_name = $this->language->get('column_total');
				} elseif ($filter_sort == 'aov') {
					$filter_sort_name = $this->language->get('column_aov');
				} elseif ($filter_sort == 'refunds') {
					$filter_sort_name = $this->language->get('column_refunds');
				} elseif ($filter_sort == 'reward_points') {
					$filter_sort_name = $this->language->get('column_customer_reward_points');				
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
			if ($filter_customer_status) {	
				$filter_criteria .= $this->language->get('entry_customer_status')." ".$filter_customer_status."; ";
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
		if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {	
		$worksheet->writeString($i, $j++, $this->language->get('column_date_added'), $boxFormatText);
		} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
		$worksheet->writeString($i, $j++, $this->language->get('column_date_start'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_date_end'), $boxFormatText);		
		} else {
		if ($filter_group == 'year') {	
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText);
		} elseif ($filter_group == 'quarter') {
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_quarter'), $boxFormatText);
		} elseif ($filter_group == 'month') {
		$worksheet->writeString($i, $j++, $this->language->get('column_year'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_month'), $boxFormatText);
		} elseif ($filter_group == 'day') {
		$worksheet->writeString($i, $j++, $this->language->get('column_date'), $boxFormatText);
		} elseif ($filter_group == 'order') {
		$worksheet->writeString($i, $j++, $this->language->get('column_order_order_id'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_order_date_added'), $boxFormatText);
		} else {
		$worksheet->writeString($i, $j++, $this->language->get('column_date_start'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_date_end'), $boxFormatText);
		}
		}	
		
		if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {

		in_array('scw_id', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_id'), $boxFormatText) : '';
		in_array('scw_customer', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer')." / ".$this->language->get('column_company'), $boxFormatText) : '';
		in_array('scw_email', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_email'), $boxFormatText) : '';
		in_array('scw_telephone', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_telephone'), $boxFormatText) : '';
		in_array('scw_customer_group', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_group'), $boxFormatText) : '';
		if ($custom_fields_name) {
		foreach ($custom_fields_name as $custom_field_name) {
		in_array('scw_custom_fields', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $custom_field_name['name'], $boxFormatText) : '';
		}
		}		
		in_array('scw_customer_status', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_status'), $boxFormatText) : '';
		in_array('scw_first_name', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_first_name'), $boxFormatText) : '';
		in_array('scw_last_name', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_name'), $boxFormatText) : '';
		in_array('scw_company', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_company'), $boxFormatText) : '';
		in_array('scw_address_1', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_1'), $boxFormatText) : '';
		in_array('scw_address_2', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_2'), $boxFormatText) : '';
		in_array('scw_city', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_city'), $boxFormatText) : '';
		in_array('scw_postcode', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_postcode'), $boxFormatText) : '';
		in_array('scw_country_id', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_id'), $boxFormatText) : '';
		in_array('scw_country', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country'), $boxFormatText) : '';
		in_array('scw_country_code', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_code'), $boxFormatText) : '';
		in_array('scw_zone_id', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_zone_id'), $boxFormatText) : '';
		in_array('scw_region_state', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state'), $boxFormatText) : '';
		in_array('scw_region_state_code', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state_code'), $boxFormatText) : '';
		in_array('scw_newsletter', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_newsletter'), $boxFormatText) : '';
		in_array('scw_approved', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_approved'), $boxFormatText) : '';
		in_array('scw_safe', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_safe'), $boxFormatText) : '';
		in_array('scw_ip', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_ip'), $boxFormatText) : '';
		in_array('scw_total_logins', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_total_logins'), $boxFormatNumber) : '';
		in_array('scw_last_login', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_login'), $boxFormatText) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_cart_quantity'), $boxFormatNumber) : '';
		in_array('scw_cart_value', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_cart_value'), $boxFormatNumber) : '';
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_wishlist_quantity'), $boxFormatNumber) : '';
		in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_wishlist_value'), $boxFormatNumber) : '';
		}
		in_array('scw_product_id', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_id'), $boxFormatText) : '';
		in_array('scw_date_added', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_date_added'), $boxFormatText) : '';
		in_array('scw_sku', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_sku'), $boxFormatText) : '';
		in_array('scw_name', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_name'), $boxFormatText) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_options', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_option'), $boxFormatText) : '';
		}		
		in_array('scw_model', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_model'), $boxFormatText) : '';
		in_array('scw_category', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_category'), $boxFormatText) : '';
		in_array('scw_manufacturer', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_manu'), $boxFormatText) : '';
		in_array('scw_attribute', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_prod_attributes'), $boxFormatText) : '';
		in_array('scw_price', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_price'), $boxFormatNumber) : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_cart_quantity'), $boxFormatNumber) : '';
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_wishlist_quantity'), $boxFormatNumber) : '';
		}
	
		} elseif ($filter_report == 'registered_customers_without_orders') {

		in_array('cwo_id', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_id'), $boxFormatText) : '';
		in_array('cwo_customer', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer')." / ".$this->language->get('column_company'), $boxFormatText) : '';
		in_array('cwo_email', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_email'), $boxFormatText) : '';
		in_array('cwo_telephone', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_telephone'), $boxFormatText) : '';
		in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_group'), $boxFormatText) : '';
		if ($custom_fields_name) {
		foreach ($custom_fields_name as $custom_field_name) {
		in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $custom_field_name['name'], $boxFormatText) : '';
		}
		}		
		in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_status'), $boxFormatText) : '';
		in_array('cwo_first_name', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_first_name'), $boxFormatText) : '';
		in_array('cwo_last_name', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_name'), $boxFormatText) : '';
		in_array('cwo_company', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_company'), $boxFormatText) : '';
		in_array('cwo_address_1', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_1'), $boxFormatText) : '';
		in_array('cwo_address_2', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_2'), $boxFormatText) : '';
		in_array('cwo_city', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_city'), $boxFormatText) : '';
		in_array('cwo_postcode', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_postcode'), $boxFormatText) : '';
		in_array('cwo_country_id', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_id'), $boxFormatText) : '';
		in_array('cwo_country', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country'), $boxFormatText) : '';
		in_array('cwo_country_code', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_code'), $boxFormatText) : '';
		in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_zone_id'), $boxFormatText) : '';
		in_array('cwo_region_state', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state'), $boxFormatText) : '';
		in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state_code'), $boxFormatText) : '';
		in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_newsletter'), $boxFormatText) : '';
		in_array('cwo_approved', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_approved'), $boxFormatText) : '';
		in_array('cwo_safe', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_safe'), $boxFormatText) : '';
		in_array('cwo_ip', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_ip'), $boxFormatText) : '';
		in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_total_logins'), $boxFormatNumber) : '';
		in_array('cwo_last_login', $advco_settings_cwo_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_login'), $boxFormatText) : '';
		
		} else {
			
		in_array('mv_id', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, (($filter_report == 'registered_and_guest_customers' or $filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id')), $boxFormatText) : '';
		in_array('mv_customer', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer')." / ".$this->language->get('column_company'), $boxFormatText) : '';
		in_array('mv_email', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_email'), $boxFormatText) : '';
		in_array('mv_telephone', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_telephone'), $boxFormatText) : '';
		in_array('mv_customer_group', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_group'), $boxFormatText) : '';
		if ($custom_fields_name) {
		foreach ($custom_fields_name as $custom_field_name) {
		in_array('mv_custom_fields', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $custom_field_name['name'], $boxFormatText) : '';
		}
		}		
		in_array('mv_customer_status', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_status'), $boxFormatText) : '';
		in_array('mv_first_name', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_first_name'), $boxFormatText) : '';
		in_array('mv_last_name', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_name'), $boxFormatText) : '';
		in_array('mv_company', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_company'), $boxFormatText) : '';
		in_array('mv_address_1', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_1'), $boxFormatText) : '';
		in_array('mv_address_2', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_address_2'), $boxFormatText) : '';
		in_array('mv_city', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_city'), $boxFormatText) : '';
		in_array('mv_postcode', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_postcode'), $boxFormatText) : '';
		in_array('mv_country_id', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_id'), $boxFormatText) : '';
		in_array('mv_country', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country'), $boxFormatText) : '';
		in_array('mv_country_code', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_country_code'), $boxFormatText) : '';
		in_array('mv_zone_id', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_zone_id'), $boxFormatText) : '';
		in_array('mv_region_state', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state'), $boxFormatText) : '';
		in_array('mv_region_state_code', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_region_state_code'), $boxFormatText) : '';
		in_array('mv_newsletter', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_newsletter'), $boxFormatText) : '';
		in_array('mv_approved', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_approved'), $boxFormatText) : '';
		in_array('mv_safe', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_safe'), $boxFormatText) : '';
		in_array('mv_ip', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_ip'), $boxFormatText) : '';
		in_array('mv_total_logins', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_total_logins'), $boxFormatNumber) : '';
		in_array('mv_last_login', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_last_login'), $boxFormatText) : '';		
		in_array('mv_mostrecent', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_mostrecent'), $boxFormatText) : '';
		in_array('mv_orders', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_orders'), $boxFormatNumber) : '';
		in_array('mv_products', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_products'), $boxFormatNumber) : '';
		in_array('mv_total', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_total'), $boxFormatNumber) : '';
		in_array('mv_aov', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_aov'), $boxFormatNumber) : '';
		in_array('mv_refunds', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_refunds'), $boxFormatNumber) : '';
		in_array('mv_reward_points', $advco_settings_mv_columns) ? $worksheet->writeString($i, $j++, $this->language->get('column_customer_reward_points'), $boxFormatNumber) : '';
		}
		
		// The actual orders data
		$i += 1;
		$j = 0;
		
			foreach ($results as $result) {
			$excelRow = $i+1;
				if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {
				$worksheet->write($i, $j++, $result['date_added'], $textFormat);
				} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
				$worksheet->write($i, $j++, $result['date_start'], $textFormat);
				$worksheet->write($i, $j++, $result['date_end'], $textFormat);
				} else {
				if ($filter_group == 'year') {	
				$worksheet->write($i, $j++, $result['year'], $textFormat);
				} elseif ($filter_group == 'quarter') {
				$worksheet->write($i, $j++, $result['year'], $textFormat);
				$worksheet->write($i, $j++, 'Q' . $result['quarter'], $textFormat);		
				} elseif ($filter_group == 'month') {
				$worksheet->write($i, $j++, $result['year'], $textFormat);
				$worksheet->write($i, $j++, $result['month'], $textFormat);
				} elseif ($filter_group == 'day') {
				$worksheet->write($i, $j++, $result['date_start'], $textFormat);
				} elseif ($filter_group == 'order') {
				$worksheet->write($i, $j++, $result['order_id'], $textFormat);
				$worksheet->write($i, $j++, $result['date_start'], $textFormat);
				} else {
				$worksheet->write($i, $j++, $result['date_start'], $textFormat);
				$worksheet->write($i, $j++, $result['date_end'], $textFormat);
				}
				}
				
				if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {

				in_array('scw_id', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['customer_id'], $textFormat) : '';
				in_array('scw_customer', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name'])) : '';
				in_array('scw_email', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_email']) : '';
				in_array('scw_telephone', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_telephone'], $textFormat) : '';
				in_array('scw_customer_group', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_group']) : '';
				if ($result['custom_fields']) {
				foreach ($result['custom_fields'] as $custom_field) {
				in_array('scw_custom_fields', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $custom_field['value'], $textFormat) : '';
				}
				} else {
				foreach ($custom_fields_name as $custom_field_name) {
				in_array('scw_custom_fields', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, '') : '';
				}
				}					
				in_array('scw_customer_status', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_status']) : '';			
				in_array('scw_first_name', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_first_name']) : '';
				in_array('scw_last_name', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_last_name']) : '';
				in_array('scw_company', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_company']) : '';
				in_array('scw_address_1', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_address_1']) : '';
				in_array('scw_address_2', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_address_2']) : '';
				in_array('scw_city', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_city']) : '';
				in_array('scw_postcode', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_postcode'], $textFormat) : '';
				in_array('scw_country_id', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_country_id'], $textFormat) : '';
				in_array('scw_country', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_country']) : '';
				in_array('scw_country_code', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_country_code']) : '';
				in_array('scw_zone_id', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_zone_id'], $textFormat) : '';
				in_array('scw_region_state', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_region_state']) : '';
				in_array('scw_region_state_code', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cust_region_state_code']) : '';
				in_array('scw_newsletter', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['newsletter']) : '';
				in_array('scw_approved', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['approved']) : '';
				in_array('scw_safe', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['safe']) : '';
				in_array('scw_ip', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['ip']) : '';
				in_array('scw_total_logins', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['total_logins']) : '';
				in_array('scw_last_login', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['last_login']) : '';
				if ($filter_report == 'customers_shopping_carts') {
				in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cart_quantity'], $soldColumnFormat) : '';
				in_array('scw_cart_value', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['cart_value_raw'], $soldColumnFormat) : '';
				} elseif ($filter_report == 'customers_wishlists') {
				in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['wishlist_quantity'], $soldColumnFormat) : '';
				in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result['wishlist_value_raw'], $soldColumnFormat) : '';	
				}	
				if ($result['product']) {	
				foreach ($result['product'] as $product) {
				$c = $j;
				in_array('scw_product_id', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['product_id'], $textFormat) : '';
				in_array('scw_date_added', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['date_added'], $textFormat) : '';
				in_array('scw_sku', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['sku'], $textFormat) : '';
				in_array('scw_name', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, html_entity_decode($product['name']), $textFormat) : '';
				if ($filter_report == 'customers_shopping_carts') {
				if ($product['option']) {
				$resultstr = array();
				foreach ($product['option'] as $option) {
				$resultstr[] = $option['name'].': '.$option['value'];
				}
				$result_options = implode("; ",$resultstr);
				in_array('scw_options', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $result_options, $textFormat) : '';
				} else {
				in_array('scw_options', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, '') : '';
				}
				}				
				in_array('scw_model', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['model'], $textFormat) : '';
				in_array('scw_category', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, html_entity_decode($product['category']), $textFormat) : '';
				in_array('scw_manufacturer', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, html_entity_decode($product['manufacturer']), $textFormat) : '';
				in_array('scw_attribute', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, html_entity_decode($product['attribute']), $textFormat) : '';
				in_array('scw_price', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['price_raw'], $priceFormat) : '';
				if ($filter_report == 'customers_shopping_carts') {
				in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['cart_quantity'], $soldColumnFormat) : '';
				} elseif ($filter_report == 'customers_wishlists') {
				in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $worksheet->write($i, $j++, $product['wishlist_quantity'], $soldColumnFormat) : '';
				}					
				$j = $c;
				$i += 1;
				}
				}
				
				} elseif ($filter_report == 'registered_customers_without_orders') {

				in_array('cwo_id', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['customer_id'], $textFormat) : '';
				in_array('cwo_customer', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name'])) : '';
				in_array('cwo_email', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_email']) : '';
				in_array('cwo_telephone', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_telephone'], $textFormat) : '';
				in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_group']) : '';
				if ($result['custom_fields']) {
				foreach ($result['custom_fields'] as $custom_field) {
				in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $custom_field['value'], $textFormat) : '';
				}
				} else {
				foreach ($custom_fields_name as $custom_field_name) {
				in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, '') : '';
				}
				}					
				in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_status']) : '';			
				in_array('cwo_first_name', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_first_name']) : '';
				in_array('cwo_last_name', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_last_name']) : '';
				in_array('cwo_company', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_company']) : '';
				in_array('cwo_address_1', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_address_1']) : '';
				in_array('cwo_address_2', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_address_2']) : '';
				in_array('cwo_city', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_city']) : '';
				in_array('cwo_postcode', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_postcode'], $textFormat) : '';
				in_array('cwo_country_id', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_country_id'], $textFormat) : '';
				in_array('cwo_country', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_country']) : '';
				in_array('cwo_country_code', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_country_code']) : '';
				in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_zone_id'], $textFormat) : '';
				in_array('cwo_region_state', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_region_state']) : '';
				in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['cust_region_state_code']) : '';
				in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['newsletter']) : '';
				in_array('cwo_approved', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['approved']) : '';
				in_array('cwo_safe', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['safe']) : '';
				in_array('cwo_ip', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['ip']) : '';
				in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['total_logins']) : '';
				in_array('cwo_last_login', $advco_settings_cwo_columns) ? $worksheet->write($i, $j++, $result['last_login']) : '';
				
				} else {
					
				in_array('mv_id', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, ($result['customer_id'] > 0 ? $result['customer_id'] : $this->language->get('text_guest')), $textFormat) : '';
				in_array('mv_customer', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name'])) : '';
				in_array('mv_email', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_email']) : '';
				in_array('mv_telephone', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_telephone'], $textFormat) : '';
				if ($filter_report == 'all_registered_customers_with_without_orders') {
				in_array('mv_customer_group', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_group']) : '';
				} else {
				if ($result['customer_id'] == 0) {
				in_array('mv_customer_group', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_group_guest']) : '';
				} else {
				in_array('mv_customer_group', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_group_reg']) : '';
				}
				}
				if ($result['custom_fields']) {
				foreach ($result['custom_fields'] as $custom_field) {
				in_array('mv_custom_fields', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $custom_field['value'], $textFormat) : '';
				}
				} else {
				foreach ($custom_fields_name as $custom_field_name) {
				in_array('mv_custom_fields', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, '') : '';
				}
				}					
				in_array('mv_customer_status', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_status']) : '';			
				in_array('mv_first_name', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_first_name']) : '';
				in_array('mv_last_name', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_last_name']) : '';
				in_array('mv_company', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_company']) : '';
				in_array('mv_address_1', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_address_1']) : '';
				in_array('mv_address_2', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_address_2']) : '';
				in_array('mv_city', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_city']) : '';
				in_array('mv_postcode', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_postcode'], $textFormat) : '';
				in_array('mv_country_id', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_country_id'], $textFormat) : '';
				in_array('mv_country', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_country']) : '';
				in_array('mv_country_code', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_country_code']) : '';
				in_array('mv_zone_id', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_zone_id'], $textFormat) : '';
				in_array('mv_region_state', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_region_state']) : '';
				in_array('mv_region_state_code', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['cust_region_state_code']) : '';
				in_array('mv_newsletter', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['newsletter']) : '';
				in_array('mv_approved', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['approved']) : '';
				in_array('mv_safe', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['safe']) : '';
				in_array('mv_ip', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['ip']) : '';
				in_array('mv_total_logins', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['total_logins']) : '';
				in_array('mv_last_login', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['last_login']) : '';				
				in_array('mv_mostrecent', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['mostrecent'], $filter_report != 'customers_abandoned_orders' ? $textFormat : $abtextFormat) : '';
				in_array('mv_orders', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['orders'], $filter_report != 'customers_abandoned_orders' ? $numberFormat : $abnumberFormat) : '';
				in_array('mv_products', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['products'], $filter_report != 'customers_abandoned_orders' ? $numberFormat : $abnumberFormat) : '';
				in_array('mv_total', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['total_raw'] != NULL ? $result['total_raw'] : '0.00', $priceFormat) : '';
				in_array('mv_aov', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['aov_raw'] != NULL ? $result['aov_raw'] : '0.00', $priceFormat) : '';
				in_array('mv_refunds', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['refunds_raw'] != NULL ? $result['refunds_raw'] : '0.00', $priceFormat) : '';
				in_array('mv_reward_points', $advco_settings_mv_columns) ? $worksheet->write($i, $j++, $result['reward_points'], $filter_report != 'customers_abandoned_orders' ? $numberFormat : $abnumberFormat) : '';
				}

				$i += 1;
				$j = 0;
			}

		$freeze = ($export_logo_criteria ? array(5, 0, 5, 0) : array(1, 0, 1, 0));
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