<?php
	class ControllerSimpleBlogCategory extends Controller {
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
            $this->language->load('simple_blog/category');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('simple_blog/category');
	
			$this->getList();
        }
        
		public function insert() {
			$this->language->load('simple_blog/category');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('simple_blog/category');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_simple_blog_category->addCategory($this->request->post);
	
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
			}
	
			$this->getForm();
		}

		public function update() {
			$this->language->load('simple_blog/category');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('simple_blog/category');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				//print "<pre>"; print_r($this->request->post); exit;
				$this->model_simple_blog_category->editCategory($this->request->get['simple_blog_category_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
			}
	
			$this->getForm();
		}
	
		public function delete() {
			$this->language->load('simple_blog/category');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$this->load->model('simple_blog/category');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $simple_blog_category_id) {
					$this->model_simple_blog_category->deleteCategory($simple_blog_category_id);
				}
	
				$this->session->data['success'] = $this->language->get('text_success');
	
				$url = '';
	
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
	
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
			}
	
			$this->getList();
		}
		
		public function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'bcd.name';
			}
	
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}
	
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}	
			
            if (isset($this->request->post['selected'])) {
    			$data['selected'] = (array)$this->request->post['selected'];
    		} else {
    			$data['selected'] = array();
    		}
            
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);
			
			$data['insert'] = $this->url->link('simple_blog/category/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$data['delete'] = $this->url->link('simple_blog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	
			$data['categories'] = array();
			
			$filter_data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
			);
			
			$category_total = $this->model_simple_blog_category->getTotalCategories($filter_data);
			
			$results = $this->model_simple_blog_category->getCategories(0);
			
			foreach($results as $result) {
				$data['categories'][] = array(
					'simple_blog_category_id' 	=> $result['simple_blog_category_id'],
					'name'            	=> $result['name'],
					'sort_order'  		=> $result['sort_order'],
					'status'      		=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'selected'        	=> isset($this->request->post['selected']) && in_array($result['simple_blog_category_id'], $this->request->post['selected']),
					'edit'          	=> $this->url->link('simple_blog/category/update', 'token=' . $this->session->data['token'] . '&simple_blog_category_id=' . $result['simple_blog_category_id'] . $url, 'SSL')
				);				
			}	
			
			$data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_confirm'] = $this->language->get('text_confirm');
			$data['text_no_results'] = $this->language->get('text_no_results');
	
			$data['column_name'] = $this->language->get('column_name');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_sort_order'] = $this->language->get('column_sort_order');
			$data['column_action'] = $this->language->get('column_action');
	
			$data['button_insert'] = $this->language->get('button_insert');
			$data['button_delete'] = $this->language->get('button_delete');
            $data['button_edit'] = $this->language->get('button_edit');
			
			$data['token'] = $this->session->data['token'];
			
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
			
			$url = '';

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$data['sort_name'] = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . '&sort=sbcd.name' . $url, 'SSL');
			$data['sort_status'] = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . '&sort=sbc.status' . $url, 'SSL');
			$data['sort_sortorder'] = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . '&sort=sbc.sort_order' . $url, 'SSL');
	
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			$pagination = new Pagination();
			$pagination->total = $category_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
	
			$data['pagination'] = $pagination->render();
            
            $data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			
            $data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
    
    		$this->response->setOutput($this->load->view('simple_blog/category_list.tpl', $data));		
		}		

		public function getForm() {
			$data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_form'] = !isset($this->request->get['simple_blog_category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
            
			$data['text_none'] = $this->language->get('text_none');
			$data['text_default'] = $this->language->get('text_default');
			$data['text_image_manager'] = $this->language->get('text_image_manager');
			$data['text_browse'] = $this->language->get('text_browse');
			$data['text_clear'] = $this->language->get('text_clear');		
			$data['text_enabled'] = $this->language->get('text_enabled');
	    	$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_percent'] = $this->language->get('text_percent');
			$data['text_amount'] = $this->language->get('text_amount');
            $data['text_select_all'] = $this->language->get('text_select_all');
			$data['text_unselect_all'] = $this->language->get('text_unselect_all');
            
            $data['help_keyword'] = $this->language->get('help_keyword');
	    	$data['help_column'] = $this->language->get('help_column');
			$data['help_article_limit'] = $this->language->get('help_article_limit');
			$data['help_top'] = $this->language->get('help_top');
					
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
			$data['entry_meta_description'] = $this->language->get('entry_meta_description');
			$data['entry_description'] = $this->language->get('entry_description');
			$data['entry_store'] = $this->language->get('entry_store');
			$data['entry_keyword'] = $this->language->get('entry_keyword');
			$data['entry_parent'] = $this->language->get('entry_parent');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_top'] = $this->language->get('entry_top');
			$data['entry_article_limit'] = $this->language->get('entry_article_limit');		
			$data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_layout'] = $this->language->get('entry_layout');
			$data['entry_column'] = $this->language->get('entry_column');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
	
	    	$data['tab_general'] = $this->language->get('tab_general');
	    	$data['tab_data'] = $this->language->get('tab_data');
			$data['tab_design'] = $this->language->get('tab_design');
			
			$data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['simple_blog_category_id'])) {
				$data['simple_blog_category_id'] = $this->request->get['simple_blog_category_id'];
			} else {
				$data['simple_blog_category_id'] = 0;
			}
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
		
	 		if (isset($this->error['name'])) {
				$data['error_name'] = $this->error['name'];
			} else {
				$data['error_name'] = array();
			}
            
            if (isset($this->error['seo_keyword'])) {
				$data['error_seo_keyword'] = $this->error['seo_keyword'];
			} else {
				$data['error_seo_keyword'] = '';
			}
			
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);
			
			if (!isset($this->request->get['simple_blog_category_id'])) {
				$data['action'] = $this->url->link('simple_blog/category/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			} else {
				$data['action'] = $this->url->link('simple_blog/category/update', 'token=' . $this->session->data['token'] . '&simple_blog_category_id=' . $this->request->get['simple_blog_category_id'] . $url, 'SSL');
			}
			
			$data['cancel'] = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			if (isset($this->request->get['simple_blog_category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      		$category_info = $this->model_simple_blog_category->getCategory($this->request->get['simple_blog_category_id']);
	    	}			
			
			$this->load->model('localisation/language');
		
			$data['languages'] = $this->model_localisation_language->getLanguages();
	
			if (isset($this->request->post['category_description'])) {
				$data['category_description'] = $this->request->post['category_description'];
			} elseif (isset($this->request->get['simple_blog_category_id'])) {
				$data['category_description'] = $this->model_simple_blog_category->getCategoryDescriptions($this->request->get['simple_blog_category_id']);
			} else {
				$data['category_description'] = array();
			}
			
			$categories = $this->model_simple_blog_category->getCategories(0);

			// Remove own id from list
			if (!empty($category_info)) {
				foreach ($categories as $key => $category) {
					if ($category['simple_blog_category_id'] == $category_info['simple_blog_category_id']) {
						unset($categories[$key]);
					}
				}
			}
			
			$data['categories'] = $categories;
			
			if (isset($this->request->post['parent_id'])) {
				$data['parent_id'] = $this->request->post['parent_id'];
			} elseif (!empty($category_info)) {
				$data['parent_id'] = $category_info['parent_id'];
			} else {
				$data['parent_id'] = 0;
			}

			$this->load->model('setting/store');
		
			$data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->post['category_store'])) {
				$data['category_store'] = $this->request->post['category_store'];
			} elseif (isset($this->request->get['simple_blog_category_id'])) {
				$data['category_store'] = $this->model_simple_blog_category->getCategoryStores($this->request->get['simple_blog_category_id']);
			} else {
				$data['category_store'] = array(0);
			}		
			
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (!empty($category_info)) {
				$data['keyword'] = $category_info['keyword'];
			} else {
				$data['keyword'] = '';
			}
	
			if (isset($this->request->post['image'])) {
				$data['image'] = $this->request->post['image'];
			} elseif (!empty($category_info)) {
				$data['image'] = $category_info['image'];
			} else {
				$data['image'] = '';
			}
			
			$this->load->model('tool/image');
	
			if (!empty($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			
			$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			
			if (isset($this->request->post['top'])) {
				$data['top'] = $this->request->post['top'];
			} elseif (!empty($category_info)) {
				$data['top'] = $category_info['top'];
			} else {
				$data['top'] = 0;
			}
			
			if (isset($this->request->post['column'])) {
				$data['column'] = $this->request->post['column'];
			} elseif (!empty($category_info)) {
				$data['column'] = $category_info['column'];
			} else {
				$data['column'] = 10;
			}
			
			if (isset($this->request->post['blog_category_column'])) {
				$data['blog_category_column'] = $this->request->post['blog_category_column'];
			} elseif (!empty($category_info)) {
				$data['blog_category_column'] = $category_info['blog_category_column'];
			} else {
				$data['blog_category_column'] = 0;
			}
					
			if (isset($this->request->post['sort_order'])) {
				$data['sort_order'] = $this->request->post['sort_order'];
			} elseif (!empty($category_info)) {
				$data['sort_order'] = $category_info['sort_order'];
			} else {
				$data['sort_order'] = 0;
			}
			
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
			} elseif (!empty($category_info)) {
				$data['status'] = $category_info['status'];
			} else {
				$data['status'] = 1;
			}
					
			if (isset($this->request->post['category_layout'])) {
				$data['category_layout'] = $this->request->post['category_layout'];
			} elseif (isset($this->request->get['simple_blog_category_id'])) {
				$data['category_layout'] = $this->model_simple_blog_category->getCategoryLayouts($this->request->get['simple_blog_category_id']);
			} else {
				$data['category_layout'] = array();
			}
	
			$this->load->model('design/layout');
			
			$data['layouts'] = $this->model_design_layout->getLayouts();
			
            $data['header'] = $this->load->controller('common/header');
  		    $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
    
            $this->response->setOutput($this->load->view('simple_blog/category_form.tpl', $data));
		}

		private function validateForm() {
			if (!$this->user->hasPermission('modify', 'simple_blog/category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
	
			foreach ($this->request->post['category_description'] as $language_id => $value) {
				if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
					$this->error['name'][$language_id] = $this->language->get('error_name');
				}
			}
			
            if ((utf8_strlen($this->request->post['keyword']) < 3) || (utf8_strlen($this->request->post['keyword']) > 64)) {
				$this->error['seo_keyword'] = $this->language->get('error_seo_not_found');
			}
            
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
						
			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}
		
		private function validateDelete() {
			if (!$this->user->hasPermission('modify', 'simple_blog/category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
	 		
			$this->load->model('simple_blog/category');
	
			foreach ($this->request->post['selected'] as $simple_blog_catgory_id) {
				$article_total = $this->model_simple_blog_category->getTotalArticleCategoryWise($simple_blog_catgory_id);
	
				if ($article_total) {
					$this->error['warning'] = sprintf($this->language->get('error_article'), $article_total);
					break;
				}	
			}
			
			if (!$this->error) {
				return true; 
			} else {
				return false;
			}
		}
	}
?>