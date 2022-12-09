<?php 
class ModelExtensionModuleEasynewslettersubscription extends Model {

	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "easynewslettersubscription`
						 (`subscribe_id` INT(11) NOT NULL AUTO_INCREMENT, 
						 `customer_email` VARCHAR(200) NULL DEFAULT NULL,
						 `customer_name` VARCHAR(100) NULL DEFAULT NULL,
						 `store_id` int(11) DEFAULT NULL,
						 `date_created` DATETIME  NOT NULL DEFAULT '0000-00-00 00:00:00', 
					 	 `language_id` VARCHAR(100) NULL DEFAULT '".$this->config->get('config_language_id')."',
						  PRIMARY KEY (`subscribe_id`));");	
	}
	public function uninstall()	{
		  $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "easynewslettersubscription`");
	}
	public function viewsubscribers($page=1, $limit=8, $sort="id", $order="DESC") {	
		if ($page) {
				$start = ($page - 1) * $limit;
			}
		$query =  $this->db->query("SELECT * FROM `" . DB_PREFIX . "easynewslettersubscription`
			ORDER BY `date_created` DESC
			LIMIT ".$start.", ".$limit);
		return $query->rows; 
	}
	
	public function getTotalSubscriptions(){
			$query = $this->db->query("SELECT COUNT(*) as `count`  FROM `" . DB_PREFIX . "easynewslettersubscription`");
		return $query->row['count']; 
	}

}
?>