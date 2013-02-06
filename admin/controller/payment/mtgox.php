<?php

class ControllerPaymentMtgox extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/mtgox');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mtgox', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['entry_apikey'] = $this->language->get('entry_apikey');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_sell'] = $this->language->get('entry_sell');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_instantly'] = $this->language->get('entry_instantly');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$this->data['entry_canceled_status'] = $this->language->get('entry_canceled_status');
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_chargeback_status'] = $this->language->get('entry_chargeback_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_mb_id'] = $this->language->get('entry_mb_id');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_custnote'] = $this->language->get('entry_custnote');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['apikey'])) {
			$this->data['error_apikey'] = $this->error['apikey'];
		} else {
			$this->data['error_apikey'] = '';
		}

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
			'href'      => $this->url->link('payment/mtgox', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/mtgox', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['mtgox_apikey'])) {
			$this->data['mtgox_apikey'] = $this->request->post['mtgox_apikey'];
		} else {
			$this->data['mtgox_apikey'] = $this->config->get('mtgox_apikey');
		}

		if (isset($this->request->post['mtgox_secret'])) {
			$this->data['mtgox_secret'] = $this->request->post['mtgox_secret'];
		} else {
			$this->data['mtgox_secret'] = $this->config->get('mtgox_secret');
		}

		if (isset($this->request->post['mtgox_description'])) {
			$this->data['mtgox_description'] = $this->request->post['mtgox_description'];
		} else {
			$this->data['mtgox_description'] = $this->config->get('mtgox_description'); 
		}

		if (isset($this->request->post['mtgox_sell'])) {
			$this->data['mtgox_sell'] = $this->request->post['mtgox_sell'];
		} else {
			$this->data['mtgox_sell'] = $this->config->get('mtgox_sell');
		}

		if (isset($this->request->post['mtgox_email'])) {
			$this->data['mtgox_email'] = $this->request->post['mtgox_email'];
		} else {
			$this->data['mtgox_email'] = $this->config->get('mtgox_email');
		}

		if (isset($this->request->post['mtgox_instantly'])) {
			$this->data['mtgox_instantly'] = $this->request->post['mtgox_instantly'];
		} else {
			$this->data['mtgox_instantly'] = $this->config->get('mtgox_instantly');
		}

		if (isset($this->request->post['mtgox_order_status_id'])) {
			$this->data['mtgox_order_status_id'] = $this->request->post['mtgox_order_status_id'];
		} else {
			$this->data['mtgox_order_status_id'] = $this->config->get('mtgox_order_status_id'); 
		}

		if (isset($this->request->post['mtgox_pending_status_id'])) {
			$this->data['mtgox_pending_status_id'] = $this->request->post['mtgox_pending_status_id'];
		} else {
			$this->data['mtgox_pending_status_id'] = $this->config->get('mtgox_pending_status_id');
		}

		if (isset($this->request->post['mtgox_canceled_status_id'])) {
			$this->data['mtgox_canceled_status_id'] = $this->request->post['mtgox_canceled_status_id'];
		} else {
			$this->data['mtgox_canceled_status_id'] = $this->config->get('mtgox_canceled_status_id');
		}

		if (isset($this->request->post['mtgox_failed_status_id'])) {
			$this->data['mtgox_failed_status_id'] = $this->request->post['mtgox_failed_status_id'];
		} else {
			$this->data['mtgox_failed_status_id'] = $this->config->get('mtgox_failed_status_id');
		}

		if (isset($this->request->post['mtgox_chargeback_status_id'])) {
			$this->data['mtgox_chargeback_status_id'] = $this->request->post['mtgox_chargeback_status_id'];
		} else {
			$this->data['mtgox_chargeback_status_id'] = $this->config->get('mtgox_chargeback_status_id');
		}

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mtgox_status'])) {
			$this->data['mtgox_status'] = $this->request->post['mtgox_status'];
		} else {
			$this->data['mtgox_status'] = $this->config->get('mtgox_status');
		}

		$this->template = 'payment/mtgox.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/mtgox')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['mtgox_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
