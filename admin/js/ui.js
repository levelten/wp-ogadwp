"use strict";jQuery(document).ready(function(){var a={action:"ogadwp_dismiss_notices",ogadwp_security_dismiss_notices:ogadwp_ui_data.security};jQuery("#ogadwp-notice .notice-dismiss").click(function(){jQuery.post(ogadwp_ui_data.ajaxurl,a)});if(ogadwp_ui_data.ed_bubble!=""){jQuery('#toplevel_page_ogadwp_settings li > a[href*="page=ogadwp_errors_debugging"]').append('&nbsp;<span class="awaiting-mod count-1"><span class="pending-count" style="padding:0 7px;">'+ogadwp_ui_data.ed_bubble+"</span></span>")}});