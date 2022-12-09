<?php 
class ModelonepagecheckoutCustomer extends Model {
		public function addCustomer($data){
			if (isset($data['customer_group_id']) && is_array($this->config->get('onepagecheckout_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('onepagecheckout_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('onepagecheckout_customer_group_id');
			}

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			$sql = "INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', email = '" . $this->db->escape($data['email']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()";
			
			if(!empty($data['firstname'])){
				$sql .= ", firstname = '" . $this->db->escape($data['firstname']) . "'";
			}
			
			if(!empty($data['lastname'])){
				$sql .= ", lastname = '" . $this->db->escape($data['lastname']) . "'";
			}
			
			if(!empty($data['telephone'])){
				$sql .= ", telephone = '" . $this->db->escape($data['telephone']) . "'";
			}
			
			if(!empty($data['fax'])){
				$sql .= ", fax = '" . $this->db->escape($data['fax']) . "'";
			}
			
			if(!empty($data['newsletter'])){
				$sql .= ", newsletter = '" . $this->db->escape($data['newsletter']) . "'";
			}
			
			if(!empty($data['custom_field']['account'])){
				$sql .= ", custom_field = '" . json_encode($data['custom_field']['account']) . "'";
			}
			
			$this->db->query($sql);
			

			$customer_id = $this->db->getLastId();
			
			
			$sq = "INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "'";
			
			if(!empty($data['firstname'])){
				$sq .= ", firstname = '" . $this->db->escape($data['firstname']) . "'";
			}
			
			if(!empty($data['lastname'])){
				$sq .= ", lastname = '" . $this->db->escape($data['lastname']) . "'";
			}
			
			
			if(!empty($data['company'])){
				$sq .= ", company = '" . $this->db->escape($data['company']) . "'";
			}
			
			if(!empty($data['address_1'])){
				$sq .= ", address_1 = '" . $this->db->escape($data['address_1']) . "'";
			}
			
			if(!empty($data['address_2'])){
				$sq .= ", address_2 = '" . $this->db->escape($data['address_2']) . "'";
			}
			
			if(!empty($data['city'])){
				$sq .= ", city = '" . $this->db->escape($data['city']) . "'";
			}
			
			if(!empty($data['postcode'])){
				$sq .= ", postcode = '" . $this->db->escape($data['postcode']) . "'";
			}
			
			if(!empty($data['country_id'])){
				$sq .= ", country_id = '" . $this->db->escape($data['country_id']) . "'";
			}
			
			if(!empty($data['zone_id'])){
				$sq .= ", zone_id = '" . $this->db->escape($data['zone_id']) . "'";
			}
			
			if(!empty($data['custom_field']['address'])){
				$sq .= ", custom_field = '" . json_encode($data['custom_field']['address']) . "'";
			}
			
			$this->db->query($sq);
			
			$address_id = $this->db->getLastId();

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			
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
		
		public function addAddress($data) {
			$sql = "INSERT INTO ".DB_PREFIX."address SET customer_id = '" . (int)$this->customer->getId() . "'";
			
			if(isset($data['firstname'])){
				$sql .= ",firstname = '" . $this->db->escape($data['firstname']) . "'";
			}
			
			if(isset($data['lastname'])){
				$sql .= ",lastname = '" . $this->db->escape($data['lastname']) . "'";
			}
			
			if(isset($data['company'])){
				$sql .= ",company = '" . $this->db->escape($data['company']) . "'";
			}
			
			if(isset($data['address_1'])){
				$sql .= ",address_1 = '" . $this->db->escape($data['address_1']) . "'";
			}
			
			if(isset($data['address_2'])){
				$sql .= ",address_2 = '" . $this->db->escape($data['address_2']) . "'";
			}
			
			if(isset($data['postcode'])){
				$sql .= ",postcode = '" . $this->db->escape($data['postcode']) . "'";
			}
			
			if(isset($data['city'])){
				$sql .= ",city = '" . $this->db->escape($data['city']) . "'";
			}
			
			if(isset($data['zone_id'])){
				$sql .= ",zone_id = '" . (int)$data['zone_id'] . "'";
			}
			
			if(isset($data['country_id'])){
				$sql .= ",country_id = '" . (int)$data['country_id'] . "'";
			}
			
			if(isset($data['custom_field'])){
				$sql .= ",custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "'";
			}
			
			$this->db->query($sql);
			
			$address_id = $this->db->getLastId();
			
			if (!empty($data['default'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
			
			return $address_id;
		}
		
		public function getCustomFieldValue($custom_field_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_value_id = '" . (int)$custom_field_value_id . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
}
?>