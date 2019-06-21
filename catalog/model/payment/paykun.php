<?php 
class ModelPaymentpaykun extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/paykun');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paykun_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('paykun_total') > 0 && $this->config->get('paykun_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('paykun_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// uncomment below line if you do not want customer to show paykun when currency is not INR
		// $status = ($this->currency->getCode() != 'INR') ? false : $status;

		$method_data = array();
		if ($status) {  
			$method_data = array( 
				'code'       => 'paykun',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('paykun_sort_order')
			);
		}
    	return $method_data;
  	}

}
?>