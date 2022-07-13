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
		$data['advanced_reviews_cut_names'] = $this->config->get('module_advanced_reviews_cut_names');
		$data['advanced_reviews_terms'] = $this->config->get('module_advanced_reviews_terms');

		if ($data['advanced_reviews_terms']) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($data['advanced_reviews_terms']);

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $data['advanced_reviews_terms'], true), $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		
		return $this->load->view('extension/module/advanced_reviews_form', $data);
	}
}