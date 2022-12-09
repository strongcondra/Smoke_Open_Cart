<?php
class ControllerCatalogheadermenu extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/headermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('catalog/headermenu');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/headermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/headermenu');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_headermenu->addheadermenu($this->request->post);
			
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
			
			$this->response->redirect($this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/headermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/headermenu');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_headermenu->editheadermenu($this->request->get['headermenu_id'], $this->request->post);
			
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
			
			$this->response->redirect($this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->language->load('catalog/headermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/headermenu');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $headermenu_id) {
				$this->model_catalog_headermenu->deleteheadermenu($headermenu_id);
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
			
			$this->response->redirect($this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
							
		$data['insert'] = $this->url->link('catalog/headermenu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/headermenu/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['headermenus'] = array();

		$data1 = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$headermenu_total = $this->model_catalog_headermenu->getTotalheadermenus();
	
		$results = $this->model_catalog_headermenu->getheadermenus($data1);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/headermenu/update', 'token=' . $this->session->data['token'] . '&headermenu_id=' . $result['headermenu_id'] . $url, 'SSL')
			);
						
			$data['headermenus'][] = array(
				'headermenu_id' => $result['headermenu_id'],
				'title'          => $result['title'],
				'level2'          => $result['level2'],
				'link'          => $result['link'],
				'sort_order'          => $result['sort_order'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['headermenu_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_link'] = $this->language->get('column_link');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
 
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
		
		$data['sort_title'] = $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $headermenu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($headermenu_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($headermenu_total - $this->config->get('config_limit_admin'))) ? $headermenu_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $headermenu_total, ceil($headermenu_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('catalog/headermenu_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
    	$data['text_left'] = $this->language->get('text_left');
    	$data['text_right'] = $this->language->get('text_right');
		
		
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_bottom'] = $this->language->get('entry_bottom');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['text_select'] = $this->language->get('text_select');
		
		////////////////////////////////////headermenus//////////////////////////
		$data['entry_level2'] = $this->language->get('entry_level2');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_level1'] = $this->language->get('entry_level1');
		$data['entry_column'] = $this->language->get('entry_column');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['headermenu'] = $this->model_catalog_headermenu->getheadermenus();
		$data['headermenu1'] = $this->model_catalog_headermenu->getheadermenus1();
		
		////////////////////////////////////headermenus//////////////////////////
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
    	
		$data['tab_general'] = $this->language->get('tab_general');
    	$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}
		
	 	if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
							
		if (!isset($this->request->get['headermenu_id'])) {
			$data['action'] = $this->url->link('catalog/headermenu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/headermenu/update', 'token=' . $this->session->data['token'] . '&headermenu_id=' . $this->request->get['headermenu_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/headermenu', 'token=' . $this->session->data['token'] . $url, 'SSL');

		
		
		$data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		

		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->get['headermenu_id'])) {
		$headermenu_info=$this->model_catalog_headermenu->getheadermenu($this->request->get['headermenu_id']);
		}	
		
	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($headermenu_info)) {
			$data['status'] = $headermenu_info['status'];
		} else {
			$data['status'] = 1;
		}	
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($headermenu_info)) {
			$data['sort_order'] = $headermenu_info['sort_order'];
		} else {
			$data['sort_order'] ='';
		}
				
	
		///////////////////////headermenus/////////////////////
		if (isset($this->request->post['headermenu_description'])) {
			$data['headermenu_description'] = $this->request->post['headermenu_description'];
		} elseif (isset($this->request->get['headermenu_id'])) {
			$data['headermenu_description'] =$this->model_catalog_headermenu->getheadermenuDescriptions($this->request->get['headermenu_id']);
		} else {
			$data['headermenu_description'] = array();
		}
		
		
		if (isset($this->request->post['link'])) {
			$data['link'] = $this->request->post['link'];
		} elseif (!empty($headermenu_info)) {
			$data['link'] = $headermenu_info['link'];
		} else {
			$data['link'] = '';
		}
	
		if (isset($this->request->post['level1'])) {
			$data['level1'] = $this->request->post['level1'];
		} elseif (!empty($headermenu_info)) {
			$data['level1'] = $headermenu_info['level1'];
		} else {
			$data['level1'] = '';
		}
		
		
		if (isset($this->request->post['level2'])) {
			$data['level2'] = $this->request->post['level2'];
		} elseif (!empty($headermenu_info)) {
			$data['level2'] = $headermenu_info['level2'];
		} else {
			$data['level2'] = '';
		}
		
		if (isset($this->request->post['column'])) {
			$data['column'] = $this->request->post['column'];
		} elseif (!empty($headermenu_info)) {
			$data['column'] = $headermenu_info['column'];
		} else {
			$data['column'] = '';
		}
		
		if (isset($this->request->post['position'])) {
			$data['position'] = $this->request->post['position'];
		} elseif (!empty($headermenu_info)) {
			$data['position'] = $headermenu_info['position'];
		} else {
			$data['position'] = 'left';
		}
		
		///////////////////////headermenus/////////////////////

		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('catalog/headermenu_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/headermenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['headermenu_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
		
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

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/headermenu')) {
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