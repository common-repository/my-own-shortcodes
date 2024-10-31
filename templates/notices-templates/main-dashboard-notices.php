<?php if (!defined('KLICK_MOS_PLUGIN_MAIN_PATH')) die('No direct access allowed'); ?>

<!-- Notice main container starts-->
<div class="klick-mos-notice-container-wrapper" id = "<?php echo 'notice' . $notice_id; ?>">
	<div class="klick-mos-notice-container">
		<div class="klick-mos-notice-content-left"> <!-- Notice image logo starts -->
			<img src="<?php echo KLICK_MOS_PLUGIN_URL . '/images/' . $image_url; ?>" width="60" height="60" alt="<?php _e('notice image', 'klick-mos'); ?>" />
		</div> <!-- Notice image logo ends -->

		<div class="klick-mos_notice_content_wrapper"> <!-- Notice content wrapper starts -->
			<h3 class="klick-mos-notice-heading"> <!-- Notice heading starts -->
				<?php echo $title; ?>
				<div class="klick-mos-notice-dismiss">
					<?php
					if ($dismiss_type == "dismiss") { ?>
						<a href="#"  onclick="jQuery('#notice<?php echo $notice_id; ?>').slideUp(); jQuery.post(ajaxurl, { action: 'klick_mos_ajax', data: '<?php echo $notice_id; ?>', subaction: 'dismiss_page_notice_until', nonce: '<?php echo wp_create_nonce('klick_mos_ajax_nonce'); ?>' });"><?php echo $dismiss_text; ?></a>
					<?php } else { ?>
							<a href="#"  onclick="jQuery('#notice<?php echo $notice_id; ?>').slideUp(); jQuery.post(ajaxurl, { action: 'klick_mos_ajax', data: '<?php echo $notice_id; ?>', subaction: 'dismiss_page_notice_until_forever', nonce: '<?php echo wp_create_nonce('klick_mos_ajax_nonce'); ?>' });"><?php echo $dismiss_text; ?></a>
					<?php }  ?>
				</div>
			</h3> <!-- Notice heading ends -->
			
			<p> <!-- Notice text starts -->
				<?php
					echo $notice_text;
					if (!empty($button_link) && !empty($button_text)) {

						// Check which Message is going to be used.
						$klick_mos->get_dashboard()->klick_mos_url($button_link, $button_text, null, 'class="klick-mos-notice-link"');
					}
				?>
			</p> <!-- Notice text ends -->
		</div> <!-- Notice content wrapper ends -->
	</div>
	<div class="clear"></div>
</div> <!-- Notice main container ends-->
