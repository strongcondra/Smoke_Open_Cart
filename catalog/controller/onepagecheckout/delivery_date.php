<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
class ControllerOnepagecheckoutDeliveryDate extends Controller {
	public function index() {
		$this->load->language('onepagecheckout/checkout');
		
		$data['start'] = $this->config->get('onepagecheckout_delivery_start_days');
		
		$today=date('Y-m-d');
		$data['next_date'] = date('Y-m-d', strtotime($today. ' + '.$data['start'].' days'));
		
		$data['end'] = $this->config->get('onepagecheckout_delivery_end_days');
		
		$disable_days = $this->config->get('onepagecheckout_delivery_disable_days');
		if($disable_days){
			$disable_days = explode(',',$disable_days);
			$data['disable_days'] = json_encode($disable_days);
		}else{
			$data['disable_days'] = '';
		}
		
		if(!empty($this->session->data['delivery_date'])){
			$data['delivery_date'] = $this->session->data['delivery_date'];
		}else{
			$data['delivery_date'] = '';
		}
		$data['delivery_required'] = $this->config->get('onepagecheckout_delivery_required');
		$data['delivery_weekend'] = ($this->config->get('onepagecheckout_delivery_weekend') ? $this->config->get('onepagecheckout_delivery_weekend') : array());
		
		$onepagecheckout_manage = $this->config->get('onepagecheckout_manage');
		
		$data['heading_title']  = (!empty($require_comment_status['delivery']['heading_title'][$this->config->get('config_language_id')]) ? $require_comment_status['delivery']['heading_title'][$this->config->get('config_language_id')] : 'Estimated Delivery Date');
		
		$data['label']  = (!empty($require_comment_status['delivery']['label'][$this->config->get('config_language_id')]) ? $require_comment_status['delivery']['label'][$this->config->get('config_language_id')] : 'Delivery Date');
		
		
		if($this->config->get('onepagecheckout_delivery_status')){
			if(version_compare(VERSION,'2.2.0.0','>=')){
				return $this->load->view('onepagecheckout/delivery_date', $data);
			}else{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/onepagecheckout/delivery_date.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/onepagecheckout/delivery_date.tpl', $data);
				} else {
					return $this->load->view('default/template/onepagecheckout/delivery_date.tpl', $data);
				}
			}
		}
	}
}