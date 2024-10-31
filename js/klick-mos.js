/**
 * Send an action via admin-ajax.php
 * 
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
var klick_mos_send_command = function (action, data, callback, json_parse) {
	json_parse = ('undefined' === typeof json_parse) ? true : json_parse;
	var ajax_data = {
		action: 'klick_mos_ajax',
		subaction: action,
		nonce: klick_mos_ajax_nonce,
		data: data
	};
	jQuery.post(ajaxurl, ajax_data, function (response) {
		
		if (json_parse) {
			try {
				var resp = JSON.parse(response);
			} catch (e) {
				console.log(e);
				console.log(response);
				return;
			}
		} else {
			var resp = response;
		}
		
		if ('undefined' !== typeof callback) callback(resp);
	});
}

/**
 * When DOM ready
 * 
 */
jQuery(document).ready(function ($) {
	klick_mos = klick_mos(klick_mos_send_command);
});

/**
 * Function for sending communications
 * 
 * @callable klick_mos_send_command Callable
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
/**
 * Main klick_mos
 * 
 * @param {sendcommandCallable} send_command
 */
var klick_mos = function (klick_mos_send_command) {
	var $ = jQuery;
	$(".klick-mos-overlay").hide();
	$('#klick_mos_add_shortcode').hide();
	
	/**
	 * Proceses the tab click handler
	 *
	 * @return void
	 */
	$('#klick_mos_nav_tab_wrapper .nav-tab').click(function (e) {
		e.preventDefault();
		
		var clicked_tab_id = $(this).attr('id');
	
		if (!clicked_tab_id) { return; }
		if ('klick_mos_nav_tab_' != clicked_tab_id.substring(0, 18)) { return; }
		
		var clicked_tab_id = clicked_tab_id.substring(18);

		$('#klick_mos_nav_tab_wrapper .nav-tab:not(#klick_mos_nav_tab_' + clicked_tab_id + ')').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('.klick-mos-nav-tab-contents:not(#klick_mos_nav_tab_contents_' + clicked_tab_id + ')').hide();
		$('#klick_mos_nav_tab_contents_' + clicked_tab_id).show();
	});
	
	/**
	 * Add shortcode
	 *
	 * @return void
	 */
	$('#klick_mos_sc_add').click(function (e) {
		e.preventDefault();
		show_mos_form();
		mos_add_form_reset();
		
	});
	
	/**
	 * Cancel shortcode
	 *
	 * @return void
	 */
	$('#klick_mos_sc_cancel').click(function (e) {
		e.preventDefault();
		show_mos_list();
	});
	
	/**
	 * Gathers the details from form
	 *
	 * @returns (string) - serialized row data
	 */
	function gather_row(command){
		var name = $("#klick_mos_sc_name").val();
		var desc = $("#klick_mos_sc_desc").val();
		var editor = tinymce.get('klick_mos_sc_editor');
		var command = command;
		var empty_status_name = is_not_empty(name);

		if(editor !== null) { // visual
			content = editor.getContent();
		} else { // text
			content = $('#klick_mos_sc_editor').val();
		}

		if(is_name_valid() === false) {
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_name);
			return false;	
		} else if (is_desc_valid() === false){
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_desc);
			return false;
		}
		else {
			return 'name=' + name + '&desc=' + desc + '&content=' + content + '&command=' + command;
		}	
	}
	
	/**
	 * Save shortcode
	 *
	 * @return void
	 */
	$('#klick_mos_sc_save').on('click',function (e) {
		e.preventDefault();
		var command = $("#mos_command").val();
		var data = gather_row(command);
		if(data === false) return false;
		klick_mos_send_command('klick_mos_save_settings', data, function (resp) {

			if(resp.status['status'] == 1) { // Save
				mos_list_reload();
				show_mos_list();
				$('.klick-ajax-message').html(resp.status['messages']);
				$('.klick-ajax-message').slideDown();
				$('.fade').delay(2000).slideUp(200, function(){
				});
			}

			if(resp.status['status'] == 2) { // Alreday sc name exist
				$('.klick-ajax-message').html(resp.status['messages']);
				$('.klick-ajax-message').slideDown();
				$('.fade').delay(10000).slideUp(200, function(){
				});
			}	
		});
	});

	/**
	 * Delete shortcode
	 *
	 * @return void
	 */
	$(document).on('click', '.klick-mos-delete-row', function(e) {	
		e.preventDefault();
		$(".klick-mos-overlay").show();
		var name = $(this).attr('data-id');
		var data = 'name='+name;
		klick_mos_send_command('klick_mos_delete_row', data, function (resp) {
			if(resp.status === true) {
				mos_list_reload();
			}
		});
	});

	/**
	 * Edit shortcode
	 *
	 * @return void
	 */
	$(document).on('click', '.klick-mos-edit-row', function(e) {
		e.preventDefault();
		var name = $(this).attr('data-id');
		var data = 'name='+name;
		klick_mos_send_command('klick_mos_edit_row', data, function (resp) {
			show_mos_form();
			$("#klick_mos_sc_desc").val(resp.status.data.desc);
			
			var editor = tinymce.get('klick_mos_sc_editor');
			
			if(editor !== null) { // visual
				editor.setContent(resp.status.data.content);
			}
			
			$('#klick_mos_sc_editor').val(resp.status.data.content);// Do text always
			
			$("#klick_mos_sc_name").val(resp.status.key);
			$('#klick_mos_sc_name').prop('readonly', true);
			$("#mos_command").val("edit");
		});
	});

	/**
	 * To reload mos list
	 *
	 * @return void
	 */
	function mos_list_reload(){
		$(".klick-mos-overlay").show();
		var data = "reload=1";
		klick_mos_send_command('klick_mos_reload', data, function (resp) {
			$(".klick-mos-overlay").hide();
			$(".mos-list-table").html(resp.data);
		});
	}

	/**
	 * To reset whole add shortcode form
	 *
	 * @return void
	 */
	function mos_add_form_reset(){
		$('#klick_mos_sc_name').val("");
		$('#klick_mos_sc_name').prop('readonly', false);
		$("#mos_command").val("add");
		$('#klick_mos_sc_desc').val("");
		
		var editor = tinymce.get('klick_mos_sc_editor');
		
		if(editor !== null) { // visual
			editor.setContent("");
		}
		
		$('#klick_mos_sc_editor').val(''); // do text as well
	}

	/**
	 * show shortcode list
	 *
	 * @return void
	 */
	function show_mos_list(){
		$('#klick_mos_add_shortcode').hide();
		$(".mos-list-container").show();
		$('#klick_mos_shortcode_list').show();
	}

	/**
	 * Show add shortcode form
	 *
	 * @return void
	 */
	function show_mos_form(){
		$('#klick_mos_shortcode_list').hide();
		$(".mos-list-container").hide();
		$('#klick_mos_add_shortcode').show();
	}

	/**
	 * Validtor for sc_name
	 *
	 * @return void
	 */
	$("#klick_mos_sc_name").keyup(function(){
		$("#klick_mos_sc_save").attr('disabled',false);
		var klick_mos_sc_name = $.trim($("#klick_mos_sc_name").val());
		if(check_for_alphanumeric_without_space(klick_mos_sc_name) === true) {
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_name);
		} else {
			$('.klick-notice-message').slideUp();
		}
	});	

	/**
	 * Validtor for sc_desc
	 *
	 * @return void
	 */
	$("#klick_mos_sc_desc").keyup(function(){
		$("#klick_mos_sc_save").attr('disabled',false);
		var klick_mos_sc_desc = $.trim($("#klick_mos_sc_desc").val());
		if(klick_mos_sc_desc.length > 2){
			if(check_for_alphanumeric_with_space(klick_mos_sc_desc) === true) {
				set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_desc);
			} else {
				$('.klick-notice-message').slideUp();
			}
		}
	});	

	/**
	 * Test expression if any non numeric or alpha is entered
	 *
	 * @return boolean
	 */
	 function check_for_alphanumeric_with_space( str ) {
	 	return !/^[0-9a-zA-Z\-\s]+$/.test(str);
	}

	/**
	 * Test expression if any non numeric or alpha is entered
	 *
	 * @return boolean
	 */
	 function check_for_alphanumeric_without_space( str ) {
	 	return !/^[0-9a-zA-Z]+$/.test(str);
	}

	/**
	 * Check whether passed param is empty or not
	 *
	 * @return boolean
	 */
	function is_not_empty(name){
		var result = (name.length == "") ? true : false;
		return result;
	}
	/**
	 * Create and render notice admin side
	 *
	 * @string string selecoter, e.g. #msg_area
	 * @msg string msg
	 * @return void
	 */
	function set_notice_message_generate(selector, msg){
		$(""+selector+"").addClass('klick-notice-message notice notice-error is-dismissible');
		$(""+selector+"").html("<p>" + msg + "</p>");
		$(""+selector+"").slideDown();
		$("#klick_mos_sc_save").attr('disabled','disabled');
		return false;
	}

	/**
	 * Check valid name by all defined rules
	 *
	 * @return boolean
	 */
	function is_name_valid(){
		var klick_mos_sc_name = $.trim($("#klick_mos_sc_name").val());
		if(is_not_empty(klick_mos_sc_name) === true){
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.empty_status_name);
			return false;
		}
		else if(check_for_alphanumeric_without_space(klick_mos_sc_name) === true) {
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_name);
			return false;
		} else {
			$('.klick-notice-message').slideUp();
			return true;
		}
	}

	/**
	 * Check valid desc by all defined rules
	 *
	 * @return boolean
	 */
	function is_desc_valid(){
		var klick_mos_sc_desc = $.trim($("#klick_mos_sc_desc").val());
		if(check_for_alphanumeric_with_space(klick_mos_sc_desc) === true) {
			set_notice_message_generate('.klick-notice-message',klick_mos_admin.notice_for_sc_desc);
			return false;
		} else {
			$('.klick-notice-message').slideUp();
			return true;
		}
	}
}
