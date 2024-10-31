<?php 

if (!defined('KLICK_MOS_PLUGIN_MAIN_PATH')) die('No direct access allowed');

/**
 * Commands available from control interface (e.g. wp-admin) are here
 * All public methods should either return the data, or a WP_Error with associated error code, message and error data
 */
/**
 * Sub commands for Ajax
 *
 */
class Klick_Mos_Commands {
	private $options;
	
	/**
	 * Constructor for Commands class
	 *
	 */
	public function __construct() {
		$this->options = Klick_Mos()->get_options();
		$this->shortcodes = Klick_Mos()->get_shortcodes();
	} 

	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until($data),
			);
	}

	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until_forever($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until_forever($data),
			);
	}
	
	/**
	 * This sends the passed data value over to the save function
	 *
	 * @param  Array    $data an array of data UI form
	 *
	 * @return Array    $status
	 */
	public function klick_mos_save_settings($data) {
		
		return array(
			'status' => $this->shortcodes->save($data),
		);
	}
	
	/**
	 * This sends the passed data value over to the delete function
	 *
	 * @param  Array    $data an array of data UI form
	 *
	 * @return Array    $status
	 */
	public function klick_mos_delete_row($data) {
		return array(
			'status' => $this->shortcodes->delete($data),
		);
	}

	/**
	 * This sends the passed data value over to the edit function
	 *
	 * @param  Array    $data an array of data UI form
	 *
	 * @return Array    $status
	 */
	public function klick_mos_edit_row($data) {
		return array(
			'status' => $this->shortcodes->edit($data),
		);
	}

	/**
	 * This sends the passed data value over to the reload function
	 *
	 * @param  Array    $data an array of data UI form
	 *
	 * @return Array    $status
	 */
	public function klick_mos_reload(){
		return array(
			'data' => $this->shortcodes->shortcode_list($data),
		);
	}
}
