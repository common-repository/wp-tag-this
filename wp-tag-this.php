<?php
/*
Plugin Name: WP Tag This!
Plugin URI: http://antonioandra.de/
Description: 
Version: 1.3
Author: António Andrade
Author URI: http://antonioandra.de
License: GPL2
*/
/*  Copyright 2012-2014  António Andrade  (email : antonio@antonioandra.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("WPtagthis")) {
	class WPtagthis {
	
		public $data;
		
		function WPtagthis() { 
			$this->data=get_option('WPtagthis');
			}
			
		function ajax(){
			if(			is_single() 
				and  $_POST['tagthisajax']==1 
				and !empty($_POST['tag'])
				and !empty($_POST['vote'])
				){
				$this->tagging_input($_POST['tag'], $_POST['vote']);
				$this->tag_this_ui();
				exit;
				}
			}
		function check_first_run(){
			
			}
		
		function all_tags_array(){
			$tags=get_tags();
			$tags_array=array();
			foreach($tags as $tag){
				$tags_array[]=$tag->name;
				}
			return $tags_array;
			}
		
		function tag_this_ui(){
			global $post;
			$votes=get_post_meta($post->ID, "_tag_this_votes", true);
			$tags=wp_get_post_tags($post->ID); // this can be done for any tax in the future
			echo "<div class='tag-this'>";
				//print_r(get_tags());
				echo "<input type='hidden' class='available-tags' value='".implode(",",$this->all_tags_array())."'/>";
				echo "<form method='post' action='".get_permalink()."' class='tagthis-input'><input type='text' name='tagthis'/><input type='submit' value='Tag This!'/></form>";
				echo "<ul class='tagthis-taglist'>";
				foreach($tags as $tag){
					echo "	<li>
									<a href='".get_tag_link($tag->term_id)."'>".$tag->name."</a>";
					if($this->data['show_vote_number']){
						// echo	" <span class='vote-count'>(".(count($votes[$tag->name])-1).")</span>";
						echo	" <span class='vote-count'>(".count($votes[$tag->name]['count']).")</span>";
						}
					echo "		<a class='tagthis-upvote' href='?tagthis=".$tag->name."&tagthisvote=add' data-tag='".$tag->name."' data-vote='add'>(+)</a>
									<a class='tagthis-downvote' href='?tagthis=".$tag->name."&tagthisvote=remove' data-tag='".$tag->name."' data-vote='remove'>(-)</a>
								</li>";
					}
				echo "</ul>";			
			echo "</div>";
			}
		
		function tag_this_input(){
			if(isset($_POST['tagthis']) || isset($_GET['tagthis'],$_GET['tagthisvote'])){
				$this->tagging_input($_REQUEST['tagthis'], $_REQUEST['tagthisvote']);
				}
			}
		
		// Process input
		
		function tagging_input($tag, $vote="add"){
			global $post;
			// check whether tag exists
			$tag=trim($tag);
			
			if($this->data['lowercase_tags']){
				$tag=strtolower($tag);
				}

            $blocked_tags=$this->data['blocked_tags']?$this->data['blocked_tags']:array();
				
			if(in_array($tag, $blocked_tags)) return;
			
			if(isset($this->data['synonyms'][$tag])) $tag=$this->data['synonyms'][$tag];
			
			$vote=($vote=="add")?1:-1;
			$ip=$this->get_client_ip();
			$votes=get_post_meta($post->ID, "_tag_this_votes", true)!=""?get_post_meta($post->ID, "_tag_this_votes", true):array();
			$tags=wp_get_post_tags($post->ID); // this can be done for any tax in the future
			
			# initial boost
			if(!isset($votes[$tag]["count"]) && $_GET['tagthisvote']!="remove"){
				$vote=$this->data['first_vote_weight']?$this->data['first_vote_weight']:2;
				}
			
			/* If user has voted before remove it */
			if(isset($votes[$tag][$ip])){
				$old_vote=$votes[$tag][$ip];
				$votes[$tag]["count"]-=$old_vote;
				}
				
			/* Register user vote */
			$votes[$tag][$ip]=$vote;
			$votes[$tag]["count"]+=$vote;
				
			if($votes[$tag]["count"]>0){
				wp_set_post_tags( $post->ID, $tag, true ); //append // this can be done for any other tax
				}
			else{
				unset($votes[$tag]);
				$insert_tags=" ";
				for($i=0; $i<=count($tags); $i++){
					if($tags[$i]->name==$tag){ unset($tags[$i]); }
					else{
						$insert_tags.=$tags[$i]->name.",";
						}
					}
				wp_set_post_tags( $post->ID, $insert_tags, false );
				}
			if( function_exists( 'prune_super_cache' ) ){
				global $cache_path;
				prune_super_cache( $cache_path, true );
				}
			update_post_meta($post->ID, "_tag_this_votes", $votes);
			}
		
		// Method to get the client ip address
		function get_client_ip() {
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
			}

		function configure_menu(){
			if(current_user_can("administrator")){
				$page=add_submenu_page("options-general.php","WP Tag This!", "Tag This!", 6, __FILE__, array('WPtagthis','admin_page'));
				add_action('admin_print_scripts-'.$page, array('WPtagthis','admin_page_script'));
				add_action('admin_print_styles-'.$page, array('WPtagthis','admin_page_style'));
				}
			}
		function admin_page_script(){
			wp_enqueue_script("wp-tag-this-js", WP_PLUGIN_URL . '/wp-tag-this/wp-tag-this-ui.js');  
			}
		function admin_page_style(){
			wp_enqueue_style("wp-tag-this-css", WP_PLUGIN_URL . '/wp-tag-this/wp-tag-this-ui.css');  
			}
		function admin_page(){
			include (dirname (__FILE__).'/wp-tag-this-ui.php');
			}
		function admin_page_action(){
			$this->check_first_run();
			$action=$_REQUEST['action'];
			global $echo;
			if(isset($action)){
				switch($action){
					case 'hide_donation_message':
						$this->data['donation_hidden_time']=time()+ 90 * 24 * 60 * 60;
						update_option('WPtagthis',$this->data);
						break;
					case 'update_settings':
						# first donation hidding time 'now'
						if(!$this->data['donation_hidden_time']){
							$this->data['donation_hidden_time']=time();
							}
						# nonce
						if(!check_admin_referer( 'wptagthis_settings')){
							die("You have no permission to do this.");
							}
						foreach(array("first_vote_weight", "show_vote_number", "lowercase_tags", "jquery_ui_theme") as $key){
							$this->data[$key]=$_POST[$key];
							}
							
						$this->data["blocked_tags"]=preg_split("/\s*,\s*|[\r\n]+/", $_POST['blocked_tags'], -1, PREG_SPLIT_NO_EMPTY);
						
						$this->data["synonyms_input"]=$_POST['synonyms'];
						$syn_rules = preg_split("/[\r\n]+/", $_POST['synonyms'], -1, PREG_SPLIT_NO_EMPTY);
						foreach($syn_rules as $r){
							$p=preg_split("/\s*=\s*/", $r, -1, PREG_SPLIT_NO_EMPTY);
							$resulting_word=$p[0];
							foreach(preg_split("/\s*,\s*/", $p[1], -1, PREG_SPLIT_NO_EMPTY) as $syn){
								$this->data["synonyms"][$syn]=$resulting_word;
								}
							}
						
						update_option("WPtagthis",$this->data);
						break;
					case 'reset_settings':
						# nonce
						if(!check_admin_referer( 'wptagthis_reset_settings')){
							die("You have no permission to do this.");
							}
						delete_option('WPtagthis');
						$this->data=array();
						$echo.=__('All settings reset.', 'wp-tag-this');
						break;
					default:
						break;
					}
				}
			}
		}
	}
if (class_exists("WPtagthis")) {
	$WPtagthis = new WPtagthis();
	}
if (isset($WPtagthis)) {
	add_action('admin_menu', array($WPtagthis,'configure_menu'));
	add_action('template_redirect',array($WPtagthis,'ajax'));
	
	wp_enqueue_script( 'wp-tag-this-frontend', plugins_url('/', __FILE__). 'wp-tag-this-frontend.js', array('jquery'), '1.0' );
	wp_enqueue_script( 'jqueryui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js' );
	if($WPtagthis->data['jquery_ui_theme']){
		if($WPtagthis->data['jquery_ui_theme']!="NONE"){
			wp_enqueue_style( 'jqueryuistyle','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/'.$WPtagthis->data['jquery_ui_theme'].'/jquery-ui.css' );
			}
		}
	else{
		wp_enqueue_style( 'jqueryuistyle','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/base/jquery-ui.css' );
		}
	
	add_action('tag_this', array($WPtagthis,'tag_this_input'));
	add_action('tag_this', array($WPtagthis,'tag_this_ui'));
	
	function TagThis(){
		do_action("tag_this");
		}
	}
	
?>