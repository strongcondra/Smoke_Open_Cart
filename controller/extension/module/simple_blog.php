<?php
	class Controllerextensionmodulesimpleblog extends Controller {
		public function index($setting) {

			$this->language->load('extension/module/simple_blog');

			$data['heading_title'] = $this->language->get('heading_title');

			$this->load->model('simple_blog/article');

			$data['articles'] = array();

			$this->document->addStyle('catalog/view/theme/default/stylesheet/blog_custom.css');
			$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

				$setting['category_id']=0;
				$setting['article_limit']=0;
			    $simple_blog_details = $this->config->get('simple_blog_module');
				$arrayFristKey = key($simple_blog_details);
				$dataextract= $simple_blog_details[$arrayFristKey] ;
				$setting['category_id']= $dataextract['category_id'];
				$setting['article_limit']=$dataextract['article_limit'];
				$setting['status'] = $this->config->get('simple_blog_status');

				if ($setting['category_id'] == 'all') {
					$data['heading_title'] = $this->language->get('text_latest_all');
					$data['article_link'] = $this->url->link('simple_blog/article');
				} elseif($setting['category_id'] == 'popular') {
					$data['heading_title'] = $this->language->get('text_popular_all');
					$data['article_link'] = $this->url->link('simple_blog/article');
				} else {
					$category_info = $this->model_simple_blog_article->getCategory($setting['category_id']);
					$data['heading_title'] = $category_info['name'];
					$data['article_link'] = $this->url->link('simple_blog/simple_category', 'simple_blog_category_id=' . $setting['category_id']);
				}

			if ($setting['category_id'] == 'all') {
				$filter_data = array(
					'start'           => 0,
					'limit'           => $setting['article_limit']
				);

			$results = $this->model_simple_blog_article->getArticleModuleWise($filter_data);

			} else if($setting['category_id'] == 'popular') {
				$filter_data = array(
					'start'           => 0,
					'limit'           => $setting['article_limit']
				);

				$results = $this->model_simple_blog_article->getPopularArticlesModuleWise($filter_data);

			} else {
				$filter_data = array(
					'filter_category_id' => $setting['category_id'],
					'start'           => 0,
					'limit'           => $setting['article_limit']
				);

				$results = $this->model_simple_blog_article->getArticleModuleWise($filter_data);
			}

            if($setting['status']) {

    			foreach($results as $result) {

    				$description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '...';

    				if($result['featured_image']) {
    					$image = $this->model_tool_image->resize($result['featured_image'], 100, 100);
    				} else if($result['image']) {
    					$image = $this->model_tool_image->resize($result['image'], 100, 100);
    				} else {
    					$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
    				}
					
					if($result['featured_image']) {
    					$article_image = $this->model_tool_image->resize($result['featured_image'], 360, 218);
    				} else if($result['image']) {
    					$article_image = $this->model_tool_image->resize($result['image'], 360, 218);
    				} else {
    					$article_image = $this->model_tool_image->resize('no_image.jpg', 360, 218);
    				}

    				// get total comments
    				$total_comments = $this->model_simple_blog_article->getTotalComments($result['simple_blog_article_id']);

    				if($total_comments != 1) {
    					$total_comments .= $this->language->get('text_comments');
    				} else {
    					$total_comments .= $this->language->get('text_comment');
    				}

    				$data['articles'][] = array(
    					'simple_blog_article_id'	=> $result['simple_blog_article_id'],
    					'article_title'		=> $result['article_title'],
    					'author_name'		=> $result['author_name'],
    					'image'				=> $image,
    					'article_image'		=> $article_image,
    					'featured_found'	=> '', // $featured_found
    					'date_added'		=> date($this->language->get('text_date_format'), strtotime($result['date_modified'])),
    					'description'		=> $description,
    					'allow_comment'		=> $result['allow_comment'],
    					'total_comment'		=> $total_comments,
    					'href'				=> $this->url->link('simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'], 'SSL'),
    					'author_href'		=> $this->url->link('simple_blog/author', 'simple_blog_author_id=' . $result['simple_blog_author_id'], 'SSL'),
    					'comment_href'		=> $this->url->link('simple_blog/article/view', 'simple_blog_article_id=' . $result['simple_blog_article_id'] . '#comment-section', 'SSL')
    				);
    			}
            }

			$data['text_no_found'] = $this->language->get('text_no_result');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'extension/module/simple_blog.tpl')) {
    			return $this->load->view($this->config->get('config_template') . 'extension/module/simple_blog.tpl', $data);
    		} else {
    			return $this->load->view('extension/module/simple_blog.tpl', $data);
    		}
		}
	}
?>
