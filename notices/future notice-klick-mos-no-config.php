<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (class_exists('Klick_Mos_No_Config')) return;

require_once(KLICK_MOS_PLUGIN_MAIN_PATH . '/includes/class-klick-mos-abstract-notice.php');

/**
 * Class Klick_Mos_No_Config
 */
class Klick_Mos_No_Config extends Klick_Mos_Abstract_Notice {
	
	/**
	 * Klick_Mos_No_Config constructor
	 */
	public function __construct() {
		$this->notice_id = 'my-own-shortcode';
		$this->title = __('My Own Shortcode plugin is installed but not configured', 'klick-mos');
		$this->klick_mos = "";
		$this->notice_text = __('Configure it Now', 'klick-mos');
		$this->image_url = '../images/our-more-plugins/mos.svg';
		$this->dismiss_time = 'dismiss-page-notice-until';
		$this->dismiss_interval = 30;
		$this->display_after_time = 0;
		$this->dismiss_type = 'dismiss';
		$this->dismiss_text = __('Hide Me!', 'klick-mos');
		$this->position = 'dashboard';
		$this->only_on_this_page = 'index.php';
		$this->button_link = KLICK_MOS_PLUGIN_SETTING_PAGE;
		$this->button_text = __('Click here', 'klick-mos');
		$this->notice_template_file = 'main-dashboard-notices.php';
		$this->validity_function_param = 'my-own-shortcode/my-own-shortcode.php';
		$this->validity_function = 'is_plugin_configured';
	}
}
