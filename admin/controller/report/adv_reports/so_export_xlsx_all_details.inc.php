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
	
	$this->objPHPExcel = new PHPExcel();
	$this->objPHPExcel->getActiveSheet()->setTitle('ADV Sales Report');
	$this->objPHPExcel->getProperties()->setCreator("ADV Reports & Statistics")
								 	   ->setLastModifiedBy("ADV Reports & Statistics")
									   ->setTitle("ADV Sales Report")
									   ->setSubject("ADV Sales Report")
									   ->setDescription("ADV Sales Report with All Details")
									   ->setKeywords("office 2007 excel")
									   ->setCategory("www.opencartreports.com");	
	$this->objPHPExcel->setActiveSheetIndex(0);
	$export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1;
	if ($export_logo_criteria ? $this->mainCounter = 2 : $this->mainCounter = 1) {
		 $j = 0;

		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_order_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);

		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_date_added'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	

		 if (in_array('all_order_inv_no', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_inv_no'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_customer_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_order_email', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_email'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_order_customer_group', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_customer_group'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if ($filter_details == 'all_details_products') {
		 if (in_array('all_prod_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_sku', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);		 
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_sku'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_model', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_model'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		
		 }
		 
		 if (in_array('all_prod_option', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_option'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 
		 $n = 0;
		 if ($option_names) {
		 foreach ($option_names as $option_name) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $option_name['name']);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 $n++;
		 }		 
		 }
		 }

		 if (in_array('all_prod_attributes', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_attributes'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_category', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_category'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 
		 if (in_array('all_prod_manu', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_manu'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_currency', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_currency'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_price', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_price'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_quantity', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_quantity'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_total_excl_vat', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_excl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_tax', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_total_incl_vat', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_total_incl_vat'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_qty_refund', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_qty_refunded'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_refund', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_refunded'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_prod_reward_points', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_prod_reward_points'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }
		 }

		 if (in_array('all_sub_total', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_sub_total'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_handling', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_handling'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_loworder', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_loworder'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_shipping'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_reward', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_reward'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_reward_points', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_earned_reward_points'));

		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_reward_points', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_used_reward_points'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_coupon', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_coupon'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_coupon_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_coupon_name'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_coupon_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_coupon_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 
		 if (in_array('all_order_tax', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_tax'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 $t = 0;
		 if ($tax_names) {
		 foreach ($tax_names as $tax_name) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $tax_name['title']);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 $t++;
		 }		 
		 }			 
		 }

		 if (in_array('all_credit', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_credit'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_voucher', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_voucher'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_voucher_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_voucher_code'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_commission', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_commission'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 
		 if (in_array('all_order_value', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_value'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_refund', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_refund'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_shipping_method', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_shipping_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_payment_method', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_payment_method'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_status', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_status'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_store', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_store'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_customer_cust_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_cust_id'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);	
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_custom_fields', $advso_settings_all_columns)) {
		 if ($custom_fields_name) {
 		 foreach ($custom_fields_name as $custom_field_name) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $custom_field_name['name']);
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }
		 }
		 }
		
		 if (in_array('all_billing_first_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_first_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_last_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_last_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_company', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_address_1', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_address_2', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_city', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_zone', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_zone_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_zone_id')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_zone_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_zone_code')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_postcode', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_country', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_country_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_country_id')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_billing_country_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_billing_country_code')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_customer_telephone', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_customer_telephone'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_first_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_first_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_last_name', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_last_name')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_company', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_company')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_address_1', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_1')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_address_2', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_address_2')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_city', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_city')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_zone', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_zone_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone_id')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_shipping_zone_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_zone_code')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_shipping_postcode', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_postcode')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_country', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_country')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_shipping_country_id', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_country_id')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_shipping_country_code', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, strip_tags($this->language->get('column_shipping_country_code')));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		 }

		 if (in_array('all_order_weight', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_weight'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		 
		 $this->objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);	
		 }

		 if (in_array('all_order_comment', $advso_settings_all_columns)) {
		 $col = PHPExcel_Cell::stringFromColumnIndex($j++);
		 $this->objPHPExcel->getActiveSheet()->setCellValue($col . $this->mainCounter, $this->language->get('column_order_comment'));
		 $this->objPHPExcel->getActiveSheet()->getStyle($col . $this->mainCounter)->getFont()->setBold(true);		 
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
			$this->objPHPExcel->getActiveSheet()->setCellValue('C1', $this->language->get('text_report_date').": ".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d H:i:s" : "Y-m-d h:i:s A"));
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
		 
		$freeze = ($export_logo_criteria ? 'C6' : 'C2');
		$this->objPHPExcel->getActiveSheet()->freezePane($freeze);
	}
	
	$counter = ($export_logo_criteria ? $this->mainCounter+4 : $this->mainCounter+1);
		
	foreach ($results as $result) {
		$j = 0;

		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_id']);

		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['date_added']);

		if (in_array('all_order_inv_no', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['invoice']);
		}

		if (in_array('all_order_customer_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['name']);
		}

		if (in_array('all_order_email', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['email']);
		}

		if (in_array('all_order_customer_group', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['cust_group'], ENT_COMPAT, 'UTF-8'));
		}

		if ($filter_details == 'all_details_products') {
		if (in_array('all_prod_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_id']);
		}

		if (in_array('all_prod_sku', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_sku']);
		}

		if (in_array('all_prod_model', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_model']);
		}

		if (in_array('all_prod_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['product_name'], ENT_COMPAT, 'UTF-8'));
		}

		if (in_array('all_prod_option', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['product_options'], ENT_COMPAT, 'UTF-8'));
		
		$o = $j;	
		if ($result['product_option']) {
		foreach ($result['product_option'] as $index => $product_option) {
		if ($product_option['name'] == $option_names[$index]['name']) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $product_option['value']);
		//$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $product_option['name'].': '.$product_option['value']);			
		} else {
		foreach ($result['product_option'] as $product_option) {
		foreach ($option_names as $option_name) {			
		if ($product_option['name'] == $option_name['name']) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $product_option['value']);
		//$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $product_option['name'].': '.$product_option['value']);	
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');				
		}			
		}
		}			
		}
		}	
		} else {
		foreach ($option_names as $option_name) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		$j=$o+$n;
		}

		if (in_array('all_prod_attributes', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['product_attributes'], ENT_COMPAT, 'UTF-8'));
		}

		if (in_array('all_prod_category', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['product_category'], ENT_COMPAT, 'UTF-8'));		
		}
		
		if (in_array('all_prod_manu', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['product_manu'], ENT_COMPAT, 'UTF-8'));
		}

		if (in_array('all_prod_currency', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['currency_code']);
		}

		if (in_array('all_prod_price', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_price_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_quantity', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_quantity']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_total_excl_vat', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_total_excl_vat_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_tax', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_tax_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_total_incl_vat', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_total_incl_vat_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_qty_refund', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_qty_refund']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_prod_refund', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_refund_raw'] != NULL ? $result['product_refund_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}
		}

		if (in_array('all_prod_reward_points', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['product_reward_points']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		}

		if (in_array('all_sub_total', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_sub_total_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_handling', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_handling_raw'] != NULL ? $result['order_handling_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_loworder', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_low_order_fee_raw'] != NULL ? $result['order_low_order_fee_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_shipping', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_shipping_raw'] != NULL ? $result['order_shipping_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_reward', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_reward_raw'] != NULL ? $result['order_reward_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_reward_points', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_earned_points']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_reward_points', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_used_points']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_coupon', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_coupon_raw'] != NULL ? $result['order_coupon_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_coupon_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_coupon_name']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_coupon_code', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_coupon_code']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}
		
		if (in_array('all_order_tax', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_tax_raw'] != NULL ? $result['order_tax_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		$x = $j;	
		if ($result['order_taxes']) {
		foreach ($result['order_taxes'] as $index => $order_taxes) {
		if ($order_taxes['title'] == $tax_names[$index]['title']) {	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $order_taxes['value'] != NULL ? $order_taxes['value'] : '0.00');	
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		} else {
		foreach ($result['order_taxes'] as $order_taxes) {
		foreach ($tax_names as $tax_name) {		
		if ($order_taxes['title'] == $tax_name['title']) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $order_taxes['value'] != NULL ? $order_taxes['value'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		} else {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}			
		}
		}			
		}
		}	
		} else {
		foreach ($tax_names as $tax_name) {	
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, '');
		}
		}
		$j=$x+$t;	
		}

		if (in_array('all_credit', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_credit_raw'] != NULL ? $result['order_credit_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_voucher', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_voucher_raw'] != NULL ? $result['order_voucher_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_voucher_code', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_voucher_code']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_order_commission', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, -$result['order_commission_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_order_value', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_value_raw']);
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}			
		}

		if (in_array('all_refund', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode('0.00');
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_refund_raw'] != NULL ? $result['order_refund_raw'] : '0.00');
		if ($filter_report == 'abandoned_orders') {
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getFont()->applyFromArray(array('strike' => true));
		}
		}

		if (in_array('all_order_shipping_method', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_method']);
		}

		if (in_array('all_order_payment_method', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_method']);
		}

		if (in_array('all_order_status', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_status']);
		}

		if (in_array('all_order_store', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['store_name'], ENT_COMPAT, 'UTF-8'));
		}

		if (in_array('all_customer_cust_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['customer_id']);
		}

		if (in_array('all_custom_fields', $advso_settings_all_columns)) {
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

		if (in_array('all_billing_first_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_firstname']);
		}

		if (in_array('all_billing_last_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_lastname']);
		}

		if (in_array('all_billing_company', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_company']);
		}

		if (in_array('all_billing_address_1', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_address_1']);
		}

		if (in_array('all_billing_address_2', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_address_2']);
		}

		if (in_array('all_billing_city', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_city']);
		}

		if (in_array('all_billing_zone', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_zone']);
		}

		if (in_array('all_billing_zone_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_zone_id']);
		}

		if (in_array('all_billing_zone_code', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_zone_code']);
		}

		if (in_array('all_billing_postcode', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_postcode']);
		}

		if (in_array('all_billing_country', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_country']);
		}

		if (in_array('all_billing_country_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_country_id']);
		}

		if (in_array('all_billing_country_code', $advso_settings_all_columns)) {		
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['payment_country_code']);
		}

		if (in_array('all_customer_telephone', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);		
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['telephone']);
		}

		if (in_array('all_shipping_first_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_firstname']);
		}

		if (in_array('all_shipping_last_name', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_lastname']);
		}

		if (in_array('all_shipping_company', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_company']);
		}

		if (in_array('all_shipping_address_1', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_address_1']);
		}

		if (in_array('all_shipping_address_2', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_address_2']);
		}

		if (in_array('all_shipping_city', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_city']);
		}

		if (in_array('all_shipping_zone', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_zone']);
		}

		if (in_array('all_shipping_zone_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_zone_id']);
		}

		if (in_array('all_shipping_zone_code', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_zone_code']);
		}

		if (in_array('all_shipping_postcode', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_postcode']);
		}

		if (in_array('all_shipping_country', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_country']);
		}

		if (in_array('all_shipping_country_id', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_country_id']);
		}

		if (in_array('all_shipping_country_code', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['shipping_country_code']);
		}

		if (in_array('all_order_weight', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->getStyle($col . $counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, $result['order_weight']);
		}

		if (in_array('all_order_comment', $advso_settings_all_columns)) {
		$col = PHPExcel_Cell::stringFromColumnIndex($j++);
		$this->objPHPExcel->getActiveSheet()->setCellValue($col . $counter, html_entity_decode($result['order_comment'], ENT_COMPAT, 'UTF-8'));
		}
		
		$counter++;
		$this->mainCounter++;
	}
	
	if (!isset($_GET['cron'])) {
		$filename = "sales_report_all_details_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A");
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
		
		$filename = $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file_to_download = $server . $file_save_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";
		$file = $file_path . '/' . $file_name."_".date($this->config->get('advso' . $user_id . '_hour_format') == '24' ? "Y-m-d_H-i-s" : "Y-m-d_h-i-s-A").".xlsx";		
		
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