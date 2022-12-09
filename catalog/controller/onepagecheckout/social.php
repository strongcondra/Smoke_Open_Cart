<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
class ControllerOnepagecheckoutSocial extends Controller {
	public function index() {
		$this->load->language('onepagecheckout/checkout');
		$data['text_social_login']  = $this->language->get('text_social_login');
		$this->load->model('account/customer');
		if (!empty($this->request->get['token'])) {
			$this->customer->logout();
			unset($this->session->data['order_id']);
			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

			if ($customer_info && $this->customer->login($customer_info['email'], '', 'SSL')) {
				$this->response->redirect($this->url->link('onepagecheckout/checkout','','SSL'));
			}else{
				$this->response->redirect($this->url->link('onepagecheckout/checkout','','SSL'));
			}
		}
		
		$this->load->model('setting/setting');
		
		$onepagecheckout_info = $this->model_setting_setting->getSetting('onepagecheckout', $this->config->get('config_store_id'));
		
		$onepagecheckout_manage = (!empty($onepagecheckout_info['onepagecheckout_manage'])) ? $onepagecheckout_info['onepagecheckout_manage'] : array();
		
		$data['fbstatus'] = (!empty($onepagecheckout_manage['social_login']['facebook_login_status']) ? $onepagecheckout_manage['social_login']['facebook_login_status'] : '');
		$data['gstatus'] = (!empty($onepagecheckout_manage['social_login']['google_login_status']) ? $onepagecheckout_manage['social_login']['google_login_status'] : '');
		
		$data['flogin'] = $this->url->link('onepagecheckout/social/fblogin','','SSL');
		$data['glogin'] = $this->url->link('onepagecheckout/social/glogin','','SSL');
		
		if($this->config->get('onepagecheckout_field_layout')){
		  $data['class1'] = 'extsm-12';
		  $data['classmargin'] = 'margin-bottom:5px;';
		}else{
		  $data['class1'] = 'extsm-6';
		  $data['classmargin'] = '';
		}
		
		if(version_compare(VERSION,'2.2.0.0','>=')){
				return $this->load->view('onepagecheckout/social', $data);
		}else{
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/social.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/social.tpl', $data);
			} else {
				return $this->load->view('default/template/onepagecheckout/social.tpl', $data);
			}
		}
	}
	
	public function glogin(){
		require_once(DIR_SYSTEM. "onepagechekout/google/autoload.php");
		$client_id 		= $this->config->get('onepagecheckout_google_appid');
		$client_secret  = $this->config->get('onepagecheckout_google_key');
		$redirect_uri 	= $this->config->get('onepagecheckout_google_callback');
		
		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->addScope("email");
		$client->addScope("profile");
		$service = new Google_Service_Oauth2($client);
		 $loginUrl = $client->createAuthUrl();
		
		header("Location: ".$loginUrl);
	}
	
	public function fblogin(){
		require_once(DIR_SYSTEM."onepagechekout/Facebook/autoload.php");
		$appId = $this->config->get('onepagecheckout_facebook_appid');
		$appSecret = $this->config->get('onepagecheckout_facebook_key');
		$homeurl = $this->config->get('onepagecheckout_facebook_callback');
		
		$fb = new Facebook\Facebook([
			'app_id' => $appId,
			'app_secret' => $appSecret,
			'default_graph_version' => 'v2.6',
		]);
		
		$helper = $fb->getRedirectLoginHelper();
	
		$permissions = ['email']; 
			
		$loginUrl = $helper->getLoginUrl($homeurl, $permissions);
		
		header("Location: ".$loginUrl);
	}
	
	public function facebook(){
		require_once(DIR_SYSTEM."onepagechekout/Facebook/autoload.php");
		
		$appId = $this->config->get('onepagecheckout_facebook_appid');
		$appSecret = $this->config->get('onepagecheckout_facebook_key');
		$homeurl = $this->config->get('onepagecheckout_facebook_callback');
		$fbPermissions = 'email';
		
		$fb = new Facebook\Facebook([
			'app_id' => $appId,
			'app_secret' => $appSecret,
			'default_graph_version' => 'v2.6',
		]);
		
		$helper = $fb->getRedirectLoginHelper();
		
		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		
		if (! isset($accessToken)) {
		  if ($helper->getError()) {
			$this->response->redirect($this->url->link('onepagecheckout/checkout'));
		  } else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		  }
		  exit;
		}
		
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		
		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		
		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId($appId); // Replace {app-id} with your app id
		
		$tokenMetadata->validateExpiration();
		
		if (! $accessToken->isLongLived()) {
		  // Exchanges a short-lived access token for a long-lived one
		  try {
			$response = $fb->get('/me?fields=id,name,first_name,middle_name,last_name,email', $accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
			exit;
		  }
			echo '<h3>Long live</h3>';
		}
		
		try {
			$response = $fb->get('/me?fields=id,name,first_name,middle_name,last_name,email', $accessToken);

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		$fbuser = $response->getGraphUser();
		if($fbuser){
			if(isset($fbuser['email'])){
					if (isset($fbuser['first_name'])) {
						$data['first_name'] = $fbuser['first_name'];
					}else{
						$data['first_name'] = '';
					}
			
					if (isset($fbuser['last_name'])) {
						$data['last_name'] = (!empty($fbuser['middle_name']) ? $fbuser['middle_name'].' ' : '').$fbuser['last_name'];
					} else {
						$data['last_name'] = '';
					}
					
					$fbdata = array(
						'firstname'	=> $data['first_name'],
						'lastname'	=> $data['last_name'],
						'email'		=> $fbuser['email'],
					);
				
				$customer_id = $this->getexistCustomer($fbdata);
				if(!empty($customer_id)){
					$token = base64_encode(64);
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
					$this->response->redirect($this->url->link('onepagecheckout/social', '&token=' . $token));
				}else{
					$this->response->redirect($this->url->link('onepagecheckout/checkout'));
				}
			}else{
				$this->session->data['error'] =   'Warning your Facebook account not providing email. Please try with another facebook account!';
				$this->response->redirect($this->url->link('onepagecheckout/checkout'));
			}
		}else{
			$this->session->data['error'] =  'Warning your Facebook account not providing email. Please try with another facebook account!';
			$this->response->redirect($this->url->link('onepagecheckout/checkout'));
		}
	}
	
	
	public function google(){
		require_once(DIR_SYSTEM. "onepagechekout/google/autoload.php");
		
		$client_id 		= $this->config->get('onepagecheckout_google_appid');
		$client_secret  = $this->config->get('onepagecheckout_google_key');
		$redirect_uri 	= $this->config->get('onepagecheckout_google_callback');
		
		
		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->addScope("email");
		$client->addScope("profile");
		$service = new Google_Service_Oauth2($client);
		
		if(isset($_GET['code'])){
			$client->authenticate($_GET['code']);
			$_SESSION['access_token'] = $client->getAccessToken();
			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			exit;
		}
		
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		   $client->setAccessToken($_SESSION['access_token']);
		} else {
		   $authUrl = $client->createAuthUrl();
		}
		
		if(!isset($authUrl)){
			$user_profile = $service->userinfo->get();
			if($user_profile['name']) {
				$names = explode(' ', $user_profile['name']);
			}else{
				$names= array();
			}
			$fbdata = array(
			 'firstname'	=> (!empty($names[0])) ? $names[0] : '',
			 'lastname'		=> (!empty($names[1])) ? $names[1] : '',
			 'email'		=> $user_profile['email'],
			);
				
			$customer_id = $this->getexistCustomer($fbdata);
			if(!empty($customer_id)){
				$token = base64_encode(64);
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
				$this->response->redirect($this->url->link('onepagecheckout/social', '&token=' . $token));
			}else{
				$this->response->redirect($this->url->link('onepagecheckout/checkout'));
			}
		}else{
			$this->session->data['error'] =  'Warning your Facebook account not providing email. Please try with another facebook account!';
			$this->response->redirect($this->url->link('onepagecheckout/checkout'));
		}
	}
	
	public function getexistCustomer($data){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($data['email'])) . "'");
		if($query->row){
			 return $query->row['customer_id'];
		}else{
			return $customer_id = $this->addnewcustomer($data);
		}
	}
	
	public function addnewcustomer($data){
		
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('onepagecheckout_customer_group_id'));
       
	    $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', customer_group_id = '" . (int)$this->config->get('onepagecheckout_customer_group_id') . "', store_id = '" . (int)$this->config->get('config_store_id') . "', email = '" . $this->db->escape($data['email']) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
		
		$customer_id = $this->db->getLastId();

		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			if(!empty($data['firstname'])){
				$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			}
			
			if(!empty($data['lastname'])) {
				$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			}
			
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			
			if(!empty($data['email'])){
				$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			}
			
			if(!empty($data['telephone'])) {
				$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";
			}

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));
			if($emails) {
				foreach ($emails as $email) {
					if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
		}

		return $customer_id;
	}
}