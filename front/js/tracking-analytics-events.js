"use strict";var ogadwpRedirectLink;var ogadwpRedirectCalled=false;var ogadwpDefaultPrevented=false;function ogadwpRedirect(){if(ogadwpRedirectCalled){return}ogadwpRedirectCalled=true;if(ogadwpDefaultPrevented==false){document.location.href=ogadwpRedirectLink}else{ogadwpDefaultPrevented=false}}function ogadwp_send_event(c,d,a,b){if(ogadwpUAEventsData.options.ga_with_gtag){if(b){if(ogadwpUAEventsData.options.event_bouncerate){gtag("event",d,{event_category:c,event_label:a,non_interaction:1,event_callback:ogadwpRedirect})}else{gtag("event",d,{event_category:c,event_label:a,event_callback:ogadwpRedirect})}}else{if(ogadwpUAEventsData.options.event_bouncerate){gtag("event",d,{event_category:c,event_label:a,non_interaction:1})}else{gtag("event",d,{event_category:c,event_label:a})}}}else{if(b){if(ogadwpUAEventsData.options.event_bouncerate){ga("send","event",c,d,a,{nonInteraction:1,hitCallback:ogadwpRedirect})}else{ga("send","event",c,d,a,{hitCallback:ogadwpRedirect})}}else{if(ogadwpUAEventsData.options.event_bouncerate){ga("send","event",c,d,a,{nonInteraction:1})}else{ga("send","event",c,d,a)}}}}jQuery(window).on("load",function(){if(ogadwpUAEventsData.options.event_tracking){jQuery("a").filter(function(){if(typeof this.href==="string"){var a=new RegExp(".*\\.("+ogadwpUAEventsData.options.event_downloads+")(\\?.*)?$");return this.href.match(a)}}).click(function(d){var b=this.getAttribute("data-vars-ga-category")||"download";var c=this.getAttribute("data-vars-ga-action")||"click";var a=this.getAttribute("data-vars-ga-label")||this.href;ogadwp_send_event(b,c,a,false)});jQuery('a[href^="mailto"]').click(function(d){var b=this.getAttribute("data-vars-ga-category")||"email";var c=this.getAttribute("data-vars-ga-action")||"send";var a=this.getAttribute("data-vars-ga-label")||this.href;ogadwp_send_event(b,c,a,false)});jQuery('a[href^="tel"]').click(function(d){var b=this.getAttribute("data-vars-ga-category")||"telephone";var c=this.getAttribute("data-vars-ga-action")||"call";var a=this.getAttribute("data-vars-ga-label")||this.href;ogadwp_send_event(b,c,a,false)});if(ogadwpUAEventsData.options.root_domain){jQuery('a[href^="http"]').filter(function(){if(typeof this.href==="string"){var a=new RegExp(".*\\.("+ogadwpUAEventsData.options.event_downloads+")(\\?.*)?$")}if(a&&!this.href.match(a)){if(this.href.indexOf(ogadwpUAEventsData.options.root_domain)==-1&&this.href.indexOf("://")>-1){return this.href}}}).click(function(d){ogadwpRedirectCalled=false;ogadwpRedirectLink=this.href;var b=this.getAttribute("data-vars-ga-category")||"outbound";var c=this.getAttribute("data-vars-ga-action")||"click";var a=this.getAttribute("data-vars-ga-label")||this.href;if(this.target!="_blank"&&ogadwpUAEventsData.options.event_precision){if(d.isDefaultPrevented()){ogadwpDefaultPrevented=true;ogadwpRedirectCalled=false}}else{ogadwpRedirectCalled=true;ogadwpDefaultPrevented=false}if(this.target!="_blank"&&ogadwpUAEventsData.options.event_precision){ogadwp_send_event(b,c,a,true);setTimeout(ogadwpRedirect,ogadwpUAEventsData.options.event_timeout);return false}else{ogadwp_send_event(b,c,a,false)}})}}if(ogadwpUAEventsData.options.event_affiliates&&ogadwpUAEventsData.options.aff_tracking){jQuery("a").filter(function(){if(ogadwpUAEventsData.options.event_affiliates!=""){if(typeof this.href==="string"){var a=new RegExp("("+ogadwpUAEventsData.options.event_affiliates.replace(/\//g,"/")+")");return this.href.match(a)}}}).click(function(d){ogadwpRedirectCalled=false;ogadwpRedirectLink=this.href;var b=this.getAttribute("data-vars-ga-category")||"affiliates";var c=this.getAttribute("data-vars-ga-action")||"click";var a=this.getAttribute("data-vars-ga-label")||this.href;if(this.target!="_blank"&&ogadwpUAEventsData.options.event_precision){if(d.isDefaultPrevented()){ogadwpDefaultPrevented=true;ogadwpRedirectCalled=false}}else{ogadwpRedirectCalled=true;ogadwpDefaultPrevented=false}if(this.target!="_blank"&&ogadwpUAEventsData.options.event_precision){ogadwp_send_event(b,c,a,true);setTimeout(ogadwpRedirect,ogadwpUAEventsData.options.event_timeout);return false}else{ogadwp_send_event(b,c,a,false)}})}if(ogadwpUAEventsData.options.root_domain&&ogadwpUAEventsData.options.hash_tracking){jQuery("a").filter(function(){if(this.href.indexOf(ogadwpUAEventsData.options.root_domain)!=-1||this.href.indexOf("://")==-1){return this.hash}}).click(function(d){var b=this.getAttribute("data-vars-ga-category")||"hashmark";var c=this.getAttribute("data-vars-ga-action")||"click";var a=this.getAttribute("data-vars-ga-label")||this.href;ogadwp_send_event(b,c,a,false)})}if(ogadwpUAEventsData.options.event_formsubmit){jQuery('input[type="submit"], button[type="submit"]').click(function(f){var d=this;var b=d.getAttribute("data-vars-ga-category")||"form";var c=d.getAttribute("data-vars-ga-action")||"submit";var a=d.getAttribute("data-vars-ga-label")||d.name||d.value;ogadwp_send_event(b,c,a,false)})}if(ogadwpUAEventsData.options.ga_pagescrolldepth_tracking){jQuery.scrollDepth({percentage:true,userTiming:false,pixelDepth:false,gtmOverride:true,nonInteraction:true})}});