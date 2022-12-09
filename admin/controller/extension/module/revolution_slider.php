<?php class ControllerExtensionModuleRevolutionSlider extends Controller {
		private $error = array();

		public function index() {
			$this->load->language('extension/module/revolution_slider');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('extension/module');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {				
				foreach($this->request->post['slider_detail'] as $key => $value) {
					if($value['heading_position'] != 'custom') {
						$this->request->post['slider_detail'][$key]['header_x_position'] = '';
						$this->request->post['slider_detail'][$key]['header_x_position'] = '';
					}
					
					if($value['caption_position'] != 'custom') {
						$this->request->post['slider_detail'][$key]['caption_x_size'] = '';
						$this->request->post['slider_detail'][$key]['caption_y_size'] = '';
					}
					
					if($value['button_position'] != 'custom') {
						$this->request->post['slider_detail'][$key]['button_x_size'] = '';
						$this->request->post['slider_detail'][$key]['button_y_size'] = '';
					}
				}
				
				if (!isset($this->request->get['module_id'])) {
					$this->model_extension_module->addModule('revolution_slider', $this->request->post);
				} else {
					$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			}

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_edit'] = $this->language->get('text_edit');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			$data['text_random'] = $this->language->get('text_random');
			$data['text_fade'] = $this->language->get('text_fade');
			$data['text_zoomout'] = $this->language->get('text_zoomout');
			$data['text_top_left'] = $this->language->get('text_top_left');
			$data['text_top_center'] = $this->language->get('text_top_center');
			$data['text_top_right'] = $this->language->get('text_top_right');
			$data['text_center_left'] = $this->language->get('text_center_left');
			$data['text_center_center'] = $this->language->get('text_center_center');
			$data['text_center_right'] = $this->language->get('text_center_right');
			$data['text_bottom_left'] = $this->language->get('text_bottom_left');
			$data['text_bottom_center'] = $this->language->get('text_bottom_center');
			$data['text_bottom_right'] = $this->language->get('text_bottom_right');
			$data['text_custom'] = $this->language->get('text_custom');

			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_display_full_width'] = $this->language->get('entry_display_full_width');
			$data['entry_width'] = $this->language->get('entry_width');
			$data['entry_height'] = $this->language->get('entry_height');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_detail'] = $this->language->get('entry_detail');
			$data['entry_heading_detail'] = $this->language->get('entry_heading_detail');
			$data['entry_caption_detail'] = $this->language->get('entry_caption_detail');
			$data['entry_button'] = $this->language->get('entry_button');
			$data['entry_link'] = $this->language->get('entry_link');
			$data['entry_transition'] = $this->language->get('entry_transition');
			$data['entry_position'] = $this->language->get('entry_position');
			$data['entry_custom_position_x'] = $this->language->get('entry_custom_position_x');
			$data['entry_custom_position_y'] = $this->language->get('entry_custom_position_y');

			$data['help_display_full_width'] = $this->language->get('help_display_full_width');

			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_slider_add'] = $this->language->get('button_slider_add');
			$data['button_remove'] = $this->language->get('button_remove');

			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->error['name'])) {
				$data['error_name'] = $this->error['name'];
			} else {
				$data['error_name'] = '';
			}

			if (isset($this->error['width'])) {
				$data['error_width'] = $this->error['width'];
			} else {
				$data['error_width'] = '';
			}

			if (isset($this->error['height'])) {
				$data['error_height'] = $this->error['height'];
			} else {
				$data['error_height'] = '';
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_module'),
				'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
			);

			if (!isset($this->request->get['module_id'])) {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/revolution_slider', 'token=' . $this->session->data['token'], 'SSL')
				);
			} else {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/revolution_slider', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
				);
			}

			if (!isset($this->request->get['module_id'])) {
				$data['action'] = $this->url->link('extension/module/revolution_slider', 'token=' . $this->session->data['token'], true);
			} else {
				$data['action'] = $this->url->link('extension/module/revolution_slider', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
			}

			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

			if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
			}
			
			$data['token'] = $this->session->data['token'];

			if (isset($this->request->post['name'])) {
				$data['name'] = $this->request->post['name'];
			} elseif (isset($module_info['name'])) {
				$data['name'] = $module_info['name'];
			} else {
				$data['name'] = '';
			}

			if (isset($this->request->post['display_full_width'])) {
				$data['display_full_width'] = $this->request->post['display_full_width'];
			} elseif (isset($module_info['display_full_width'])) {
				$data['display_full_width'] = $module_info['display_full_width'];
			} else {
				$data['display_full_width'] = 0;
			}

			if (isset($this->request->post['width'])) {
				$data['width'] = $this->request->post['width'];
			} elseif (isset($module_info['width'])) {
				$data['width'] = $module_info['width'];
			} else {
				$data['width'] = 1170;
			}

			if (isset($this->request->post['height'])) {
				$data['height'] = $this->request->post['height'];
			} elseif (isset($module_info['height'])) {
				$data['height'] = $module_info['height'];
			} else {
				$data['height'] = 500;
			}

			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
			} elseif (isset($module_info['status'])) {
				$data['status'] = $module_info['status'];
			} else {
				$data['status'] = '';
			}
			
			if (isset($this->request->post['slider_detail'])) {
				$slider_detail = $this->request->post['slider_detail'];
			} elseif (isset($module_info['slider_detail'])) {
				$slider_detail = $module_info['slider_detail'];
			} else {
				$slider_detail = array();
			}
			
			$this->load->model('tool/image');
			
			$data['slider_detail'] = array();
			
			foreach ($slider_detail as $slider) {
				if (is_file(DIR_IMAGE . $slider['image'])) {
					$image = $slider['image'];
					$thumb = $slider['image'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}
				
				if(isset($slider['heading_x_size'])) {
					$heading_x_size = $slider['heading_x_size'];
				} else {
					$heading_x_size = '';
				}
				
				if(isset($slider['heading_y_size'])) {
					$heading_y_size = $slider['heading_y_size'];
				} else {
					$heading_y_size = '';
				}
				
				if(isset($slider['caption_x_size'])) {
					$caption_x_size = $slider['caption_x_size'];
				} else {
					$caption_x_size = '';
				}
				
				if(isset($slider['caption_y_size'])) {
					$caption_y_size = $slider['caption_y_size'];
				} else {
					$caption_y_size = '';
				}
				
				if(isset($slider['button_x_size'])) {
					$button_x_size = $slider['button_x_size'];
				} else {
					$button_x_size = '';
				}
				
				if(isset($slider['button_y_size'])) {
					$button_y_size = $slider['button_y_size'];
				} else {
					$button_y_size = '';
				}
				
				$data['slider_detail'][] = array(
					'image'			=> $image,
					'thumb'         => $this->model_tool_image->resize($thumb, 100, 100),
					'heading' 		=> $slider['heading'],
					'heading_position' => $slider['heading_position'],
					'heading_x_size' => $heading_x_size,
					'heading_y_size' => $heading_y_size,
					'caption' 		=> $slider['caption'],
					'caption_position' => $slider['caption_position'],
					'caption_x_size' => $caption_x_size,
					'caption_y_size' => $caption_y_size,
					'button_text'	=> $slider['button_text'],
					'link'          => $slider['link'],
					'button_position' => $slider['button_position'],
					'button_x_size' => $button_x_size,
					'button_y_size' => $button_y_size,
					'transition'    => $slider['transition']
				);
			}
			
			$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('extension/module/revolution_slider.tpl', $data));
		}
		
		public function validate() {
			if (!$this->user->hasPermission('modify', 'extension/module/revolution_slider')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			return !$this->error;
		}
	}
?>
