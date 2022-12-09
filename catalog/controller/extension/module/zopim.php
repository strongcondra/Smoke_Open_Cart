<?php
#################################################################
## Open Cart Module:  ZOPIM LIVE CHAT WIDGET			       ##
##-------------------------------------------------------------##
## Copyright © 2016 MB "Programanija" All rights reserved.     ##
## http://www.opencartextensions.eu						       ##
## http://www.programanija.com   						       ##
##-------------------------------------------------------------##
## Permission is hereby granted, when purchased, to  use this  ##
## mod on one domain. This mod may not be reproduced, copied,  ##
## redistributed, published and/or sold.				       ##
##-------------------------------------------------------------##
## Violation of these rules will cause loss of future mod      ##
## updates and account deletion				      			   ##
#################################################################

class ControllerExtensionModuleZopim extends Controller {
	
	public function index() {
		
        $data = array();
        
		$data['zopim_code'] = $this->config->get('zopim_code');
		
		if($this->config->get('zopim_status') == 'On') {
            
		  return $this->load->view('extension/module/zopim', $data);
		}
	}
}