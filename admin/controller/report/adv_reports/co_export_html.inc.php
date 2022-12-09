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
	$c = 0;
	
	$export_html ="<html><head>";
	$export_html .="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	$export_html .="</head>";
	$export_html .="<body>";
	$export_html .="<style type='text/css'>
	.list_criteria {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DBE5F1;
		border-left: 1px solid #DBE5F1;	
		padding: 3px;		
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		background: url('$logo') 5px 18px no-repeat #DBE5F1;
		background-size: 268px 50px;
	}
	.list_criteria td {
		border-right: 1px solid #DBE5F1;
		border-bottom: 1px solid #DBE5F1;	
	}
	
	.list_main {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;	
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	.list_main td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;	
	}
	.list_main thead td {
		background-color: #F0F0F0;
		padding: 3px;
		font-weight: bold;
	}
	.list_main tbody a {
		text-decoration: none;
	}
	.list_main tbody td {
		vertical-align: middle;
		padding: 3px;
	}

	.list_detail {
		border-collapse: collapse;
		width: 100%;
		border-top: 1px solid #DDDDDD;
		border-left: 1px solid #DDDDDD;
		font-family: Arial, Helvetica, sans-serif;	
		margin-top: 5px;
		margin-bottom: 5px;
	}
	.list_detail td {
		border-right: 1px solid #DDDDDD;
		border-bottom: 1px solid #DDDDDD;
	}
	.list_detail thead td {
		background-color: #f5f5f5;
		padding: 0px 3px;
		font-size: 10px;
		font-weight: bold;	
	}
	.list_detail tbody td {
		padding: 0px 3px;
		font-size: 10px;	
	}
	
	.total {
		background-color: #E7EFEF;
		color: #003A88;
		font-weight: bold;
	}	
	</style>";
	if ($export_logo_criteria) {
	$export_html .="<table class='list_criteria'>";
	$export_html .="<tr>";
	$export_html .= "<td colspan='3' align='center'>".$this->language->get('text_report_date').": ".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A")."</td><td></td></tr>";
	$export_html .="<tr>";
	$export_html .= "<td colspan='3' align='center' style='height:50px; font-size:24; font-weight:bold;'>".$this->language->get('adv_ext_name')."</td><td width='1%' align='left' valign='top' nowrap='nowrap'><b>".$this->config->get('config_name')."</b> <br>".$this->config->get('config_address')." <br>".$this->language->get('text_email')."".$this->config->get('config_email')." <br>".$this->language->get('text_telephone')."".$this->config->get('config_telephone')." </td></tr>";
	$export_html .="<tr>";
	$export_html .= "<td align='right' valign='top' style='height:50px; width:150px; font-weight:bold;'>".$this->language->get('text_report_criteria')." </td>";	
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
			$filter_criteria .= "<br />";
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
			$filter_criteria .= "<br />";
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
	$export_html .= "<td colspan='2' align='left' valign='top'>".$filter_criteria."</td><td></td></tr>";
	$export_html .="</table>";
	}	
	$export_html .="<table class='list_main'>";
	$export_html .="<thead>";
	$export_html .="<tr>";
	if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {	
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_date_added')."</td>";
	} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	$c++ . $export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$c++ . $export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	} else {
	if ($filter_group == 'year') {				
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_quarter')."</td>";				
	} elseif ($filter_group == 'month') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_year')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_month')."</td>";
	} elseif ($filter_group == 'day') {
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap'>".$this->language->get('column_date')."</td>";
	} elseif ($filter_group == 'order') {
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_order_id')."</td>";
	$export_html .= "<td align='left' nowrap='nowrap'>".$this->language->get('column_order_date_added')."</td>";
	} else {
	$export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_start')."</td>";
	$export_html .= "<td align='left' width='80' nowrap='nowrap'>".$this->language->get('column_date_end')."</td>";	
	}
	}
	if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {	
	in_array('scw_id', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_id')."</td>" : '';
	in_array('scw_customer', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left' style='min-width:120px;'>".$this->language->get('column_customer')." / ".$this->language->get('column_company')."</td>" : '';
	in_array('scw_email', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_email')."</td>" : '';
	in_array('scw_telephone', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_telephone')."</td>" : '';
	in_array('scw_customer_group', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_customer_group')."</td>" : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$custom_field_name['name']."</td>" : '';
    }
    }
	in_array('scw_customer_status', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_customer_status')."</td>" : '';
	in_array('scw_first_name', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_first_name')."</td>" : '';
	in_array('scw_last_name', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_last_name')."</td>" : '';
	in_array('scw_company', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_company')."</td>" : '';
	in_array('scw_address_1', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_address_1')."</td>" : '';
	in_array('scw_address_2', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_address_2')."</td>" : '';
	in_array('scw_city', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_city')."</td>" : '';
	in_array('scw_postcode', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_postcode')."</td>" : '';
	in_array('scw_country_id', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_country_id')."</td>" : '';
	in_array('scw_country', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_country')."</td>" : '';
	in_array('scw_country_code', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_country_code')."</td>" : '';
	in_array('scw_zone_id', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_zone_id')."</td>" : '';
	in_array('scw_region_state', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_region_state')."</td>" : '';
	in_array('scw_region_state_code', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left' style='min-width:80px;'>".$this->language->get('column_region_state_code')."</td>" : '';
	in_array('scw_newsletter', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_newsletter')."</td>" : '';
	in_array('scw_approved', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_approved')."</td>" : '';
	in_array('scw_safe', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_safe')."</td>" : '';
	in_array('scw_ip', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_ip')."</td>" : '';
	in_array('scw_total_logins', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_total_logins')."</td>" : '';
	in_array('scw_last_login', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='left'>".$this->language->get('column_last_login')."</td>" : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_cart_quantity')."</td>" : '';
	in_array('scw_cart_value', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_cart_value')."</td>" : '';
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_wishlist_quantity')."</td>" : '';
	in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $c++ . $export_html .= "<td align='right'>".$this->language->get('column_wishlist_value')."</td>" : '';
	}	
	} elseif ($filter_report == 'registered_customers_without_orders') {
	in_array('cwo_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_id')."</td>" : '';
	in_array('cwo_customer', $advco_settings_cwo_columns) ? $export_html .= "<td align='left' style='min-width:120px;'>".$this->language->get('column_customer')." / ".$this->language->get('column_company')."</td>" : '';
	in_array('cwo_email', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_email')."</td>" : '';
	in_array('cwo_telephone', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_telephone')."</td>" : '';
	in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_group')."</td>" : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$custom_field_name['name']."</td>" : '';
    }
    }	
	in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_status')."</td>" : '';
	in_array('cwo_first_name', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_first_name')."</td>" : '';
	in_array('cwo_last_name', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_last_name')."</td>" : '';
	in_array('cwo_company', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_company')."</td>" : '';
	in_array('cwo_address_1', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_1')."</td>" : '';
	in_array('cwo_address_2', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_2')."</td>" : '';
	in_array('cwo_city', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_city')."</td>" : '';
	in_array('cwo_postcode', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_postcode')."</td>" : '';
	in_array('cwo_country_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country_id')."</td>" : '';
	in_array('cwo_country', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country')."</td>" : '';
	in_array('cwo_country_code', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country_code')."</td>" : '';
	in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_zone_id')."</td>" : '';
	in_array('cwo_region_state', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_region_state')."</td>" : '';
	in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $export_html .= "<td align='left' style='min-width:80px;'>".$this->language->get('column_region_state_code')."</td>" : '';
	in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_newsletter')."</td>" : '';
	in_array('cwo_approved', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_approved')."</td>" : '';
	in_array('cwo_safe', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_safe')."</td>" : '';
	in_array('cwo_ip', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_ip')."</td>" : '';
	in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_total_logins')."</td>" : '';
	in_array('cwo_last_login', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_last_login')."</td>" : '';
	} else {
	in_array('mv_id', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".(($filter_report == 'registered_and_guest_customers' or $filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id'))."</td>" : '';
	in_array('mv_customer', $advco_settings_mv_columns) ? $export_html .= "<td align='left' style='min-width:120px;'>".$this->language->get('column_customer')." / ".$this->language->get('column_company')."</td>" : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_email')."</td>" : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_telephone')."</td>" : '';
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_group')."</td>" : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$custom_field_name['name']."</td>" : '';
    }
    }		
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_customer_status')."</td>" : '';
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_first_name')."</td>" : '';
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_last_name')."</td>" : '';
	in_array('mv_company', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_company')."</td>" : '';
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_1')."</td>" : '';
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_address_2')."</td>" : '';
	in_array('mv_city', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_city')."</td>" : '';
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_postcode')."</td>" : '';
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country_id')."</td>" : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country')."</td>" : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_country_code')."</td>" : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_zone_id')."</td>" : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_region_state')."</td>" : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_html .= "<td align='left' style='min-width:80px;'>".$this->language->get('column_region_state_code')."</td>" : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_newsletter')."</td>" : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_approved')."</td>" : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_safe')."</td>" : '';
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_ip')."</td>" : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_total_logins')."</td>" : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_last_login')."</td>" : '';	
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_mostrecent')."</td>" : '';
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_orders')."</td>" : '';
	in_array('mv_products', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_products')."</td>" : '';
	in_array('mv_total', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_total')."</td>" : '';	
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_html .= "<td align='right' style='min-width:70px;'>".$this->language->get('column_aov')."</td>" : '';	
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_refunds')."</td>" : '';	
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_customer_reward_points')."</td>" : '';
	}
	$export_html .="</tr>";
	$export_html .="</thead><tbody>";
	
	foreach ($results as $result) {
	$export_html .="<tr>";
	if ($filter_report == 'all_registered_customers_with_without_orders' or $filter_report == 'registered_customers_without_orders') {	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_added']."</td>";
	} elseif ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_end']."</td>";
	} else {
	if ($filter_group == 'year') {				
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";
	} elseif ($filter_group == 'quarter') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['quarter']."</td>";						
	} elseif ($filter_group == 'month') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['year']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['month']."</td>";	
	} elseif ($filter_group == 'day') {
	$export_html .= "<td colspan='2' align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	} elseif ($filter_group == 'order') {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['order_id']."</td>";	
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	} else {
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_start']."</td>";
	$export_html .= "<td align='left' nowrap='nowrap' style='background-color:#F9F9F9;'>".$result['date_end']."</td>";
	}
	}
	if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	in_array('scw_id', $advco_settings_scw_columns) ? $export_html .= "<td align='right'>".$result['customer_id']."</td>" : '';
	in_array('scw_customer', $advco_settings_scw_columns) ? $export_html .= "<td align='left' style='color:#03C;'><b>".$result['cust_name']."</b><br>".$result['cust_company']."</td>" : '';
	in_array('scw_email', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_email']."</td>" : '';
	in_array('scw_telephone', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_telephone']."</td>" : '';
	in_array('scw_customer_group', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_group']."</td>" : '';
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$custom_field['value']."</td>" : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('scw_custom_fields', $advco_settings_scw_columns) ? $export_html .= "<td align='left'></td>" : '';
	}
	}	
	in_array('scw_customer_status', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_status']."</td>" : '';
	in_array('scw_first_name', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_first_name']."</td>" : '';
	in_array('scw_last_name', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_last_name']."</td>" : '';
	in_array('scw_company', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_company']."</td>" : '';
	in_array('scw_address_1', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_address_1']."</td>" : '';
	in_array('scw_address_2', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_address_2']."</td>" : '';
	in_array('scw_city', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_city']."</td>" : '';
	in_array('scw_postcode', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_postcode']."</td>" : '';
	in_array('scw_country_id', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_country_id']."</td>" : '';
	in_array('scw_country', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_country']."</td>" : '';
	in_array('scw_country_code', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_country_code']."</td>" : '';
	in_array('scw_zone_id', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_zone_id']."</td>" : '';
	in_array('scw_region_state', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state']."</td>" : '';
	in_array('scw_region_state_code', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state_code']."</td>" : '';
	in_array('scw_newsletter', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['newsletter']."</td>" : '';
	in_array('scw_approved', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['approved']."</td>" : '';
	in_array('scw_safe', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['safe']."</td>" : '';
	in_array('scw_ip', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['ip']."</td>" : '';
	in_array('scw_total_logins', $advco_settings_scw_columns) ? $export_html .= "<td align='right'>".$result['total_logins']."</td>" : '';
	in_array('scw_last_login', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$result['last_login']."</td>" : '';
	if ($filter_report == 'customers_shopping_carts') {
	in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right' style='background-color:#FFC;'>".$result['cart_quantity']."</td>" : '';
	in_array('scw_cart_value', $advco_settings_scw_columns) ? $export_html .= "<td align='right' style='background-color:#FFC;'>".$result['cart_value']."</td>" : '';
	} elseif ($filter_report == 'customers_wishlists') {
	in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right' style='background-color:#FFC;'>".$result['wishlist_quantity']."</td>" : '';
	in_array('scw_wishlist_value', $advco_settings_scw_columns) ? $export_html .= "<td align='right' style='background-color:#FFC;'>".$result['wishlist_value']."</td>" : '';
	}	
	} elseif ($filter_report == 'registered_customers_without_orders') {
	in_array('cwo_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='right'>".$result['customer_id']."</td>" : '';
	in_array('cwo_customer', $advco_settings_cwo_columns) ? $export_html .= "<td align='left' style='color:#03C;'><b>".$result['cust_name']."</b><br>".$result['cust_company']."</td>" : '';
	in_array('cwo_email', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_email']."</td>" : '';
	in_array('cwo_telephone', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_telephone']."</td>" : '';
	in_array('cwo_customer_group', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_group']."</td>" : '';
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$custom_field['value']."</td>" : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('cwo_custom_fields', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'></td>" : '';
	}
	}		
	in_array('cwo_customer_status', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_status']."</td>" : '';
	in_array('cwo_first_name', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_first_name']."</td>" : '';
	in_array('cwo_last_name', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_last_name']."</td>" : '';
	in_array('cwo_company', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_company']."</td>" : '';
	in_array('cwo_address_1', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_address_1']."</td>" : '';
	in_array('cwo_address_2', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_address_2']."</td>" : '';
	in_array('cwo_city', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_city']."</td>" : '';
	in_array('cwo_postcode', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_postcode']."</td>" : '';
	in_array('cwo_country_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_country_id']."</td>" : '';
	in_array('cwo_country', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_country']."</td>" : '';
	in_array('cwo_country_code', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_country_code']."</td>" : '';
	in_array('cwo_zone_id', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_zone_id']."</td>" : '';
	in_array('cwo_region_state', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state']."</td>" : '';
	in_array('cwo_region_state_code', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state_code']."</td>" : '';
	in_array('cwo_newsletter', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['newsletter']."</td>" : '';
	in_array('cwo_approved', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['approved']."</td>" : '';
	in_array('cwo_safe', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['safe']."</td>" : '';
	in_array('cwo_ip', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['ip']."</td>" : '';
	in_array('cwo_total_logins', $advco_settings_cwo_columns) ? $export_html .= "<td align='right'>".$result['total_logins']."</td>" : '';
	in_array('cwo_last_login', $advco_settings_cwo_columns) ? $export_html .= "<td align='left'>".$result['last_login']."</td>" : '';
	} else {
	in_array('mv_id', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".($result['customer_id'] > 0 ? $result['customer_id'] : $this->language->get('text_guest'))."</td>" : '';
	in_array('mv_customer', $advco_settings_mv_columns) ? $export_html .= "<td align='left' style='color:#03C;'><b>".$result['cust_name']."</b><br>".$result['cust_company']."</td>" : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_email']."</td>" : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_telephone']."</td>" : '';		
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>" : '';
	if ($filter_report == 'all_registered_customers_with_without_orders') {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "".$result['cust_group']."" : '';
	} else {	
	if ($result['customer_id'] == 0) {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "".$result['cust_group_guest']."" : '';
	} else {
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "".$result['cust_group_reg']."" : '';
	}
	}
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "</td>" : '';
	if ($result['custom_fields']) {
	foreach ($result['custom_fields'] as $custom_field) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$custom_field['value']."</td>" : '';
	}
	} else {
	foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_html .= "<td align='left'></td>" : '';
	}
	}		
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_status']."</td>" : '';
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_first_name']."</td>" : '';
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_last_name']."</td>" : '';
	in_array('mv_company', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_company']."</td>" : '';
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_address_1']."</td>" : '';
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_address_2']."</td>" : '';
	in_array('mv_city', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_city']."</td>" : '';
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_postcode']."</td>" : '';
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_country_id']."</td>" : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_country']."</td>" : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_country_code']."</td>" : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_zone_id']."</td>" : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state']."</td>" : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['cust_region_state_code']."</td>" : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['newsletter']."</td>" : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['approved']."</td>" : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['safe']."</td>" : '';
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['ip']."</td>" : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_html .= "<td align='right'>".$result['total_logins']."</td>" : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['last_login']."</td>" : '';
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_html .= "<td align='left'>".$result['mostrecent']."</td>" : '';
	} else {
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_html .= "<td align='left' nowrap='nowrap' style='text-decoration:line-through;'>".$result['mostrecent']."</td>" : '';
	}	
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['orders']."</td>" : '';
	} else {
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['orders']."</td>" : '';
	}		
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_products', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['products']."</td>" : '';
	} else {
	in_array('mv_products', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['products']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_total', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['total']."</td>" : '';
	} else {
	in_array('mv_total', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['total']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='width:80px;'>".$result['aov']."</td>" : '';
	} else {
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='width:80px; text-decoration:line-through;'>".$result['aov']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['refunds']."</td>" : '';
	} else {
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['refunds']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$result['reward_points']."</td>" : '';
	} else {
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='text-decoration:line-through;'>".$result['reward_points']."</td>" : '';
	}
	}
	$export_html .="</tr>";
	if ($filter_report == 'customers_shopping_carts' or $filter_report == 'customers_wishlists') {
	$export_html .="<tr>";
	$export_html .= "<td colspan='".$c."' align='center'>";
		$export_html .="<table class='list_detail'>";
		$export_html .="<thead>";
		$export_html .="<tr>";
		in_array('scw_product_id', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_id')."</td>" : '';		
		in_array('scw_date_added', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_date_added')."</td>" : '';
		in_array('scw_sku', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_sku')."</td>" : '';
		in_array('scw_name', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_name')."</td>" : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_options', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_option')."</td>" : '';
		}
		in_array('scw_model', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_model')."</td>" : '';
		in_array('scw_category', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_category')."</td>" : '';
		in_array('scw_manufacturer', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_manu')."</td>" : '';
		in_array('scw_attribute', $advco_settings_scw_columns) ? $export_html .= "<td align='left'>".$this->language->get('column_prod_attributes')."</td>" : '';
		in_array('scw_price', $advco_settings_scw_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_price')."</td>" : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_cart_quantity')."</td>" : '';
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right'>".$this->language->get('column_wishlist_quantity')."</td>" : '';
		}
		$export_html .="</tr>";
		$export_html .="</thead><tbody>";
		foreach ($result['product'] as $product) {
		$export_html .="<tr>";
		in_array('scw_product_id', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap' style='background-color:#fff2d0;'>".$product['product_id']."</td>" : '';		
		in_array('scw_date_added', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['date_added']."</td>" : '';
		in_array('scw_sku', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['sku']."</td>" : '';
		in_array('scw_name', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['name']."</td>" : '';
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_options', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>" : '';			
		if ($product['option']) {
		$resultstr = array();
		foreach ($product['option'] as $option) {
		$resultstr[] = $option['name'].': '.$option['value'];
		}
		$result_options = implode("; ",$resultstr);
		in_array('scw_options', $advco_settings_scw_columns) ? $export_html .= "".$result_options."" : '';
		}
		}
		in_array('scw_options', $advco_settings_scw_columns) ? $export_html .= "</td>" : '';		
		in_array('scw_model', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['model']."</td>" : '';
		in_array('scw_category', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['category']."</td>" : '';
		in_array('scw_manufacturer', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['manufacturer']."</td>" : '';
		in_array('scw_attribute', $advco_settings_scw_columns) ? $export_html .= "<td align='left' nowrap='nowrap'>".$product['attribute']."</td>" : '';
		in_array('scw_price', $advco_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap'>".$product['price']."</td>" : '';	
		if ($filter_report == 'customers_shopping_carts') {
		in_array('scw_cart_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$product['cart_quantity']."</td>" : '';	
		} elseif ($filter_report == 'customers_wishlists') {
		in_array('scw_wishlist_quantity', $advco_settings_scw_columns) ? $export_html .= "<td align='right' nowrap='nowrap' style='background-color:#FFC;'>".$product['wishlist_quantity']."</td>" : '';	
		}			
		$export_html .="</tr>";	
		}
		$export_html .="</tbody></table>";
	$export_html .="</td>";
	$export_html .="</tr>";	
	}	
	}
	$export_html .="</tbody>";
	
	if ($filter_report != 'registered_customers_without_orders' && $filter_report != 'customers_shopping_carts' && $filter_report != 'customers_wishlists') {		
	$export_html .="<tbody><tr>";	
	if ($filter_report == 'all_registered_customers_with_without_orders') {	
	$export_html .= "<td align='right' style='background-color:#E5E5E5; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	} else {
	$export_html .= "<td colspan='2' align='right' style='background-color:#E5E5E5; font-weight:bold;'>".$this->language->get('text_filter_total')."</td>";
	}	
	in_array('mv_id', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_customer', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_email', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_telephone', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	in_array('mv_customer_group', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
    if ($custom_fields_name) {
    foreach ($custom_fields_name as $custom_field_name) {
	in_array('mv_custom_fields', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
    }
    }	
	in_array('mv_customer_status', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_first_name', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_last_name', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_company', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_address_1', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_address_2', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_city', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_postcode', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_country_id', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_country', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_country_code', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_zone_id', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_region_state', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_region_state_code', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_newsletter', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_approved', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_safe', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';	
	in_array('mv_ip', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_total_logins', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_last_login', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	in_array('mv_mostrecent', $advco_settings_mv_columns) ? $export_html .= "<td style='background-color:#E5E5E5;'></td>" : '';
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['orders_total']."</td>" : '';
	} else {
	in_array('mv_orders', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['orders_total']."</td>" : '';
	}		
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_products', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['products_total']."</td>" : '';	
	} else {
	in_array('mv_products', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['products_total']."</td>" : '';	
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_total', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['total_total']."</td>" : '';
	} else {
	in_array('mv_total', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['total_total']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['aov_total']."</td>" : '';
	} else {
	in_array('mv_aov', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['aov_total']."</td>" : '';
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['refunds_total']."</td>" : '';	
	} else {
	in_array('mv_refunds', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['refunds_total']."</td>" : '';	
	}
	if ($filter_report != 'customers_abandoned_orders') {
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total'>".$result['reward_points_total']."</td>" : '';	
	} else {
	in_array('mv_reward_points', $advco_settings_mv_columns) ? $export_html .= "<td align='right' nowrap='nowrap' class='total' style='text-decoration:line-through;'>".$result['reward_points_total']."</td>" : '';	
	}	
	$export_html .="</tr></tbody>";
	}	
	$export_html .="</table>";	
	$export_html .="</body></html>";

	if (!isset($_GET['cron'])) {
		$filename = "customers_report_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Expires: 0');
		header('Cache-control: private');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');			
		header('Content-Disposition: attachment; filename='.$filename.".html");
		print $export_html;		
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
		
		$filename = $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".html";		
		
		file_put_contents($file, $export_html);
		
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