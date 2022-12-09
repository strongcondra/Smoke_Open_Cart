<?php
class ModelReportAdvSales extends Model {
	public function getSales($data = array()) {
		$query = $this->db->query("SET SESSION group_concat_max_len=500000");
		
		$token = $this->session->data['token'];

		if (!empty($data['filter_date_start'])) {	
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = '';
		}

		if (!empty($data['filter_date_end'])) {	
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_range'])) {
			$range = $data['filter_range'];
		} else {
			$range = 'current_year'; //show Current Year in Statistical Range by default
		}

		switch($range) 
		{
			case 'custom';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
				$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				break;			
			case 'today';
				$date_start = "DATE(o.date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(o.date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = '';
				break;					
			case 'last_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";			
				break;					
			case 'last_year';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;	
		}
		
		$date = ' WHERE (' . $date_start . $date_end . ')';
		
		if (isset($data['filter_report']) && $data['filter_report'] != 'abandoned_orders') {
		$osi = '';
		$sdate = '';			
		if (!empty($data['filter_order_status_id'])) {
			if ((!empty($data['filter_status_date_start'])) && (!empty($data['filter_status_date_end']))) {			
				$osi .= " AND (SELECT DISTINCT oh.order_id FROM `" . DB_PREFIX . "order_history` oh WHERE o.order_id = oh.order_id AND (";
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "oh.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					$osi .= implode(" OR ", $implode) . "";
				}
				$osi .= ") AND DATE(oh.date_added) >= '" . $this->db->escape($data['filter_status_date_start']) . "' AND DATE(oh.date_added) <= '" . $this->db->escape($data['filter_status_date_end']) . "')";
			} else {
				$osi .= " AND (";
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					$osi .= implode(" OR ", $implode) . "";
				}
				$osi .= ")";
				
				$status_date_start = '';
				$status_date_end = '';
				$sdate = $status_date_start . $status_date_end;				
			}
		} else {
			if (!empty($data['filter_status_date_start'])) {		
				$status_date_start = "AND DATE(o.date_modified) >= '" . $this->db->escape($data['filter_status_date_start']) . "'";
			} else {
				$status_date_start = '';
			}
			if (!empty($data['filter_status_date_end'])) {
				$status_date_end = "AND DATE(o.date_modified) <= '" . $this->db->escape($data['filter_status_date_end']) . "'";	
			} else {
				$status_date_end = '';
			}

			$osi = ' AND o.order_status_id > 0';
			$sdate = $status_date_start . $status_date_end;
		}
		} else {
			$osi = ' AND o.order_status_id = 0';
			$sdate = '';
		}

		$order_id_from = '';
		$order_id_to = '';
		if (!empty($data['filter_order_id_from'])) {		
			$order_id_from = " AND o.order_id >= '" . $this->db->escape($data['filter_order_id_from']) . "'";
		} else {
			$order_id_from = '';
		}
		if (!empty($data['filter_order_id_to'])) {	
			$order_id_to = " AND o.order_id <= '" . $this->db->escape($data['filter_order_id_to']) . "'";	
		} else {
			$order_id_to = '';
		}
		$order_id = $order_id_from . $order_id_to;

		$order_value_min = '';
		$order_value_max = '';
		if (!empty($data['filter_order_value_min'])) {		
			$order_value_min = " AND o.total >= '" . $this->db->escape($data['filter_order_value_min']) . "'";
		} else {
			$order_value_min = '';
		}
		if (!empty($data['filter_order_value_max'])) {	
			$order_value_max = " AND o.total <= '" . $this->db->escape($data['filter_order_value_max']) . "'";	
		} else {
			$order_value_max = '';
		}
		$order_value = $order_value_min . $order_value_max;
		
		$store = '';
		if (!empty($data['filter_store_id'])) {
			$store .= " AND (";
			$implode = array();
			foreach ($data['filter_store_id'] as $store_id) {
				$implode[] = "o.store_id = '" . (int)$store_id . "'";
			}
			if ($implode) {
				$store .= implode(" OR ", $implode) . "";
			}
			$store .= ")";
		}
		
		$cur = '';
		if (!empty($data['filter_currency'])) {
			$cur .= " AND (";
			$implode = array();
			foreach ($data['filter_currency'] as $currency) {
				$implode[] = "o.currency_id = '" . (int)$currency . "'";
			}
			if ($implode) {
				$cur .= implode(" OR ", $implode) . "";
			}
			$cur .= ")";
		}
		
		$tax = '';
		if (!empty($data['filter_taxes'])) {
			if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
				$tax .= " AND (";
			} else {
				$tax .= " AND (SELECT DISTINCT ot.order_id FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND (";
			}
			$implode = array();
			foreach ($data['filter_taxes'] as $taxes) {
				$implode[] = "LCASE(ot.title) = '" . $taxes . "'";
			}
			if ($implode) {
				$tax .= implode(" OR ", $implode) . "";
			}
			if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
				$tax .= ")";
			} else {
				$tax .= "))";				
			}
		}

		$tclass = '';
		if (!empty($data['filter_tax_classes'])) {
			$tclass .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "tax_class` tc, `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_tax_classes'] as $tax_classes) {
				$implode[] = "p.tax_class_id = '" . (int)$tax_classes . "'";
			}
			if ($implode) {
				$tclass .= implode(" OR ", $implode) . "";
			}
			$tclass .= "))";
		}
		
		$geo_zone = '';
		if (!empty($data['filter_geo_zones'])) {
			$geo_zone .= " AND (SELECT zgz.geo_zone_id FROM `" . DB_PREFIX . "zone_to_geo_zone` zgz WHERE (";
			$implode = array();
			foreach ($data['filter_geo_zones'] as $geo_zones) {
				$implode[] = "(zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "') OR (o.payment_zone_id = zgz.zone_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "')";
				// $implode[] = "(zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "')";
			}
			if ($implode) {
				$geo_zone .= implode(" OR ", $implode) . "";
			}
			$geo_zone .= "))";
		}
		
		$cgrp = '';
		if (!empty($data['filter_customer_group_id'])) {
			$cgrp .= " AND (";
			$implode = array();
			foreach ($data['filter_customer_group_id'] as $customer_group_id) {
				$implode[] = "(SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$customer_group_id . "') OR (o.customer_group_id = '" . (int)$customer_group_id . "' AND o.customer_id = 0)";
			}
			if ($implode) {
				$cgrp .= implode(" OR ", $implode) . "";
			}
			$cgrp .= ")";
		}
		
		$cust = '';
		if (!empty($data['filter_customer_name'])) {
			$cust = " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		} else {
			$cust = '';
		}

		$email = '';
		if (!empty($data['filter_customer_email'])) {
			$email = " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		} else {
			$email = '';
		}

		$tel = '';
		if (!empty($data['filter_customer_telephone'])) {
			$tel = " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		} else {
			$tel = '';
		}

		$ip = '';
		if (!empty($data['filter_ip'])) {
        	$ip = " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		} else {
			$ip = '';
		}
		
		$pcomp = '';
		if (!empty($data['filter_payment_company'])) {
			$pcomp = " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		} else {
			$pcomp = '';
		}

		$paddr = '';
		if (!empty($data['filter_payment_address'])) {
			$paddr = " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		} else {
			$paddr = '';
		}

		$pcity = '';
		if (!empty($data['filter_payment_city'])) {
			$pcity = " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		} else {
			$pcity = '';
		}

		$pzone = '';
		if (!empty($data['filter_payment_zone'])) {
			$pzone = " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		} else {
			$pzone = '';
		}

		$ppsc = '';
		if (!empty($data['filter_payment_postcode'])) {
			$ppsc = " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		} else {
			$ppsc = '';
		}

		$pcntr = '';
		if (!empty($data['filter_payment_country'])) {
			$pcntr = " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
		} else {
			$pcntr = '';
		}

		$pmeth = '';
		if (!empty($data['filter_payment_method'])) {
			$pmeth .= " AND (";
			$implode = array();
			foreach ($data['filter_payment_method'] as $payment_code) {
				$implode[] = "o.payment_code = '" . $payment_code . "'";
			}
			if ($implode) {
				$pmeth .= implode(" OR ", $implode) . "";
			}
			$pmeth .= ")";
		}

		$scomp = '';
		if (!empty($data['filter_shipping_company'])) {
			$scomp = " AND LCASE(o.shipping_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_company'], 'UTF-8')) . "%'";
		} else {
			$scomp = '';
		}

		$saddr = '';
		if (!empty($data['filter_shipping_address'])) {
			$saddr = " AND LCASE(CONCAT(o.shipping_address_1, ', ', o.shipping_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_address'], 'UTF-8')) . "%'";
		} else {
			$saddr = '';
		}

		$scity = '';
		if (!empty($data['filter_shipping_city'])) {
			$scity = " AND LCASE(o.shipping_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_city'], 'UTF-8')) . "%'";
		} else {
			$scity = '';
		}

		$szone = '';
		if (!empty($data['filter_shipping_zone'])) {
			$szone = " AND LCASE(o.shipping_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_zone'], 'UTF-8')) . "%'";
		} else {
			$szone = '';
		}

		$spsc = '';
		if (!empty($data['filter_shipping_postcode'])) {
			$spsc = " AND LCASE(o.shipping_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_postcode'], 'UTF-8')) . "%'";			
		} else {
			$spsc = '';
		}

		$scntr = '';
		if (!empty($data['filter_shipping_country'])) {
			$scntr = " AND LCASE(o.shipping_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_country'], 'UTF-8')) . "%'";			
		} else {
			$scntr = '';
		}

		$smeth = '';
		if (!empty($data['filter_shipping_method'])) {
			$smeth .= " AND (";
			$implode = array();
			foreach ($data['filter_shipping_method'] as $shipping_code) {
				$implode[] = "o.shipping_code = '" . $shipping_code . "'";
			}
			if ($implode) {
				$smeth .= implode(" OR ", $implode) . "";
			}
			$smeth .= ")";
		}
		
		$cat = '';
		if (!empty($data['filter_category'])) {
			$cat .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product_to_category` p2c, `" . DB_PREFIX . "order_product` op WHERE p2c.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_category'] as $category_id) {
				$implode[] = "p2c.category_id = '" . (int)$category_id . "'";
			}
			if ($implode) {
				$cat .= implode(" OR ", $implode) . "";
			}
			$cat .= "))";
		}
		
		$manu = '';
		if (!empty($data['filter_manufacturer'])) {
			$manu .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_manufacturer'] as $manufacturer) {
				$implode[] = "p.manufacturer_id = '" . (int)$manufacturer . "'";
			}
			if ($implode) {
				$manu .= implode(" OR ", $implode) . "";
			}
			$manu .= "))";
		}
		
		$sku = '';
		if (!empty($data['filter_sku'])) {
        	$sku = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND LCASE(p.sku) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_sku'], 'UTF-8')) . "%')";	
		} else {
			$sku = '';
		}
		
		$prod = '';
		if (!empty($data['filter_product_name'])) {
        	$prod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_name'], 'UTF-8')) . "%')";				
		} else {
			$prod = '';
		}
		
		$mod = '';
		if (!empty($data['filter_model'])) {
        	$mod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.model) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%')";		
		} else {
			$mod = '';
		}
		
		$opt = '';
		if (!empty($data['filter_option'])) {
			$opt .= " AND ";
			$implode = array();
			foreach ($data['filter_option'] as $option) {
				$implode[] = "(SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND oo.order_product_id = op.order_product_id AND LCASE(CONCAT(oo.name,'_',oo.value,'_',oo.type)) = '" . $option . "' AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_name'], 'UTF-8')) . "%')";
			}
			if ($implode) {
				$opt .= implode(" AND ", $implode) . "";
			}
		}

		$atr = '';
		if (!empty($data['filter_attribute'])) {
			$atr .= " AND ";
			$implode = array();
			foreach ($data['filter_attribute'] as $attribute) {
				$implode[] = "(SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE o.order_id = op.order_id AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND LCASE(CONCAT(agd.name,'_',ad.name,'_',pa.text)) = '" . $attribute . "')";
			}
			if ($implode) {
				$atr .= implode(" AND ", $implode) . "";
			}
		}
		
		$loc = '';
		if (!empty($data['filter_location'])) {
			$loc .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_location'] as $location) {
				$implode[] = "LCASE(p.location) = '" . $location . "'";
			}
			if ($implode) {
				$loc .= implode(" OR ", $implode) . "";
			}
			$loc .= "))";
		}

		$affn = '';
		if (!empty($data['filter_affiliate_name'])) {
			$affn .= " AND (";
			$implode = array();
			foreach ($data['filter_affiliate_name'] as $affiliate_name) {
				$implode[] = "o.affiliate_id = '" . (int)$affiliate_name . "'";
			}
			if ($implode) {
				$affn .= implode(" OR ", $implode) . "";
			}
			$affn .= ")";
		}		

		$affe = '';
		if (!empty($data['filter_affiliate_email'])) {
			$affe .= " AND (SELECT a.affiliate_id FROM `" . DB_PREFIX . "affiliate` a WHERE a.affiliate_id = o.affiliate_id AND (";
			$implode = array();
			foreach ($data['filter_affiliate_email'] as $affiliate_email) {
				$implode[] = "o.affiliate_id = '" . (int)$affiliate_email . "'";
			}
			if ($implode) {
				$affe .= implode(" OR ", $implode) . "";
			}
			$affe .= "))";
		}

		$cpn = '';
		if (!empty($data['filter_coupon_name'])) {
			$cpn .= " AND (SELECT DISTINCT cph.order_id FROM `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = o.order_id AND (";
			$implode = array();
			foreach ($data['filter_coupon_name'] as $coupon_name) {
				$implode[] = "cph.coupon_id = '" . (int)$coupon_name . "'";
			}
			if ($implode) {
				$cpn .= implode(" OR ", $implode) . "";
			}
			$cpn .= "))";
		}

		$cpc = '';
		if (!empty($data['filter_coupon_code'])) {
			$cpc = " AND (SELECT DISTINCT cph.order_id FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.coupon_id = cp.coupon_id AND cph.order_id = o.order_id AND LCASE(cp.code) LIKE '" . $this->db->escape(mb_strtolower($data['filter_coupon_code'], 'UTF-8')) . "')";	
		} else {
			$cpc = '';
		}

		$gvc = '';
		if (!empty($data['filter_voucher_code'])) {
        	$gvc = " AND (SELECT DISTINCT gvh.order_id FROM `" . DB_PREFIX . "voucher` gv, `" . DB_PREFIX . "voucher_history` gvh WHERE gvh.voucher_id = gv.voucher_id AND gvh.order_id = o.order_id AND LCASE(gv.code) LIKE '" . $this->db->escape(mb_strtolower($data['filter_voucher_code'], 'UTF-8')) . "')";	
		} else {
			$gvc = '';
		}
		
		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {
			
		if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
			
		$sql = "SELECT *, 
		YEAR(o.date_added) AS year, 
		QUARTER(o.date_added) AS quarter, 		
		MONTHNAME(o.date_added) AS month, 
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		ot.title AS tax_title, 
		(SELECT tr.rate FROM `" . DB_PREFIX . "tax_rate` tr WHERE tr.name = ot.title GROUP BY tr.name) AS tax_rate, 
		COUNT(ot.order_id) AS `orders`, 
		SUM(ot.value) AS total_tax ";
					
		$sql .= "FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot" . $date . "AND ot.order_id = o.order_id AND ot.code = 'tax'" . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
			
		$sql = "SELECT o.*, 
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		ch.coupon_id AS coupon_id, 
		ch.order_id AS order_id, 
		c.name AS coupon_name, 
		c.code AS coupon_code, 
		c.discount AS coupon_discount, 
		c.type AS coupon_type, 
		c.status AS coupon_status, 
		c.date_added AS coupon_date_added, 
		MAX(ch.date_added) AS coupon_last_used, 
		COUNT(DISTINCT ch.order_id) AS coupon_orders, 
		SUM(o.total) AS coupon_total, 
		SUM(ch.amount) AS coupon_amount ";

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
			
		$sql = "SELECT o.*, 
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		v.voucher_id AS voucher_id, 
		vh.order_id AS order_id, 
		v.code AS voucher_code, 
		v.from_name AS voucher_from_name, 
		v.from_email AS voucher_from_email, 
		v.to_name AS voucher_to_name, 
		v.to_email AS voucher_to_email, 
		(SELECT vtd.name FROM " . DB_PREFIX . "voucher_theme_description vtd WHERE vtd.voucher_theme_id = v.voucher_theme_id AND vtd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS voucher_theme, 
		v.status AS voucher_status, 
		v.date_added AS voucher_date_added, 
		v.amount AS voucher_amount, 
		COUNT(DISTINCT vh.order_id) AS voucher_orders, 
		SUM(o.total) AS voucher_total, 
		(SELECT SUM(vh.amount) FROM " . DB_PREFIX . "voucher_history vh WHERE vh.voucher_id = v.voucher_id) AS voucher_used_value, 
		(SELECT (v.amount+SUM(vh.amount)) FROM " . DB_PREFIX . "voucher_history vh WHERE vh.voucher_id = v.voucher_id) AS voucher_remaining_value ";
		
		} else {
			
		$sql = "SELECT *, 
		YEAR(o.date_added) AS year, 
		QUARTER(o.date_added) AS quarter, 		
		MONTHNAME(o.date_added) AS month, 
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		DAYNAME(o.date_added) AS day_of_week, 
		HOUR(o.date_added) AS hour, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS customer_group, 
		COUNT(o.order_id) AS `orders`, 
		COUNT(DISTINCT CONCAT(o.lastname,o.firstname)) AS customers, 
		SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, 
		(SELECT c.iso_code_2 FROM `" . DB_PREFIX . "country` c WHERE o.payment_country_id = c.country_id) AS iso_code, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id)) AS sub_total, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'handling' GROUP BY ot.order_id)) AS handling, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'low_order_fee' GROUP BY ot.order_id)) AS low_order_fee, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'shipping' GROUP BY ot.order_id)) AS shipping, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'reward' GROUP BY ot.order_id)) AS reward, 
		SUM((SELECT SUM(op.reward) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id AND o.customer_id > 0 GROUP BY op.order_id)) AS earned_reward_points, 
		SUM((SELECT SUM(crp.points) FROM `" . DB_PREFIX . "customer_reward` crp WHERE crp.order_id = o.order_id AND crp.points < 0)) AS used_reward_points, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'coupon' GROUP BY ot.order_id)) AS coupon, 
		SUM((SELECT SUM(ROUND(ot.value, 2)) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS taxes, 	
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'credit' GROUP BY ot.order_id)) AS credit, 
		SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'voucher' GROUP BY ot.order_id)) AS voucher, 
		SUM((SELECT SUM(op.price*r.quantity) FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND o.order_id = op.order_id AND r.return_action_id = '1' GROUP BY r.order_id)) AS refunds, 
		SUM(o.commission) AS commission, 		
		SUM(o.total) AS total ";

		}
		
		if (isset($data['filter_report']) && $data['filter_report'] != 'tax') {
		if (isset($data['filter_details']) && $data['filter_details'] == 'basic_details') {
			$sql .= ", GROUP_CONCAT(o.order_id ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_id, 
			GROUP_CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',o.order_id,'\">',o.order_id,'</a>' ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_id_link, ";
			if ($this->config->get('advso' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
				$sql .= "GROUP_CONCAT(DATE_FORMAT(o.date_added, '%d/%m/%Y') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_date, ";
			} else {	
				$sql .= "GROUP_CONCAT(DATE_FORMAT(o.date_added, '%m/%d/%Y') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_date, ";
			}
			$sql .= "GROUP_CONCAT(IFNULL(o.invoice_prefix,'&nbsp;&nbsp;'),IFNULL(o.invoice_no,'&nbsp;&nbsp;') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_inv_no, 
			GROUP_CONCAT(CONCAT(o.firstname,' ',o.lastname) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_name, 
			GROUP_CONCAT(o.email ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_email, 
			GROUP_CONCAT(IFNULL((SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'),'&nbsp;') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_group, 	
			GROUP_CONCAT(IF (o.shipping_method = '','&nbsp;&nbsp;',o.shipping_method) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_shipping_method, 
			GROUP_CONCAT(IF (o.payment_method = '','&nbsp;&nbsp;',o.payment_method) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_payment_method, 
			GROUP_CONCAT(IFNULL((SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'),'0') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_status, 
 			GROUP_CONCAT(o.store_name ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_store, 
			GROUP_CONCAT(o.currency_code ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_currency, 
			GROUP_CONCAT(IFNULL((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id),'&nbsp;&nbsp;') ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_products, 
			GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_sub_total, 
			GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'shipping' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_shipping, 
			GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_tax, ";
			if (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
			$sql .= "GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'coupon' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_coupon, ";
			}
			if (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
			$sql .= "GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'voucher' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_voucher, ";
			}			
			$sql .= "GROUP_CONCAT(ROUND(o.currency_value*o.total, 2) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_value, 
			
			GROUP_CONCAT((SELECT GROUP_CONCAT(op.order_id SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_id, 
			GROUP_CONCAT((SELECT GROUP_CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',op.order_id,'\">',op.order_id,'</a>' SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_id_link, ";
			if ($this->config->get('advso' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
				$sql .= "GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT DATE_FORMAT(o.date_added, '%d/%m/%Y') FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_date, ";
			} else {	
				$sql .= "GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT DATE_FORMAT(o.date_added, '%m/%d/%Y') FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_date, ";
			}			
			$sql .= "GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT CONCAT('<a href=\"index.php?route=catalog/product/edit&token=$token&product_id=',op.product_id,'\">',op.product_id,'</a>') FROM `" . DB_PREFIX . "product` p WHERE op.product_id = p.product_id),op.product_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_pid_link, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(op.product_id SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_pid, 
			GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT IF (p.sku = '','&nbsp;&nbsp;',p.sku) FROM `" . DB_PREFIX . "product` p WHERE op.product_id = p.product_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_sku, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(op.model SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_model, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(op.name SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_name, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(CONCAT(oo.name,': ',oo.value) SEPARATOR '; ') FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND oo.type != 'text' AND oo.type != 'textarea' AND oo.type != 'file' AND oo.type != 'image' AND oo.type != 'date' AND oo.type != 'datetime' AND oo.type != 'time' ORDER BY op.order_product_id),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_option, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(CONCAT(agd.name,' &gt; ',ad.name,' &gt; ',pa.text) ORDER BY agd.name, ad.name, pa.text ASC SEPARATOR '; ') FROM `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_attributes, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE op.product_id = p.product_id AND p.manufacturer_id = m.manufacturer_id),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_manu, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE op.product_id = p2c.product_id AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_category, 
			GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT o.currency_code FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_currency, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*op.price, 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_price, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(op.quantity SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_quantity, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*op.total, 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_total_excl_vat, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*(op.tax*op.quantity), 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_tax, 
			GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*(op.total+(op.tax*op.quantity)), 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_total_incl_vat, 

			GROUP_CONCAT(o.order_id ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_ord_id, 
			GROUP_CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',o.order_id,'\">',o.order_id,'</a>' ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_ord_id_link, ";
			if ($this->config->get('advso' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
				$sql .= "GROUP_CONCAT(DATE_FORMAT(o.date_added, '%d/%m/%Y') ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_ord_date, ";
			} else {	
				$sql .= "GROUP_CONCAT(DATE_FORMAT(o.date_added, '%m/%d/%Y') ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_ord_date, ";
			}	
			$sql .= "GROUP_CONCAT(IF (o.customer_id = 0,'0',CONCAT('<a href=\"index.php?route=customer/customer/edit&token=$token&customer_id=',o.customer_id,'\">',o.customer_id,'</a>')) ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_cust_id_link, 
			GROUP_CONCAT(IF (o.customer_id = 0,'0',o.customer_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_cust_id, 
			GROUP_CONCAT(IF ((CONCAT(o.payment_firstname,o.payment_lastname) = ''),'&nbsp;&nbsp;',(CONCAT(o.payment_firstname,' ',o.payment_lastname))) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_name, 
			GROUP_CONCAT(IF (o.payment_company = '','&nbsp;&nbsp;',o.payment_company) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_company, 
			GROUP_CONCAT(IF (o.payment_address_1 = '','&nbsp;&nbsp;',o.payment_address_1) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_address_1, 
			GROUP_CONCAT(IF (o.payment_address_2 = '','&nbsp;&nbsp;',o.payment_address_2) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_address_2, 
			GROUP_CONCAT(IF (o.payment_city = '','&nbsp;&nbsp;',o.payment_city) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_city, 
			GROUP_CONCAT(IF (o.payment_zone = '','&nbsp;&nbsp;',o.payment_zone) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_zone, 
			GROUP_CONCAT(IF (o.payment_postcode = '','&nbsp;&nbsp;',o.payment_postcode) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_postcode, 
			GROUP_CONCAT(IF (o.payment_country = '','&nbsp;&nbsp;',o.payment_country) ORDER BY o.order_id DESC SEPARATOR '<br>') AS billing_country, 
			GROUP_CONCAT(IF (o.telephone = '','&nbsp;&nbsp;',o.telephone) ORDER BY o.order_id DESC SEPARATOR '<br>') AS customer_telephone, 
			GROUP_CONCAT(IF ((CONCAT(o.shipping_firstname,o.shipping_lastname) = ''),'&nbsp;&nbsp;',(CONCAT(o.shipping_firstname,' ',o.shipping_lastname))) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_name, 
			GROUP_CONCAT(IF (o.shipping_company = '','&nbsp;&nbsp;',o.shipping_company) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_company, 
			GROUP_CONCAT(IF (o.shipping_address_1 = '','&nbsp;&nbsp;',o.shipping_address_1) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_address_1, 
			GROUP_CONCAT(IF (o.shipping_address_2 = '','&nbsp;&nbsp;',o.shipping_address_2) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_address_2, 
			GROUP_CONCAT(IF (o.shipping_city = '','&nbsp;&nbsp;',o.shipping_city) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_city, 
			GROUP_CONCAT(IF (o.shipping_zone = '','&nbsp;&nbsp;',o.shipping_zone) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_zone, 			
			GROUP_CONCAT(IF (o.shipping_postcode = '','&nbsp;&nbsp;',o.shipping_postcode) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_postcode, 
			GROUP_CONCAT(IF (o.shipping_country = '','&nbsp;&nbsp;',o.shipping_country) ORDER BY o.order_id DESC SEPARATOR '<br>') AS shipping_country ";
		}
		}

		if (isset($data['filter_report']) && $data['filter_report'] != 'tax') {
		if (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
			$sql .= "FROM `" . DB_PREFIX . "coupon_history` ch, `" . DB_PREFIX . "coupon` c, `" . DB_PREFIX . "order` o" . $date . "AND ch.coupon_id = c.coupon_id AND ch.order_id = o.order_id" . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
			$sql .= "FROM `" . DB_PREFIX . "voucher_history` vh, `" . DB_PREFIX . "voucher` v, `" . DB_PREFIX . "order` o" . $date . "AND vh.voucher_id = v.voucher_id AND vh.order_id = o.order_id" . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		} else {
			$sql .= "FROM `" . DB_PREFIX . "order` o" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		}
		}
		
		} else {
			
		$sql = "SELECT *, 
		CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',o.order_id,'\">',o.order_id,'</a>') AS order_id_link, 
		CONCAT('<a href=\"index.php?route=catalog/product/edit&token=$token&product_id=',op.product_id,'\">',op.product_id,'</a>') AS product_id_link, 		
		CONCAT('<a href=\"index.php?route=customer/customer/edit&token=$token&customer_id=',o.customer_id,'\">',o.customer_id,'</a>') AS customer_id_link, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE op.order_id = o.order_id AND cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS cust_group, 
		(SELECT p.sku FROM " . DB_PREFIX . "product p WHERE op.order_id = o.order_id AND op.product_id = p.product_id) AS product_sku, 
		op.model AS product_model, 
		op.name AS product_name, 
		(SELECT GROUP_CONCAT(CONCAT(oo.name,': ',oo.value) SEPARATOR '; ') FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND oo.type != 'text' AND oo.type != 'textarea' AND oo.type != 'file' AND oo.type != 'image' AND oo.type != 'date' AND oo.type != 'datetime' AND oo.type != 'time' ORDER BY op.order_product_id) AS product_options, 
		(SELECT GROUP_CONCAT(CONCAT(agd.name,' &gt; ',ad.name,' &gt; ',pa.text) ORDER BY agd.name, ad.name, pa.text ASC SEPARATOR '; ') FROM `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product_attributes, 
		(SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE op.product_id = p.product_id AND p.manufacturer_id = m.manufacturer_id) AS product_manu, 
		(SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE op.product_id = p2c.product_id AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0) AS product_category, 
		(o.currency_value*op.price) AS product_price, 
		op.quantity AS product_quantity, 
		(o.currency_value*op.total) AS product_total_excl_vat, 
		(o.currency_value*op.tax*op.quantity) AS product_tax, 
		(o.currency_value*(op.total+(op.tax*op.quantity))) AS product_total_incl_vat, 
		IFNULL((SELECT SUM(r.quantity) FROM `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND r.return_action_id = '1'), 0) AS product_qty_refund, 
		(SELECT o.currency_value*(op.price*r.quantity) FROM `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND r.return_action_id = '1') AS product_refund, 
		op.reward AS product_reward_points, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id) AS order_sub_total, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'handling' GROUP BY ot.order_id) AS order_handling, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'low_order_fee' GROUP BY ot.order_id) AS order_low_order_fee, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'shipping' GROUP BY ot.order_id) AS order_shipping, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'reward' GROUP BY ot.order_id) AS order_reward, 
		(SELECT SUM(op.reward) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_earned_points, 
		(SELECT SUM(crp.points) FROM `" . DB_PREFIX . "customer_reward` crp WHERE crp.order_id = o.order_id AND crp.points < 0 GROUP BY op.order_id) AS order_used_points, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'coupon' GROUP BY ot.order_id) AS order_coupon, 
		(SELECT cp.name FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = op.order_id AND cph.coupon_id = cp.coupon_id) AS order_coupon_name, 
		(SELECT cp.code FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = op.order_id AND cph.coupon_id = cp.coupon_id) AS order_coupon_code, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS order_tax, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit' GROUP BY ot.order_id) AS order_credit, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'voucher' GROUP BY ot.order_id) AS order_voucher, 
		(SELECT v.code FROM `" . DB_PREFIX . "voucher` v, `" . DB_PREFIX . "voucher_history` vh WHERE vh.order_id = op.order_id AND vh.voucher_id = v.voucher_id) AS order_voucher_code, 
		(SELECT o.currency_value*SUM(op.price*r.quantity) FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND o.order_id = op.order_id AND r.return_action_id = '1' GROUP BY r.order_id) AS order_refund, 
		(o.currency_value*o.total) AS order_value, 
		(SELECT o.currency_value*SUM(at.amount) FROM " . DB_PREFIX . "affiliate_transaction at WHERE at.order_id = o.order_id GROUP BY at.order_id) AS order_commission, 
		IFNULL((SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE op.order_id = o.order_id AND os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'),'0') AS order_status, 
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = o.payment_zone_id) AS payment_zone_code, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = o.payment_country_id) AS payment_country_code, 
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = o.shipping_zone_id) AS shipping_zone_code, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = o.shipping_country_id) AS shipping_country_code, 
		ROUND(IFNULL((SELECT SUM((p.weight*op.quantity) / wc.value) FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "weight_class` wc WHERE op.product_id = p.product_id AND op.order_id = o.order_id AND wc.weight_class_id = p.weight_class_id GROUP BY op.order_id),0) + IFNULL((SELECT SUM((pov.weight*op.quantity) / wc.value) FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "product_option_value` pov, `" . DB_PREFIX . "weight_class` wc WHERE op.product_id = p.product_id AND op.order_id = o.order_id AND op.order_product_id = oo.order_product_id AND oo.product_option_value_id = pov.product_option_value_id AND wc.weight_class_id = p.weight_class_id GROUP BY op.order_id),0), 2) AS order_weight, 
		(SELECT wcd.unit FROM `" . DB_PREFIX . "weight_class_description` wcd WHERE wcd.weight_class_id = '" . $this->config->get('config_weight_class_id') . "' AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class ";

		if (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
			$sql .= "FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "coupon_history` ch ON (ch.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "coupon` c ON (ch.coupon_id = c.coupon_id)" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
			$sql .= "FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "voucher_history` vh ON (vh.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "voucher` v ON (vh.voucher_id = v.voucher_id)" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		} else {
			$sql .= "FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		}

		if (isset($data['filter_details']) && $data['filter_details'] == 'all_details_orders') {
			$sql .= " GROUP BY o.order_id";
		}
		
		$sql .= " ORDER BY o.order_id DESC";
		}
		
		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {
			$report = $data['filter_report'];
		} else {
			$report = 'sales_summary'; //show Sales Summary in Report By default
		}
		
		switch($report) {
			case 'sales_summary';
				$sql .= '';
				break;			
			case 'day_of_week';
				$sql .= " GROUP BY day_of_week";
				break;
			case 'hour':
				$sql .= " GROUP BY hour";
				break;
			case 'store':
				$sql .= " GROUP BY store_id";
				break;
			case 'currency':
				$sql .= " GROUP BY currency_id";
				break;
			case 'customer_group':
				$sql .= " GROUP BY customer_group_id";
				break;
			case 'tax':
				$sql .= '';
				break;				
			case 'country':
				$sql .= " GROUP BY payment_country_id";
				break;
			case 'postcode':
				$sql .= " GROUP BY payment_postcode";
				break;
			case 'region_state':
				$sql .= " GROUP BY payment_zone_id";
				break;
			case 'city':
				$sql .= " GROUP BY payment_city";
				break;
			case 'payment_method':
				$sql .= " GROUP BY payment_code";
				break;
			case 'shipping_method':
				$sql .= " GROUP BY shipping_code";
				break;
			case 'coupon':
				$sql .= " GROUP BY c.coupon_id";
				break;	
			case 'voucher':
				$sql .= " GROUP BY v.voucher_id";
				break;					
			case 'abandoned_orders';
				$sql .= '';
				break;					
		}

		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {
	
		if (isset($data['filter_group']) && (isset($data['filter_report']) && ($data['filter_report'] == 'sales_summary' or $data['filter_report'] == 'abandoned_orders' or $data['filter_report'] == 'tax'))) {
			$group = $data['filter_group'];	
		} elseif (isset($data['filter_report']) && ($data['filter_report'] != 'sales_summary' && $data['filter_report'] != 'abandoned_orders' && $data['filter_report'] != 'tax')) {
			$group = '';	
		} else {
			$group = 'month'; //show Month in Group Report by default
		}
		
		switch($group) {
			case 'order';
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
   					$sql .= " GROUP BY o.order_id, LCASE(ot.title)";
				} else {	
					$sql .= " GROUP BY o.order_id";
				}
				break;				
			case 'day';
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
   					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), LCASE(ot.title)";
				} else {	
					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				}			
				break;
			case 'week':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					if ($this->config->get('advso' . $this->user->getId() . '_week_days') == 'mon_sun') {
						$sql .= " GROUP BY YEARWEEK(o.date_added, 1), LCASE(ot.title)";
					} else {	
						$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), LCASE(ot.title)";
					}					
				} else {	
					if ($this->config->get('advso' . $this->user->getId() . '_week_days') == 'mon_sun') {
						$sql .= " GROUP BY YEARWEEK(o.date_added, 1)";
					} else {	
						$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
					}
				}
				break;			
			case 'month':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
   					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), LCASE(ot.title)";
				} else {	
					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				}	
				break;
			case 'quarter':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
   					$sql .= " GROUP BY YEAR(o.date_added), QUARTER(o.date_added), LCASE(ot.title)";
				} else {	
					$sql .= " GROUP BY YEAR(o.date_added), QUARTER(o.date_added)";
				}	
				break;				
			case 'year':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
   					$sql .= " GROUP BY YEAR(o.date_added), LCASE(ot.title)";
				} else {	
					$sql .= " GROUP BY YEAR(o.date_added)";
				}	
				break;			
		}

		if (!isset($data['filter_order'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] != 'sales_summary' && $data['filter_report'] != 'abandoned_orders' && $data['filter_report'] != 'tax')) {
				$sort_order = " ASC";				
			} else {
				$sort_order = " DESC";
			}
		} else {
			if (isset($data['filter_order']) && $data['filter_order'] == 'asc') {
				$sort_order = " ASC";				
			} else {
				$sort_order = " DESC";
			}
		}			
		
		if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {

		if (isset($data['filter_sort']) && $data['filter_sort'] == 'report_type') {
			$sql .= " ORDER BY date_added" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'tax_name') {
			$sql .= " ORDER BY tax_title" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'tax_rate') {
			$sql .= " ORDER BY tax_rate" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'tax_orders') {
			$sql .= " ORDER BY orders" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'tax_total') {
			$sql .= " ORDER BY total_tax" . $sort_order . " ";
		}	

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {

		if (isset($data['filter_sort']) && $data['filter_sort'] == 'report_type') {
			$sql .= " ORDER BY date_added" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_name') {
			$sql .= " ORDER BY coupon_name" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_code') {
			$sql .= " ORDER BY coupon_code" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_discount') {
			$sql .= " ORDER BY coupon_discount" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_type') {
			$sql .= " ORDER BY coupon_type" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_status') {
			$sql .= " ORDER BY coupon_status" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_last_used') {
			$sql .= " ORDER BY coupon_last_used" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_amount') {
			$sql .= " ORDER BY coupon_amount" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_orders') {
			$sql .= " ORDER BY coupon_orders" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon_total') {
			$sql .= " ORDER BY coupon_total" . $sort_order . " ";
		}	
		
		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {

		if (isset($data['filter_sort']) && $data['filter_sort'] == 'report_type') {
			$sql .= " ORDER BY date_added" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_code') {
			$sql .= " ORDER BY voucher_code" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_from_name') {
			$sql .= " ORDER BY voucher_from_name" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_from_email') {
			$sql .= " ORDER BY voucher_from_email" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_to_name') {
			$sql .= " ORDER BY voucher_to_name" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_to_email') {
			$sql .= " ORDER BY voucher_to_email" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_theme') {
			$sql .= " ORDER BY voucher_theme" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_status') {
			$sql .= " ORDER BY voucher_status" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_date_added') {
			$sql .= " ORDER BY voucher_date_added" . $sort_order . " ";	
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_amount') {
			$sql .= " ORDER BY voucher_amount" . $sort_order . " ";	
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_used_value') {
			$sql .= " ORDER BY voucher_used_value" . $sort_order . " ";	
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_remaining_value') {
			$sql .= " ORDER BY voucher_remaining_value" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_orders') {
			$sql .= " ORDER BY voucher_orders" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher_total') {
			$sql .= " ORDER BY voucher_total" . $sort_order . " ";
		}	
		
		} else {
			
		if (isset($data['filter_sort']) && $data['filter_sort'] == 'report_type') {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'sales_summary' or $data['filter_report'] == 'abandoned_orders')) {			
				$sql .= " ORDER BY date_added" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'day_of_week') {
				if ($this->config->get('advso' . $this->user->getId() . '_week_days') == 'mon_sun') {
					$sql .= " ORDER BY FIELD(day_of_week, 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY') ";
				} else {	
					$sql .= " ORDER BY FIELD(day_of_week,  'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY') ";
				}				
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'hour') {
				$sql .= " ORDER BY hour ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'store') {
				$sql .= " ORDER BY LCASE(store_name)" . $sort_order . " ";	
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'currency') {
				$sql .= " ORDER BY LCASE(currency_code)" . $sort_order . " ";	
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customer_group') {
				$sql .= " ORDER BY LCASE(customer_group)" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'country') {
				$sql .= " ORDER BY LCASE(payment_country)" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'postcode') {
				$sql .= " ORDER BY payment_postcode" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'region_state') {
				$sql .= " ORDER BY LCASE(payment_zone)" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'city') {
				$sql .= " ORDER BY LCASE(payment_city)" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'payment_method') {
				$sql .= " ORDER BY LCASE(payment_method)" . $sort_order . " ";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'shipping_method') {
				$sql .= " ORDER BY LCASE(shipping_method)" . $sort_order . " ";			
			}			
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'customers') {
			$sql .= " ORDER BY customers" . $sort_order . ", total DESC ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'orders') {
			$sql .= " ORDER BY orders" . $sort_order . ", total DESC ";			
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'products') {
			$sql .= " ORDER BY products" . $sort_order . ", total DESC ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'sub_total') {
			$sql .= " ORDER BY sub_total" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'shipping') {
			$sql .= " ORDER BY shipping" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'reward') {
			$sql .= " ORDER BY reward" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'earned_reward_points') {
			$sql .= " ORDER BY earned_reward_points" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'used_reward_points') {
			$sql .= " ORDER BY used_reward_points" . $sort_order . " ";		
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'coupon') {
			$sql .= " ORDER BY coupon" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'tax') {
			$sql .= " ORDER BY taxes" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'credit') {
			$sql .= " ORDER BY credit" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'voucher') {
			$sql .= " ORDER BY voucher" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'commission') {
			$sql .= " ORDER BY commission" . $sort_order . " ";				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'total') {
			$sql .= " ORDER BY total" . $sort_order . " ";		
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'aov') {
			$sql .= " ORDER BY SUM(total) / COUNT(o.order_id)" . $sort_order . " ";
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'refunds') {
			$sql .= " ORDER BY refunds" . $sort_order . " ";		
		} else {
			$sql .= " ORDER BY date_added" . $sort_order . " ";
		}
		
		}

		}
		
		if (isset($data['start']) || isset($data['filter_limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['filter_limit'] < 1) {
				$data['filter_limit'] = 25;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['filter_limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	

	public function getSalesTotal($data = array()) {
		if (isset($data['filter_report'])) {
			$report = $data['filter_report'];
		} else {
			$report = 'sales_summary'; //show Sales Summary in Report By default
		}
		
		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {
			
		switch($report) {
			case 'sales_summary';
				$sql = '';
				break;
			case 'abandoned_orders';
				$sql = '';
				break;				
			case 'day_of_week';
				$sql = "SELECT COUNT(DISTINCT DAYNAME(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'hour':
				$sql = "SELECT COUNT(DISTINCT HOUR(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'store':
				$sql = "SELECT COUNT(DISTINCT o.store_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'currency':
				$sql = "SELECT COUNT(DISTINCT o.currency_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'customer_group':
				$sql = "SELECT COUNT(DISTINCT o.customer_group_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'tax':
				$sql = '';
				break;					
			case 'country':
				$sql = "SELECT COUNT(DISTINCT o.payment_country_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'postcode':
				$sql = "SELECT COUNT(DISTINCT o.payment_postcode) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'region_state':
				$sql = "SELECT COUNT(DISTINCT o.payment_zone_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'city':
				$sql = "SELECT COUNT(DISTINCT o.payment_city) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'payment_method':
				$sql = "SELECT COUNT(DISTINCT o.payment_code) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'shipping_method':
				$sql = "SELECT COUNT(DISTINCT o.shipping_code) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'coupon':
				$sql = "SELECT COUNT(DISTINCT ch.coupon_id) AS total FROM `" . DB_PREFIX . "coupon_history` ch, `" . DB_PREFIX . "order` o WHERE ch.order_id = o.order_id";
				break;
			case 'voucher':
				$sql = "SELECT COUNT(DISTINCT vh.voucher_id) AS total FROM `" . DB_PREFIX . "voucher_history` vh, `" . DB_PREFIX . "order` o WHERE vh.order_id = o.order_id";
				break;
		}
		
		if (isset($data['filter_group']) && (isset($data['filter_report']) && ($data['filter_report'] == 'sales_summary' or $data['filter_report'] == 'abandoned_orders' or $data['filter_report'] == 'tax'))) {
			$group = $data['filter_group'];	
		} elseif (isset($data['filter_report']) && ($data['filter_report'] != 'sales_summary' && $data['filter_report'] != 'abandoned_orders' && $data['filter_report'] != 'tax')) {
			$group = '';	
		} else {
			$group = 'month'; //show Month in Group Report by default
		}
		
		switch($group) {
			case 'order';
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT o.order_id, ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(o.order_id) AS total FROM `" . DB_PREFIX . "order` o";
				}			
				break;				
			case 'day';
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				}			
				break;
			case 'week':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				}				
				break;			
			case 'month':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				}				
				break;
			case 'quarter':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), QUARTER(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), QUARTER(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				}				
				break;				
			case 'year':
				if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax'";
				} else {	
					$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added)) AS total FROM `" . DB_PREFIX . "order` o";
				}				
				break;			
		}
		
		} else {

		if (isset($data['filter_details']) && $data['filter_details'] == 'all_details_products') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
				$sql = "SELECT op.order_product_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "coupon_history` ch ON (ch.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "coupon` c ON (ch.coupon_id = c.coupon_id)";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
				$sql = "SELECT op.order_product_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "voucher_history` vh ON (vh.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "voucher` v ON (vh.voucher_id = v.voucher_id)";
			} else {
				$sql = "SELECT op.order_product_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
			}			
		} elseif (isset($data['filter_details']) && $data['filter_details'] == 'all_details_orders') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'coupon') {
				$sql = "SELECT o.order_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "coupon_history` ch ON (ch.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "coupon` c ON (ch.coupon_id = c.coupon_id)";
			} elseif (isset($data['filter_report']) && $data['filter_report'] == 'voucher') {
				$sql = "SELECT o.order_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) INNER JOIN `" . DB_PREFIX . "voucher_history` vh ON (vh.order_id = o.order_id) INNER JOIN `" . DB_PREFIX . "voucher` v ON (vh.voucher_id = v.voucher_id)";
			} else {
				$sql = "SELECT o.order_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
			}				
		}
		
		}
		
		if (!empty($data['filter_date_start'])) {	
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = '';
		}

		if (!empty($data['filter_date_end'])) {	
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_range'])) {
			$range = $data['filter_range'];
		} else {
			$range = 'current_year'; //show Current Year in Statistical Range by default
		}

		switch($range) 
		{
			case 'custom';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
				$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				break;			
			case 'today';
				$date_start = "DATE(o.date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(o.date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				break;			
			case 'month';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = '';
				break;					
			case 'last_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";			
				break;					
			case 'last_year';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;	
		}

		if (isset($data['filter_report']) && ($data['filter_report'] == 'tax' or $data['filter_report'] == 'coupon' or $data['filter_report'] == 'voucher')) {
			$sql .= ' AND (' . $date_start . $date_end . ')';
		} else {	
			$sql .= ' WHERE (' . $date_start . $date_end . ')';
		}	
		
		if (isset($data['filter_report']) && $data['filter_report'] != 'abandoned_orders') {
		if (!empty($data['filter_order_status_id'])) {
			if ((!empty($data['filter_status_date_start'])) && (!empty($data['filter_status_date_end']))) {			
				$sql .= " AND (SELECT DISTINCT oh.order_id FROM `" . DB_PREFIX . "order_history` oh WHERE o.order_id = oh.order_id AND (";
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "oh.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					$sql .= implode(" OR ", $implode) . "";
				}
				$sql .= ") AND DATE(oh.date_added) >= '" . $this->db->escape($data['filter_status_date_start']) . "' AND DATE(oh.date_added) <= '" . $this->db->escape($data['filter_status_date_end']) . "')";
			} else {
				$sql .= " AND (";
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					$sql .= implode(" OR ", $implode) . "";
				}
				$sql .= ")";				
			}
		} else {
			if (!empty($data['filter_status_date_start'])) {		
				$sql .= "AND DATE(o.date_modified) >= '" . $this->db->escape($data['filter_status_date_start']) . "'";
			} else {
				$sql .= '';
			}
			if (!empty($data['filter_status_date_end'])) {
				$sql .= "AND DATE(o.date_modified) <= '" . $this->db->escape($data['filter_status_date_end']) . "'";	
			} else {
				$sql .= '';
			}

			$sql .= ' AND o.order_status_id > 0';
		}
		} else {
			$sql .= ' AND o.order_status_id = 0';
		}	

		if (!empty($data['filter_order_id_from'])) {		
			$sql .= " AND o.order_id >= '" . $this->db->escape($data['filter_order_id_from']) . "'";
		} else {
			$sql .= '';
		}
		if (!empty($data['filter_order_id_to'])) {	
			$sql .= " AND o.order_id <= '" . $this->db->escape($data['filter_order_id_to']) . "'";	
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_order_value_min'])) {		
			$sql .= " AND o.total >= '" . $this->db->escape($data['filter_order_value_min']) . "'";
		} else {
			$sql .= '';
		}
		if (!empty($data['filter_order_value_max'])) {	
			$sql .= " AND o.total <= '" . $this->db->escape($data['filter_order_value_max']) . "'";	
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_store_id'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_store_id'] as $store_id) {
				$implode[] = "o.store_id = '" . (int)$store_id . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (!empty($data['filter_currency'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_currency'] as $currency) {
				$implode[] = "o.currency_id = '" . (int)$currency . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (!empty($data['filter_taxes'])) {
			if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
				$sql .= " AND (";
			} else {
				$sql .= " AND (SELECT DISTINCT ot.order_id FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND (";
			}
			$implode = array();
			foreach ($data['filter_taxes'] as $taxes) {
				$implode[] = "LCASE(ot.title) = '" . $taxes . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			if (isset($data['filter_report']) && $data['filter_report'] == 'tax') {
				$sql .= ")";
			} else {
				$sql .= "))";				
			}
		}

		if (!empty($data['filter_tax_classes'])) {
			$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "tax_class` tc, `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_tax_classes'] as $tax_classes) {
				$implode[] = "p.tax_class_id = '" . (int)$tax_classes . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}
		
		if (!empty($data['filter_geo_zones'])) {
			$sql .= " AND (SELECT zgz.geo_zone_id FROM `" . DB_PREFIX . "zone_to_geo_zone` zgz WHERE (";
			$implode = array();
			foreach ($data['filter_geo_zones'] as $geo_zones) {
				$implode[] = "(zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "') OR (o.payment_zone_id = zgz.zone_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "')";
				// $implode[] = "(zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND zgz.geo_zone_id = '" . (int)$geo_zones . "')";				
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_customer_group_id'] as $customer_group_id) {
				$implode[] = "(SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$customer_group_id . "') OR (o.customer_group_id = '" . (int)$customer_group_id . "' AND o.customer_id = 0)";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_customer_email'])) {
			$sql .= " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_customer_telephone'])) {
			$sql .= " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_ip'])) {
        	$sql .= " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_payment_company'])) {
			$sql .= " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_address'])) {
			$sql .= " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_city'])) {
			$sql .= " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_zone'])) {
			$sql .= " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_postcode'])) {
			$sql .= " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_country'])) {
			$sql .= " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_method'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_payment_method'] as $payment_method) {
				$implode[] = "o.payment_code = '" . $payment_method . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}

		if (!empty($data['filter_shipping_company'])) {
			$sql .= " AND LCASE(o.shipping_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_company'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_address'])) {
			$sql .= " AND LCASE(CONCAT(o.shipping_address_1, ', ', o.shipping_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_address'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_city'])) {
			$sql .= " AND LCASE(o.shipping_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_city'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_zone'])) {
			$sql .= " AND LCASE(o.shipping_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_zone'], 'UTF-8')) . "%'";
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_postcode'])) {
			$sql .= " AND LCASE(o.shipping_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_postcode'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_country'])) {
			$sql .= " AND LCASE(o.shipping_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_country'], 'UTF-8')) . "%'";			
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_shipping_method'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_shipping_method'] as $shipping_method) {
				$implode[] = "o.shipping_code = '" . $shipping_method . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (!empty($data['filter_category'])) {
			$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product_to_category` p2c, `" . DB_PREFIX . "order_product` op WHERE p2c.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_category'] as $category_id) {
				$implode[] = "p2c.category_id = '" . (int)$category_id . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}
		
		if (!empty($data['filter_manufacturer'])) {
			$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_manufacturer'] as $manufacturer) {
				$implode[] = "p.manufacturer_id = '" . (int)$manufacturer . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}
		
		if (!empty($data['filter_sku'])) {
        	$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND LCASE(p.sku) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_sku'], 'UTF-8')) . "%')";	
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_product_name'])) {
        	$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_name'], 'UTF-8')) . "%')";				
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_model'])) {
        	$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.model) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%')";		
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_option'])) {
			$sql .= " AND ";
			$implode = array();
			foreach ($data['filter_option'] as $option) {
				$implode[] = "(SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND oo.order_product_id = op.order_product_id AND LCASE(CONCAT(oo.name,'_',oo.value,'_',oo.type)) = '" . $option . "' AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_name'], 'UTF-8')) . "%')";
			}
			if ($implode) {
				$sql .= implode(" AND ", $implode) . "";
			}
		}

		if (!empty($data['filter_attribute'])) {
			$sql .= " AND ";
			$implode = array();
			foreach ($data['filter_attribute'] as $attribute) {
				$implode[] = "(SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE o.order_id = op.order_id AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND LCASE(CONCAT(agd.name,'_',ad.name,'_',pa.text)) = '" . $attribute . "')";
			}
			if ($implode) {
				$sql .= implode(" AND ", $implode) . "";
			}
		}
		
		if (!empty($data['filter_location'])) {
			$sql .= " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND (";
			$implode = array();
			foreach ($data['filter_location'] as $location) {
				$implode[] = "LCASE(p.location) = '" . $location . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}

		if (!empty($data['filter_affiliate_name'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_affiliate_name'] as $affiliate_name) {
				$implode[] = "o.affiliate_id = '" . (int)$affiliate_name . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}		

		if (!empty($data['filter_affiliate_email'])) {
			$sql .= " AND (SELECT a.affiliate_id FROM `" . DB_PREFIX . "affiliate` a WHERE a.affiliate_id = o.affiliate_id AND (";
			$implode = array();
			foreach ($data['filter_affiliate_email'] as $affiliate_email) {
				$implode[] = "o.affiliate_id = '" . (int)$affiliate_email . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}

		if (!empty($data['filter_coupon_name'])) {
			$sql .= " AND (SELECT DISTINCT cph.order_id FROM `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = o.order_id AND (";
			$implode = array();
			foreach ($data['filter_coupon_name'] as $coupon_name) {
				$implode[] = "cph.coupon_id = '" . (int)$coupon_name . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
		}

		if (!empty($data['filter_coupon_code'])) {
			$sql .= " AND (SELECT DISTINCT cph.order_id FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.coupon_id = cp.coupon_id AND cph.order_id = o.order_id AND LCASE(cp.code) LIKE '" . $this->db->escape(mb_strtolower($data['filter_coupon_code'], 'UTF-8')) . "')";	
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_voucher_code'])) {
        	$sql .= " AND (SELECT DISTINCT gvh.order_id FROM `" . DB_PREFIX . "voucher` gv, `" . DB_PREFIX . "voucher_history` gvh WHERE gvh.voucher_id = gv.voucher_id AND gvh.order_id = o.order_id AND LCASE(gv.code) LIKE '" . $this->db->escape(mb_strtolower($data['filter_voucher_code'], 'UTF-8')) . "')";	
		} else {
			$sql .= '';
		}

		if (isset($data['filter_details']) && $data['filter_details'] == 'all_details_orders') {
			$sql .= " GROUP BY o.order_id";
		}	
		
		$query = $this->db->query($sql);
		
		if (isset($data['filter_details']) && ($data['filter_details'] == 'all_details_products' or $data['filter_details'] == 'all_details_orders')) {
			return $query->rows;
		} else {
			return $query->row['total'];
		}
	}
	
	public function getOrderStatuses($data = array()) {
		$query = $this->db->query("SELECT DISTINCT os.name, os.order_status_id FROM `" . DB_PREFIX . "order_status` os WHERE os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY LCASE(os.name) ASC");
		
		return $query->rows;	
	}
	
	public function getOrderStores($data = array()) {
		$query = $this->db->query("SELECT DISTINCT o.store_name, o.store_id FROM `" . DB_PREFIX . "order` o ORDER BY LCASE(o.store_name) ASC");
		
		return $query->rows;	
	}
	
	public function getOrderCurrencies($data = array()) {
		$query = $this->db->query("SELECT DISTINCT cur.currency_id, cur.code, cur.title FROM `" . DB_PREFIX . "currency` cur ORDER BY LCASE(cur.title) ASC");
		
		return $query->rows;	
	}

	public function getOrderTaxes($data = array()) {
		$query = $this->db->query("SELECT DISTINCT ot.title AS tax_title, LCASE(ot.title) AS tax FROM `" . DB_PREFIX . "order_total` ot WHERE ot.code = 'tax' ORDER BY LCASE(ot.title) ASC");
		
		return $query->rows;	
	}

	public function getOrderTaxClasses($data = array()) {
		$query = $this->db->query("SELECT DISTINCT tc.title AS tax_class_title, tc.tax_class_id AS tax_class FROM `" . DB_PREFIX . "tax_class` tc ORDER BY LCASE(tc.title) ASC");
		
		return $query->rows;	
	}
	
	public function getOrderGeoZones($data = array()) {
		$query = $this->db->query("SELECT DISTINCT gz.name AS geo_zone_name, gz.geo_zone_id AS geo_zone_country_id FROM `" . DB_PREFIX . "geo_zone` gz ORDER BY LCASE(gz.name) ASC");
		
		return $query->rows;	
	}
	
	public function getOrderCustomerGroups($data = array()) {
		$query = $this->db->query("SELECT DISTINCT cgd.name, cgd.customer_group_id FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY (cgd.name) ASC");
		
		return $query->rows;	
	}
	
	public function getOrderPaymentMethods($data = array()) {
		if (isset($data['filter_report']) && $data['filter_report'] != 'abandoned_orders') {
		$query = $this->db->query("SELECT DISTINCT o.payment_method, o.payment_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.payment_code IS NOT NULL AND o.payment_code != '' GROUP BY o.payment_code ORDER BY LCASE(o.payment_method) ASC");
		} else {
		$query = $this->db->query("SELECT DISTINCT o.payment_method, o.payment_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id = 0 AND o.payment_code IS NOT NULL AND o.payment_code != '' GROUP BY o.payment_code ORDER BY LCASE(o.payment_method) ASC");
		}	
		
		return $query->rows;	
	}
	
	public function getOrderShippingMethods($data = array()) {
		if (isset($data['filter_report']) && $data['filter_report'] != 'abandoned_orders') {
		$query = $this->db->query("SELECT DISTINCT o.shipping_method, o.shipping_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.shipping_code IS NOT NULL AND o.shipping_code != '' GROUP BY o.shipping_code ORDER BY LCASE(o.shipping_method) ASC");
		} else {
		$query = $this->db->query("SELECT DISTINCT o.shipping_method, o.shipping_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id = 0 AND o.shipping_code IS NOT NULL AND o.shipping_code != '' GROUP BY o.shipping_code ORDER BY LCASE(o.shipping_method) ASC");
		}	
		
		return $query->rows;	
	}	

	public function getProductsCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY name";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getProductManufacturers($manufacturer_id) {
		$product_manufacturer_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		
		foreach ($query->rows as $result) {
			$product_manufacturer_data[] = $result['manufacturer_id'];
		}

		return $product_manufacturer_data;
	}
	
	public function getProductsManufacturers($data = array()) {
		$query = $this->db->query("SELECT DISTINCT m.name, m.manufacturer_id FROM `" . DB_PREFIX . "manufacturer` m ORDER BY LCASE(m.name) ASC");
		
		return $query->rows;	
	}

	public function getOrderOptions($order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_product_id = '" . (int)$order_product_id . "' ORDER BY LCASE(name) ASC");

		return $query->rows;
	}

	public function getOrderTaxesDivided($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE code = 'tax' AND order_id = '" . (int)$order_id . "' ORDER BY LCASE(title) ASC");

		return $query->rows;
	}
	
	public function getOrderOptionsNames($data = array()) {
		$query = $this->db->query("SELECT oo.name FROM `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "order` o WHERE oo.order_id = o.order_id AND o.order_status_id > 0 AND (oo.type != 'checkbox' && oo.type != 'text' && oo.type != 'textarea' && oo.type != 'file' && oo.type != 'image' && oo.type != 'date' && oo.type != 'datetime' && oo.type != 'time') GROUP BY oo.name ORDER BY LCASE(oo.name) ASC");

		return $query->rows;
	}

	public function getOrderTaxNames($data = array()) {
		$query = $this->db->query("SELECT ot.title FROM `" . DB_PREFIX . "order_total` ot, `" . DB_PREFIX . "order` o WHERE ot.order_id = o.order_id AND o.order_status_id > 0 AND ot.code = 'tax' GROUP BY ot.title ORDER BY LCASE(ot.title) ASC");

		return $query->rows;
	}
	
	public function getProductOptions($data = array()) {
		$query = $this->db->query("SELECT DISTINCT LCASE(CONCAT(oo.name,'_',oo.value,'_',oo.type)) AS options, oo.name AS option_name, oo.value AS option_value FROM `" . DB_PREFIX . "order_option` oo WHERE (oo.type != 'text' && oo.type != 'textarea' && oo.type != 'file' && oo.type != 'image' && oo.type != 'date' && oo.type != 'datetime' && oo.type != 'time') GROUP BY oo.name, oo.value, oo.type ORDER BY oo.name, oo.value, oo.type ASC");		

		return $query->rows;
	}

	public function getProductAttributes($data = array()) {
		$query = $this->db->query("SELECT DISTINCT LCASE(CONCAT(agd.name,'_',ad.name,'_',pa.text)) AS attribute_title, CONCAT(agd.name,'&nbsp;&nbsp;&gt;&nbsp;&nbsp;',ad.name,'&nbsp;&nbsp;&gt;&nbsp;&nbsp;',pa.text) AS attribute_name FROM `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY agd.name, ad.name, pa.text ORDER BY agd.name, ad.name, pa.text ASC");		

		return $query->rows;
	}
	
	public function getProductLocations($data = array()) {
		$query = $this->db->query("SELECT DISTINCT p.location AS location_name, LCASE(p.location) AS location_title FROM `" . DB_PREFIX . "product` p WHERE p.location != '' ORDER BY LCASE(p.location) ASC");
		
		return $query->rows;	
	}	

	public function getOrderAffiliates($data = array()) {
		$query = $this->db->query("SELECT DISTINCT a.affiliate_id, CONCAT(a.firstname, ' ', a.lastname) AS affiliate_name, a.email AS affiliate_email FROM `" . DB_PREFIX . "affiliate` a ORDER BY LCASE(a.firstname) ASC");
		
		return $query->rows;	
	}	

	public function getOrderCouponNames($data = array()) {
		$query = $this->db->query("SELECT DISTINCT cp.coupon_id, cp.name AS coupon_name FROM `" . DB_PREFIX . "coupon` cp ORDER BY LCASE(cp.code) ASC");
		
		return $query->rows;	
	}	

	public function getCustomFieldsNames($data = array()) {
		$query = $this->db->query("SELECT cfd.name FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.location = 'account' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->rows;	
	}	
	
	public function getCustomerAutocomplete($data = array()) {
		if (isset($data['filter_report']) && $data['filter_report'] != 'abandoned_orders') {
		$sql = "SELECT DISTINCT o.customer_id, CONCAT(o.firstname, ' ', o.lastname) AS cust_name, o.email AS cust_email, o.telephone AS cust_telephone, o.payment_company, CONCAT(o.payment_address_1, ', ', o.payment_address_2) AS payment_address, o.payment_city, o.payment_zone, o.payment_postcode, o.payment_country, o.shipping_company, CONCAT(o.shipping_address_1, ', ', o.shipping_address_2) AS shipping_address, o.shipping_city, o.shipping_zone, o.shipping_postcode, o.shipping_country, o.ip AS cust_ip FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0";
		} else {
		$sql = "SELECT DISTINCT o.customer_id, CONCAT(o.firstname, ' ', o.lastname) AS cust_name, o.email AS cust_email, o.telephone AS cust_telephone, o.payment_company, CONCAT(o.payment_address_1, ', ', o.payment_address_2) AS payment_address, o.payment_city, o.payment_zone, o.payment_postcode, o.payment_country, o.shipping_company, CONCAT(o.shipping_address_1, ', ', o.shipping_address_2) AS shipping_address, o.shipping_city, o.shipping_zone, o.shipping_postcode, o.shipping_country, o.ip AS cust_ip FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id = 0";
		}
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_customer_email'])) {
			$sql .= " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_customer_telephone'])) {
			$sql .= " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		}
		
		if (!empty($data['filter_payment_company'])) {
			$sql .= " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_address'])) {
			$sql .= " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_city'])) {
			$sql .= " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_zone'])) {
			$sql .= " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_postcode'])) {
			$sql .= " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_payment_country'])) {
			$sql .= " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
		}
		
		if (!empty($data['filter_shipping_company'])) {
			$sql .= " AND LCASE(o.shipping_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_company'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_shipping_address'])) {
			$sql .= " AND LCASE(CONCAT(o.shipping_address_1, ', ', o.shipping_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_address'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_shipping_city'])) {
			$sql .= " AND LCASE(o.shipping_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_city'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_shipping_zone'])) {
			$sql .= " AND LCASE(o.shipping_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_zone'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_shipping_postcode'])) {
			$sql .= " AND LCASE(o.shipping_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_postcode'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_shipping_country'])) {
			$sql .= " AND LCASE(o.shipping_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_country'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_ip'])) {
        	$sql .= " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getProductAutocomplete($data = array()) {
		$sql = "SELECT DISTINCT op.product_id, p.sku AS prod_sku, op.name AS prod_name, op.model AS prod_model FROM " . DB_PREFIX . "order_product op, " . DB_PREFIX . "product p WHERE op.product_id = p.product_id";
		
		if (!empty($data['filter_sku'])) {
        	$sql .= " AND LCASE(p.sku) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_sku'], 'UTF-8')) . "%'";				
		}
		
		if (!empty($data['filter_product_name'])) {
        	$sql .= " AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_name'], 'UTF-8')) . "%'";				
		}

		if (!empty($data['filter_model'])) {
        	$sql .= " AND LCASE(op.model) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%'";				
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
						
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getCouponAutocomplete($data = array()) {
		$sql = "SELECT DISTINCT cp.coupon_id, cp.code AS coupon_code FROM `" . DB_PREFIX . "coupon` cp";
		
		if (!empty($data['filter_coupon_code'])) {
        	$sql .= " WHERE LCASE(cp.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_coupon_code'], 'UTF-8')) . "%'";
		}	

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
						
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getVoucherAutocomplete($data = array()) {
		$sql = "SELECT DISTINCT gv.voucher_id, gv.code AS voucher_code FROM `" . DB_PREFIX . "voucher` gv";
		
		if (!empty($data['filter_voucher_code'])) {
        	$sql .= " WHERE LCASE(gv.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_voucher_code'], 'UTF-8')) . "%'";
		}	

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
						
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}