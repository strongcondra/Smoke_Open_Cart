<?php
class ControllerExtensionModuleEasyNewsLetterSubscription extends Controller {
	private $data = array();
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/module/easynewslettersubscription');
        $this->load->model('extension/module/easynewslettersubscription');
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$this->document->addStyle('view/stylesheet/easynewslettersubscription.css');		
		$this->document->setTitle($this->language->get('heading_title'));

		if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }

        $this->data['error_warning'] = '';

        $store = $this->getCurrentStore($this->request->get['store_id']);

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
			if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
				$this->request->post['easynewslettersubscription']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
			}
			if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
				$this->request->post['easynewslettersubscription']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']),true);
			}
			$store = $this->getCurrentStore($this->request->post['store_id']);
				
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (!empty($this->request->get['activate'])) {
				$this->session->data['success'] = $this->language->get('text_success_activation');
			}
			
			$this->model_setting_setting->editSetting('easynewslettersubscription', $this->request->post, $this->request->post['store_id']);			
			$this->response->redirect($this->url->link('extension/module/easynewslettersubscription', 'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
		}
		
		$languages = $this->model_localisation_language->getLanguages();;
		$this->data['languages'] = $languages;
		$firstLanguage = array_shift($languages);
		$this->data['firstLanguageCode'] = $firstLanguage['code'];
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['save_changes'] = $this->language->get('save_changes');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_layout_options'] = $this->language->get('entry_layout_options');
		$this->data['entry_position_options'] = $this->language->get('entry_position_options');
		$this->data['entry_enable_disable']	= $this->language->get('entry_enable_disable');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['token'] = $this->session->data['token'];
 		
		if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/easynewslettersubscription', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['stores']				= array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $this->data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
		$this->data['store']                = $store;
		$this->data['action'] 				= $this->url->link('extension/module/easynewslettersubscription', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] 				= $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['data']					= $this->model_setting_setting->getSetting('easynewslettersubscription', $store['store_id']);
		$this->data['header']				= $this->load->controller('common/header');
        $this->data['column_left']			= $this->load->controller('common/column_left');
        $this->data['footer']				= $this->load->controller('common/footer');
    
        $this->response->setOutput($this->load->view('extension/module/easynewslettersubscription.tpl', $this->data));
	}
 	public function install()
    {
        $this->load->model('extension/module/easynewslettersubscription');
        $this->model_extension_module_easynewslettersubscription->install();
    }
    public function uninstall()
    {
       	$this->load->model('setting/setting');
		
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting('easynewslettersubscription_module',0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting('easynewslettersubscription', $store['store_id']);
		}
		
        $this->load->model('extension/module/easynewslettersubscription');
        $this->model_extension_module_easynewslettersubscription->uninstall();
    }
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/easynewslettersubscription')) {
			$this->error = true;
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	 public function getsubscribers() {
        if (!empty($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        }
		
        $this->load->model('extension/module/easynewslettersubscription');	
		$limit = 10;
		$this->data['limit'] = $limit;		
		$total								= $this->model_extension_module_easynewslettersubscription->getTotalSubscriptions();
		$pagination 						= new Pagination();
		$pagination->total 					= $total;
		$pagination->page 					= $page;
		$pagination->limit 					= $limit;
		$pagination->url 					= $this->url->link('extension/module/easynewslettersubscription/getsubscribers', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');
		$this->data['sources']    			= $this->model_extension_module_easynewslettersubscription->viewsubscribers($page, $pagination->limit);
		$this->data['pagination']			= $pagination->render();
		$this->data['results']				= sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));
		
        $this->data['token'] = $this->session->data['token'];
        $this->response->setOutput($this->load->view('extension/module/easynewslettersubscription/viewsubscribers.tpl', $this->data));
    }
	
	public function removesubscriber() {
		if (isset($_POST['subscribe_id'])) {
			$run_query = $this->db->query("DELETE FROM `" . DB_PREFIX . "easynewslettersubscription` WHERE `subscribe_id`=".(int)$_POST['subscribe_id']);
			if ($run_query) echo "Success!";
		}
	}

	private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }
	 

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
		
		
	public function exporttocsv() {	
		$filename = fopen('php://memory', 'w');
 
		fputcsv($filename, array('Customer Name','Email'));
		$query =  $this->db->query("SELECT customer_name, customer_email FROM `" . DB_PREFIX . "easynewslettersubscription`
		ORDER BY `date_created` DESC "); 
	 
		foreach($query->rows as $row)
		{
			fputcsv($filename, array($row['customer_name'], $row['customer_email']));
		}
		
		fseek($filename, 0);
		header('Content-Type: application/csv');
		header('Content-Disposition: attachement; filename="ExportedEasyNewsLetterSubscribers.csv"');
		fpassthru($filename);			
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/easynewslettersubscription')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }
	
}
?>