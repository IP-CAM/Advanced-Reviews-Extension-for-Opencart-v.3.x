<?php
class ControllerExtensionModuleAdvancedReviews extends Controller {
	public function index() {
		$data = [];
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/advanced_reviews.css');

		$this->load->language('extension/module/advanced_reviews_form');

		$data['review_status'] = $this->config->get('config_review_status');

		if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
			$data['review_guest'] = true;
		} else {
			$data['review_guest'] = false;
		}

		if ($this->customer->isLogged()) {
			$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
		} else {
			$data['customer_name'] = '';
		}


		$data['advanced_reviews_require_email'] = $this->config->get('module_advanced_reviews_require_email');
		$data['advanced_reviews_cut_names'] 	= $this->config->get('module_advanced_reviews_cut_names');
		$data['advanced_reviews_terms'] 		= $this->config->get('module_advanced_reviews_terms');
		$data['advanced_reviews_verified_faq'] 	= $this->config->get('module_advanced_reviews_verified_faq');

		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
		
		$this->load->model('catalog/information');

		if ($data['advanced_reviews_terms']) {

			$information_info = $this->model_catalog_information->getInformation($data['advanced_reviews_terms']);

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $data['advanced_reviews_terms'], true), $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if($data['advanced_reviews_verified_faq']) {
			$information_info = $this->model_catalog_information->getInformation($data['advanced_reviews_verified_faq']);

			if ($information_info) {
				$data['text_verified_faq'] = sprintf($this->language->get('text_verified_faq'), $this->url->link('information/information/agree', 'information_id=' . $data['advanced_reviews_verified_faq'], true), $information_info['title']);
			} else {
				$data['text_verified_faq'] = '';
			}
		} else {
			$data['text_verified_faq'] = '';
		}
		
		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}

		return $this->load->view('extension/module/advanced_reviews_form', $data);
	}
}