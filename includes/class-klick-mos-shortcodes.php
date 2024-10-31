<?php
if (!defined('KLICK_MOS_VERSION')) die('No direct access allowed');
/**
 * Access via Klick_Mos()->get_shortcodes().
 */
class Klick_Mos_Shortcodes {
	
	public $_shortcodes = array();

	protected $_current_shortcode = array();

	private $options;

	public function __construct() {
		$this->options = Klick_Mos()->get_options();
		$this->_shortcodes = $this->options->get_option('shortcodes');
		$all_shotcodes = $this->get_all();
		if(isset($all_shotcodes) && !empty($all_shotcodes)){
			foreach ($all_shotcodes as $key => $value) {
				add_shortcode($key, array($this, 'execute_shortcode'));
			}
		}
		
	}

	/**
	 * Get full list of shortcodes
	 * 
	 * @return array
	 */
	public function get_all(){
		return $this->_shortcodes;
	}

	/**
	 * Save shortcode
	 * 
	 * @param  array $data
	 * @return array
	 */
	public function save($data) {
		$existence = $this->options->get_option('shortcodes');

		// Check name is already exist only when add
		if ($data['command'] == "add") { 
			$is_name_exist = array_key_exists($data['name'], $existence);
			if ($is_name_exist == true) {
				$return_array['messages'] = $this->show_admin_warning(__("Shortcode name already exists.", "klick-mos"),'updated fade');
				$return_array['status'] = 2;
				return $return_array;
			}
		}

		$current = array($data['name'] => array('desc' => $data['desc'], 'content' => stripslashes_deep($data['content'])));

		if ($existence != "") {
			$existence[$data['name']] = array('desc' => $data['desc'],'content' => stripslashes_deep($data['content']));
			$this->options->update_option('shortcodes', $existence);

		} else {

			$this->options->update_option('shortcodes', $current);
		}

		$return_array['messages'] = $this->show_admin_warning(__("Shortcode Saved.", "klick-mos"),'updated fade');
		$return_array['status'] = 1;
		return $return_array;
	}

	/**
	 * Delete shortcode
	 * 
	 * @param  array $data
	 * @return boolean
	 */
	public function delete($data) {
		$existence = $this->options->get_option('shortcodes');
		unset($existence[$data['name']]);
		$this->options->update_option('shortcodes', $existence);
		return true;
	}

	/**
	 * Edit shortcode
	 * 
	 * @param  array $data
	 * @return array
	 */
	public function edit($data) {
		$existence = $this->options->get_option('shortcodes');
		$arr = array('key' => $data['name'],'data' => $existence[$data['name']]);
		return $arr;
	}

	/**
	 * Render shortcode list in row format
	 * 
	 * @param  array $data
	 * @return array
	 */
	public function shortcode_list(){
		if(count($this->_shortcodes) > 0 && !empty($this->_shortcodes)){
			$table = "";
			$table .= '<table class="wp-list-table widefat fixed striped pages mos-table">';
					$table .= '<thead>';
						$table .= '<tr>';
							$table .= '<th style="width: 30px; text-align: center;"></th>';
							$table .= '<th style="width: 30px; text-align: center;"></th>';
							$table .= '<th style="width: 150px;">Name</th>';
							$table .= '<th>Description</th>';
						$table .= '</tr>';
					$table .= '</thead>';
					$table .= '<tbody id="the-list" class="mos-table-body">';
			foreach ($this->get_all() as $key => $value) {
				$table .= "<tr id='$key'>";
				$table .= "<td data-label='Edit' style='width: 30px; text-align: center;' data-id='$key' class='klick-mos-edit-row'><span class='dashicons dashicons-edit'></span></td>";
				$table .= "<td data-label='Delete' style='width: 30px; text-align: center;' data-id='$key' class='klick-mos-delete-row'><span class='dashicons dashicons-trash'></span></td>";
				$table .= "<td data-label='Name' style='width: 150px;'>" . $key . "</td>";
				$table .= "<td data-label='Description'>" . $value['desc'] . "</td>";
				$table .= "</tr>";
			}
			$table .= '</tbody></table>';
			return $table;
		}

		return "You have not created any shortcode yet!!!";
	}

	/**
	 * Execute shortcode
	 * 
	 * @param  array  $atts 
	 * @param  string $content
	 * @param  string $tag
	 * @return mixed  do_shortcode()	content of executed shortcode
	 */
	public function execute_shortcode($atts, $content, $tag) {
		$sc_content = $this->get_this_one($tag);
		return do_shortcode( $sc_content );
	}

	/**
	 * Get particular one shortcode
	 * 
	 * @param  string $tag
	 * @return mixed
	 */
	public function get_this_one($tag){
		$existence = $this->get_all();
		$sc_content = $existence[$tag]['content']; 
		return $sc_content;
	}

	/**
	 * Create ajax notice
	 *
	 * @param  String 	$message as a notice
	 * @param  String 	$class an string if many then separated by space defalt is 'updated'
	 *
	 * @return String 	returns message
	 */
	public function show_admin_warning($message, $class = "updated") {
		return  '<div class="klick-ajax-notice ' . $class . '">' . "<p>$message</p></div>";
	}
}
