<?php
$plugin_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
$q=explode('&',$_SERVER['QUERY_STRING']);
$purl='http'.((!empty($_SERVER['HTTPS'])) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$q[0];
global $WPtagthis, $echo;
$WPtagthis->admin_page_action();
$wptagthis_data=get_option('wptagthis_data');
//print_r($wptagthis_data);
?>
<div id="wptagthis-page" class="wrap">
	<h2>Wp Tag This!</h2>
	<?php 
	if(!current_user_can("administrator")) {
		echo '<p>'.__('Please log in as admin','wp-tag-this').'</p>';
		return;
		}
	?>
	
	<!-- SIDEBAR START -->
	
	<div id="wptagthis-sidebar">
		<div class="wptagthis-section">
			<div class="wptagthis-section-title stuffbox">
				<!--<div title="Click to toggle" class="handlediv" style="background:url('<?php bloginfo("wpurl")?>/wp-admin/images/menu-bits.gif') no-repeat scroll left -111px transparent"><br></div>-->
				<h3><?php _e('About this Plugin', 'wp-tag-this'); ?></h3>
			</div>
			<div class="wptagthis-inputs">
				<ul>
					<li><a href=""><img height ="16" width="16" src="<?php echo $plugin_url ?>/images/antonioandra.de_favicon.png"> Plugin Homepage</a></li>
					<li><a href=""><img src="<?php echo $plugin_url ?>/images/favicon.ico"> Plugin at WordPress.org </a></li>
					<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=antonio%40antonioandra%2ede&lc=US&item_name=WP%20Table%20of%20Paginated%20Contents%20%28Antonio%20Andrade%29&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest"><img width="16" src="<?php echo $plugin_url ?>/images/pp_favicon_x.ico"> Donate with Paypal</a></li>
				</ul>
			</div>
		</div>
		<div class="wptagthis-section">
			<div class="wptagthis-section-title stuffbox">
				<h3><?php _e('Latest donations', 'wp-tag-this'); ?></h3>
			</div>
			<div class="wptagthis-inputs">
				<iframe src="http://antonioandra.de/wp-tag-this-donations/" width="220"></iframe>
			</div>
		</div>
		<p id="foot">WP Tag This! <?php _e('by', 'wp-tag-this'); ?> António Andrade</p>
	</div>
	
	<!-- SIDEBAR END -->
	
	<div id="wptagthis-main">
		<form method="post" action="<?php echo $purl?>">
			<?php if($echo!=''){?>
				<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);"><p><?php echo $echo;?></p></div>
			<?php }
			# Donation Message
			if(!isset($wptagthis_data['donation_hidden_time']) || ($wptagthis_data['donation_hidden_time']&&$wptagthis_data['donation_hidden_time']<time())){?>
				<div class="updated">
					<p>
						<strong>Is this plugin useful? Consider making a donation encouraging me to continue supporting it!</strong>
						<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=antonio%40antonioandra%2ede&lc=US&item_name=WP%20Table%20of%20Paginated%20Contents%20%28Antonio%20Andrade%29&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest"><img alt="Donate" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donate_SM.gif"></a>
						<span><a href="<?php echo $purl?>&action=hide_donation_message">Hide this message</a></span>
					</p>
				</div>
			<?php }?>
			
			<div class="wptagthis-section permanently-open">
				<div class="wptagthis-section-title stuffbox">
					<h3><?php _e('WP Tag This', 'wp-tag-this');?></h3>
				</div>
				<table class="form-table wptagthis-inputs start-open">
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Instructions', 'wp-tag-this'); ?></th>
						<td >
							<p class="description"><?php _e('To use this plugin add the following inside <a href="http://codex.wordpress.org/The_Loop">the loop</a>:', 'wp-tag-this'); ?></p>
							<p><code>&lt;?php if( function_exists( &#39;TagThis&#39; ) ){ TagThis(); } ?&gt;</code></p>
						</td>
					</tr>
				</table>
			</div>
			<div class="wptagthis-section permanently-open">
				<div class="wptagthis-section-title stuffbox">
					<div title="Click to toggle" class="handlediv" style="background:url('<?php bloginfo("wpurl")?>/wp-admin/images/menu-bits.gif') no-repeat scroll left -111px transparent"><br></div>
					<h3><?php _e('Settings', 'wp-tag-this');?></h3>
				</div>
				<table class="form-table wptagthis-inputs start-open">
					<!-- AUTCOMPLETE THEME -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Autocomplete Theme', 'wp-tag-this'); ?></th>
						<td>
							<?php
								$themes=array("base","black-tie","blitzer","cupertino","dark-hive","dot-luv","eggplant","excite-bike","flick","hot-sneaks","humanity", "le-frog","mint-choc", "overcast","pepper-grinder", "redmond","smoothness", "south-street", "start", "sunny", "swanky-purse","trontastic","ui-darkness","ui-lightness","vader", "NONE");
							?>
							<select name="jquery_ui_theme">
								<?php foreach($themes as $t){
										echo "<option ".(($WPtagthis->data['jquery_ui_theme']==$t)?"selected='selected'":"").">".$t."</option>";
									}?>
							</select>
						</td>
						<td valign="middle">
							<p class="description"><?php _e('These are jQuery UI themes you may preview at <a href="http://jqueryui.com/themeroller/">http://jqueryui.com/themeroller/</a>', 'wp-tag-this'); ?></p>
						</td>
					</tr>
					<!-- FIRST VOTE WEIGHT -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('First Vote Weight', 'wp-tag-this'); ?></th>
						<td>
							<input type="text" name="first_vote_weight" value="<?php echo $WPtagthis->data['first_vote_weight']?$WPtagthis->data['first_vote_weight']:2; ?>" />
						</td>
						<td valign="middle">
							<p class="description"><?php _e('', 'wp-tag-this'); ?></p>
						</td>
					</tr>
					<!-- SHOW VOTE NUMBER -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Show vote count', 'wp-tag-this'); ?></th>
						<td>
							<input type="checkbox" name="show_vote_number" value="1" <?php if($WPtagthis->data['show_vote_number']){ echo "checked='checked'";}; ?> />
						</td>
						<td valign="middle">
							<p class="description"><?php _e('Show number of votes next to each tag.', 'wp-tag-this'); ?></p>
						</td>
					</tr>
					<!-- LOWERCASE TAGS -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Lowercase tags', 'wp-tag-this'); ?></th>
						<td>
							<input type="checkbox" name="lowercase_tags" value="1" <?php if($WPtagthis->data['lowercase_tags']){ echo "checked='checked'";}; ?> />
						</td>
						<td valign="middle">
							<p class="description"><?php _e('Lowercase all submitted tags.', 'wp-tag-this'); ?></p>
						</td>
					</tr>
				</table>
			</div>
			
			<!-- Tagging Control -->
			<div class="wptagthis-section permanently-open">
				<div class="wptagthis-section-title stuffbox">
					<div title="Click to toggle" class="handlediv" style="background:url('<?php bloginfo("wpurl")?>/wp-admin/images/menu-bits.gif') no-repeat scroll left -111px transparent"><br></div>
					<h3><?php _e('Tagging Control', 'wp-tag-this');?></h3>
				</div>
				<table class="form-table wptagthis-inputs start-open">
					<!-- BLOCKED WORDS -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Blocked Tags', 'wp-tag-this'); ?></th>
						<td>
							<textarea name="blocked_tags" style="width:100%;"><?php echo $WPtagthis->data['blocked_tags']?implode(", ", $WPtagthis->data['blocked_tags']):""; ?></textarea>
						</td>
						<td valign="middle">
							<p class="description"><?php _e('', 'wp-tag-this'); ?></p>
						</td>
					</tr>
					<!-- SYNONYMS -->
					<tr valign="top">
						<th scope="row" style="width:18%;"><?php _e('Synonyms', 'wp-tag-this'); ?></th>
						<td style="width:50%;">
							<textarea name="synonyms" style="width:100%;"><?php echo $WPtagthis->data['synonyms_input']?$WPtagthis->data['synonyms_input']:""; ?></textarea>
						</td>
						<td valign="middle">
							<p class="description"><?php _e('tag=tag-to-be-replaced, another-tag-to-be-replaced (line break before new rule)', 'wp-tag-this'); ?></p>
							<p class="description"><?php _e('This interface is to be improved.', 'wp-tag-this'); ?></p>
						</td>
					</tr>
				</table>
			</div>
			
			<?php wp_nonce_field('wptagthis_settings'); ?>
			<input type="hidden" name="action" value="update_settings" />
			<div class="wptagthis-menu">
				<a class="button-secondary" href="<?php echo wp_nonce_url($purl."&action=reset_settings", 'wptagthis_reset_settings'); ?>"><?php _e('Reset settings', 'wp-tag-this'); ?></a>
				<input type="submit" class="button-primary" value="<?php _e('Save all changes', 'wp-tag-this'); ?>" />
			</div>
		</form>
	</div>
</div>
