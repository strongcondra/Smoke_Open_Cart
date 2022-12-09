<?php
class ModelReportAdvCustomers extends Model {
	public function getCustomers($data = array()) {
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

		switch($range) {
			case 'custom';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(sc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(cw.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";					
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "') AND (DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'))";
				} else {
					$type = '';
				}				
				break;	
			case 'today';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) = CURDATE()";
					$date_end = '';				
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE()";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) = CURDATE()";
					$date_end = '';		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) = CURDATE()";
					$date_end = '';						
				} else {
					$date_start = "DATE(o.date_added) = CURDATE()";
					$date_end = '';	
				}
				$type = '';				
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) = CURDATE()))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE())) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) = CURDATE()))";
				} else {
					$type = '';
				}					
				break;
			case 'yesterday';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(o.date_added) < CURDATE()";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(sc.date_added) < CURDATE()";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(cw.date_added) < CURDATE()";						
				} else {
					$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(o.date_added) < CURDATE()";	
				}
				$type = '';				
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_ADD(CURDATE(), INTERVAL -1 DAY))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)) AND (DATE(o.date_added) < CURDATE()))";					
				} else {
					$type = '';
				}					
				break;					
			case 'week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";						
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";		
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'))";
				} else {
					$type = '';
				}					
				break;
			case 'month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";							
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'))";
				} else {
					$type = '';
				}					
				break;			
			case 'quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'))";						
				} else {
					$type = '';
				}					
				break;
			case 'year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'))";					
				} else {
					$type = '';
				}					
				break;
			case 'current_week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE())";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";							
				} else {
					$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE())))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE()))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())))";
				} else {
					$type = '';
				}				
				break;	
			case 'current_month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "YEAR(sc.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(sc.date_added) = MONTH(CURDATE())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "YEAR(cw.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(cw.date_added) = MONTH(CURDATE())";	
				} else {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";				
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - DAYOFMONTH(CURDATE()) + 1))";
				} else {
					$type = '';
				}					
				break;
			case 'current_quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "QUARTER(sc.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(sc.date_added) = YEAR(CURDATE())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "QUARTER(cw.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(cw.date_added) = YEAR(CURDATE())";
				} else {
					$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";				
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER))";					
				} else {
					$type = '';
				}
				break;					
			case 'current_year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - YEAR(CURDATE())";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "YEAR(sc.date_added) = YEAR(CURDATE())";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "YEAR(cw.date_added) = YEAR(CURDATE())";
					$date_end = '';		
				} else {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = '';					
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - YEAR(CURDATE())))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - YEAR(CURDATE()))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - YEAR(CURDATE())))";
				} else {
					$type = '';
				}					
				break;					
			case 'last_week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(sc.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(cw.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} else {
					$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY) AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY))";
				} else {
					$type = '';
				}				
				break;	
			case 'last_month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(sc.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(cw.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} else {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01'))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')) AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')))";
				} else {
					$type = '';
				}					
				break;
			case 'last_quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "QUARTER(sc.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "QUARTER(cw.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = " AND YEAR(cw.date_added) = YEAR(CURDATE())";
				} else {
					$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY) AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 1 QUARTER) + INTERVAL 1 DAY))";					
				} else {
					$type = '';
				}					
				break;					
			case 'last_year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(sc.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(cw.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} else {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01'))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')) AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')))";
				} else {
					$type = '';
				}					
				break;					
			case 'all_time';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";
				}	
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} else {
					$type = '';
				}					
				break;	
		}

		if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$date = ' AND (' . $date_start . $date_end . ')';								
		} else {
			$date = ' WHERE (' . $date_start . $date_end . ')';				
		}
		
		$osi = '';
		$osii = '';
		$sdate = '';
		if (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists') {
		if (isset($data['filter_report']) && $data['filter_report'] != 'customers_abandoned_orders') {
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
				if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$osi .= " AND (SELECT o.order_id FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0 AND (";
				$osii .= " AND (";
				} else {
				$osi .= " AND (";
				}
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
					$osi .= implode(" OR ", $implode) . "";
					$osii .= implode(" OR ", $implode) . "";
					} else {
					$osi .= implode(" OR ", $implode) . "";
					}
				}
				if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$osi .= ") GROUP BY o.customer_id)";
				$osii .= ")";
				} else {
				$osi .= ")";
				}
				
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

			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
			$osi .= '';
			} else {
			$osi = " AND o.order_status_id > 0";
			}
			$sdate = $status_date_start . $status_date_end;
		}
		} else {
			$osi = " AND o.order_status_id = 0";
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
		}
		
		$store = '';
		if (!empty($data['filter_store_id'])) {
			$store .= " AND (";
			$implode = array();
			foreach ($data['filter_store_id'] as $store_id) {
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$implode[] = "c.store_id = '" . (int)$store_id . "'";	
				} else {
				$implode[] = "o.store_id = '" . (int)$store_id . "'";
				}
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
			$tax .= " AND (SELECT DISTINCT ot.order_id FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND (";
			$implode = array();
			foreach ($data['filter_taxes'] as $taxes) {
				$implode[] = "LCASE(ot.title) = '" . $taxes . "'";
			}
			if ($implode) {
				$tax .= implode(" OR ", $implode) . "";
			}
			$tax .= "))";
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
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$implode[] = "c.customer_group_id = '" . (int)$customer_group_id . "'";	
				} else {
				$implode[] = "(SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$customer_group_id . "') OR (o.customer_group_id = '" . (int)$customer_group_id . "' AND o.customer_id = 0)";
				}
			}
			if ($implode) {
				$cgrp .= implode(" OR ", $implode) . "";
			}
			$cgrp .= ")";
		}

		$stat = '';
		if (!empty($data['filter_customer_status'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$stat .= " AND (";
			} else {
			$stat .= " AND (SELECT DISTINCT c.customer_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND (";
			}
			$implode = array();
			foreach ($data['filter_customer_status'] as $customer_status) {
				$implode[] = "c.status = '" . (int)$customer_status . "'";
			}
			if ($implode) {
				$stat .= implode(" OR ", $implode) . "";
			}
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$stat .= ")";
			} else {
			$stat .= "))";
			}
		}
		
		$cust = '';
		if (!empty($data['filter_customer_name'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$cust = " AND LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
			} else {
			$cust = " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
			}
		} else {
			$cust = '';
		}

		$email = '';
		if (!empty($data['filter_customer_email'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$email = " AND LCASE(c.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";
			} else {
			$email = " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";
			}			
		} else {
			$email = '';
		}

		$tel = '';
		if (!empty($data['filter_customer_telephone'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$tel = " AND LCASE(c.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";
			} else {
			$tel = " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";
			}				
		} else {
			$tel = '';
		}

		$ip = '';
		if (!empty($data['filter_ip'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
        	$ip = " AND LCASE(c.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
			} else {
        	$ip = " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
			}	
		} else {
			$ip = '';
		}
		
		$pcomp = '';
		if (!empty($data['filter_payment_company'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$pcomp = " AND LCASE(adr.company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
			} else {
        	$pcomp = " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
			}	
		} else {
			$pcomp = '';
		}

		$paddr = '';
		if (!empty($data['filter_payment_address'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$paddr = " AND LCASE(CONCAT(adr.address_1, ', ', adr.address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
			} else {
        	$paddr = " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
			}	
		} else {
			$paddr = '';
		}

		$pcity = '';
		if (!empty($data['filter_payment_city'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$pcity = " AND LCASE(adr.city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
			} else {
        	$pcity = " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
			}
		} else {
			$pcity = '';
		}

		$pzone = '';
		if (!empty($data['filter_payment_zone'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$pzone = " AND (SELECT LCASE(z.name) FROM `" . DB_PREFIX . "zone` z WHERE adr.zone_id = z.zone_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
			} else {
        	$pzone = " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
			}
		} else {
			$pzone = '';

		}

		$ppsc = '';
		if (!empty($data['filter_payment_postcode'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$ppsc = " AND LCASE(adr.postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";	
			} else {
        	$ppsc = " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";
			}		
		} else {
			$ppsc = '';
		}

		$pcntr = '';
		if (!empty($data['filter_payment_country'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$pcntr = " AND (SELECT LCASE(cnt.name) FROM `" . DB_PREFIX . "country` cnt WHERE adr.country_id = cnt.country_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";	
			} else {
        	$pcntr = " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";
			}			
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
			
		if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
			
		$sql = "SELECT c.*, adr.*, 
		c.customer_id AS id, 
		c.firstname AS first_name, 
		c.lastname AS last_name, 
		CONCAT(c.firstname, ' ', c.lastname) AS name, 
		cgd.name AS customer_group, 
		c.custom_field AS customer_custom_field, 
		(SELECT cnt.name FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country_code, 
		(SELECT z.name FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state,
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state_code, 
		(SELECT COUNT(ca.customer_id) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS total_logins, 
		(SELECT MAX(ca.date_added) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS last_login, 
		(SELECT MAX(o.date_added) FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0" . $date . $osii . " GROUP BY o.customer_id) AS mostrecent, 		
		(SELECT COUNT(o.order_id) FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0" . $date . $osii . " GROUP BY o.customer_id) AS orders, 
		(SELECT SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0" . $date . $osii . " GROUP BY o.customer_id) AS products,  
		(SELECT SUM(o.total) FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0" . $date . $osii . " GROUP BY o.customer_id) AS total, 
		(SELECT SUM(op.price*r.quantity) FROM `" . DB_PREFIX . "order` o, `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND o.order_id = op.order_id AND o.customer_id = c.customer_id AND r.return_action_id = '1'" . $date . $osii . " GROUP BY r.order_id) AS refunds, 
		(SELECT SUM((SELECT SUM(op.reward) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.customer_id > 0 AND o.order_status_id > 0" . $date . $osii . " GROUP BY o.customer_id) AS reward_points ";
					
		$sql .= "FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (c.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0" . $date . ") OR c.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0" . $date . "))" . $osi . $store . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $type;

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_without_orders') {
			
		$sql = "SELECT c.*, adr.*, 
		c.customer_id AS id, 
		c.firstname AS first_name, 
		c.lastname AS last_name, 
		CONCAT(c.firstname, ' ', c.lastname) AS name, 
		cgd.name AS customer_group, 
		c.custom_field AS customer_custom_field, 
		(SELECT cnt.name FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country_code, 
		(SELECT z.name FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state,
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state_code, 
		(SELECT COUNT(ca.customer_id) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS total_logins, 
		(SELECT MAX(ca.date_added) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS last_login ";
					
		$sql .= "FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0" . $date . ")" . $store . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $type;

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {

		$sql = "SELECT c.*, adr.*, sc.*,  
		HEX(sc.date_added) AS date, 
		MIN(sc.date_added) AS date_start, 
		MAX(sc.date_added) AS date_end, 	
		c.customer_id AS id, 
		c.firstname AS first_name, 
		c.lastname AS last_name, 
		CONCAT(c.firstname, ' ', c.lastname) AS name, 
		cgd.name AS customer_group, 
		c.custom_field AS customer_custom_field, 
		(SELECT cnt.name FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country_code, 
		(SELECT z.name FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state,
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state_code, 
		(SELECT COUNT(ca.customer_id) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS total_logins, 
		(SELECT MAX(ca.date_added) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS last_login, 		
		SUM(sc.quantity) AS cart_quantity ";
		
		$sql .= "FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "cart sc, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = sc.customer_id AND c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'" . $date . $store . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $type;
		
		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {

		$sql = "SELECT c.*, adr.*, cw.*,  
		HEX(cw.date_added) AS date, 
		MIN(cw.date_added) AS date_start, 
		MAX(cw.date_added) AS date_end, 	
		c.customer_id AS id, 
		c.firstname AS first_name, 
		c.lastname AS last_name, 
		CONCAT(c.firstname, ' ', c.lastname) AS name, 
		cgd.name AS customer_group, 
		c.custom_field AS customer_custom_field, 
		(SELECT cnt.name FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id) AS country_code, 
		(SELECT z.name FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state,
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id) AS region_state_code, 
		(SELECT COUNT(ca.customer_id) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS total_logins, 
		(SELECT MAX(ca.date_added) FROM `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = ca.customer_id AND ca.key = 'login') AS last_login, 		
		COUNT(cw.product_id) AS wishlist_quantity ";
		
		$sql .= "FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "customer_wishlist cw, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = cw.customer_id AND c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'" . $date . $store . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $type;

		} else {
			
		$sql = "SELECT *, 
		o.date_added AS date, 
		YEAR(o.date_added) AS year, 
		QUARTER(o.date_added) AS quarter, 		
		MONTHNAME(o.date_added) AS month, 		
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		o.customer_id, 
		CONCAT(o.firstname, ' ', o.lastname) AS cust_name, 	
		o.email AS cust_email, 
		o.telephone AS cust_telephone, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY o.customer_group_id) AS cust_group, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd, `" . DB_PREFIX . "customer` c WHERE o.customer_id > 0 AND c.customer_id = o.customer_id AND cgd.customer_group_id = c.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS cust_group_reg, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE o.customer_id = 0 AND cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS cust_group_guest, 
		(SELECT c.custom_field FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS customer_custom_field, 
		(SELECT c.status FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS cust_status, 
		(SELECT adr.firstname FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS first_name, 
		(SELECT adr.lastname FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS last_name, 
		(SELECT adr.company FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS company, 
		(SELECT adr.address_1 FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS address_1, 
		(SELECT adr.address_2 FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS address_2, 
		(SELECT adr.city FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS city, 
		(SELECT adr.postcode FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS postcode, 
		(SELECT adr.country_id FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS country_id, 
		(SELECT cnt.name FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr, `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id AND c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr, `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = adr.country_id AND c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS country_code, 
		(SELECT adr.zone_id FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr WHERE c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS zone_id, 
		(SELECT z.name FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr, `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id AND c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS region_state, 
		(SELECT z.code FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "address` adr, `" . DB_PREFIX . "zone` z WHERE z.zone_id = adr.zone_id AND c.address_id = adr.address_id AND c.customer_id = o.customer_id GROUP BY o.customer_id) AS region_state_code, 
		o.payment_firstname AS guest_firstname, 
		o.payment_lastname AS guest_lastname, 
		o.payment_company AS guest_company, 
		o.payment_address_1 AS guest_address_1, 
		o.payment_address_2 AS guest_address_2, 
		o.payment_city AS guest_city, 
		o.payment_postcode AS guest_postcode, 
		o.payment_country_id AS guest_country_id, 
		o.payment_country AS guest_country, 
		(SELECT cnt.iso_code_2 FROM `" . DB_PREFIX . "country` cnt WHERE cnt.country_id = o.payment_country_id) AS guest_country_code, 
		o.payment_zone_id AS guest_zone_id, 
		o.payment_zone AS guest_region_state, 
		(SELECT z.code FROM `" . DB_PREFIX . "zone` z WHERE z.zone_id = o.payment_zone_id) AS guest_region_state_code, 
		(SELECT c.newsletter FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS newsletter, 
		(SELECT c.approved FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS approved, 
		(SELECT c.safe FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS safe, 
		(SELECT c.ip FROM `" . DB_PREFIX . "customer` c WHERE o.customer_id = c.customer_id) AS ip, 
		(SELECT COUNT(ca.customer_id) FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = o.customer_id AND c.customer_id = ca.customer_id AND ca.key = 'login') AS total_logins, 
		(SELECT MAX(ca.date_added) FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "customer_activity` ca WHERE c.customer_id = o.customer_id AND c.customer_id = ca.customer_id AND ca.key = 'login') AS last_login, 		
		MAX(o.date_added) AS mostrecent, 
		COUNT(o.order_id) AS orders, 
		SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, 
		SUM(o.total) AS total, 
		SUM((SELECT SUM(op.price*r.quantity) FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "return` r WHERE r.product_id = op.product_id AND r.order_id = op.order_id AND o.order_id = op.order_id AND r.return_action_id = '1' GROUP BY r.order_id)) AS refunds, 
		SUM((SELECT SUM(op.reward) FROM `" . DB_PREFIX . "customer` c, `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id AND o.customer_id = c.customer_id AND o.customer_id > 0 GROUP BY o.customer_id)) AS reward_points ";

		if (isset($data['filter_details']) && $data['filter_details'] == 'basic_details') {
		if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_with_orders' or $data['filter_report'] == 'registered_and_guest_customers' or $data['filter_report'] == 'guest_customers' or $data['filter_report'] == 'new_customers' or $data['filter_report'] == 'old_customers' or $data['filter_report'] == 'customers_abandoned_orders')) {
			$sql .= ", GROUP_CONCAT(o.order_id ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_id, 
			GROUP_CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',o.order_id,'\">',o.order_id,'</a>' ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_ord_id_link, ";
			if ($this->config->get('advco' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
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
			GROUP_CONCAT(IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id),0) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_tax, 
			GROUP_CONCAT(ROUND(o.currency_value*o.total, 2) ORDER BY o.order_id DESC SEPARATOR '<br>') AS order_value, 

			GROUP_CONCAT((SELECT GROUP_CONCAT(op.order_id SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_id, 
			GROUP_CONCAT((SELECT GROUP_CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',op.order_id,'\">',op.order_id,'</a>' SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_ord_id_link, ";
			if ($this->config->get('advco' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
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
			if ($this->config->get('advco' . $this->user->getId() . '_date_format') == 'DDMMYYYY') {
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

		$sql .= "FROM `" . DB_PREFIX . "order` o" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc . $type;
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
		(SELECT wcd.unit FROM `" . DB_PREFIX . "weight_class_description` wcd WHERE wcd.weight_class_id = '" . $this->config->get('config_weight_class_id') . "' AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class 

		FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)" . $date . $sdate . $osi . $order_id . $order_value . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $stat . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc . $type;
		
		if (isset($data['filter_details']) && $data['filter_details'] == 'all_details_orders') {
			$sql .= " GROUP BY o.order_id";
		}
		
		$sql .= " ORDER BY o.order_id DESC";
		}

		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {

		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'no_group'; //show No Grouping in Group By default
		}
		
		if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_wishlists')) {
		
			$sql .= " GROUP BY c.customer_id";
			
		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
			
			$sql .= " GROUP BY sc.customer_id";			

		} elseif (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_with_orders' or $data['filter_report'] == 'new_customers' or $data['filter_report'] == 'old_customers')) {
		
		switch($group) {
			case 'no_group';
				$sql .= " GROUP BY o.customer_id";
				break;	
			case 'order';
				$sql .= " GROUP BY o.order_id, o.customer_id";
				break;				
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added) DESC, MONTH(o.date_added) DESC, DAY(o.date_added) DESC, o.customer_id";
				break;
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, WEEK(o.date_added) DESC, o.customer_id";
				break;			
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, MONTH(o.date_added) DESC, o.customer_id";
				break;
			case 'quarter':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, QUARTER(o.date_added) DESC, o.customer_id";
				break;				
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, o.customer_id";
				break;	
		}
		
		} else {
		
		switch($group) {
			case 'no_group';
				$sql .= " GROUP BY o.email";
				break;	
			case 'order';
				$sql .= " GROUP BY o.order_id, o.email";
				break;				
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added) DESC, MONTH(o.date_added) DESC, DAY(o.date_added) DESC, o.email";
				break;
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, WEEK(o.date_added) DESC, o.email";
				break;			
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, MONTH(o.date_added) DESC, o.email";
				break;
			case 'quarter':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, QUARTER(o.date_added) DESC, o.email";
				break;				
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added) DESC, o.email";
				break;			
		}
		
		}

		if (!isset($data['filter_order'])) {
			if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_without_orders') {
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
		
		if (isset($data['filter_report']) && (($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders') or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sort_date = " date_added";
			$sort_customer = " LCASE(CONCAT(first_name,last_name))";
			$sort_email = " LCASE(email)";
			$sort_telephone = " LCASE(telephone)";
			$sort_customer_group = " LCASE(customer_group)";
			$sort_customer_status = " LCASE(status)";
			$sort_first_name = " LCASE(first_name)";
			$sort_last_name = " LCASE(last_name)";
			$sort_company = " LCASE(company)";
			$sort_address_1 = " LCASE(address_1)";
			$sort_address_2 = " LCASE(address_2)";
			$sort_city = " LCASE(city)";
			$sort_postcode = " LCASE(postcode)";
			$sort_country_id = " country_id";
			$sort_country = " LCASE(country)";
			$sort_country_code = " LCASE(country_code)";
			$sort_zone_id = " zone_id";
			$sort_region_state = " LCASE(region_state)";
			$sort_region_state_code = " LCASE(region_state_code)";
		} else {
			$sort_date = " date_start";
			$sort_customer = " LCASE(cust_name)";
			$sort_email = " LCASE(cust_email)";
			$sort_telephone = " LCASE(cust_telephone)";
			$sort_customer_group = " LCASE(cust_group)";
			$sort_customer_status = " LCASE(cust_status)";
			$sort_first_name = " LCASE(guest_firstname)";
			$sort_last_name = " LCASE(guest_lastname)";
			$sort_company = " LCASE(guest_company)";
			$sort_address_1 = " LCASE(guest_address_1)";
			$sort_address_2 = " LCASE(guest_address_2)";
			$sort_city = " LCASE(guest_city)";
			$sort_postcode = " LCASE(guest_postcode)";
			$sort_country_id = " guest_country_id";
			$sort_country = " LCASE(guest_country)";
			$sort_country_code = " LCASE(guest_country_code)";
			$sort_zone_id = " guest_zone_id";
			$sort_region_state = " LCASE(guest_region_state)";
			$sort_region_state_code = " LCASE(guest_region_state_code)";	
		}
			
		if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {	
			$sort_aov = " total / orders";	
		} else {			
			$sort_aov = " SUM(o.total) / COUNT(o.order_id)";				
		}
			
		if (isset($data['filter_sort']) && $data['filter_sort'] == 'date') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_products_with_without_orders') {
				$sql .= " ORDER BY" . $sort_date . $sort_order . ", orders ASC";
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_without_orders') {
				$sql .= " ORDER BY" . $sort_date . $sort_order . ", id ASC";
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
				$sql .= " ORDER BY date_start" . $sort_order . ", cart_quantity DESC";	
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
				$sql .= " ORDER BY date_start" . $sort_order . ", wishlist_quantity DESC";
			} else {
				$sql .= " ORDER BY" . $sort_date . $sort_order . ", orders DESC ";
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'id') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY id" . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY id" . $sort_order . " ";
			} else {	
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY customer_id" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY customer_id" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, customer_id" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, customer_id" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, customer_id" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, customer_id" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, customer_id" . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'customer') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_customer . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_customer . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_customer . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_customer . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_customer . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_customer . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_customer . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_customer . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_customer . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'email') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_email . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_email . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_email . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_email . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_email . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_email . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_email . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_email . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_email . $sort_order . " ";
			}	
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'telephone') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_telephone . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_telephone . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_telephone . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_telephone . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_telephone . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_telephone . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_telephone . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_telephone . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_telephone . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'customer_group') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_customer_group . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_customer_group . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_customer_group . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_customer_group . $sort_order . ", order_id DESC ";					
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_customer_group . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_customer_group . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_customer_group . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_customer_group . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_customer_group . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'customer_status') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_customer_status . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_customer_status . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_customer_status . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_customer_status . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_customer_status . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_customer_status . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_customer_status . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_customer_status . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_customer_status . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'first_name') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_first_name . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_first_name . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_first_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_first_name . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_first_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_first_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_first_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_first_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_first_name . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'last_name') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_last_name . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_last_name . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_last_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_last_name . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_last_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_last_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_last_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_last_name . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_last_name . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'company') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_company . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_company . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_company . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_company . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_company . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_company . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_company . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_company . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_company . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'address_1') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_address_1 . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_address_1 . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_address_1 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_address_1 . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_address_1 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_address_1 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_address_1 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_address_1 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_address_1 . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'address_2') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_address_2 . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_address_2 . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_address_2 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_address_2 . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_address_2 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_address_2 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_address_2 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_address_2 . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_address_2 . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'city') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_city . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_city . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_city . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_city . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_city . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_city . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_city . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_city . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_city . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'postcode') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_postcode . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_postcode . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_postcode . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_postcode . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_postcode . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_postcode . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_postcode . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_postcode . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_postcode . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'country_id') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_country_id . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_country_id . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_country_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_country_id . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_country_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_country_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_country_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_country_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_country_id . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'country') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_country . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_country . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_country . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_country . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_country . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_country . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_country . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_country . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_country . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'country_code') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_country_code . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_country_code . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_country_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_country_code . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_country_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_country_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_country_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_country_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_country_code . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'zone_id') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_zone_id . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_zone_id . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_zone_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_zone_id . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_zone_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_zone_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_zone_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_zone_id . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_zone_id . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'region_state') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_region_state . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_region_state . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_region_state . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_region_state . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_region_state . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_region_state . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_region_state . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_region_state . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_region_state . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'region_state_code') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY" . $sort_region_state_code . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY" . $sort_region_state_code . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_region_state_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_region_state_code . $sort_order . ", order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_region_state_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_region_state_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_region_state_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_region_state_code . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_region_state_code . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'newsletter') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY LCASE(newsletter)" . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY LCASE(newsletter)" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, LCASE(newsletter)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, LCASE(newsletter)" . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'approved') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY LCASE(approved)" . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY LCASE(approved)" . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY LCASE(approved)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY LCASE(approved)" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, LCASE(approved)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, LCASE(approved)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, LCASE(approved)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, LCASE(approved)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, LCASE(approved)" . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'safe') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY LCASE(safe)" . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY LCASE(safe)" . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY LCASE(safe)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY LCASE(safe)" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, LCASE(safe)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, LCASE(safe)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, LCASE(safe)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, LCASE(safe)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, LCASE(safe)" . $sort_order . ", orders DESC ";
			}
			}				
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'ip') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY LCASE(ip)" . $sort_order . ", orders DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY LCASE(ip)" . $sort_order . ", id ASC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY LCASE(ip)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY LCASE(ip)" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, LCASE(ip)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, LCASE(ip)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, LCASE(ip)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, LCASE(ip)" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, LCASE(ip)" . $sort_order . ", orders DESC ";
			}
			}
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'total_logins') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY total_logins" . $sort_order . ", last_login DESC ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY total_logins" . $sort_order . ", last_login DESC";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY total_logins" . $sort_order . ", last_login DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY total_logins" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, total_logins" . $sort_order . ", last_login DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, total_logins" . $sort_order . ", last_login DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, total_logins" . $sort_order . ", last_login DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, total_logins" . $sort_order . ", last_login DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, total_logins" . $sort_order . ", last_login DESC ";
			}
			}	
		} elseif (isset($data['filter_sort']) && $data['filter_sort'] == 'last_login') {
			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " ORDER BY last_login" . $sort_order . " ";
			} else if (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$sql .= " ORDER BY last_login" . $sort_order . " ";
			} else {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY last_login" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY last_login" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, last_login" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, last_login" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, last_login" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, last_login" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, last_login" . $sort_order . " ";
			}
			}				
		} elseif (isset($data['filter_sort']) && ($data['filter_sort'] == 'cart_quantity' or $data['filter_sort'] == 'wishlist_quantity')) {
			if (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
				$sql .= " ORDER BY cart_quantity" . $sort_order;
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
				$sql .= " ORDER BY wishlist_quantity" . $sort_order;
			}
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'mostrecent') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY mostrecent" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY mostrecent" . $sort_order . ", order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, mostrecent" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, mostrecent" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, mostrecent" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, mostrecent" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, mostrecent" . $sort_order . ", orders DESC ";
			}
			} else {
				$sql .= " ORDER BY mostrecent" . $sort_order . ", orders DESC ";
			}				
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'orders') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY orders" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY order_id DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, orders" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, orders" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, orders" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, orders" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, orders" . $sort_order . ", total DESC ";
			}
			} else {
				$sql .= " ORDER BY orders" . $sort_order . ", total DESC ";
			}	
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'products') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY products" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY products" . $sort_order . ", orders DESC, order_id DESC ";				
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, products" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, products" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, products" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, products" . $sort_order . ", orders DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, products" . $sort_order . ", orders DESC ";
			}
			} else {
				$sql .= " ORDER BY products" . $sort_order . ", orders DESC ";
			}				
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'total') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY total" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY total" . $sort_order . ", order_id DESC ";					
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, total" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, total" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, total" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, total" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, total" . $sort_order . " ";
			}	
			} else {
				$sql .= " ORDER BY total" . $sort_order . " ";
			}				
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'aov') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY" . $sort_aov . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY" . $sort_aov . $sort_order . ", order_id DESC ";					
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC," . $sort_aov . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC," . $sort_aov . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC," . $sort_aov . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC," . $sort_aov . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC," . $sort_aov . $sort_order . " ";
			}	
			} else {
				$sql .= " ORDER BY" . $sort_aov . $sort_order . " ";
			}				
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'refunds') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY refunds" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY refunds DESC, order_id DESC ";					
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, refunds" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, refunds" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, refunds" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, refunds" . $sort_order . " ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, refunds" . $sort_order . " ";
			}
			} else {
				$sql .= " ORDER BY refunds" . $sort_order . " ";
			}				
		} elseif ((isset($data['filter_sort']) && $data['filter_sort'] == 'reward_points') && (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists')) {
			if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders') {
			if (isset($data['filter_group']) && $data['filter_group'] == 'no_group') {		
				$sql .= " ORDER BY reward_points" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'order') {	
				$sql .= " ORDER BY reward_points" . $sort_order . ", order_id DESC ";					
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'day') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, reward_points" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'week') {	
				$sql .= " ORDER BY YEAR(date) DESC, WEEK(date) DESC, reward_points" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'month') {	
				$sql .= " ORDER BY YEAR(date) DESC, MONTH(date) DESC, reward_points" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'quarter') {	
				$sql .= " ORDER BY YEAR(date) DESC, QUARTER(date) DESC, reward_points" . $sort_order . ", total DESC ";
			} elseif (isset($data['filter_group']) && $data['filter_group'] == 'year') {	
				$sql .= " ORDER BY YEAR(date) DESC, reward_points DESC, total DESC ";
			}
			} else {
				$sql .= " ORDER BY reward_points" . $sort_order . ", total DESC ";
			}				
		} else {
			if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_without_orders') {
				$sql .= " ORDER BY id" . $sort_order . " ";
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
				$sql .= " ORDER BY cart_quantity" . $sort_order;
			} else if (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
				$sql .= " ORDER BY wishlist_quantity" . $sort_order;				
			} else {
				$sql .= " ORDER BY orders" . $sort_order . ", total DESC ";
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

	public function getCustomersTotal($data = array()) {
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

		switch($range) {
			case 'custom';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(sc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(cw.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";					
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape($data['filter_date_start']) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "') AND (DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'))";
				} else {
					$type = '';
				}				
				break;	
			case 'today';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) = CURDATE()";
					$date_end = '';				
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE()";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) = CURDATE()";
					$date_end = '';		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) = CURDATE()";
					$date_end = '';						
				} else {
					$date_start = "DATE(o.date_added) = CURDATE()";
					$date_end = '';	
				}
				$type = '';				
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) = CURDATE()))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE())) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) = CURDATE()))";
				} else {
					$type = '';
				}					
				break;
			case 'yesterday';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(o.date_added) < CURDATE()";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(sc.date_added) < CURDATE()";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(cw.date_added) < CURDATE()";						
				} else {
					$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
					$date_end = " AND DATE(o.date_added) < CURDATE()";	
				}
				$type = '';				
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_ADD(CURDATE(), INTERVAL -1 DAY))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)) AND (DATE(o.date_added) < CURDATE()))";					
				} else {
					$type = '';
				}					
				break;					
			case 'week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";						
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";		
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'))";
				} else {
					$type = '';
				}					
				break;
			case 'month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";							
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'))";
				} else {
					$type = '';
				}					
				break;			
			case 'quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'))";						
				} else {
					$type = '';
				}					
				break;
			case 'year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "')) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'))";					
				} else {
					$type = '';
				}					
				break;
			case 'current_week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE())";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";							
				} else {
					$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE())))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - WEEKDAY(CURDATE()))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())))";
				} else {
					$type = '';
				}				
				break;	
			case 'current_month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";		
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "YEAR(sc.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(sc.date_added) = MONTH(CURDATE())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "YEAR(cw.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(cw.date_added) = MONTH(CURDATE())";	
				} else {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";				
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - DAYOFMONTH(CURDATE()) + 1)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - DAYOFMONTH(CURDATE()) + 1))";
				} else {
					$type = '';
				}					
				break;
			case 'current_quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "QUARTER(sc.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(sc.date_added) = YEAR(CURDATE())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "QUARTER(cw.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(cw.date_added) = YEAR(CURDATE())";
				} else {
					$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
					$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";				
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER))";					
				} else {
					$type = '';
				}
				break;					
			case 'current_year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - YEAR(CURDATE())";
					$date_end = '';					
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "YEAR(sc.date_added) = YEAR(CURDATE())";
					$date_end = '';	
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "YEAR(cw.date_added) = YEAR(CURDATE())";
					$date_end = '';		
				} else {
					$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
					$date_end = '';					
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - YEAR(CURDATE())))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - YEAR(CURDATE()))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - YEAR(CURDATE())))";
				} else {
					$type = '';
				}					
				break;					
			case 'last_week';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(sc.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(cw.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				} else {
					$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
					$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY) AND (DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY))";
				} else {
					$type = '';
				}				
				break;	
			case 'last_month';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(sc.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(cw.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				} else {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01'))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')) AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')))";
				} else {
					$type = '';
				}					
				break;
			case 'last_quarter';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "QUARTER(sc.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "QUARTER(cw.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = " AND YEAR(cw.date_added) = YEAR(CURDATE())";
				} else {
					$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
					$date_end = '';
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY)) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= LAST_DAY(CURRENT_DATE - INTERVAL 2 QUARTER) + INTERVAL 1 DAY) AND (DATE(o.date_added) < LAST_DAY(CURRENT_DATE - INTERVAL 1 QUARTER) + INTERVAL 1 DAY))";					
				} else {
					$type = '';
				}					
				break;					
			case 'last_year';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = '';
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(sc.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(cw.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				} else {
					$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
					$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";
				}
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'new_customers') {
					$type = " AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')))";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {					
					$type = " AND o.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "') AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01'))) AND o.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.customer_id > 0 AND (DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')) AND (DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')))";
				} else {
					$type = '';
				}					
				break;					
			case 'all_time';
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders')) {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'old_customers') {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
					$date_start = "DATE(sc.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(sc.date_added) <= DATE (NOW())";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
					$date_start = "DATE(cw.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(cw.date_added) <= DATE (NOW())";
				} else {
					$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
					$date_end = " AND DATE(o.date_added) <= DATE (NOW())";
				}	
				$type = '';
				if (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_with_orders') {
					$type = " AND o.customer_id > 0";
				} elseif (isset($data['filter_report']) && $data['filter_report'] == 'guest_customers') {
					$type = " AND o.customer_id = 0";
				} else {
					$type = '';
				}					
				break;	
		}
		
		if (isset($data['filter_details']) && $data['filter_details'] != 'all_details_products' && $data['filter_details'] != 'all_details_orders') {

		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'no_group'; //show No Grouping in Group By default
		}
		
		if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
		
			$sql = "SELECT COUNT(DISTINCT c.customer_id) AS total FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND (c.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND " . $date_start . $date_end . ") OR c.customer_id IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND " . $date_start . $date_end . "))";			

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'registered_customers_without_orders') {
		
			$sql = "SELECT COUNT(DISTINCT c.customer_id) AS total FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.customer_id NOT IN (SELECT o.customer_id FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND " . $date_start . $date_end . ")";

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_shopping_carts') {
			
			$sql = "SELECT COUNT(DISTINCT sc.customer_id) AS total FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "cart sc, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = sc.customer_id AND c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		} elseif (isset($data['filter_report']) && $data['filter_report'] == 'customers_wishlists') {
			
			$sql = "SELECT COUNT(DISTINCT cw.customer_id) AS total FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "customer_wishlist cw, " . DB_PREFIX . "address adr, " . DB_PREFIX . "customer_group_description cgd WHERE c.customer_id = cw.customer_id AND c.customer_id = adr.customer_id AND c.address_id = adr.address_id AND c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
		} elseif (isset($data['filter_report']) && ($data['filter_report'] == 'registered_customers_with_orders' or $data['filter_report'] == 'new_customers' or $data['filter_report'] == 'old_customers')) {
		
		switch($group) {
			case 'no_group';
				$sql = "SELECT COUNT(DISTINCT o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'order';
				$sql = "SELECT COUNT(DISTINCT o.order_id, o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'day';	
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;			
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'quarter':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), QUARTER(o.date_added), o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), o.customer_id) AS total FROM `" . DB_PREFIX . "order` o";
				break;	
		}
		
		} else {
		
		switch($group) {
			case 'no_group';
				$sql = "SELECT COUNT(DISTINCT o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'order';
				$sql = "SELECT COUNT(DISTINCT o.order_id, o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'day';	
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;			
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'quarter':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), QUARTER(o.date_added), o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;				
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), o.email) AS total FROM `" . DB_PREFIX . "order` o";
				break;			
		}
		
		}
		
		} else {

		if (isset($data['filter_details']) && $data['filter_details'] == 'all_details_products') {
			$sql = "SELECT op.order_product_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
		} elseif (isset($data['filter_details']) && $data['filter_details'] == 'all_details_orders') {
			$sql = "SELECT o.order_id AS counter FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
		}
		
		}
		
		if (isset($data['filter_report']) && $data['filter_report'] != 'all_registered_customers_with_without_orders' && $data['filter_report'] != 'registered_customers_without_orders') {
		if (isset($data['filter_report']) && ($data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= ' AND (' . $date_start . $date_end . ')';
			$sql .= $type;
		} else {	
			$sql .= ' WHERE (' . $date_start . $date_end . ')';
			$sql .= $type;
		}
		}

		if (isset($data['filter_report']) && $data['filter_report'] != 'registered_customers_without_orders' && $data['filter_report'] != 'customers_shopping_carts' && $data['filter_report'] != 'customers_wishlists') {
		if (isset($data['filter_report']) && $data['filter_report'] != 'customers_abandoned_orders') {
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
				if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= " AND (SELECT o.order_id FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id AND o.order_status_id > 0 AND (";
				} else {
				$sql .= " AND (";
				}
				$implode = array();
				foreach ($data['filter_order_status_id'] as $order_status_id) {
					$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
				}
				if ($implode) {
					$sql .= implode(" OR ", $implode) . "";
				}
				if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
				$sql .= ") GROUP BY o.customer_id)";
				} else {
				$sql .= ")";
				}				
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

			if (isset($data['filter_report']) && $data['filter_report'] == 'all_registered_customers_with_without_orders') {
			$sql .= '';
			} else {
			$sql .= ' AND o.order_status_id > 0';
			}
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
		}
				
		if (!empty($data['filter_store_id'])) {
			$sql .= " AND (";
			$implode = array();
			foreach ($data['filter_store_id'] as $store_id) {
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$implode[] = "c.store_id = '" . (int)$store_id . "'";	
				} else {
				$implode[] = "o.store_id = '" . (int)$store_id . "'";
				}
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
			$sql .= " AND (SELECT DISTINCT ot.order_id FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND (";
			$implode = array();
			foreach ($data['filter_taxes'] as $taxes) {
				$implode[] = "LCASE(ot.title) = '" . $taxes . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= "))";
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
				if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
				$implode[] = "c.customer_group_id = '" . (int)$customer_group_id . "'";	
				} else {
				$implode[] = "(SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$customer_group_id . "') OR (o.customer_group_id = '" . (int)$customer_group_id . "' AND o.customer_id = 0)";
				}
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}

		if (!empty($data['filter_customer_status'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND (";
			} else {
			$sql .= " AND (SELECT DISTINCT c.customer_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND (";
			}
			$implode = array();
			foreach ($data['filter_customer_status'] as $customer_status) {
				$implode[] = "c.status = '" . (int)$customer_status . "'";
			}
			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= ")";
			} else {
			$sql .= "))";
			}
		}
		
		if (!empty($data['filter_customer_name'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
			} else {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
			}
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_customer_email'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(c.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";
			} else {
			$sql .= " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";
			}		
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_customer_telephone'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(c.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";
			} else {
			$sql .= " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";
			}	
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_ip'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
        	$sql .= " AND LCASE(c.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
			} else {
        	$sql .= " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
			}	
		} else {
			$sql .= '';
		}
		
		if (!empty($data['filter_payment_company'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(adr.company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
			} else {
        	$sql .= " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
			}	
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_address'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(CONCAT(adr.address_1, ', ', adr.address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
			} else {
        	$sql .= " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
			}	
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_city'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(adr.city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
			} else {
        	$sql .= " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
			}
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_zone'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND (SELECT LCASE(z.name) FROM `" . DB_PREFIX . "zone` z WHERE adr.zone_id = z.zone_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
			} else {
        	$sql .= " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
			}
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_postcode'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND LCASE(adr.postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";	
			} else {
        	$sql .= " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";
			}		
		} else {
			$sql .= '';
		}

		if (!empty($data['filter_payment_country'])) {
			if (isset($data['filter_report']) && ($data['filter_report'] == 'all_registered_customers_with_without_orders' or $data['filter_report'] == 'registered_customers_without_orders' or $data['filter_report'] == 'customers_shopping_carts' or $data['filter_report'] == 'customers_wishlists')) {
			$sql .= " AND (SELECT LCASE(cnt.name) FROM `" . DB_PREFIX . "country` cnt WHERE adr.country_id = cnt.country_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";	
			} else {
        	$sql .= " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";
			}			
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

	public function getOrderCustomerStatuses($data = array()) {
		$query = $this->db->query("SELECT DISTINCT c.status FROM `" . DB_PREFIX . "customer` c");

		return $query->rows;
	}
	
	public function getOrderPaymentMethods($data = array()) {
		if (isset($data['filter_report']) && $data['filter_report'] != 'customers_abandoned_orders') {
		$query = $this->db->query("SELECT DISTINCT o.payment_method, o.payment_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id > 0 AND o.payment_code IS NOT NULL AND o.payment_code != '' GROUP BY o.payment_code ORDER BY LCASE(o.payment_method) ASC");
		} else {
		$query = $this->db->query("SELECT DISTINCT o.payment_method, o.payment_code FROM `" . DB_PREFIX . "order` o WHERE o.order_status_id = 0 AND o.payment_code IS NOT NULL AND o.payment_code != '' GROUP BY o.payment_code ORDER BY LCASE(o.payment_method) ASC");
		}	
		
		return $query->rows;	
	}
	
	public function getOrderShippingMethods($data = array()) {
		if (isset($data['filter_report']) && $data['filter_report'] != 'customers_abandoned_orders') {
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
		if (isset($data['filter_report']) && $data['filter_report'] != 'customers_abandoned_orders') {
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

	public function getCustomerAutocompleteAllRegistered($data = array()) {
		$sql = "SELECT DISTINCT c.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS cust_name, c.email AS cust_email, c.telephone AS cust_telephone, c.ip AS cust_ip, adr.company AS payment_company, CONCAT(adr.address_1, ', ', adr.address_2) AS payment_address, adr.city AS payment_city, (SELECT z.name FROM `" . DB_PREFIX . "zone` z WHERE adr.zone_id = z.zone_id) AS payment_zone, adr.postcode AS payment_postcode, (SELECT cnt.name FROM `" . DB_PREFIX . "country` cnt WHERE adr.country_id = cnt.country_id) AS payment_country, '' AS shipping_company, '' AS shipping_address, '' AS shipping_city, '' AS shipping_zone, '' AS shipping_postcode, '' AS shipping_country FROM " . DB_PREFIX . "customer c, " . DB_PREFIX . "address adr WHERE c.address_id = adr.address_id";
		
		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_customer_email'])) {
			$sql .= " AND LCASE(c.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_customer_telephone'])) {
			$sql .= " AND LCASE(c.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_ip'])) {
        	$sql .= " AND LCASE(c.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		}
		
		if (!empty($data['filter_payment_company'])) {
			$sql .= " AND LCASE(adr.company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_address'])) {
			$sql .= " AND LCASE(CONCAT(adr.address_1, ', ', adr.address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_city'])) {
			$sql .= " AND LCASE(adr.city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_zone'])) {
			$sql .= " AND (SELECT LCASE(z.name) FROM `" . DB_PREFIX . "zone` z WHERE adr.zone_id = z.zone_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		}

		if (!empty($data['filter_payment_postcode'])) {
			$sql .= " AND LCASE(adr.postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		}

		if (!empty($data['filter_payment_country'])) {
			$sql .= " AND (SELECT LCASE(cnt.name) FROM `" . DB_PREFIX . "country` cnt WHERE adr.country_id = cnt.country_id) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
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