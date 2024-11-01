jQuery(document).ready(function(){
	collapseSections();
	function collapseSections(){
		jQuery("#wptagthis-main .wptagthis-inputs,.wptagthis-section-title a").not(".wptagthis-inputs.start-open").hide();
		jQuery(".handlediv").fadeOut(0);
		jQuery("#wptagthis-main .wptagthis-section").hover(
			function(){
				jQuery(this).find(".handlediv").fadeIn(150);
				},
			function(){
				jQuery(this).find(".handlediv").fadeOut(150);
				}
			)
		jQuery("#wptagthis-main .wptagthis-section:not(.permanently-open) .wptagthis-section-title h3").click(
			function(){
				if(jQuery(this).parent().find("a")){
					jQuery(this).parent().find("a").toggle();
					}
				jQuery(this).parent().next(".wptagthis-inputs").toggle();
				}
			);
		}
	});