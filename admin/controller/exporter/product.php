<?php
set_time_limit(0);

ini_set('memory_limit', '999M');
ini_set('set_time_limit', '0');

class ControllerExporterProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('exporter/product');

		$this->load->model('exporter/product');
		
		$this->document->addStyle('view/stylesheet/modulepoints/export.css');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('exporter/product', 'token=' . $this->session->data['token'], true)
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_export'] = $this->language->get('button_export');
		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_qty'] = $this->language->get('entry_qty');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_format'] = $this->language->get('entry_format');
		$data['entry_review'] = $this->language->get('entry_review');
		
		
		$data['placeholder_quantity_start'] = $this->language->get('placeholder_quantity_start');
		$data['placeholder_quantity_limit'] = $this->language->get('placeholder_quantity_limit');
		$data['placeholder_price_start'] = $this->language->get('placeholder_price_start');
		$data['placeholder_price_limit'] = $this->language->get('placeholder_price_limit');
		$data['placeholder_product_start'] = $this->language->get('placeholder_product_start');
		$data['placeholder_product_limit'] = $this->language->get('placeholder_product_limit');
		
		$data['text_all_status'] = $this->language->get('text_all_status');
		$data['text_all_stock_status'] = $this->language->get('text_all_stock_status');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_xls'] = $this->language->get('text_xls');
		$data['text_xlsx'] = $this->language->get('text_xlsx');
		$data['text_csv'] = $this->language->get('text_csv');
		$data['text_xml'] = $this->language->get('text_xml');
		
		$data['help_product'] = $this->language->get('help_product');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_manufacturer'] = $this->language->get('help_manufacturer');
		$data['help_qty'] = $this->language->get('help_qty');
		$data['help_price'] = $this->language->get('help_price');
		$data['help_product_limit'] = $this->language->get('help_product_limit');
		
		$data['text_form'] = $this->language->get('text_form');
		$data['text_all_store'] = $this->language->get('text_all_store');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_extra'] = $this->language->get('tab_extra');
		$data['tab_images'] = $this->language->get('tab_images');
		$data['tab_review'] = $this->language->get('tab_review');
		$data['tab_support'] = $this->language->get('tab_support');
		
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_fields'] = $this->language->get('text_no_fields');
		$data['text_no_fields'] = $this->language->get('text_no_fields');
		
		$data['extrafields'] = $this->model_exporter_product->getExtraFields();
		$data['exporter_action'] = $this->url->link('exporter/product/export', 'token=' . $this->session->data['token'], true);
		
		$data['token'] = $this->session->data['token'];
		
		$this->load->model('setting/store');
		$this->load->model('localisation/language');
		$this->load->model('localisation/stock_status');
		
		$data['stores'] = $this->model_setting_store->getStores();
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('exporter/product.tpl', $data));
	}
	
	// Product Export Function
	public function export() {
		require_once(DIR_SYSTEM.'library/PHPExcel.php');
	
		$this->load->language('exporter/product');
		
		$this->load->model('exporter/product');
		$this->load->model('setting/store');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->setActiveSheetIndex(0);  
		
		if(isset($this->request->post['find_store_id']) && $this->request->post['find_store_id'] != '') {
			$find_store_id = $this->request->post['find_store_id'];
		}else{
			$find_store_id = null;
		}
		
		if(isset($this->request->post['find_language_id']) && $this->request->post['find_language_id'] != '') {
			$find_language_id = $this->request->post['find_language_id'];
		}else{
			$find_language_id = null;
		}
		
		if(isset($this->request->post['find_quantity_start']) && $this->request->post['find_quantity_start'] != '') {
			$find_quantity_start = $this->request->post['find_quantity_start'];
		}else{
			$find_quantity_start = '';
		}
		
		if(isset($this->request->post['find_quantity_limit']) && $this->request->post['find_quantity_limit'] != '') {
			$find_quantity_limit = $this->request->post['find_quantity_limit'];
		}else{
			$find_quantity_limit = '';
		}
		
		if(isset($this->request->post['find_price_start']) && $this->request->post['find_price_start'] != '') {
			$find_price_start = $this->request->post['find_price_start'];
		}else{
			$find_price_start = '';
		}
		
		if(isset($this->request->post['find_price_limit']) && $this->request->post['find_price_limit'] != '') {
			$find_price_limit = $this->request->post['find_price_limit'];
		}else{
			$find_price_limit = '';
		}
		
		if(isset($this->request->post['find_product_start']) && $this->request->post['find_product_start'] != '') {
			$find_product_start = $this->request->post['find_product_start'];
		}else{
			$find_product_start = '';
		}
		
		if(isset($this->request->post['find_product_limit']) && $this->request->post['find_product_limit'] != '') {
			$find_product_limit = $this->request->post['find_product_limit'];
		}else{
			$find_product_limit = '';
		}
		
		$find_image = 1;
		
		if(isset($this->request->post['find_status']) && $this->request->post['find_status'] != '') {
			$find_status = $this->request->post['find_status'];
		}else{
			$find_status = null;
		}
		
		if(isset($this->request->post['find_stock_status_id']) && $this->request->post['find_stock_status_id'] != '') {
			$find_stock_status_id = $this->request->post['find_stock_status_id'];
		}else{
			$find_stock_status_id = null;
		}
		
		if(isset($this->request->post['find_format']) && $this->request->post['find_format'] != '') {
			$find_format = $this->request->post['find_format'];
		}else{
			$find_format = null;
		}
		
		if(isset($this->request->post['find_model']) && $this->request->post['find_model'] != '') {
			$find_model = $this->request->post['find_model'];
		}else{
			$find_model = null;
		}
		
		if(isset($this->request->post['find_product']) && $this->request->post['find_product'] != '') {
			$find_product = $this->request->post['find_product'];
		}else{
			$find_product = null;
		}
		
		if(isset($this->request->post['find_manufacturer']) && $this->request->post['find_manufacturer'] != '') {
			$find_manufacturer = $this->request->post['find_manufacturer'];
		}else{
			$find_manufacturer = null;
		}
		
		if(isset($this->request->post['find_category']) && $this->request->post['find_category'] != '') {
			$find_category = $this->request->post['find_category'];
		}else{
			$find_category = null;
		}
		
		if(isset($this->request->post['find_extrafields']) && $this->request->post['find_extrafields'] != '') {
			$find_extrafields = $this->request->post['find_extrafields'];
		}else{
			$find_extrafields = null;
		}


		$find_extrafields = array();
		$db_extrafields = $this->model_exporter_product->getExtraFields();
		foreach ($db_extrafields as $db_extrafield) {
			if(!empty($db_extrafield['fields'])) {
				foreach ($db_extrafield['fields'] as $db_extrafield_column) {
					$find_extrafields[] = $db_extrafield['tablename'].'::'.$db_extrafield_column;
				}
			}
		}

		$find_review = 1;
		
		$filter_data = array(
			'find_store_id'						=> $find_store_id,
			'find_language_id'				=> $find_language_id,
			'find_model'							=> $find_model,
			'find_status'							=> $find_status,
			'find_quantity_start'			=> $find_quantity_start,
			'find_quantity_limit'			=> $find_quantity_limit,
			'find_price_start'				=> $find_price_start,
			'find_price_limit'				=> $find_price_limit,
			'find_product_start'			=> $find_product_start,
			'find_product_limit'			=> $find_product_limit,
			'find_stock_status_id'		=> $find_stock_status_id,
			'find_product'						=> $find_product,
			'find_manufacturer'				=> $find_manufacturer,
			'find_category'						=> $find_category,			
		);
				
		$i = 1;
		$char = 'A';
		
		$objPHPExcel->getActiveSheet()->getStyle('1')->getFill()->applyFromArray(array(
			'type'				=> PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' 	=> array(
			'rgb' 				=> '017FBE',
			),
		));
				
		$objPHPExcel->getActiveSheet()->getStyle('1')->applyFromArray(array(
			'font'  => array(
			'color' => array('rgb' => 'FFFFFF'),
			'bold'  => true,
			)
		));

		$objPHPExcel->getActiveSheet()->freezePane('D2');

		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_product_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_product_name'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_model'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_language'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_store'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_description'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_meta_title'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_meta_description'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_meta_keyword'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_tag'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_image'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_sku'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_upc'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_ean'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_jan'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_isbn'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char++ .$i, $this->language->get('export_mpn'));
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_location'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_price'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_min_quantity'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_quantity'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_status'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_sort_order'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_taxclass_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_tax_class'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_subtract'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_stock_status_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_stock_status'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_shipping'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_seo'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_date_avaiable'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_length'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_length_class_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_length_class'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_width'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_height'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_weight'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_weight_class_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_weight_class'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_manufacturer_id'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_manufacturer'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_categories'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_categories_name'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_filter'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_download'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_related_products'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_attribute'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_options'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_discount'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_special'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_images'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_points'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_reward'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_viewed'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_date_added'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $this->language->get('export_date_modified'))->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue($char++ .$i, $this->language->get('export_review'));
		
		
		
		if(!empty($find_extrafields)) {
			foreach($find_extrafields as $find_extrafield) {
				$find_extrafield = explode('::', $find_extrafield);
				if(isset($find_extrafield[0]) && isset($find_extrafield[1])) {
					$objPHPExcel->getActiveSheet()->setCellValue($char .$i, $find_extrafield[0].'::'.$find_extrafield[1])->getColumnDimension($char)->setAutoSize(true); $objPHPExcel->getActiveSheet()->getStyle($char++ .$i)->getAlignment()->setWrapText(true);
				}
			}
		}
		
		// Fetch Products
		$results = $this->model_exporter_product->getProducts($filter_data);
		if($results) {
			// Fetch Total Products
			$objPHPExcel->getActiveSheet()->setTitle(sprintf($this->language->get('export_title'), count($results)));
			
			foreach($results as $result) {
				$char_value = 'A'; $i++; 
				
				// Language
				$language_info = $this->model_exporter_product->getLanguage($result['language_id']);
				$result['language'] = ($language_info) ? $language_info['code']: '';
							
				// Store
				if (isset($find_store_id) && $find_store_id != '') {
					$stores = $this->model_exporter_product->getProductStores($result['product_id'], $find_store_id);
				}else{
					$stores = $this->model_exporter_product->getProductStores($result['product_id']);
				}
				
				$export_stores = array();
				foreach($stores as $store_id) {
					if($store_id == '0') {
						$export_stores[] = $this->language->get('text_default');
					}else{
						$store_info = $this->model_setting_store->getStore($store_id);
						$export_stores[] = ($store_info) ? $store_info['name'] : '';
					}
				}			
				$result['store'] = implode('::', $export_stores);			
				
				// Tax Class
				$tax_class_info = $this->model_exporter_product->getTaxClass($result['tax_class_id']);
				$result['tax_class'] = ($tax_class_info) ? $tax_class_info['title'] : '';
				
				// Stock Status
				$stock_status_info = $this->model_exporter_product->getStockStatus($result['stock_status_id'], $result['language_id']);			
				$result['stock_status'] = ($stock_status_info) ? $stock_status_info['name'] : '';
				
				// Keyword
				$keyword_info = $this->model_exporter_product->getKeyword($result['product_id']);			
				$result['seo_url'] = ($keyword_info) ? $keyword_info['keyword'] : '';
				
				// Length Class
				$length_class_info = $this->model_exporter_product->getLengthClass($result['length_class_id'], $result['language_id']);
				$result['length_class'] = ($length_class_info) ? $length_class_info['title'] : '';
				
				// Weight Class
				$weight_class_info = $this->model_exporter_product->getWeightClass($result['weight_class_id'], $result['language_id']);
				
				$result['weight_class'] = ($weight_class_info) ? $weight_class_info['title'] : '';
				
				// Manufacturer
				$manufacturer_info = $this->model_exporter_product->getManufacturer($result['manufacturer_id']);
				$result['manufacturer'] = ($manufacturer_info) ? $manufacturer_info['name'] : '';
				
				// Categories
				$categories_ids = $this->model_exporter_product->getProductCategories($result['product_id']);
				$result['categories_ids'] = ($categories_ids) ? implode(',', $categories_ids) : '';
							
				// Category Names
				$category_names = array();
				foreach($categories_ids as $category_id) {
					$category_info = $this->model_exporter_product->getCategory($category_id, $result['language_id']);
					if($category_info) {
						$category_names[] = $category_info['name'];
					}
				}
				$result['category_names'] = ($category_names) ? implode(' :: ', $category_names) : '';
				
				// Filters
				$filter_ids = $this->model_exporter_product->getProductFilters($result['product_id']);
				$filter_names = array();
				foreach($filter_ids as $filter_id) {
					$filter_info = $this->model_exporter_product->getFilter($filter_id, $result['language_id']);
					if($filter_info) {
						$filter_names[] = $filter_info['group'] .' - '. $filter_info['name'];
					}
				}
				
				$result['filter_names'] = ($filter_names) ? implode(' :: ', $filter_names) : '';
				
				// Downloads
				$downloads = $this->model_exporter_product->getProductDownloads($result['product_id']);
				$download_names = array();
				foreach($downloads as $download_id) {
					$download_info = $this->model_exporter_product->getDownload($download_id, $result['language_id']);
					if($download_info) {
						$download_names[] = $download_info['name'];
					}
				}
				$result['download_names'] = ($download_names) ? implode(' :: ', $download_names) : '';
				
				// Related Products
				$product_ids = $this->model_exporter_product->getProductRelated($result['product_id']);
				$result['related_products'] = ($product_ids) ? implode(',', $product_ids) : '';
				
				// Attribute
				$attributes = $this->model_exporter_product->getProductAttributes($result['product_id']);
				$attribute_names = array();
				foreach($attributes as $attribute) {
					$attribute_info = $this->model_exporter_product->getAttribute($attribute['attribute_id'], $result['language_id']);
					if($attribute_info) {
						$attribute_group_info = $this->model_exporter_product->getAttributeGroup($attribute_info['attribute_group_id'], $result['language_id']);
						if($attribute_group_info) {
							$attribute_names[] = $attribute_info['name'].'::'.$attribute_group_info['name'].'::'.$attribute['product_attribute_description'][$result['language_id']]['text'];
						}
					}
				}
				
				$result['attribute_names'] = ($attribute_names) ? implode('; ', $attribute_names) : '';
				
				// Images
				$result['additional_images'] = '';
				if($find_image) {
					$images = $this->model_exporter_product->getProductImages($result['product_id']);
					$additional_images = array();
					foreach($images as $image) {
						$additional_images[] = $image['image'];
					}
					$result['additional_images'] = ($additional_images) ? implode(' :: ', $additional_images) : '';
				}
				
				// Specials
				$specials = $this->model_exporter_product->getProductSpecials($result['product_id']);
				$specials_offers = array();
				foreach($specials as $special) {
					$specials_offers[] = $special['customer_group_id']. '::' .$special['priority'] .'::'. $special['price'] .'::'. $special['date_start'] .'::'. $special['date_end'];;
				}
				$result['specials_offers'] = ($specials_offers) ? implode('; ', $specials_offers) : '';
				
				// Discount Offer
				$discounts = $this->model_exporter_product->getProductDiscounts($result['product_id']);
				$discounts_offers = array();
				foreach($discounts as $discount) {
					$discounts_offers[] = $discount['customer_group_id']. '::' .$discount['quantity'] .'::' .$discount['priority'] .'::'. $discount['price'] .'::'. $discount['date_start'] .'::'. $discount['date_end'];
				}
				$result['discounts_offers'] = ($discounts_offers) ? implode('; ', $discounts_offers) : '';
				
				// Rewards
				$rewards = $this->model_exporter_product->getProductRewards($result['product_id']);
				$rewards_data = array();
				foreach($rewards as $customer_group_id => $reward) {
					$rewards_data[] = $customer_group_id .'::'. $reward['points'];
				}
				$result['rewards_data'] = ($rewards_data) ? implode('; ', $rewards_data) : '';
				
				// Options
				$options = $this->model_exporter_product->getProductOptions($result['product_id'], $result['language_id']);
				$options_data = array();
				foreach($options as $option) {
					$options_string = html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8') .' :: '.$option['type'].' :: '.$option['required'] .' :: ';
					if($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
						$option_value_row = 1;
						foreach($option['product_option_value'] as $option_value_key => $product_option_value) {
							$options_string .= $product_option_value['name'] .' ^^ '.$product_option_value['quantity'] .' ^^ '. $product_option_value['subtract'].' ^^ '. $product_option_value['price'] .' ^^ '. $product_option_value['price_prefix'] .' ^^ '. $product_option_value['points'] .' ^^ '. $product_option_value['points_prefix'] .' ^^ '. $product_option_value['weight'] .' ^^ '. $product_option_value['weight_prefix'];
							
							if(count($option['product_option_value']) != $option_value_row) {
								$options_string .= ' || ';
							}
							
							$option_value_row++; 
						}
					}else if($option['type'] == 'file') {
						// No Value for type file;
					}else {
						$options_string .= $option['value'];
					}
					
					$options_data[] = $options_string;
				}
				
				$result['options_data'] = ($options_data) ? implode(';; ', $options_data) : '';
				
				// Reviews
				$result['reviews_data'] = '';
				if($find_review) {
					$reviews = $this->model_exporter_product->getReviews($result['product_id'], $result['language_id']);
					$reviews_data = array();
					foreach($reviews as $review) {
						$reviews_data[] = $review['customer_id'] .' :: '. $review['author'] .' :: '. $review['text'] .' :: '. $review['rating'] .' :: '. $review['status'] .' :: '. $review['date_added'] .' :: '. $review['date_modified'];
					}
					$result['reviews_data'] = ($reviews_data) ? implode(';; ', $reviews_data) : '';
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['product_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['model']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['language']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['store']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'));
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['meta_title']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['meta_description']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['meta_keyword']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['tag']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['image']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['sku']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['upc']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['ean']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['jan']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['isbn']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['mpn']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['location']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value .$i, number_format((float)$result['price'], 2))->getStyle($char_value++ .$i)->getNumberFormat()->setFormatCode('#,##0.00');
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['minimum']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['quantity']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['status']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['sort_order']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['tax_class_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['tax_class']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['subtract']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['stock_status_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['stock_status']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['shipping']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['seo_url']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['date_available']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['length']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['length_class_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['length_class']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['width']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['height']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['weight']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['weight_class_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['weight_class']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['manufacturer_id']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['manufacturer']);
				
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['categories_ids']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result['category_names'], ENT_QUOTES, 'UTF-8'));
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['filter_names']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['download_names']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['related_products']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['attribute_names']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['options_data']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['discounts_offers']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['specials_offers']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['additional_images']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['points']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['rewards_data']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['viewed']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['date_added']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['date_modified']);
				$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, $result['reviews_data']);
				
				if(!empty($find_extrafields)) {
					foreach($find_extrafields as $find_extrafield) {
						$find_extrafield = explode('::', $find_extrafield);
						if(isset($find_extrafield[0]) && isset($find_extrafield[1])) {
							$objPHPExcel->getActiveSheet()->setCellValue($char_value++ .$i, html_entity_decode($result[$find_extrafield[1]], ENT_QUOTES, 'UTF-8'));
						}
					}
				}
			}
			
			// Find Format
			if($find_format == 'xls') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
				$file_name = 'ProductList.xls';
			}else if($find_format == 'xlsx') {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
				$file_name = 'ProductList.xlsx';
			}else if($find_format == 'csv') {
				$file_name = 'ProductList.csv';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
			}else{
				$file_name = '';
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			}
			
			$file_to_save = DIR_UPLOAD . $file_name;
			$objWriter->save(DIR_UPLOAD . $file_name); 
			
			$json['href'] = str_replace('&amp;', '&', $this->url->link('exporter/product/fileDownload', 'token='. $this->session->data['token'] .'&file_name='. $file_name .'&find_format='. $find_format, true));
			
			$json['success'] = $this->language->get('text_success');
		} else{
			$json['error'] = $this->language->get('text_no_results');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function fileDownload() {
		$file_to_save = DIR_UPLOAD . $this->request->get['file_name'];
		
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename="'. $this->request->get['file_name'] .'"'); 
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '. filesize($file_to_save));
		header('Cache-Control: max-age=0'); 
		header('Accept-Ranges: bytes');
		readfile($file_to_save);
		
		unlink($file_to_save);
	}
}