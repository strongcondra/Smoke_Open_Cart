<?php
class ControllerExtensionModuleFaqManager extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/faq_manager');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//print "<pre>"; print_r($this->request->post); die;
			$this->model_setting_setting->editSetting('faq_manager', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_extension'] = $this->language->get('text_extension');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_section_name'] = $this->language->get('entry_section_name');
		$data['entry_section_question_answer'] = $this->language->get('entry_section_question_answer');
		$data['entry_question'] = $this->language->get('entry_question');
		$data['entry_answer'] = $this->language->get('entry_answer');
		
		$data['button_add_question'] = $this->language->get('button_add_question');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['faq_manager_sections'])) {
			$data['error_faq_manager_sections'] = $this->error['faq_manager_sections'];
		} else {
			$data['error_faq_manager_sections'] = '';
		}
	
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/faq_manager', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/faq_manager', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->post['faq_manager_status'])) {
			$data['faq_manager_status'] = $this->request->post['faq_manager_status'];
		} else {
			$data['faq_manager_status'] = $this->config->get('faq_manager_status');
		}
		
		$data['faq_manager_sections'] = array();
				
		if (isset($this->request->post['faq_manager_sections'])) {
			$data['faq_manager_sections'] = $this->request->post['faq_manager_sections'];
		} else {
			$data['faq_manager_sections'] = $this->config->get('faq_manager_sections');
		}
		
		if(!$data['faq_manager_sections']) {
			$data['faq_manager_sections'] = array();
		}
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/faq_manager.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/faq_manager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		if(!isset($this->request->post['faq_manager_sections'])) {
			$this->error['warning'] = $this->language->get('error_faq');
		} else {
			foreach($this->request->post['faq_manager_sections'] as $key => $value) {
				foreach($languages as $language) {
					if(strlen($value['title'][$language['language_id']]) < 3 || strlen($value['title'][$language['language_id']]) > 64) {
						$this->error['faq_manager_sections'][$key]['title'][$language['language_id']] = $this->language->get('error_section_name_required');
					}
				}
				
				if(!isset($value['groups'])) {
					$this->error['warning'] = $this->language->get('error_faq');
				} else {
					foreach($value['groups'] as $key_val => $val) {
						
						foreach($languages as $language) {
							if(strlen($val['question'][$language['language_id']]) < 3 || strlen($value['title'][$language['language_id']]) > 64) {
								$this->error['faq_manager_sections'][$key]['groups'][$key_val]['question'][$language['language_id']] = $this->language->get('error_section_question_required');
							}
							
							//$description = html_entity_decode(strip_tags($val['answer'][$language['language_id']]), ENT_QUOTES, 'UTF-8');
							$description = strip_tags($val['answer'][$language['language_id']]);
							//echo $description; die;
							if(strlen($description) < 3) {
								$this->error['faq_manager_sections'][$key]['groups'][$key_val]['answer'][$language['language_id']] = $this->language->get('error_section_answer_required');
							}	
						}
					}
				}				
			}
		}
		
		
		return !$this->error;
		//return false;
	}
}
