<?php
class ControllerPaymentpaykun extends Controller {

    private $error 					= array();
    private $save_paykun_response 	= true; /* save paykun response in db */
    private $max_retry_count 		= 3; /* number of retries untill cURL gets success */

    /**
     * create `paykun_order_data` table and install this module.
     */
    public function install() {
        $this->load->model('payment/paykun');
        $this->model_payment_paykun->install();
    }
    /**
     * drop `paykun_order_data` table and uninstall this module.
     */
    public function uninstall() {
        $this->load->model('payment/paykun');
        $this->model_payment_paykun->uninstall();
    }
    /**
     * get Default callback url
     */
    private function getCallbackUrl(){
        $callback_url = "index.php?route=payment/paykun/callback";
        return (!empty($_SERVER['HTTPS']))? HTTPS_CATALOG . $callback_url : HTTP_CATALOG . $callback_url;
    }

    public function index() {

        $this->language->load('payment/paykun');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('paykun', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            // if(!$this->validateCurl($this->request->post['paykun_transaction_status_url'])){
            // 	$this->session->data['warning'] = $this->language->get('error_curl_warning');
            // 	$this->redirect($this->url->link('payment/paykun', 'token=' . $this->session->data['token'], 'SSL'));
            // }

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] 				= $this->language->get('heading_title');
        $this->data['text_enabled'] 				= $this->language->get('text_enabled');
        $this->data['text_disabled'] 				= $this->language->get('text_disabled');
        $this->data['text_all_zones'] 			= $this->language->get('text_all_zones');


        $this->data['text_opencart_version'] 	= $this->language->get('text_opencart_version');
        $this->data['text_curl_version'] 		= $this->language->get('text_curl_version');
        $this->data['text_php_version'] 			= $this->language->get('text_php_version');
        $this->data['text_last_updated'] 		= $this->language->get('text_last_updated');
        $this->data['text_curl_disabled'] 		= $this->language->get('text_curl_disabled');
        $this->data['tab_general']				=$this->language->get('tab_general');
        $this->data['paykun_status']			= $this->language->get('paykun_status');

        $this->data['paykun_merchant_id'] 		= $this->language->get('paykun_merchant_id');
        $this->data['entry_access_token_help'] 		= $this->language->get('entry_access_token_help');
        $this->data['entry_enc_key_help'] 				= $this->language->get('entry_enc_key_help');
        $this->data['entry_order_success_status_help'] 		= $this->language->get('entry_order_success_status_help');
        $this->data['entry_order_failed_status_help'] 	= $this->language->get('entry_order_failed_status_help');
        $this->data['entry_log_status_help'] = $this->language->get('entry_log_status_help');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_merchant_id'] = "Merchant ID";
        $this->data['entry_access_token'] = "Access Token";
        $this->data['entry_merchant_id_help'] = "Enter your Merchant Id";
        $this->data['entry_enc_key'] = "Encryption Key";
        $this->data['entry_order_success_status'] = "success status";
        $this->data['entry_order_failed_status'] = "failed status";
        //$this->data['footer_text']="footer text";
        $this->data['entry_log_status'] = "log status";


        $this->data['button_save'] 				= $this->language->get('button_save');
        $this->data['button_cancel'] 				= $this->language->get('button_cancel');

        if (isset($this->session->data['warning'])) {
            $this->data['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        } else {
            $this->data['warning'] = '';
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['merchant_id'])) {
            $this->data['error_merchant_id'] = $this->error['merchant_id'];
        } else {
            $this->data['error_merchant_id'] = '';
        }
        if (isset($this->error['access_token'])) {
            $this->data['error_access_token'] = $this->error['access_token'];
        } else {
            $this->data['error_access_token'] = '';
        }
        if (isset($this->error['encryption_key'])) {
            $this->data['error_enc_key'] = $this->error['encryption_key'];
        } else {
            $this->data['error_enc_key'] = '';
        }

        if (isset($this->error['order_success_status'])) {
            $this->data['order_statuses'] = $this->error['order_success_status'];
        } else {
            $this->data['paykun_order_success_status_id'] = '';
        }

        if (isset($this->error['order_failed_status'])) {
            $this->data['paykun_order_failed_status_id'] = $this->error['order_failed_status'];
        } else {
            $this->data['error_transaction_url'] = '';
        }

        // if (isset($this->error['transaction_status_url'])) {
        // 	$this->data['error_transaction_status_url'] = $this->error['transaction_status_url'];
        // } else {
        // 	$this->data['error_transaction_status_url'] = '';
        // }

        // if (isset($this->error['callback_url_status'])) {
        // 	$this->data['error_callback_url_status'] = $this->error['callback_url_status'];
        // } else {
        // 	$this->data['error_callback_url_status'] = '';
        // }

        // if (isset($this->error['callback_url'])) {
        // 	$this->data['error_callback_url'] = $this->error['callback_url'];
        // } else {
        // 	$this->data['error_callback_url'] = '';
        // }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/paykun', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/paykun', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        // if (isset($this->request->post['paykun_merchant_id'])) {
        // 	$this->data['paykun_merchant_id'] = $this->request->post['paykun_merchant_id'];
        // } else {
        // 	$this->data['paykun_merchant_id'] = $this->config->get('paykun_merchant_id');
        // }

        if (isset($this->request->post['paykun_merchant_id'])) {
            $this->data['paykun_merchant_id'] = $this->request->post['paykun_merchant_id'];
        } else {
            $this->data['paykun_merchant_id'] = $this->config->get('paykun_merchant_id');
        }
        if (isset($this->request->post['paykun_access_token'])) {
            $this->data['paykun_access_token'] = $this->request->post['paykun_access_token'];
        } else {
            $this->data['paykun_access_token'] = $this->config->get('paykun_access_token');
        }
        if (isset($this->request->post['paykun_enc_key'])) {
            $this->data['paykun_enc_key'] = $this->request->post['paykun_enc_key'];
        } else {
            $this->data['paykun_enc_key'] = $this->config->get('paykun_enc_key');
        }

        // if (isset($this->request->post['paykun_website'])) {
        // 	$this->data['paykun_website'] = $this->request->post['paykun_website'];
        // } else {
        // 	$this->data['paykun_website'] = $this->config->get('paykun_website');
        // }

        // if (isset($this->request->post['paykun_industry_type'])) {
        // 	$this->data['paykun_industry_type'] = $this->request->post['paykun_industry_type'];
        // } else {
        // 	$this->data['paykun_industry_type'] = $this->config->get('paykun_industry_type');
        // }

        if (isset($this->request->post['paykun_transaction_url'])) {
            $this->data['paykun_transaction_url'] = $this->request->post['paykun_transaction_url'];
        } else {
            $this->data['paykun_transaction_url'] = $this->config->get('paykun_transaction_url');
        }

        if (isset($this->request->post['paykun_transaction_status_url'])) {
            $this->data['paykun_transaction_status_url'] = $this->request->post['paykun_transaction_status_url'];
        } else {
            $this->data['paykun_transaction_status_url'] = $this->config->get('paykun_transaction_status_url');
        }

        if (isset($this->request->post['paykun_callback_url_status'])) {
            $this->data['paykun_callback_url_status'] = $this->request->post['paykun_callback_url_status'];
        } else if($this->config->get('paykun_callback_url_status')){
            $this->data['paykun_callback_url_status'] = $this->config->get('paykun_callback_url_status');
        } else {
            $this->data['paykun_callback_url_status'] = "0";
        }

        $this->data["default_callback_url"] = $this->getCallbackUrl();

        if (isset($this->request->post['paykun_callback_url_status']) && $this->request->post['paykun_callback_url_status'] == 1) {
            $this->data['paykun_callback_url'] = $this->request->post['paykun_callback_url'];
        } else if($this->config->get('paykun_callback_url')) {
            $this->data['paykun_callback_url'] = $this->config->get('paykun_callback_url');
        } else {
            $this->data['paykun_callback_url'] = $this->data["default_callback_url"];
        }

        if (isset($this->request->post['paykun_order_success_status_id'])) {
            $this->data['paykun_order_success_status_id'] = $this->request->post['paykun_order_success_status_id'];
        } else {
            $this->data['paykun_order_success_status_id'] = $this->config->get('paykun_order_success_status_id');
        }

        if (isset($this->request->post['paykun_order_failed_status_id'])) {
            $this->data['paykun_order_failed_status_id'] = $this->request->post['paykun_order_failed_status_id'];
        } else {
            $this->data['paykun_order_failed_status_id'] = $this->config->get('paykun_order_failed_status_id');
        }

        if (isset($this->request->post['paykun_order_status_id'])) {
            $this->data['paykun_order_status_id'] = $this->request->post['paykun_order_status_id'];
        } else {
            $this->data['paykun_order_status_id'] = $this->config->get('paykun_order_status_id');
        }

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['paykun_total'])) {
            $this->data['paykun_total'] = $this->request->post['paykun_total'];
        } else {
            $this->data['paykun_total'] = $this->config->get('paykun_total');
        }

        // if (isset($this->request->post['paykun_geo_zone_id'])) {
        // 	$this->data['paykun_geo_zone_id'] = $this->request->post['paykun_geo_zone_id'];
        // } else {
        // 	$this->data['paykun_geo_zone_id'] = $this->config->get('paykun_geo_zone_id');
        // }

        // $this->load->model('localisation/geo_zone');
        // $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        // if (isset($this->request->post['paykun_status'])) {
        // 	$this->data['paykun_status'] = $this->request->post['paykun_status'];
        // } else {
        // 	$this->data['paykun_status'] = $this->config->get('paykun_status');
        // }

        if (isset($this->request->post['paykun_sort_order'])) {
            $this->data['paykun_sort_order'] = $this->request->post['paykun_sort_order'];
        } else {
            $this->data['paykun_sort_order'] = $this->config->get('paykun_sort_order');
        }

        $this->data['last_updated'] = "";
        $path = DIR_SYSTEM . "/paykun/paykun_version.txt";
        if(file_exists($path)){
            $handle = fopen($path, "r");
            if($handle !== false){
                $date = fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
                $this->data['last_updated'] = date("d F Y", strtotime($date));
            }
        }

        // Check cUrl is enabled or not
        if(function_exists('curl_version')){
            $this->data['curl_version'] = (!empty($curl_ver_array = curl_version()) && $curl_ver_array['version']) ? $curl_ver_array['version']:'';
        }else{
            $this->data['curl_version'] = '';
        }

        $this->data['opencart_version'] = VERSION;
        $this->data['php_version'] = PHP_VERSION;

        $this->template = 'payment/paykun.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }


    /**
     * check and test cURL is working or able to communicate properly with paykun
     */
    private function validateCurl($paykun_transaction_status_url = ''){
        if(!empty($paykun_transaction_status_url) && function_exists("curl_init")){
            $ch 	= curl_init(trim($paykun_transaction_status_url));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res 	= curl_exec($ch);
            curl_close($ch);
            return $res !== false;
        }
        return false;
    }

    //validate function to ensure required fields are filled before proceeding
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/paykun')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['paykun_status']) {
            $this->error['paykun_status'] = $this->language->get('paykun_status');
        }
        if (!$this->request->post['paykun_merchant_id']) {
            $this->error['merchant_key'] = $this->language->get('error_merchant_key');
        }
        if (!$this->request->post['paykun_access_token']) {
            $this->error['error_access_token'] = $this->language->get('error_access_token');
        }
        if (!$this->request->post['paykun_enc_key']) {
            $this->error['error_enc_key'] = $this->language->get('error_enc_key');
        }


        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>