<?php
	class Controllerextensionmodulesimpleblogcategory extends Controller {
		public function index($setting) {


			$this->language->load('extension/module/simple_blog_category');

			$data['heading_title'] = $this->language->get('heading_title');

			$this->load->model('simple_blog/article');

			$data['text_search_article'] = $this->language->get('text_search_article');
			$data['button_search'] = $this->language->get('button_search');

			if (isset($this->request->get['simple_blog_category_id'])) {
				$parts = explode('_', (string)$this->request->get['simple_blog_category_id']);
			} else {
				$parts = array();
			}

			if (isset($parts[0])) {
				$data['category_id'] = $parts[0];
			} else {
				$data['category_id'] = 0;
			}

			if (isset($parts[1])) {
				$data['child_id'] = $parts[1];
			} else {
				$data['child_id'] = 0;
			}

			$this->load->model('simple_blog/article');

			$data['categories'] = array();

			$categories = $this->model_simple_blog_article->getCategories(0);

			foreach ($categories as $category) {

				$children_data = array();

				$children = $this->model_simple_blog_article->getCategories($category['simple_blog_category_id']);

				foreach ($children as $child) {

					$article_total = $this->model_simple_blog_article->getTotalArticles($child['simple_blog_category_id']);

					$children_data[] = array(
						'category_id' => $child['simple_blog_category_id'],
						'name'  => $child['name'],
						'href'  => $this->url->link('simple_blog/category', 'simple_blog_category_id=' . $category['simple_blog_category_id'] . '_' . $child['simple_blog_category_id'])
					);
				}

				$data['categories'][] = array(
					'simple_blog_category_id' => $category['simple_blog_category_id'],
					'name'     => $category['name'],
					'children' => $children_data,
					'href'     => $this->url->link('simple_blog/category', 'simple_blog_category_id=' . $category['simple_blog_category_id'])
				);
			}

            //print "<pre>"; print_r($data['categories']); die;

            if($this->config->has('simple_blog_category_search_article')) {
                $data['simple_blog_category_search_article'] = $this->config->get('simple_blog_category_search_article');
            }

			//print "<pre>"; print_r($data['categories']); exit;
			if (isset($this->request->get['blog_search'])) {
				$data['blog_search'] = $this->request->get['blog_search'];
			} else {
				$data['blog_search'] = '';
			}

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'extension/module/simple_blog_category.tpl')) {
    			return $this->load->view($this->config->get('config_template') . 'extension/module/simple_blog_category.tpl', $data);
    		} else {
    			return $this->load->view('extension/module/simple_blog_category.tpl', $data);
    		}
		}
	}
?>
