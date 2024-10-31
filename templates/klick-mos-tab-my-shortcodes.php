<!-- First Tab content -->
<div id="klick_mos_tab_first">
	<div class="klick-notice-message"></div>
	<div class="klick-ajax-message"></div>

	<div class="klick-mos-data-box">
		<div class="klick-mos-overlay">
			<img class="loading-image" src="<?php echo KLICK_MOS_PLUGIN_URL . 'images/ajax-loader.gif' ?> " alt="Loading.." />'
		</div>
		<div class="mos-list-container">
			<article class="klick-mos-data-table">
				<h1>My Own Shortcodes</h1> <!-- Header tab-->
				<div id="klick_mos_shortcode_list" class="klick-mos-add-btn">
					<div class="klick-mos-info">
					 	<button id = "klick_mos_sc_add" class = "klick_btn button button-primary">Add New Shortcode</button>
					 	<input type="hidden" id="mos_command" value="">
					</div>
				</div>
				<div class="mos-list-table">
				 <?php 
				echo Klick_Mos()->get_shortcodes()->shortcode_list();
				 ?>			
				</div>
			</article>
		</div>
		<div id="klick_mos_add_shortcode">
			<ul>
				<li>
					<label>Name :</label>
					<div class="mos-list-label-content">
						<input type="text" name="klick_mos_sc_name" id="klick_mos_sc_name">
					</div>
				</li>
				<li>
					<label>Description :</label>
					<div class="mos-list-label-content">
						<textarea cols="40" rows="5" name="klick_mos_sc_desc" id="klick_mos_sc_desc"></textarea>
					</div>
				</li>
				<li>
					<label>Content :</label>
					<div class="mos-list-label-content">
						<?php wp_editor('', 'klick_mos_sc_editor', array( 'textarea_rows' => 15, 'tinymce' => 1, 'wpautop' => true)); ?>
					</div>
				</li>
				<li>
					<label>&nbsp;</label>
					<div class="mos-list-label-content">
						<button id = "klick_mos_sc_save" class = "klick_btn button button-primary">Save</button>
						<button id = "klick_mos_sc_cancel" class = "klick_btn button button-primary">Cancel</button>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript">
	var klick_mos_ajax_nonce ='<?php echo wp_create_nonce('klick_mos_ajax_nonce'); ?>';
</script>
