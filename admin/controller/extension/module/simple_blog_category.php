<?php
	class Controllerextensionmodulesimpleblogcategory extends Controller {

        private $error = array();

        public function index() {
            $url = $this->request->get['route'];
            if($this->checkDatabase()) {

                $this->language->load('simple_blog/install');

                $this->document->setTitle($this->language->get('error_database'));

                $data['install_database'] = $this->url->link('simple_blog/install/installDatabase', 'token=' . $this->session->data['token'] . '&url=' . $url, 'SSL');

                $data['text_install_message'] = $this->language->get('text_install_message');

                $data['text_upgread'] = $this->language->get('text_upgread');

                $data['error_database'] = $this->language->get('error_database');

                $data['breadcrumbs'] = array();

    	   		$data['breadcrumbs'][] = array(
    	       		'text'      => $this->language->get('text_home'),
    				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    	      		'separator' => false
    	   		);

                $data['header'] = $this->load->controller('common/header');
      		    $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');

                $this->response->setOutput($this->load->view('simple_blog/notification.tpl', $data));

            } else {
                $this->getData();
            }
		}

        public function checkDatabase() {
            $database_not_found = $this->load->controller('simple_blog/install/validateTable');

            if(!$database_not_found) {
                return true;
            }

            return false;
        }

		public function getData() {
			$this->language->load('extension/module/simple_blog_category');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('setting/setting');

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
        //echo  "<pre>";print_r($this->request->post);die;
				$this->model_setting_setting->editSetting('simple_blog_category', $this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

					$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			}

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			$data['text_content_top'] = $this->language->get('text_content_top');
			$data['text_content_bottom'] = $this->language->get('text_content_bottom');
			$data['text_column_left'] = $this->language->get('text_column_left');
			$data['text_column_right'] = $this->language->get('text_column_right');
			$data['text_category_related'] = $this->language->get('text_category_related');
      $data['text_edit'] = $this->language->get('text_edit');
      $data['help_search_article'] = $this->language->get('help_search_article');
			$data['entry_search_article'] = $this->language->get('entry_search_article');
			$data['entry_layout'] = $this->language->get('entry_layout');
			$data['entry_position'] = $this->language->get('entry_position');
			$data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_module_add'] = $this->language->get('button_module_add');
			$data['button_remove'] = $this->language->get('button_remove');
      $data['tab_module'] = $this->language->get('tab_module');
			$data['token'] = $this->session->data['token'];

			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
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
					'href' => $this->url->link('extension/module/simple_blog_category', 'token=' . $this->session->data['token'], true)
				);

				$data['action'] = $this->url->link('extension/module/simple_blog_category', 'token=' . $this->session->data['token'], true);

				$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);




            if (isset($this->request->post['simple_blog_category_status'])) {
				$data['simple_blog_category_status'] = $this->request->post['simple_blog_category_status'];
			} elseif ($this->config->get('simple_blog_category_status')) {
				$data['simple_blog_category_status'] = $this->config->get('simple_blog_category_status');
			} else {
			    $data['simple_blog_category_status'] = '';
			}

			if (isset($this->request->post['simple_blog_category_search_article'])) {
				$data['simple_blog_category_search_article'] = $this->request->post['simple_blog_category_search_article'];
			} elseif ($this->config->get('simple_blog_category_search_article')) {
				$data['simple_blog_category_search_article'] = $this->config->get('simple_blog_category_search_article');
			}else {
				$data['simple_blog_category_search_article'] = '';
			}

        if (isset($this->request->post['simple_blog_category_module'])) {
    			$modules = $this->request->post['simple_blog_category_module'];
    		} elseif ($this->config->has('simple_blog_category_module')) {
    			$modules = $this->config->get('simple_blog_category_module');
    		} else {
    			$modules = array();
    		}

    		$data['modules'] = array();

    		foreach ($modules as $key => $module) {
    			$data['modules'][] = array(
    				'key'           => $key,
    				'status'        => $module['status'],
    				'sort_order'   => $module['sort_order']
    			);
    		}

			$this->load->model('design/layout');

			$data['layouts'] = $this->model_design_layout->getLayouts();

            $data['header'] = $this->load->controller('common/header');
  		    $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('extension/module/simple_blog_category', $data));
		}

		protected function validate() {
			if (!$this->user->hasPermission('modify', 'extension/module/simple_blog_category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}
	}
?>
