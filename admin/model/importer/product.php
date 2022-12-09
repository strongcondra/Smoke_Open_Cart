<?php
class ModelImporterProduct extends Model {
	public function getProductById($product_id) {
		$query = $this->db->query("SELECT product_id, model FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'");

		return $query->row;
	}
	
	public function getProductByModel($model) {
		$query = $this->db->query("SELECT product_id, model FROM " . DB_PREFIX . "product p WHERE p.model = '" . $this->db->escape($model) . "'");
		
		return $query->row;
	}
	
	public function getProductByName($name, $language_id) {
		$query = $this->db->query("SELECT pd.product_id, pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.name = '" . $this->db->escape($name) . "' AND pd.language_id = '". (int)$language_id ."'");
		
		return $query->row;
	}
	
	public function addProduct($data, $find_data) {
		$sql = "INSERT INTO " . DB_PREFIX . "product SET ";

		$implode = array();

		if(!empty($find_data['cell_operations']['model'])) {
			$implode[] = " model = '" . $this->db->escape($data['model']) . "'";
		}

		if(!empty($find_data['cell_operations']['sku'])) {
			$implode[] = " sku = '" . $this->db->escape($data['sku']) . "'";
		}

		if(!empty($find_data['cell_operations']['upc'])) {
			$implode[] = " upc = '" . $this->db->escape($data['upc']) . "'";
		}
		if(!empty($find_data['cell_operations']['ean'])) {
			$implode[] = " ean = '" . $this->db->escape($data['ean']) . "'";
		}
		if(!empty($find_data['cell_operations']['jan'])) {
			$implode[] = " jan = '" . $this->db->escape($data['jan']) . "'";
		}
		if(!empty($find_data['cell_operations']['isbn'])) {
			$implode[] = " isbn = '" . $this->db->escape($data['isbn']) . "'";
		}
		if(!empty($find_data['cell_operations']['mpn'])) {
			$implode[] = " mpn = '" . $this->db->escape($data['mpn']) . "'";
		}
		if(!empty($find_data['cell_operations']['location'])) {
			$implode[] = " location = '" . $this->db->escape($data['location']) . "'";
		}
		if(!empty($find_data['cell_operations']['product_image'])) {
			$implode[] = " image = '" . $this->db->escape(($data['product_image'])) . "'";
		}
		if(!empty($find_data['cell_operations']['quantity'])) {
			$implode[] = " quantity = '" . (int)$data['quantity'] . "'";
		}
		if(!empty($find_data['cell_operations']['minimum_quantity'])) {
			$implode[] = " minimum = '" . (int)$data['minimum_quantity'] . "'";
		}
		if(!empty($find_data['cell_operations']['subtract'])) {
			$implode[] = " subtract = '" . (int)$data['subtract'] . "'";
		}
		if(!empty($find_data['cell_operations']['stock_status_id'])) {
			$implode[] = " stock_status_id = '" . (int)$data['stock_status_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['date_available'])) {
			$implode[] = " date_available = '" . $this->db->escape($data['date_available']) . "'";
		}
		
		if(!empty($find_data['cell_operations']['manufacturer_id'])) {
			if(!empty($data['manufacturer_id'])) {
				$implode[] = " manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
			} else if(!empty($data['manufacturer'])) {
				$manufacturer_info = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($data['manufacturer']) . "'")->row;

				if($manufacturer_info) {
					$implode[] = " manufacturer_id = '" . (int)$manufacturer_info['manufacturer_id'] . "'";
				}

			}
		}

		if(!empty($find_data['cell_operations']['shipping_required'])) {
			$implode[] = " shipping = '" . (int)$data['shipping_required'] . "'";
		}
		if(!empty($find_data['cell_operations']['price'])) {
			$implode[] = " price = '" . (float)str_replace(',', '', $data['price']) . "'";
		}
		if(!empty($find_data['cell_operations']['points'])) {
			$implode[] = " points = '" . (int)$data['points'] . "'";
		}
		if(!empty($find_data['cell_operations']['weight'])) {
			$implode[] = " weight = '" . (float)str_replace(',', '', $data['weight']) . "'";
		}
		if(!empty($find_data['cell_operations']['weight_class_id'])) {
			$implode[] = " weight_class_id = '" . (int)$data['weight_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['length'])) {
			$implode[] = " length = '" . (float)str_replace(',', '', $data['length']) . "'";
		}
		if(!empty($find_data['cell_operations']['width'])) {
			$implode[] = " width = '" . (float)str_replace(',', '', $data['width']) . "'";
		}
		if(!empty($find_data['cell_operations']['height'])) {
			$implode[] = " height = '" . (float)str_replace(',', '', $data['height']) . "'";
		}
		if(!empty($find_data['cell_operations']['length_class_id'])) {
			$implode[] = " length_class_id = '" . (int)$data['length_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['status'])) {
			$implode[] = " status = '" . (int)$data['status'] . "'";
		}
		if(!empty($find_data['cell_operations']['tax_class_id'])) {
			$implode[] = " tax_class_id = '" . (int)$data['tax_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['sort_order'])) {
			$implode[] = " sort_order = '" . (int)$data['sort_order'] . "'";
		}
		if(!empty($find_data['cell_operations']['viewed'])) {
			$implode[] = " viewed = '". $this->db->escape($data['viewed']) ."'";
		}
		if(!empty($find_data['cell_operations']['date_added'])) {
			$implode[] = " date_added = '". $this->db->escape($data['date_added']) ."'";
		}
		if(!empty($find_data['cell_operations']['date_modified'])) {
			$implode[] = " date_modified = '". $this->db->escape($data['date_modified']) ."'";
		}
		
		if(!empty($data['product_id'])) {
			$implode[] = " product_id = '" . (int)$data['product_id'] . "'";
		}
		
		if(!$implode) {
			$implode[] = " date_modified = NOW() ";
		}

		if($implode) {			
			$sql .= implode(', ', $implode);
		}

		$this->db->query($sql);
		$product_id = $this->db->getLastId();

		// Product Description
		$sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$find_data['language_id']. "'";

		$implode = array();
		if(!empty($find_data['cell_operations']['product_name'])) {
			$implode[] = " name = '" . $this->db->escape(($data['product_name'])) . "'";
		}

		if(!empty($find_data['cell_operations']['description'])) {
			$implode[] = " description = '" . $this->db->escape(($data['description'])) . "'";
		}

		if(!empty($find_data['cell_operations']['meta_tag'])) {
			$implode[] = " tag = '" . $this->db->escape(($data['meta_tag'])) . "'";
		}
		
		if(!empty($find_data['cell_operations']['meta_title'])) {
			$implode[] = " meta_title = '" . $this->db->escape(($data['meta_title'])) . "'";
		}

		if(!empty($find_data['cell_operations']['meta_description'])) {
			$implode[] = " meta_description = '" . $this->db->escape(($data['meta_description'])) . "'";
		}
		if(!empty($find_data['cell_operations']['meta_keyword'])) {
			$implode[] = " meta_keyword = '" . $this->db->escape(($data['meta_keyword'])) . "'";
		}

		if($implode) {
			$sql .= ", ";
		}

		$sql .= implode(', ', $implode);
		$this->db->query($sql);
		
		// Product Keyword
		if (!empty($data['seo_keyword']) && !empty($find_data['cell_operations']['seo_keyword'])) {
			$data['seo_keyword'] = $this->seoUrl($data['seo_keyword']);
			
			$i = 0;
			$this->load->model('catalog/url_alias');
			$seo_keyword = $data['seo_keyword'];
			do {
				$exists = false;
				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias(($data['seo_keyword']));
				if(!empty($url_alias_info)) {
					$data['seo_keyword'] = $seo_keyword.'-'. $i;
					
					$exists = true;
					$i++;
				}
			} while($exists);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape(($data['seo_keyword'])) . "'");
		}
		
		// Product Store
		if (isset($find_data['store']) && !empty($find_data['cell_operations']['store'])) {
			foreach ($find_data['store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}		
		
		// Product Attribute
		if (!empty($data['attribute']) && !empty($find_data['cell_operations']['store'])) {
			foreach ($data['attribute'] as $product_attribute) {
				$product_attribute = (explode('::', $product_attribute)); 
				$product_attribute = array_map('trim', $product_attribute);
				
				if(count($product_attribute) == 3) {
					// $attribute_info = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name = '" . $this->db->escape(($product_attribute[0])) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
					$attribute_info = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name = '" . $this->db->escape(($product_attribute[0])) . "'")->row;
					if (!empty($attribute_info)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_info['attribute_id'] . "', language_id = '" . (int)$find_data['language_id'] . "', text = '" .  $this->db->escape($product_attribute[2]) . "'");
					}
				}
			}
		}
		
		// Product Discount
		if (!empty($data['discount']) && !empty($find_data['cell_operations']['discount'])) {
			foreach ($data['discount'] as $product_discount) {
				$product_discount = (explode('::', $product_discount)); 
				$product_discount = array_map('trim', $product_discount);
				
				if(count($product_discount) == 6) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount[0] . "', quantity = '" . (int)$product_discount[1] . "', priority = '" . (int)$product_discount[2] . "', price = '" . (float)str_replace(',', '', $product_discount[3]) . "', date_start = '" . $this->db->escape($product_discount[4]) . "', date_end = '" . $this->db->escape($product_discount[5]) . "'");
				}
			}
		}
		
		// Product Special
		if (!empty($data['special']) && !empty($find_data['cell_operations']['special'])) {
			foreach ($data['special'] as $product_special) {
				$product_special = (explode('::', $product_special)); 
				$product_special = array_map('trim', $product_special);
				
				if(count($product_special) == 5) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special[0] . "', priority = '" . (int)$product_special[1] . "', price = '" . (float)str_replace(',', '', $product_special[2]) . "', date_start = '" . $this->db->escape($product_special[3]) . "', date_end = '" . $this->db->escape($product_special[4]) . "'");
				}
			}
		}
		
		
		// Product Image
		if(!empty($find_data['images'])) {
			if (!empty($data['additional_images'])) {
				$product_image_sort_order = 0;
				foreach ($data['additional_images'] as $product_image) {
					$product_image = trim($product_image);
					if($product_image) {
						if((substr($product_image, 0, 7) == "http://" || substr($product_image, 0, 8) == "https://") && $this->file_contents_exist($product_image)) {
							
							$imageString = file_get_contents($product_image);
							
							$dir_name = 'catalog/storeimages/';
							$folder_name = DIR_IMAGE. $dir_name;
							
							$this->makeDir($folder_name);

							$filename = basename(html_entity_decode($product_image, ENT_QUOTES, 'UTF-8'));

							$filename = str_replace(array(' ', '&nbsp;', '%20'), '_', $filename);

							if(file_exists($folder_name . $filename)) {
								$pathinfo_file = pathinfo($folder_name . $filename);
								if($pathinfo_file) {
									$final_file = $pathinfo_file['filename'] .'_'. time().rand(0,1000) .'.'.$pathinfo_file['extension'];
								} else{
									$final_file = $filename;
								}
							} else{
								$final_file = $filename;
							}

							$save = file_put_contents($folder_name . $final_file, $imageString);
							$product_image = $dir_name . $final_file;
						}
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image) . "', sort_order = '" . (int)$product_image_sort_order . "'");
					}
					
					$product_image_sort_order++; 
				}
			}
		}
		
		// Product Download
		if (!empty($data['download']) && !empty($find_data['cell_operations']['download'])) {
			foreach ($data['download'] as $product_download) {
				$product_download = trim($product_download);
				if($product_download) {
					// $download_info = $this->db->query("SELECT download_id FROM " . DB_PREFIX . "download_description WHERE name = '" . $this->db->escape(($product_download)) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
					$download_info = $this->db->query("SELECT download_id FROM " . DB_PREFIX . "download_description WHERE name = '" . $this->db->escape(($product_download)) . "'")->row;
					if($download_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_info['download_id'] . "'");
					}
				}
			}
		}
		
		// Product Category
		if(!empty($find_data['cell_operations']['category_ids'])) {
			if (!empty($data['category_ids'])) {
				foreach ($data['category_ids'] as $category_id) {
					$category_id = trim($category_id);
					if($category_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
					}
				}
			} else if (!empty($data['category_name'])) {
				foreach ($data['category_name'] as $category_name) {
					$category_name = explode('&gt;', $category_name);
					$category_name = end($category_name);
					$category_name = trim(str_replace('&nbsp;',' ', htmlentities($category_name)));
					
					$category_info = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape($category_name) . "' AND language_id = '" . (int)$find_data['language_id'] . "'")->row;

					if($category_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_info['category_id'] . "'");
					}
				}				
			}
		}
		
		// Product Filter		
		if (!empty($data['filter']) && !empty($find_data['cell_operations']['filter'])) {
			foreach ($data['filter'] as $filter) {
				$filter = trim($filter);
				if($filter) {
					$filter_data = (explode('-', $filter)); 
					$filter_data = array_map('trim', $filter_data);
					if(count($filter_data) == 2) {
						// $filter_group_info = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name = '" . $this->db->escape(($filter_data[0])) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
						$filter_group_info = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name = '" . $this->db->escape(($filter_data[0])) . "'")->row;
						if($filter_group_info) {
							// $filter_info = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name = '" . $this->db->escape(($filter_data[1])) . "' AND language_id = '". (int)$find_data['language_id'] ."' AND filter_group_id = '". (int)$filter_group_info['filter_group_id'] ."'")->row;
							$filter_info = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name = '" . $this->db->escape(($filter_data[1])) . "' AND filter_group_id = '". (int)$filter_group_info['filter_group_id'] ."'")->row;
							if($filter_info) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_info['filter_id'] . "'");
							}
						}
					}
				}
			}
		}
		
		// Product Related		
		if (!empty($data['related_products']) && !empty($find_data['cell_operations']['related_products'])) {
			foreach ($data['related_products'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
			}
		}
		
		// Product Options		
		if (!empty($data['options']) && !empty($find_data['cell_operations']['options'])) {
			foreach ($data['options'] as $product_option) {
				$product_option = (explode('::', $product_option)); 
				$product_option = array_map('trim', $product_option);
				if(count($product_option) >= 3) {
					$option_info = $this->db->query("SELECT o.option_id FROM " . DB_PREFIX . "option o left JOIN ". DB_PREFIX ."option_description od ON (o.option_id=od.option_id) WHERE od.name = '" . $this->db->escape(($product_option[0])) . "' AND o.type = '" . $this->db->escape($product_option[1]) . "'")->row;
					
					if($option_info) {
						if ($product_option[1] == 'select' || $product_option[1] == 'radio' || $product_option[1] == 'checkbox' || $product_option[1] == 'image') {
							if (isset($product_option[3])) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', required = '" . (isset($product_option[2]) ? (int)$product_option[2] : '') . "'");

								$product_option_id = $this->db->getLastId();

								$product_option_values = (explode('||', $product_option[3])); 
								$product_option_values = array_map('trim', $product_option_values);
								
								foreach ($product_option_values as $product_option_value) {
									$product_option_value = (explode('^^', $product_option_value)); 
									$product_option_value = array_map('trim', $product_option_value);

									$excel_option_value_name = (isset($product_option_value[0]) ? $product_option_value[0] : '');
									if($excel_option_value_name) {
										$fetch_option_value_description = $this->db->query("SELECT option_value_id, name FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_info['option_id'] . "' AND name = '" . $this->db->escape($excel_option_value_name) . "' AND language_id = '" . (int)$find_data['language_id'] . "'")->row;
									} else{
										$fetch_option_value_description = array();
									}
									
									$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', option_value_id = '" . (isset($fetch_option_value_description['option_value_id']) ? (int)$fetch_option_value_description['option_value_id'] : '') . "', quantity = '" . (isset($product_option_value[1]) ? (int)$product_option_value[1] : '') . "', subtract = '" . (isset($product_option_value[2]) ? (int)$product_option_value[2] : '') . "', price = '" . (isset($product_option_value[3]) ? (float)str_replace(',', '', $product_option_value[3]) : '') . "', price_prefix = '" . (isset($product_option_value[4]) ? $this->db->escape($product_option_value[4]) : '') . "', points = '" . (isset($product_option_value[5]) ? (int)$product_option_value[5] : '') . "', points_prefix = '" . (isset($product_option_value[6]) ? $this->db->escape($product_option_value[6]) : '') . "', weight = '" . (isset($product_option_value[7]) ? (float)str_replace(',', '', $product_option_value[7]) : '') . "', weight_prefix = '" . (isset($product_option_value[8]) ? $this->db->escape($product_option_value[8]) : '') . "'");
								}
							}
						}else {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', value = '" . (isset($product_option[3]) ? $this->db->escape($product_option[3]) : '') . "', required = '" . (isset($product_option[2]) ? (int)$product_option[2] : '') . "'");
						}
					}
				}
			}
		}
		
		// Product Reward
		if (!empty($data['reward']) && count($data['reward']) == 2 && !empty($find_data['cell_operations']['reward'])) {
			if ((int)$data['reward'][1] > 0) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$data['reward'][0] . "', points = '" . (int)$data['reward'][1] . "'");
			}
		}
		
		// Product Reviews
		if(!empty($find_data['review'])) {
			if (!empty($data['reviews'])) {
				foreach ($data['reviews'] as $review) { 
					$review = (explode('::', $review)); 
					$review = array_map('trim', $review);
					if (count($review) >= 5) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "review SET product_id = '" . (int)$product_id . "', customer_id = '" . (int)$review[0] . "', author = '" . $this->db->escape($review[1]) . "', text = '" . $this->db->escape($review[2]) . "', rating = '" . (int)$review[3] . "', status = '" . (int)$review[4] . "', date_added = '" . $this->db->escape($review[5]) . "', date_modified = '" . (isset($review[6]) ? $this->db->escape($review[6]) : '') . "'");
					}
				}
			}
		}
		
		// Product Custom Fields
		if (!empty($data['custom_fields_data']) && !empty($find_data['custom_fields'])) {
			foreach ($data['custom_fields_data'] as $custom_fields_data) {
				if(count($custom_fields_data) >= 2) {
					if($custom_fields_data['table_name'] == 'product') {
						$this->db->query("UPDATE " . DB_PREFIX . $custom_fields_data['table_name'] ." SET ". $custom_fields_data['field_name'] ." = '" . (isset($custom_fields_data['value']) ? $this->db->escape(($custom_fields_data['value'])) : '') . "' WHERE product_id = '" . (int)$product_id . "'");
					}else if($custom_fields_data['table_name'] == 'product_description') {
						$this->db->query("UPDATE " . DB_PREFIX . $custom_fields_data['table_name'] ." SET ". $custom_fields_data['field_name'] ." = '" . (isset($custom_fields_data['value']) ? $this->db->escape(($custom_fields_data['value'])) : '') . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$find_data['language_id']. "'");
					}
				}
			}
		}
	}
	
	public function editProduct($product_id, $data, $find_data) {
		$sql = "UPDATE " . DB_PREFIX . "product SET product_id = '". (int)$product_id ."'";

		$implode = array();

		if(!empty($find_data['cell_operations']['model'])) {
			$implode[] = " model = '" . $this->db->escape($data['model']) . "'";
		}

		if(!empty($find_data['cell_operations']['sku'])) {
			$implode[] = " sku = '" . $this->db->escape($data['sku']) . "'";
		}

		if(!empty($find_data['cell_operations']['upc'])) {
			$implode[] = " upc = '" . $this->db->escape($data['upc']) . "'";
		}
		if(!empty($find_data['cell_operations']['ean'])) {
			$implode[] = " ean = '" . $this->db->escape($data['ean']) . "'";
		}
		if(!empty($find_data['cell_operations']['jan'])) {
			$implode[] = " jan = '" . $this->db->escape($data['jan']) . "'";
		}
		if(!empty($find_data['cell_operations']['isbn'])) {
			$implode[] = " isbn = '" . $this->db->escape($data['isbn']) . "'";
		}
		if(!empty($find_data['cell_operations']['mpn'])) {
			$implode[] = " mpn = '" . $this->db->escape($data['mpn']) . "'";
		}
		if(!empty($find_data['cell_operations']['location'])) {
			$implode[] = " location = '" . $this->db->escape($data['location']) . "'";
		}
		if(!empty($find_data['cell_operations']['product_image'])) {
			$implode[] = " image = '" . $this->db->escape(($data['product_image'])) . "'";
		}
		if(!empty($find_data['cell_operations']['quantity'])) {
			$implode[] = " quantity = '" . (int)$data['quantity'] . "'";
		}
		if(!empty($find_data['cell_operations']['minimum_quantity'])) {
			$implode[] = " minimum = '" . (int)$data['minimum_quantity'] . "'";
		}
		if(!empty($find_data['cell_operations']['subtract'])) {
			$implode[] = " subtract = '" . (int)$data['subtract'] . "'";
		}
		if(!empty($find_data['cell_operations']['stock_status_id'])) {
			$implode[] = " stock_status_id = '" . (int)$data['stock_status_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['date_available'])) {
			$implode[] = " date_available = '" . $this->db->escape($data['date_available']) . "'";
		}

		if(!empty($find_data['cell_operations']['manufacturer_id'])) {
			if(!empty($data['manufacturer_id'])) {
				$implode[] = " manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
			} else if(!empty($data['manufacturer'])) {
				$manufacturer_info = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($data['manufacturer']) . "'")->row;

				if($manufacturer_info) {
					$implode[] = " manufacturer_id = '" . (int)$manufacturer_info['manufacturer_id'] . "'";
				}

			}
		}

		if(!empty($find_data['cell_operations']['shipping_required'])) {
			$implode[] = " shipping = '" . (int)$data['shipping_required'] . "'";
		}
		if(!empty($find_data['cell_operations']['price'])) {
			$implode[] = " price = '" . (float)str_replace(',', '', $data['price']) . "'";
		}
		if(!empty($find_data['cell_operations']['points'])) {
			$implode[] = " points = '" . (int)$data['points'] . "'";
		}
		if(!empty($find_data['cell_operations']['weight'])) {
			$implode[] = " weight = '" . (float)str_replace(',', '', $data['weight']) . "'";
		}
		if(!empty($find_data['cell_operations']['weight_class_id'])) {
			$implode[] = " weight_class_id = '" . (int)$data['weight_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['length'])) {
			$implode[] = " length = '" . (float)str_replace(',', '', $data['length']) . "'";
		}
		if(!empty($find_data['cell_operations']['width'])) {
			$implode[] = " width = '" . (float)str_replace(',', '', $data['width']) . "'";
		}
		if(!empty($find_data['cell_operations']['height'])) {
			$implode[] = " height = '" . (float)str_replace(',', '', $data['height']) . "'";
		}
		if(!empty($find_data['cell_operations']['length_class_id'])) {
			$implode[] = " length_class_id = '" . (int)$data['length_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['status'])) {
			$implode[] = " status = '" . (int)$data['status'] . "'";
		}
		if(!empty($find_data['cell_operations']['tax_class_id'])) {
			$implode[] = " tax_class_id = '" . (int)$data['tax_class_id'] . "'";
		}
		if(!empty($find_data['cell_operations']['sort_order'])) {
			$implode[] = " sort_order = '" . (int)$data['sort_order'] . "'";
		}
		if(!empty($find_data['cell_operations']['viewed'])) {
			$implode[] = " viewed = '". $this->db->escape($data['viewed']) ."'";
		}
		if(!empty($find_data['cell_operations']['date_added'])) {
			$implode[] = " date_added = '". $this->db->escape($data['date_added']) ."'";
		}
		if(!empty($find_data['cell_operations']['date_modified'])) {
			$implode[] = " date_modified = '". $this->db->escape($data['date_modified']) ."'";
		}

		if($implode) {
			$sql .= ", ";
		}

		$sql .= implode(', ', $implode);

	 	$sql .= " WHERE product_id = '". (int)$product_id ."'";
		
		$this->db->query($sql);
		
		
		// Product Description
		$product_description_query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$find_data['language_id'] . "'");
		
		if($product_description_query->row['total'] > 0) {
			// Product Description
			$sql = "UPDATE " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$find_data['language_id']. "'";

			$implode = array();
			if(!empty($find_data['cell_operations']['product_name'])) {
				$implode[] = " name = '" . $this->db->escape(($data['product_name'])) . "'";
			}

			if(!empty($find_data['cell_operations']['description'])) {
				$implode[] = " description = '" . $this->db->escape(($data['description'])) . "'";
			}

			if(!empty($find_data['cell_operations']['meta_tag'])) {
				$implode[] = " tag = '" . $this->db->escape(($data['meta_tag'])) . "'";
			}
			
			if(!empty($find_data['cell_operations']['meta_title'])) {
				$implode[] = " meta_title = '" . $this->db->escape(($data['meta_title'])) . "'";
			}

			if(!empty($find_data['cell_operations']['meta_description'])) {
				$implode[] = " meta_description = '" . $this->db->escape(($data['meta_description'])) . "'";
			}
			if(!empty($find_data['cell_operations']['meta_keyword'])) {
				$implode[] = " meta_keyword = '" . $this->db->escape(($data['meta_keyword'])) . "'";
			}

			if($implode) {
				$sql .= ", ";
			}

			$sql .= implode(', ', $implode);

			$sql .= " WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$find_data['language_id']. "'";

			$this->db->query($sql);
		} else {
			// Product Description
			$sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$find_data['language_id']. "'";

			$implode = array();
			if(!empty($find_data['cell_operations']['product_name'])) {
				$implode[] = " name = '" . $this->db->escape(($data['product_name'])) . "'";
			}

			if(!empty($find_data['cell_operations']['description'])) {
				$implode[] = " description = '" . $this->db->escape(($data['description'])) . "'";
			}

			if(!empty($find_data['cell_operations']['meta_tag'])) {
				$implode[] = " tag = '" . $this->db->escape(($data['meta_tag'])) . "'";
			}
			
			if(!empty($find_data['cell_operations']['meta_title'])) {
				$implode[] = " meta_title = '" . $this->db->escape(($data['meta_title'])) . "'";
			}

			if(!empty($find_data['cell_operations']['meta_description'])) {
				$implode[] = " meta_description = '" . $this->db->escape(($data['meta_description'])) . "'";
			}
			if(!empty($find_data['cell_operations']['meta_keyword'])) {
				$implode[] = " meta_keyword = '" . $this->db->escape(($data['meta_keyword'])) . "'";
			}

			if($implode) {
				$sql .= ", ";
			}

			$sql .= implode(', ', $implode);
			$this->db->query($sql);
		}

		// Product Keyword
		if(!empty($find_data['cell_operations']['seo_keyword'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
			
			if (!empty($data['seo_keyword'])) {
				$data['seo_keyword'] = $this->seoUrl($data['seo_keyword']);
				
				$i = 0;
				$this->load->model('catalog/url_alias');
				$seo_keyword = $data['seo_keyword'];
				do {
					$exists = false;
					$url_alias_info = $this->model_catalog_url_alias->getUrlAlias(($data['seo_keyword']));
					if (!empty($url_alias_info) && $url_alias_info['query'] != 'product_id=' . $product_id) {
						$data['seo_keyword'] = $seo_keyword.'-'. $i;
						
						$exists = true;
						$i++;
					}
				} while($exists);
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape(($data['seo_keyword'])) . "'");
			}
		}
		
		if(!empty($find_data['cell_operations']['store'])) {
			// Product Store
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

			if (isset($find_data['store'])) {
				foreach ($find_data['store'] as $store_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		}
		
		// Product Attribute
		if(!empty($find_data['cell_operations']['attribute'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$find_data['language_id'] . "'");
			
			if (!empty($data['attribute'])) {
				foreach ($data['attribute'] as $product_attribute) {
					$product_attribute = (explode('::', $product_attribute)); 
					$product_attribute = array_map('trim', $product_attribute);
					
					if(count($product_attribute) == 3) {
						// $attribute_info = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name = '" . $this->db->escape(($product_attribute[0])) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
						$attribute_info = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name = '" . $this->db->escape(($product_attribute[0])) . "'")->row;
						if (!empty($attribute_info)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_info['attribute_id'] . "', language_id = '" . (int)$find_data['language_id'] . "', text = '" .  $this->db->escape($product_attribute[2]) . "'");
						}
					}
				}
			}
		}
		
		// Product Discount
		if(!empty($find_data['cell_operations']['discount'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['discount'])) {
				foreach ($data['discount'] as $product_discount) {
					$product_discount = (explode('::', $product_discount)); 
					$product_discount = array_map('trim', $product_discount);
					
					if(count($product_discount) == 6) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount[0] . "', quantity = '" . (int)$product_discount[1] . "', priority = '" . (int)$product_discount[2] . "', price = '" . (float)str_replace(',', '', $product_discount[3]) . "', date_start = '" . $this->db->escape($product_discount[4]) . "', date_end = '" . $this->db->escape($product_discount[5]) . "'");
					}
				}
			}
		}
		
		if(!empty($find_data['cell_operations']['special'])) {
			// Product Special
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['special'])) {
				foreach ($data['special'] as $product_special) {
					$product_special = (explode('::', $product_special)); 
					$product_special = array_map('trim', $product_special);
					
					if(count($product_special) == 5) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special[0] . "', priority = '" . (int)$product_special[1] . "', price = '" . (float)str_replace(',', '', $product_special[2]) . "', date_start = '" . $this->db->escape($product_special[3]) . "', date_end = '" . $this->db->escape($product_special[4]) . "'");
					}
				}
			}
		}
		
		if(!empty($find_data['cell_operations']['product_image'])) {
			// Product Image
			if(!empty($find_data['images'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
				
				if (!empty($data['additional_images'])) {
					$product_image_sort_order = 0;
					foreach ($data['additional_images'] as $product_image) {
						$product_image = trim($product_image);
						if($product_image) {
							if((substr($product_image, 0, 7) == "http://" || substr($product_image, 0, 8) == "https://") && $this->file_contents_exist($product_image)) {
								
								$imageString = file_get_contents($product_image);
								
								$dir_name = 'catalog/storeimages/';
								$folder_name = DIR_IMAGE. $dir_name;
								
								$this->makeDir($folder_name);
						
								$filename = basename(html_entity_decode($product_image, ENT_QUOTES, 'UTF-8'));

								$filename = str_replace(array(' ', '&nbsp;', '%20'), '_', $filename);

								if(file_exists($folder_name . $filename)) {
									$pathinfo_file = pathinfo($folder_name . $filename);
									if($pathinfo_file) {
										$final_file = $pathinfo_file['filename'] .'_'. time().rand(0,1000) .'.'.$pathinfo_file['extension'];
									} else{
										$final_file = $filename;
									}
								} else{
									$final_file = $filename;
								}

								$save = file_put_contents($folder_name . $final_file, $imageString);
								$product_image = $dir_name . $final_file;
							}

							$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image) . "', sort_order = '" . (int)$product_image_sort_order . "'");
						}
						
						$product_image_sort_order++; 
					}
				}
			}
		}

		// Product Download
		if(!empty($find_data['cell_operations']['download'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['download'])) {
				foreach ($data['download'] as $product_download) {
					$product_download = trim($product_download);
					if($product_download) {
						// $download_info = $this->db->query("SELECT download_id FROM " . DB_PREFIX . "download_description WHERE name = '" . $this->db->escape(($product_download)) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
						$download_info = $this->db->query("SELECT download_id FROM " . DB_PREFIX . "download_description WHERE name = '" . $this->db->escape(($product_download)) . "'")->row;
						if($download_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_info['download_id'] . "'");
						}
					}
				}
			}
		}
		
		if(!empty($find_data['cell_operations']['category_ids'])) {
			// Product Category
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['category_ids'])) {
				foreach ($data['category_ids'] as $category_id) {
					$category_id = trim($category_id);
					if($category_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
					}
				}
			} else if (!empty($data['category_name'])) {
				foreach ($data['category_name'] as $category_name) {
					$category_name = explode('&gt;', $category_name);
					$category_name = end($category_name);
					$category_name = trim(str_replace('&nbsp;',' ', htmlentities($category_name)));
					
					$category_info = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape($category_name) . "' AND language_id = '" . (int)$find_data['language_id'] . "'")->row;

					if($category_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_info['category_id'] . "'");
					}
				}				
			}
		}
		
		if(!empty($find_data['cell_operations']['filter'])) {
			// Product Filter
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['filter'])) {
				foreach ($data['filter'] as $filter) {
					$filter = trim($filter);
					if($filter) {
						$filter_data = (explode('-', $filter)); 
						$filter_data = array_map('trim', $filter_data);
						if(count($filter_data) == 2) {
							// $filter_group_info = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name = '" . $this->db->escape(($filter_data[0])) . "' AND language_id = '". (int)$find_data['language_id'] ."'")->row;
							$filter_group_info = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name = '" . $this->db->escape(($filter_data[0])) . "'")->row;
							if($filter_group_info) {
								// $filter_info = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name = '" . $this->db->escape(($filter_data[1])) . "' AND language_id = '". (int)$find_data['language_id'] ."' AND filter_group_id = '". (int)$filter_group_info['filter_group_id'] ."'")->row;
								$filter_info = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name = '" . $this->db->escape(($filter_data[1])) . "' AND filter_group_id = '". (int)$filter_group_info['filter_group_id'] ."'")->row;
								if($filter_info) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_info['filter_id'] . "'");
								}
							}
						}
					}
				}
			}
		}

		if(!empty($find_data['cell_operations']['related_products'])) {
			// Product Related
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['related_products'])) {
				foreach ($data['related_products'] as $related_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				}
			}
		}
		
		if(!empty($find_data['cell_operations']['options'])) {
			// Product Options
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['options'])) {
				foreach ($data['options'] as $product_option) {
					$product_option = (explode('::', $product_option)); 
					$product_option = array_map('trim', $product_option);
					if(count($product_option) >= 3) {
						$option_info = $this->db->query("SELECT o.option_id FROM " . DB_PREFIX . "option o left JOIN ". DB_PREFIX ."option_description od ON (o.option_id=od.option_id) WHERE od.name = '" . $this->db->escape(($product_option[0])) . "' AND o.type = '" . $this->db->escape($product_option[1]) . "'")->row;
						
						if($option_info) {
							if ($product_option[1] == 'select' || $product_option[1] == 'radio' || $product_option[1] == 'checkbox' || $product_option[1] == 'image') {
								if (isset($product_option[3])) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', required = '" . (isset($product_option[2]) ? (int)$product_option[2] : '') . "'");

									$product_option_id = $this->db->getLastId();

									$product_option_values = (explode('||', $product_option[3])); 
									$product_option_values = array_map('trim', $product_option_values);
									
									foreach ($product_option_values as $product_option_value) {
										$product_option_value = (explode('^^', $product_option_value)); 
										$product_option_value = array_map('trim', $product_option_value);	

										$excel_option_value_name = (isset($product_option_value[0]) ? $product_option_value[0] : '');
										if($excel_option_value_name) {
											$fetch_option_value_description = $this->db->query("SELECT option_value_id, name FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_info['option_id'] . "' AND name = '" . $this->db->escape($excel_option_value_name) . "' AND language_id = '" . (int)$find_data['language_id'] . "'")->row;
										} else{
											$fetch_option_value_description = array();
										}
										
										$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', option_value_id = '" . (isset($fetch_option_value_description['option_value_id']) ? (int)$fetch_option_value_description['option_value_id'] : '') . "', quantity = '" . (isset($product_option_value[1]) ? (int)$product_option_value[1] : '') . "', subtract = '" . (isset($product_option_value[2]) ? (int)$product_option_value[2] : '') . "', price = '" . (isset($product_option_value[3]) ? (float)str_replace(',', '', $product_option_value[3]) : '') . "', price_prefix = '" . (isset($product_option_value[4]) ? $this->db->escape($product_option_value[4]) : '') . "', points = '" . (isset($product_option_value[5]) ? (int)$product_option_value[5] : '') . "', points_prefix = '" . (isset($product_option_value[6]) ? $this->db->escape($product_option_value[6]) : '') . "', weight = '" . (isset($product_option_value[7]) ? (float)str_replace(',', '', $product_option_value[7]) : '') . "', weight_prefix = '" . (isset($product_option_value[8]) ? $this->db->escape($product_option_value[8]) : '') . "'");
									}
								}
							}else {
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_info['option_id'] . "', value = '" . (isset($product_option[3]) ? $this->db->escape($product_option[3]) : '') . "', required = '" . (isset($product_option[2]) ? (int)$product_option[2] : '') . "'");
							}
						}
					}
				}
			}
		}
		
		if(!empty($find_data['cell_operations']['reward'])) {
			// Product Reward
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

			if (!empty($data['reward']) && count($data['reward']) == 2) {
				if ((int)$data['reward'][1] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$data['reward'][0] . "', points = '" . (int)$data['reward'][1] . "'");
				}
			}
		}
		
		// Product Reviews
		if(!empty($find_data['review'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['reviews'])) {
				foreach ($data['reviews'] as $review) { 
					$review = (explode('::', $review)); 
					$review = array_map('trim', $review);
					if (count($review) >= 5) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "review SET product_id = '" . (int)$product_id . "', customer_id = '" . (int)$review[0] . "', author = '" . $this->db->escape($review[1]) . "', text = '" . $this->db->escape($review[2]) . "', rating = '" . (int)$review[3] . "', status = '" . (int)$review[4] . "', date_added = '" . $this->db->escape($review[5]) . "', date_modified = '" . (isset($review[6]) ? $this->db->escape($review[6]) : '') . "'");
					}
				}
			}
		}
		
		// Product Custom Fields
		if (!empty($data['custom_fields_data']) && !empty($find_data['custom_fields'])) {
			foreach ($data['custom_fields_data'] as $custom_fields_data) {
				if(count($custom_fields_data) >= 2) {
					if($custom_fields_data['table_name'] == 'product') {
						$this->db->query("UPDATE " . DB_PREFIX . $custom_fields_data['table_name'] ." SET ". $custom_fields_data['field_name'] ." = '" . (isset($custom_fields_data['value']) ? $this->db->escape(($custom_fields_data['value'])) : '') . "' WHERE product_id = '" . (int)$product_id . "'");
					}else if($custom_fields_data['table_name'] == 'product_description') {
						$this->db->query("UPDATE " . DB_PREFIX . $custom_fields_data['table_name'] ." SET ". $custom_fields_data['field_name'] ." = '" . (isset($custom_fields_data['value']) ? $this->db->escape(($custom_fields_data['value'])) : '') . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$find_data['language_id']. "'");
					}
				}
			}
		}
	}
	
	public function getColumns($table_name, $field_name) {
		return $this->db->query("SHOW COLUMNS FROM ". DB_PREFIX . $table_name . " WHERE `Field` = '". $this->db->escape($field_name) ."'")->row;
	}
	
	public function seoUrl($string) {

    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    /*$string = preg_replace("/[^a-z0-9\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);*/
    
    return $string;
	}

	public function makeDir($dir,$permission=0777) {
		if(!is_dir($dir)) {
			$oldmask = umask(0);
			mkdir($dir, $permission);
			umask($oldmask);
		}
	}

	public function file_contents_exist($url, $response_code = 200) {
		  $headers = get_headers($url);
	    if (substr($headers[0], 9, 3) == $response_code)
	    {
	    	  return true;
	    }
	    else
	    {
	        return false;
	    }
	}

	public function preg_trim($subject) {

    	$regex = "/\s*(\.*)\s*/s";
    	if (preg_match ($regex, $subject, $matches)) {
	        $subject = $matches[1];
	    }

	    echo $subject; die();
	    return $subject;
	}
}