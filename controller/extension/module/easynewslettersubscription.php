<?php  
class ControllerExtensionModuleEasynewslettersubscription extends Controller {
	private $data = array();
	public function index() {
		
		$this->language->load('extension/module/easynewslettersubscription');
      	$this->data['heading_title'] = $this->language->get('heading_title');
      	$this->data['EasyNewsletterSubscription_Title'] = $this->language->get('EasyNewsletterSubscription_Title');
      	$this->data['EasyNewsletterSubscription_Email'] = $this->language->get('EasyNewsletterSubscription_Email');
      	$this->data['EasyNewsletterSubscription_Name'] = $this->language->get('EasyNewsletterSubscription_Name');
      	$this->data['EasyNewsletterSubscription_SubscribeNow'] = $this->language->get('EasyNewsletterSubscription_SubscribeNow');
      	$this->data['EasyNewsletterSubscription_Error1'] = $this->language->get('EasyNewsletterSubscription_Error1');
      	$this->data['EasyNewsletterSubscription_Error2'] = $this->language->get('EasyNewsletterSubscription_Error2');
      	$this->data['EasyNewsletterSubscription_Success'] = $this->language->get('EasyNewsletterSubscription_Success');
		$this->data['currenttemplate'] = $this->config->get('config_template');
		$this->data['language_id'] = $this->config->get('config_language_id');
		$this->data['store_id'] = $this->config->get('config_store_id');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['data']['easynewslettersubscription'] = str_replace('http', 'https', $this->config->get('easynewslettersubscription'));
		} else {
			$this->data['data']['easynewslettersubscription'] = $this->config->get('easynewslettersubscription');
		}	
		
		if(!isset($this->data['data']['easynewslettersubscription']['CustomText'][$this->config->get('config_language')])){
			$this->data['data']['easynewslettersubscription']['CustomText'] = '';
		} else {
			$this->data['data']['easynewslettersubscription']['CustomText'] = $this->data['data']['easynewslettersubscription']['CustomText'][$this->config->get('config_language')];
		}
		

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/easynewslettersubscription.tpl')) {
			return $this->load->view($this->config->get('config_template').'/template/extension/module/easynewslettersubscription.tpl', $this->data);
		} else {
			return $this->load->view('extension/module/easynewslettersubscription.tpl', $this->data);
		}
	}
	
	public function subscribecustomer() { 
		if (isset($_POST['YourName']) && isset($_POST['YourEmail'])) {
				$this->language->load('extension/module/easynewslettersubscription');
				$check_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easynewslettersubscription` 
					WHERE `customer_email`='".$_POST['YourEmail']."'");
					if (!$check_query->row) {
						$run_query = $this->db->query("
						INSERT INTO `" . DB_PREFIX . "easynewslettersubscription` 
						(customer_email, customer_name, date_created, language_id, store_id)
					 	VALUES ('".$_POST['YourEmail']."', '".$_POST['YourName']."', NOW(), '".$_POST['language_id']."', '".$_POST['store_id']."')
						");
						if ($run_query) echo $this->language->get('EasyNewsletterSubscription_Success');
					} else {
						echo $this->language->get('EasyNewsletterSubscription_Duplicate');
					}
				}
	}
	
	public function unsubscribecustomer() {
		if (isset($this->request->get['email'])) {
			$email = base64_decode($this->request->get['email']);
			
			$run_query = $this->db->query("DELETE FROM `" . DB_PREFIX . "easynewslettersubscription` 
				WHERE `customer_email` = '".$email."'");
			if ($run_query) echo "You are now unsubscribed from the newsletter!";
		}
	}
}
?>