<?php
class ControllerExtensionModuleAdvancedReviews extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/advanced_reviews');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_advanced_reviews', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/advanced_reviews', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/advanced_reviews', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_advanced_reviews_status'])) {
			$data['module_advanced_reviews_status'] = $this->request->post['module_advanced_reviews_status'];
		} else {
			$data['module_advanced_reviews_status'] = $this->config->get('module_advanced_reviews_status');
		}

		if (isset($this->request->post['module_advanced_reviews_terms'])) {
			$data['module_advanced_reviews_terms'] = $this->request->post['module_advanced_reviews_terms'];
		} else {
			$data['module_advanced_reviews_terms'] = $this->config->get('module_advanced_reviews_terms');
		}

		if (isset($this->request->post['module_advanced_reviews_cut_names'])) {
			$data['module_advanced_reviews_cut_names'] = $this->request->post['module_advanced_reviews_cut_names'];
		} else {
			$data['module_advanced_reviews_cut_names'] = $this->config->get('module_advanced_reviews_cut_names');
		}

		if (isset($this->request->post['module_advanced_reviews_require_email'])) {
			$data['module_advanced_reviews_require_email'] = $this->request->post['module_advanced_reviews_require_email'];
		} else {
			$data['module_advanced_reviews_require_email'] = $this->config->get('module_advanced_reviews_require_email');
		}

		

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/advanced_reviews', $data));
	}

	public function install() {
		$this->load->model("extension/module/advanced_reviews");
		$this->model_extension_module_advanced_reviews->install();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/advanced_reviews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}