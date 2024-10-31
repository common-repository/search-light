<?php
/*
Plugin Name: Search Light
Plugin URI: http://www.itsystempartner.de/index.php/technologie/wordpress-plugins/searchlight/
Description: Provides an ajax search-dropdown for WordPress.
Version: 1.4.2
Author: Daniel Kowalski, Sai Liu, IT Systempartner
Author URI: http://www.itsystempartner.de
*/
define("ITSAS_SETTINGS_FILE","search-light/settings.php");
define("ITSAS_THEME_DIR",WP_PLUGIN_DIR."/search-light/themes");
define("ITSAS_THEME_URL",WP_PLUGIN_URL."/search-light/themes");
function itsas_onAction_activate(){
	update_option('itsas_version', '2.8.1');
	add_option('itsas_search_form_id', 'searchform');
	add_option('itsas_search_field_id', 's');
	add_option('itsas_top', '0');
	add_option('itsas_left', '0');
	add_option('itsas_page', 'Seiten');
	add_option('itsas_post', 'Artikel');
	add_option('itsas_result_text', 'Treffer');
	add_option('itsas_no_result_text', 'Keine Treffer gefunden');
	add_option('itsas_displayed_page_count', '10');
	add_option('itsas_displayed_post_count', '10');
	add_option('itsas_all_result_display', 'Alle Treffer anzeigen');
	add_option('itsas_theme',ITSAS_THEME_URL.'/default');
	add_option('itsas_display_thumbnails', '1');
}
register_activation_hook(__FILE__,"itsas_onAction_activate");

function itsas_onAction_admin_menu()
{
	$base_url = ITSAS_SETTINGS_FILE;
	add_options_page("Search Light Settings", "Search Light", 7, $base_url);
 	
	if( function_exists( 'add_meta_box' )) {
	    add_meta_box( 'itsas_searchlight_box', 'Search Light', 
	                'itsas_searchlight_box', 'post', 'side','high' );
	    add_meta_box( 'itsas_searchlight_box', 'Search Light', 
	                'itsas_searchlight_box', 'page', 'side','high' );
   }
}
add_action('admin_menu', 'itsas_onAction_admin_menu');

function itsas_onAction_init(){
	if ($_GET['itsasAjaxSearch'] == true){
		if ($_GET['a'] == "getSettings"){
			echo '{"pageText":"'.htmlspecialchars (addcslashes  (get_option('itsas_page'),"\\")).'","postText":"'.htmlspecialchars (addcslashes(get_option('itsas_post'),"\\")).'","searchFormID":"'.get_option('itsas_search_form_id').'","searchFieldID":"'.get_option('itsas_search_field_id').'","top":'.get_option('itsas_top').', "left":'.get_option('itsas_left').',"theme":"'.get_option('itsas_theme').'"}';
			exit();
		}
		$key = $_GET['key'];
		itsas_search($key);
		exit();
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('itsys_ajax', WP_PLUGIN_URL."/search-light/ajax.js");
}
add_action('init','itsas_onAction_init');

function add_my_stylesheet() {
	if (get_option('itsas_theme') == FALSE){
		add_option('itsas_theme',ITSAS_THEME_URL.'/default');
	}
	wp_register_style('searchLightStyleSheets', get_option('itsas_theme').'/searchLight.css');
    wp_enqueue_style( 'searchLightStyleSheets');
	/*
    $searchLightStyleUrl = WP_PLUGIN_URL . '/search-light/themes/dark/searchLight.css';
    $searchLightStyleFile = WP_PLUGIN_DIR . '/search-light/themes/dark/searchLight.css';
    if ( file_exists($searchLightStyleFile) ) {
        wp_register_style('searchLightStyleSheets', $searchLightStyleUrl);
        wp_enqueue_style( 'searchLightStyleSheets');
    }
    */
}
add_action('wp_print_styles', 'add_my_stylesheet');

function itsas_searchlight_box($post){
	//global $post;
	echo '<input type="hidden" name="itsas_showin_searchlight" id="itsas_showin_searchlight" value="' . 
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  	// The actual fields for data entry
	echo '<p>';
	echo '<input type="radio" class="searchlightNoneStyle" id="chkShowInSearchlight" name="chkShowInSearchlight" value="itsas_searchlight_showin" ';
	if (get_post_meta($post->ID,'ShowInSearchlight',true) == "true"){
		echo ' checked="checked" ';
	}
	echo '/>';
  	echo '&nbsp;<label for="chkShowInSearchlight">Tagged for Search Light</label>';
  	
  	echo '</p>';
  	// The actual fields for data entry
	echo '<p>';
	echo '<input type="radio" class="searchlightNoneStyle" id="chkNotShowInSearchlight" name="chkShowInSearchlight" value="itsas_searchlight_notshowin" ';
	if (get_post_meta($post->ID,'NotShowInSearchlight',true) == "true"){
		echo ' checked="checked" ';
	}
	echo '/>';
  	echo '&nbsp;<label for="chkNotShowInSearchlight">Hide from Search Light</label>';
  	
  	echo '</p>';
  	echo '<p>';
  	echo '<a href="#" onclick="document.getElementById(\'chkShowInSearchlight\').checked=false;document.getElementById(\'chkNotShowInSearchlight\').checked=false;" title="Clear options">Clear options</a>';
  	echo '</p>';
}

function itsas_onAction_save_post($post_id){
  if ( !wp_verify_nonce( $_POST['itsas_showin_searchlight'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
  // to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    return $post_id;
  }
  
  // Check permissions
  if ( 'post' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }
 
/* version 1.4
  if ($_POST['chkShowInSearchlight'] == 'itsas_searchlight_showin'){
 	update_post_meta($post_id,'ShowInSearchlight','true');
  }else {
  	delete_post_meta($post_id,'ShowInSearchlight','true');
  }
  */
  if ($_POST['chkShowInSearchlight'] == 'itsas_searchlight_showin'){
 	update_post_meta($post_id,'ShowInSearchlight','true');
 	delete_post_meta($post_id,'NotShowInSearchlight','true');
  }else if  ($_POST['chkShowInSearchlight'] == 'itsas_searchlight_notshowin') {
  	update_post_meta($post_id,'NotShowInSearchlight','true');
  	delete_post_meta($post_id,'ShowInSearchlight','true');
  }else{
  	delete_post_meta($post_id,'ShowInSearchlight','true');
  	delete_post_meta($post_id,'NotShowInSearchlight','true');
  }
  
  return $post_id;
   //return $mydata;
}
add_action('save_post', 'itsas_onAction_save_post');

function itsas_sqlWhere($search_key){
	$sql_where = "";
	if ($search_key != ""){	
		$sql_where = "post_status = 'publish' ";
		$search_keys = explode(" ",$search_key);
		
		$count = count($search_keys);
		if ($count < 1){
			return "";
		}else if ($count == 1){
			if ($sql_where != "")
				$sql_where .= " AND ";
			$sql_where .= " (post_title LIKE '%$search_keys[0]%' ";
			$sql_where .= " OR post_content LIKE '%$search_keys[0]%')";
		}else if ($count > 1){
			if ($sql_where != "")
				$sql_where .= " AND ";
			$sql_where .= " (post_title LIKE '%$search_keys[0]%' ";
			$sql_where .= " OR post_content LIKE '%$search_keys[0]%')";
			for($i = 1; $i < $count; $i++){
				$sql_where .= " AND (post_title LIKE '%$search_keys[$i]%' OR post_content LIKE '%$search_keys[$i]%') ";
			}
		}
	}
	return $sql_where;
}
function itsas_result_count($search_key){
	global $wpdb;
	$count = 0;
	$sql_base = "SELECT COUNT(*) FROM {$wpdb->prefix}posts ";
	$sql_where = itsas_sqlWhere($search_key);
	
	if ($sql_where != ""){
		$sql_where = " WHERE " . $sql_where;
		$sql = $sql_base.$sql_where; //echo $sql;
		$count = $wpdb->get_var($sql);
	}else{
		$count = 0;
	}
	return $count;
}
function itsas_result($search_key, $post_type, $beginIx = 0,$endIx = 10){
	global $wpdb;
	$sql_base = "SELECT * FROM {$wpdb->prefix}posts ";
	$sql_where = itsas_sqlWhere($search_key);
	if ($sql_where != ""){
		$sql_where = " WHERE post_type LIKE '$post_type' AND " . $sql_where;
		$sql = $sql_base.$sql_where." ORDER BY post_date desc LIMIT $beginIx,$endIx";
		$results = $wpdb->get_results($sql,ARRAY_A);
	}else{
		$results = null;
	}
	return $results;
}

function itsas_search($search_key){	
	global $wpdb;
	$isShowThumbnail = false;
	if (get_option('itsas_display_thumbnails') === '1' && function_exists('current_theme_supports') && current_theme_supports( 'post-thumbnails' ) ){
		$isShowThumbnail = true;
	}
	
	$pageCount = get_option('itsas_displayed_page_count');
	$postCount = get_option('itsas_displayed_post_count');
	
	echo '<div id="searchLightData" style="display:none"><span id="searchKey">'.$search_key.'</span>';
	//echo '<span id="viewTop">'.get_option('itsas_top').'</span>';
	//echo '<span id="viewLeft">'.get_option('itsas_left').'</span>';
	echo '</div>';
	$count = itsas_result_count($search_key);
	
	$showInPages = itsas_get_show_in_pages();//print_r($showInPages);
	$showInPosts = itsas_get_show_in_posts();
	$showInPostsWithoutSticky = array();
	$stickyPosts = itsas_get_sticky_posts();
	$notShowInPages = itsas_get_not_show_in_pages();
	$notShowInPosts = itsas_get_not_show_in_posts();
	foreach($stickyPosts as $key => $value){
		if ($notShowInPosts[$key]){
			unset($stickyPosts[$key]);
		}
	}
	if ($count > 0 
		|| count($showInPages) > 0
		|| count($showInPosts) > 0
		|| count($stickyPosts) > 0
		){
		echo '<div id="searchLightStatus">';
		echo '<h1>'.$count.' '. get_option('itsas_result_text').'</h1>';
		echo '</div>';
		$results = itsas_result($search_key,'page', 0, $pageCount);
		if ($results || count($showInPages) > 0){
			echo '<h2>'.get_option('itsas_page').'</h2>';
			echo '<ul>';
			// show in search light
			foreach($showInPages as $key=>$title){
				echo '<li>';				
				echo '<a href="'.get_permalink($key).'" >';
				if ($isShowThumbnail){
					echo get_the_post_thumbnail($key);
				}
				echo $title.'</a>';
				echo '</li>';
			}//echo '<li>...........</li>';
			// search reslut
			if ($results)
			foreach($results as $row){
				if ($showInPages[$row['ID']]){
					continue;
				}
				if ($notShowInPages[$row['ID']]){
					continue;
				}
				echo '<li>';				
				echo '<a href="'.get_permalink($row['ID']).'" >';
				if ($isShowThumbnail){
					echo get_the_post_thumbnail($row['ID']);
				}
				echo $row['post_title'].'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		$results = itsas_result($search_key,'post', 0, $postCount);
		itsas_get_sticky_posts();
		if ($results 
			|| count($showInPosts) > 0
			|| count($stickyPosts) > 0
			){
			echo '<h2>'.get_option('itsas_post').'</h2>';
			echo '<ul>';
			// show in search light and is sticky
			foreach($showInPosts as $key=>$title){
				if (is_sticky($key)){
					echo '<li>';				
					echo '<a href="'.get_permalink($key).'" >';
					if ($isShowThumbnail){
						echo get_the_post_thumbnail($key);
					}
					echo $title.'</a>';
					echo '</li>';
					//unset($showInPosts[$key]);
					unset($stickyPosts[$key]);
				}else{
					$showInPostsWithoutSticky[$key] = $title;
				}
			}//echo '<li>...........</li>';
			// show in search
			foreach($showInPostsWithoutSticky as $key=>$title){
					echo '<li>';				
					echo '<a href="'.get_permalink($key).'" >';
					if ($isShowThumbnail){
						echo get_the_post_thumbnail($key);
					}
					echo $title.'</a>';
					echo '</li>';
			}//echo '<li>...........</li>';
			// sticky posts
			if (count($stickyPosts) > 0){
				$sql = "SELECT * FROM {$wpdb->prefix}posts WHERE ID IN(".implode(",", $stickyPosts).") ORDER BY post_date desc";
				//echo $sql;
				$stickyResult = $wpdb->get_results($sql,ARRAY_A);
				if ($stickyResult){
					foreach($stickyResult as $row){
						if ($notShowInPosts[$row['ID']]){
							continue;
						}
						echo '<li>';				
						echo '<a href="'.get_permalink($row['ID']).'" >';
						if ($isShowThumbnail){
							echo get_the_post_thumbnail($row['ID']);
						}
						echo $row['post_title'].'</a>';
						echo '</li>';
					}
				}
			}
			//echo '<li>...........</li>';
			// search reslut
			if ($results)
			foreach($results as $row){
				if ($showInPosts[$row['ID']] || is_sticky($row['ID'])){
					continue;
				}
				if ($notShowInPosts[$row['ID']]){
					continue;
				}
				echo '<li>';				
				echo '<a href="'.get_permalink($row['ID']).'" >';
				if ($isShowThumbnail){
					echo get_the_post_thumbnail($row['ID']);
				}
				echo $row['post_title'].'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		echo '<h3><a href="#" onclick="document.getElementById(itsas_searchFormId).submit(); return false;" title="">'.get_option('itsas_all_result_display').'</a></h3>';
	}else{
		echo '<div id="searchLightStatus">';
		echo '<h1>'.get_option('itsas_no_result_text').'</h1>';
		echo '</div>';
	}
}

function itsas_get_show_in_pages(){
	global $wpdb;
	$sql = "SELECT ID,post_title FROM {$wpdb->prefix}posts p, {$wpdb->prefix}postmeta m WHERE p.ID=m.post_id AND p.post_type='page' AND m.meta_key='ShowInSearchlight' AND m.meta_value='true' AND post_status = 'publish' ORDER BY p.post_date desc ";
	$results = $wpdb->get_results($sql,ARRAY_A);
	$rlt = array();
	if ($results){
		foreach($results as $row){
			$rlt[$row['ID']] = $row['post_title'];
		}
	}
	return $rlt;
}
function itsas_get_show_in_posts(){
	global $wpdb;
	$sql = "SELECT ID,post_title FROM {$wpdb->prefix}posts p, {$wpdb->prefix}postmeta m WHERE p.ID=m.post_id AND p.post_type='post' AND m.meta_key='ShowInSearchlight' AND m.meta_value='true' AND post_status = 'publish' ORDER BY p.post_date desc ";
	$results = $wpdb->get_results($sql,ARRAY_A);
	$rlt = array();
	if ($results){
		foreach($results as $row){
			$rlt[$row['ID']] = $row['post_title'];
		}
	}
	return $rlt;
}
function itsas_get_not_show_in_pages(){
	global $wpdb;
	$sql = "SELECT ID,post_title FROM {$wpdb->prefix}posts p, {$wpdb->prefix}postmeta m WHERE p.ID=m.post_id AND p.post_type='page' AND m.meta_key='NotShowInSearchlight' AND m.meta_value='true' AND post_status = 'publish' ORDER BY p.post_date desc ";
	$results = $wpdb->get_results($sql,ARRAY_A);
	$rlt = array();
	if ($results){
		foreach($results as $row){
			$rlt[$row['ID']] = $row['post_title'];
		}
	}
	return $rlt;
}
function itsas_get_not_show_in_posts(){
	global $wpdb;
	$sql = "SELECT ID,post_title FROM {$wpdb->prefix}posts p, {$wpdb->prefix}postmeta m WHERE p.ID=m.post_id AND p.post_type='post' AND m.meta_key='NotShowInSearchlight' AND m.meta_value='true' AND post_status = 'publish' ORDER BY p.post_date desc ";
	$results = $wpdb->get_results($sql,ARRAY_A);
	$rlt = array();
	if ($results){
		foreach($results as $row){
			$rlt[$row['ID']] = $row['post_title'];
		}
	}
	return $rlt;
}
function itsas_get_sticky_posts(){
	global $wpdb;
	$sticky = get_option('sticky_posts');
	$rlt = array();
	foreach($sticky as $value){
		$rlt[$value] = $value;
	}
	return $rlt;
}
?>