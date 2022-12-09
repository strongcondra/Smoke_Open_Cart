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
	
	$this->objPHPExcel = new PHPExcel();
	$this->objPHPExcel->getActiveSheet()->setTitle('ADV Customers Report');
	$this->objPHPExcel->getProperties()->setCreator("ADV Reports & Statistics")
									   ->setLastModifiedBy("ADV Reports & Statistics")
									   ->setTitle("ADV Customers Report")
									   ->setSubject("ADV Customers Report")
									   ->setDescription("ADV Customers Report with Basic Details")
									   ->setKeywords("office 2007 excel")
									   ->setCategory("www.opencartreports.com");
	$this->objPHPExcel->setActiveSheetIndex(0);
	$export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1;
	if ($export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1) {
		 $j = 0;
		 
		 if ($filter_group == 'year') {	 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'quarter') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_quarter'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'month') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_year'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_month'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'day') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date'));
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } elseif ($filter_group == 'order') {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 } else {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date_start'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_date_end'));	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		 
		 }

		 if (in_array('mv_id', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, (($filter_report == 'registered_and_guest_customers' or $filter_report == 'guest_customers') ? $this->language->get('column_id_guest') : $this->language->get('column_id')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_customer', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer')." / ".$this->language->get('column_company'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_email', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_email'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_telephone', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_telephone'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_customer_group', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_group'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_custom_fields', $advco_settings_mv_columns)) {		 
		 if ($custom_fields_name) {
		 foreach ($custom_fields_name as $custom_field_name) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $custom_field_name['name']);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 }
		 }			 
		 if (in_array('mv_customer_status', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_first_name', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_first_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_last_name', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_last_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_company', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_company'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_address_1', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_address_1'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_address_2', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_address_2'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_city', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_city'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_postcode', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_postcode'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_country_id', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_country_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_country', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_country'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_country_code', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_country_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_zone_id', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_zone_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_region_state', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_region_state'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_region_state_code', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_region_state_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_newsletter', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_newsletter'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_approved', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_approved'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_safe', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_safe'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_ip', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_ip'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_total_logins', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total_logins'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_last_login', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_last_login'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_mostrecent', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_mostrecent'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_orders', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_orders'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('mv_products', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_products'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }		
		 if (in_array('mv_total', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_total'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('mv_aov', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_aov'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		
		 if (in_array('mv_refunds', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_refunds'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }		
		 if (in_array('mv_reward_points', $advco_settings_mv_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_reward_points'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 if (in_array('ol_order_order_id', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_date_added', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_inv_no', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_inv_no'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }	 
		 if (in_array('ol_order_customer', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_email', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_email'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_customer_group', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer_group'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_shipping_method', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_shipping_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_payment_method', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_payment_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_status', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_store', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_store'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_currency', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('ol_order_quantity', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_order_sub_total', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_sub_total'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_order_shipping', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_shipping'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_order_tax', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('ol_order_value', $advco_settings_ol_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_value'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 if (in_array('cl_customer_cust_id', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_cust_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }	
		 if (in_array('cl_billing_name', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_company', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_address_1', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_address_2', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_city', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_zone', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_postcode', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_billing_country', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_customer_telephone', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_telephone'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_name', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_company', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_address_1', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_address_2', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_city', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_zone', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_postcode', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('cl_shipping_country', $advco_settings_cl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
			 
		 if (in_array('pl_prod_order_id', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_date_added', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 } 
		 if (in_array('pl_prod_id', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_sku', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_sku'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_model', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_model'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_name', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_option', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_option'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_attributes', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_attributes'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }			 
		 if (in_array('pl_prod_category', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_manu', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }	 
		 if (in_array('pl_prod_currency', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 if (in_array('pl_prod_price', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_quantity', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_total_excl_vat', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_tax', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 if (in_array('pl_prod_total_incl_vat', $advco_settings_pl_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }	
		 
		 if ($export_logo_criteria) {
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$this->objPHPExcel->getActiveSheet()->insertNewRowBefore(1,1);
			$lastCell = $this->objPHPExcel->getActiveSheet()->getHighestDataColumn();
			$lastRow = $this->objPHPExcel->getActiveSheet()->getHighestDataRow();
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
			$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', $this->language->get('text_report_date').": ".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A"));
			$this->objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1:' . $lastCell . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C1:' . $lastCell . '1')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C1:' . $lastCell . '1');
				
			//Add logo to header
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName($this->config->get('config_name'));
			$objDrawing->setDescription($this->config->get('config_name'));
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('A2');
			$objDrawing->setWidth(155);
			$objDrawing->setOffsetX(5);
			$objDrawing->setOffsetY(20);
			$objDrawing->setResizeProportional(true);
			$objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
			$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C2', $this->language->get('adv_ext_name'));
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(24);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . $lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . '2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C2:' . $lastCell . '2')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C2:' . $lastCell . '2');
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
			$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setRGB('DBE5F1');	
			$this->objPHPExcel->getActiveSheet()->setCellValue('C3', $this->config->get('config_name').", ".$this->config->get('config_address').", ".$this->language->get('text_email')."".$this->config->get('config_email').", ".$this->language->get('text_telephone')."".$this->config->get('config_telephone'));
			$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(14);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . $lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . '3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C3:' . $lastCell . '3')->getFill()->getStartColor()->setRGB('DBE5F1');
			$this->objPHPExcel->getActiveSheet()->mergeCells('C3:' . $lastCell . '3');
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
			$this->objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
			$this->objPHPExcel->getActiveSheet()->setCellValue('A4', $this->language->get('text_report_criteria'));
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->getStartColor()->setRGB('DBE5F1');				
			$this->objPHPExcel->getActiveSheet()->mergeCells('C4:' . $lastCell . '4');
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
			$filter_criteria .= "\r";
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
			$filter_criteria .= "\r";
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
			$this->objPHPExcel->getActiveSheet()->setCellValue('C4', $filter_criteria);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4:' . $lastCell . '4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$this->objPHPExcel->getActiveSheet()->getStyle('C4:' . $lastCell . '4')->getFill()->getStartColor()->setRGB('DBE5F1');				
		 }	
		 
		$freeze = ($export_logo_criteria ? 'A6' : 'A2');
		$this->objPHPExcel->getActiveSheet()->freezePane($freeze);
	}
	
	$counter = ($export_logo_criteria ? $this->mainCounter+4 : $this->mainCounter+1);
		
	foreach ($results as $result) {	
		$j = 0;
				
		if ($filter_group == 'year') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		} elseif ($filter_group == 'quarter') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['quarter']);			
		} elseif ($filter_group == 'month') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['year']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['month']);
		} elseif ($filter_group == 'day') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		} elseif ($filter_group == 'order') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_id']);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_start']);
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_end']);				 
		}
		
		if (in_array('mv_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, ($result['customer_id'] > 0 ? $result['customer_id'] : $this->language->get('text_guest')));
		}
		if (in_array('mv_customer', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, ($result['cust_company'] ? $result['cust_name']." / ".$result['cust_company'] : $result['cust_name']));
		}	
		if (in_array('mv_email', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_email']);
		}	
		if (in_array('mv_telephone', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_telephone']);
		}	
		if (in_array('mv_customer_group', $advco_settings_mv_columns)) {				
		if ($filter_report == 'all_registered_customers_with_without_orders') {			
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_group']);
		} else {
		if ($result['customer_id'] == 0) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_group_guest']);
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_group_reg']);
		}
		}				
		}
		if (in_array('mv_custom_fields', $advco_settings_mv_columns)) {			
		if ($result['custom_fields']) {
		foreach ($result['custom_fields'] as $custom_field) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $custom_field['value']);
		}
		} else {
		foreach ($custom_fields_name as $custom_field_name) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		}
		if (in_array('mv_customer_status', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_status']);
		}	
		if (in_array('mv_first_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_first_name']);
		}	
		if (in_array('mv_last_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_last_name']);
		}			
		if (in_array('mv_company', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_company']);
		}	
		if (in_array('mv_address_1', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_address_1']);
		}
		if (in_array('mv_address_2', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_address_2']);
		}
		if (in_array('mv_city', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_city']);
		}
		if (in_array('mv_postcode', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_postcode']);
		}
		if (in_array('mv_country_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_country_id']);
		}
		if (in_array('mv_country', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_country']);
		}	
		if (in_array('mv_country_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_country_code']);
		}	
		if (in_array('mv_zone_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_zone_id']);
		}	
		if (in_array('mv_region_state', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_region_state']);
		}	
		if (in_array('mv_region_state_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['cust_region_state_code']);
		}	
		if (in_array('mv_newsletter', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['newsletter']);
		}	
		if (in_array('mv_approved', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['approved']);
		}	
		if (in_array('mv_safe', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['safe']);
		}	
		if (in_array('mv_ip', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['ip']);
		}			
		if (in_array('mv_total_logins', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_logins']);
		}
		if (in_array('mv_last_login', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['last_login']);
		}
		if (in_array('mv_mostrecent', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['mostrecent']);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}
		if (in_array('mv_orders', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['orders']);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		if (in_array('mv_products', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['products']);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		if (in_array('mv_total', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['total_raw'] != NULL ? $result['total_raw'] : '0.00');
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		if (in_array('mv_aov', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['aov_raw'] != NULL ? $result['aov_raw'] : '0.00');
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		if (in_array('mv_refunds', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['refunds_raw'] != NULL ? $result['refunds_raw'] : '0.00');
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}
		}
		if (in_array('mv_reward_points', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['reward_points']);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		}

		$dcounter = $counter;	
		if (in_array('ol_order_order_id', $advco_settings_ol_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['order_ord_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_date_added', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_ord_date']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_inv_no', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_inv_no']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_customer', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_email', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_email']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_customer_group', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_group']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_shipping_method', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_shipping_method']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_payment_method', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_payment_method']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_status', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_status']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_store', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_store']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('ol_order_currency', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_currency']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('ol_order_quantity', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_products']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_sub_total', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_sub_total']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_shipping', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_shipping']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_tax', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_tax']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('ol_order_value', $advco_settings_ol_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['order_value']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		
		if (in_array('cl_customer_cust_id', $advco_settings_cl_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['customer_cust_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('cl_billing_name', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_company', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_company']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_address_1', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_address_1']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_address_2', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_address_2']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_city', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_city']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_zone', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_zone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_postcode', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_postcode']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_billing_country', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['billing_country']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_customer_telephone', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['customer_telephone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_name', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_company', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_company']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_address_1', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_address_1']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_address_2', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_address_2']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_city', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_city']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_zone', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_zone']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_postcode', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_postcode']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('cl_shipping_country', $advco_settings_cl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['shipping_country']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}

		$j = 0;
		if ($filter_group == 'year') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'quarter') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');		
		} elseif ($filter_group == 'month') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'day') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'order') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');		 
		}
		
		if (in_array('mv_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_customer', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_email', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_telephone', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_customer_group', $advco_settings_mv_columns)) {				
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');				
		}
		if (in_array('mv_custom_fields', $advco_settings_mv_columns)) {			
		if ($result['custom_fields']) {
		foreach ($result['custom_fields'] as $custom_field) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		} else {
		foreach ($custom_fields_name as $custom_field_name) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		}
		if (in_array('mv_customer_status', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_first_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_last_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('mv_company', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_address_1', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_address_2', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_city', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_postcode', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_country_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_country', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_country_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_zone_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_region_state', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_region_state_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_newsletter', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_approved', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_safe', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_ip', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('mv_total_logins', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_last_login', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_mostrecent', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_orders', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_products', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_total', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');	
		}
		if (in_array('mv_aov', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_refunds', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_reward_points', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}

		if (in_array('ol_order_order_id', $advco_settings_ol_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_date_added', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_inv_no', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_customer', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_email', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_customer_group', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_shipping_method', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_payment_method', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_status', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_store', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_currency', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('ol_order_quantity', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_sub_total', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_shipping', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_tax', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_value', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		
		if (in_array('cl_customer_cust_id', $advco_settings_cl_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('cl_billing_name', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_company', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_address_1', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_address_2', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_city', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_zone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_postcode', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_country', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_customer_telephone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_name', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_company', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_address_1', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_address_2', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_city', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_zone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_postcode', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_country', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
				
		if (in_array('pl_prod_order_id', $advco_settings_pl_columns)) {		
		$counter = $dcounter;		
		$details = explode('<br>', $result['product_ord_id']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_date_added', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_ord_date']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_id', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_pid']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_sku', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_sku']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_model', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_model']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_name', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_name']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_option', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_option']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, str_replace('&nbsp;','',$value));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_attributes', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_attributes']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}	
		if (in_array('pl_prod_category', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_category']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}		
		if (in_array('pl_prod_manu', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_manu']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode(str_replace('&nbsp;','',$value)));
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}		
		if (in_array('pl_prod_currency', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_currency']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}			
		if (in_array('pl_prod_price', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_price']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_quantity', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_quantity']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_total_excl_vat', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_total_excl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_tax', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_tax']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}
		if (in_array('pl_prod_total_incl_vat', $advco_settings_pl_columns)) {
		$counter = $dcounter;			
		$details = explode('<br>', $result['product_total_incl_vat']);	
		foreach ($details as $key => $value) {
		$c = $j;
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $value);
		if ($filter_report == 'customers_abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}		
		$j = $c;
		$counter += 1;		
		}
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		}

		$j = 0;
		if ($filter_group == 'year') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'quarter') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');		
		} elseif ($filter_group == 'month') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'day') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} elseif ($filter_group == 'order') {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');		 
		}
		
		if (in_array('mv_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_customer', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_email', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_telephone', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_customer_group', $advco_settings_mv_columns)) {				
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');				
		}
		if (in_array('mv_custom_fields', $advco_settings_mv_columns)) {			
		if ($result['custom_fields']) {
		foreach ($result['custom_fields'] as $custom_field) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		} else {
		foreach ($custom_fields_name as $custom_field_name) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		}
		if (in_array('mv_customer_status', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_first_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_last_name', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('mv_company', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_address_1', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_address_2', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_city', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_postcode', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_country_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_country', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_country_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_zone_id', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_region_state', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_region_state_code', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_newsletter', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_approved', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_safe', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('mv_ip', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('mv_total_logins', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_last_login', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_mostrecent', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_orders', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_products', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_total', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');	
		}
		if (in_array('mv_aov', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_refunds', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('mv_reward_points', $advco_settings_mv_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}

		if (in_array('ol_order_order_id', $advco_settings_ol_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_date_added', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_inv_no', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_customer', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_email', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_customer_group', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_shipping_method', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_payment_method', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_status', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_store', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('ol_order_currency', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		if (in_array('ol_order_quantity', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_sub_total', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_shipping', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_tax', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('ol_order_value', $advco_settings_ol_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		
		if (in_array('cl_customer_cust_id', $advco_settings_cl_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}	
		if (in_array('cl_billing_name', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_company', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_address_1', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_address_2', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_city', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_zone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_postcode', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_billing_country', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_customer_telephone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_name', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_company', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_address_1', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_address_2', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_city', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_zone', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_postcode', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		if (in_array('cl_shipping_country', $advco_settings_cl_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);				
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}

		$counter++;
		$this->mainCounter++;
	}
	
	if (!isset($_GET['cron'])) {
		$filename = "customers_report_basic_details_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
		header('Expires: 0');
		header('Cache-control: private');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8; encoding=UTF-8');
		header('Content-Disposition: attachment;filename='.$filename.".xlsx");
		header('Content-Transfer-Encoding: UTF-8');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');	
		$objWriter->save('php://output');	
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
		
		$filename = $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advco' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";		
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');	
		$objWriter->save($file);
		
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
	exit();
	}
?>