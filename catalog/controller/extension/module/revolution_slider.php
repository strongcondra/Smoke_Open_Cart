<?php class ControllerExtensionModuleRevolutionSlider extends Controller {
		public function index($setting) {
			static $rev_slider_module = 0;
			
			$this->load->model('tool/image');
			
			$this->document->addStyle('catalog/view/javascript/revolution-slider/css/style.css');
			$this->document->addStyle('catalog/view/javascript/revolution-slider/css/symbol.css');
			$this->document->addStyle('catalog/view/javascript/revolution-slider/css/settings.css');
			
			$this->document->addScript('catalog/view/javascript/revolution-slider/js/jquery.themepunch.tools.min.js');
			$this->document->addScript('catalog/view/javascript/revolution-slider/js/jquery.themepunch.revolution.min.js');
			
			$data['sliders'] = array();
			
			foreach($setting['slider_detail'] as $slider) {
				if (is_file(DIR_IMAGE . $slider['image'])) {
					$layers = array();
					
					if($slider['heading_position'] == 'top_left') {
						$heading_data = 'data-x=0 data-y=0';
					} else if($slider['heading_position'] == 'top_center') {
						$heading_data = 'data-x=center data-y=0';
					} else if($slider['heading_position'] == 'top_right') {
						$heading_data = 'data-x=right data-y=0';
					} else if($slider['heading_position'] == 'center_left') {
						$heading_data = 'data-x=0 data-y=center';
					} else if($slider['heading_position'] == 'center_center') {
						$heading_data = 'data-x=center data-y=center';
					} else if($slider['heading_position'] == 'center_right') {
						$heading_data = 'data-x=right data-y=center';
					} else if($slider['heading_position'] == 'bottom_left') {
						$heading_data = 'data-x=0 data-y=bottom';
					} else if($slider['heading_position'] == 'bottom_center') {
						$heading_data = 'data-x=center data-y=bottom';
					} else if($slider['heading_position'] == 'bottom_right') {
						$heading_data = 'data-x=right data-y=bottom';
					} else if($slider['heading_position'] == 'custom') {
						if($slider['heading_x_size'] && $slider['heading_y_size']) {
							$heading_data = 'data-x=' . $slider['heading_x_size'] . ' data-y=' . $slider['heading_y_size'];
						} else {
							$heading_data = 'data-x=150 data-y=150';
						}
					}
					
					if($slider['caption_position'] == 'top_left') {
						$caption_data = 'data-x=0 data-y=0';
					} else if($slider['caption_position'] == 'top_center') {
						$caption_data = 'data-x=center data-y=0';
					} else if($slider['caption_position'] == 'top_right') {
						$caption_data = 'data-x=right data-y=0';
					} else if($slider['caption_position'] == 'center_left') {
						$caption_data = 'data-x=0 data-y=center';
					} else if($slider['caption_position'] == 'center_center') {
						$caption_data = 'data-x=center data-y=center';
					} else if($slider['caption_position'] == 'center_right') {
						$caption_data = 'data-x=right data-y=center';
					} else if($slider['caption_position'] == 'bottom_left') {
						$caption_data = 'data-x=0 data-y=bottom';
					} else if($slider['caption_position'] == 'bottom_center') {
						$caption_data = 'data-x=center data-y=bottom';
					} else if($slider['caption_position'] == 'bottom_right') {
						$caption_data = 'data-x=right data-y=bottom';
					} else if($slider['caption_position'] == 'custom') {
						if($slider['caption_x_size'] && $slider['caption_y_size']) {
							$caption_data = 'data-x=' . $slider['caption_x_size'] . ' data-y=' . $slider['caption_y_size'];
						} else {
							$caption_data = 'data-x=150 data-y=150';
						}
					}
					
					if($slider['button_position'] == 'top_left') {
						$button_position = 'data-x=0 data-y=0';
					} else if($slider['button_position'] == 'top_center') {
						$button_position = 'data-x=center data-y=0';
					} else if($slider['button_position'] == 'top_right') {
						$button_position = 'data-x=right data-y=0';
					} else if($slider['button_position'] == 'center_left') {
						$button_position = 'data-x=0 data-y=center';
					} else if($slider['button_position'] == 'center_center') {
						$button_position = 'data-x=center data-y=center';
					} else if($slider['button_position'] == 'center_right') {
						$button_position = 'data-x=right data-y=center';
					} else if($slider['button_position'] == 'bottom_left') {
						$button_position = 'data-x=0 data-y=bottom';
					} else if($slider['button_position'] == 'bottom_center') {
						$button_position = 'data-x=center data-y=bottom';
					} else if($slider['button_position'] == 'bottom_right') {
						$button_position = 'data-x=right data-y=bottom';
					} else if($slider['button_position'] == 'custom') {
						if($slider['button_x_size'] && $slider['button_y_size']) {
							$button_position = 'data-x=' . $slider['button_x_size'] . ' data-y=' . $slider['button_y_size'];
						} else {
							$button_position = 'data-x=150 data-y=150';
						}
					}
					
					$first 		= $heading_data .' data-speed=500 data-start="3300" data-easing="Power4.easeOut" style="z-index:1; color: #fff;"';
					$second 	= $caption_data .' data-speed=800 data-start="3600" data-easing="Power4.easeOut" style="z-index:2; color: #fff;"';
					$third 		= $button_position .' data-speed=1000 data-start="3900" data-easing="Power4.easeOut" style="z-index:3; color: #fff;"';
					
					$layers[] = array(
						'text'	=> html_entity_decode($slider['heading'], ENT_QUOTES, 'UTF-8'),
						'type'	=> 'large_heading',
						'link'			=> '',
						'position'	=> $heading_data,
						'string'	=> $first
					);
					
					$layers[] = array(
						'text'	=> html_entity_decode($slider['caption'], ENT_QUOTES, 'UTF-8'),
						'type'	=> 'medium_heading',
						'link'			=> '',
						'position'	=> $caption_data,
						'string'	=> $second
					);
					
					$layers[] = array(
						'text'	=> $slider['button_text'],
						'link'			=> $slider['link'],
						'type'	=> 'button',
						'position'	=> $button_position,
						'string'	=> $third
					);
					
					$data['sliders'][] = array(
						'image' => HTTP_SERVER . 'image/' . $slider['image'],
						'transition' => $slider['transition'],
						'layer'		=> $layers
					);
				}
			}
		
			$data['display_full_width'] = $setting['display_full_width'];
			$data['width'] = $setting['width'];
			$data['height'] = $setting['height'];
			
			$data['module'] = $rev_slider_module++;
			
			if ($data['sliders']) {
				return $this->load->view('extension/module/revolution_slider', $data);
			}
		}
	}
?>
