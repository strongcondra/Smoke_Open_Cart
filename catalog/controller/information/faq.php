<?php
class ControllerInformationFaq extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/faq');
		
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['page_banner'] = $this->model_tool_image->resize($this->config->get('config_common_banner'), $this->config->get($this->config->get('config_theme') . '_slider_category_width'), $this->config->get($this->config->get('config_theme') . '_slider_category_height'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/faq')
		);

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_faq_found'] = $this->language->get('text_no_faq_found');
		
		$data['faq_manager_status'] = $this->config->get('faq_manager_status');
		
		$language_id = $this->config->get('config_language_id');
		$i = 0;
		$j = 0;
		
		$data['faq_managers'] = array();
		
		if($this->config->get('faq_manager_sections')) {
			foreach($this->config->get('faq_manager_sections') as $key => $value) {
				$i++;
				$sub_sections = array();
				
				foreach($value['groups'] as $key_val => $val) {
					$j++;
					/*$sub_sections[] = array(
						'question' => $val['question'][$language_id],
						'answer' => html_entity_decode($val['answer'][$language_id], ENT_QUOTES, 'UTF-8'),
						'inner_counter' => $j
					);*/
					
					$sub_sections[] = array(
						'question' => ((isset($val['question'][$language_id])) ? $val['question'][$language_id] : ''),
						'answer' => ((isset($val['answer'][$language_id])) ? html_entity_decode($val['answer'][$language_id], ENT_QUOTES, 'UTF-8') : ''),
						'inner_counter' => $j
					);
					
				}
				
				/*$data['faq_managers'][] = array(
					'section_title' => $value['title'][$language_id],
					'sub_sections'	=> $sub_sections,
					'counter' => $i
				);*/
				
				if(isset($value['title'][$language_id])) {
					$data['faq_managers'][] = array(
						'section_title' => $value['title'][$language_id],
						'sub_sections'	=> $sub_sections,
						'counter' => $i
					);
				}
				
			}
		}
		
		//print "<pre>"; print_r($data['faq_managers']); die;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/faq', $data));
	}
}
