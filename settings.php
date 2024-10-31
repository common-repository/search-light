<?php
$itsas_search_form_id = $_POST['txtSearchFormID'];
$itsas_search_field_id = $_POST['txtSearchFieldID'];
$itsas_top = $_POST['txtTop'];
$itsas_left = $_POST['txtLeft'];
$itsas_page = $_POST['txtPage'];
$itsas_post = $_POST['txtPost'];
$itsas_result_text = $_POST['txtResultText'];
$itsas_no_result_text = $_POST['txtNoResultText'];
$itsas_page_count = $_POST['txtPageCount'];
$itsas_post_count = $_POST['txtPostCount'];
$itsas_all_result_display = $_POST['txtAllDisplayText'];
$itsas_display_thumbnails = $_POST['chbDisplayThumbnails'];

$itsas_theme = $_POST['sltTheme'];
$itsas_themes = array();
fillThemes();
$itsas_msg = array();
$itsas_valid_data = true;

//echo ITSAS_THEME_URL. '<br>';
//echo ITSAS_THEME_DIR. '<br>';


function fillThemes(){
	global $itsas_themes;
	$dir = ITSAS_THEME_DIR;
	if (is_dir($dir)) {
	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	        	$currentItemDir = $dir.'/'.$file;
	        	$currentItemUrl = ITSAS_THEME_URL.'/'.rawurlencode($file);
	        	if (is_file($currentItemDir . '/searchLight.css')){	        		
	        		$name = getThemeName($currentItemDir . '/searchLight.css');
	        		if ($name !== ""){
	        			$itsas_themes[$currentItemUrl] = $name; 
	        		}
	        	}
	        }
	        closedir($dh);
	    }
	}
	//print_r($itsas_themes);
}
function getThemeName($file){
	$name = "";
	$handle = @fopen($file, "r");
	if ($handle) {
	    while (!feof($handle)) {
	        $buffer = fgets($handle);
	        if (preg_match('/Theme Name:(.+)/i',$buffer,$matches) === 1){
	        	$name = $matches[1];
	        	break;
	        }
	    }
	    fclose($handle);
	}
	return $name;
}
if ($_POST['btnSubmit'] != ""){
	if (empty($itsas_search_form_id)){
		$itsas_msg[] = "Please entry Search Form ID.";
		$itsas_valid_data = false;
	}
	if (empty($itsas_search_field_id)){
		$itsas_msg[] = "Please entry Sie Search Field ID.";
		$itsas_valid_data = false;
	}
	if (!is_numeric($itsas_top)){
		$itsas_msg[] = "Top must Integer.";
		$itsas_valid_data = false;
	}
	if (!is_numeric($itsas_left)){
		$itsas_msg[] = "Left must Integer.";
		$itsas_valid_data = false;
	}
	if (empty($itsas_page)){
		$itsas_msg[] = "Please entry Page Label.";
		$itsas_valid_data = false;
	}
	if (empty($itsas_post)){
		$itsas_msg[] = "Please entry Post Label.";
		$itsas_valid_data = false;
	}
	if (empty($itsas_result_text)){
		$itsas_msg[] = "Please entry Results Label.";
		$itsas_valid_data = false;
	}
	if (empty($itsas_no_result_text)){
		$itsas_msg[] = "Please entry NoResults Label.";
		$itsas_valid_data = false;
	}
	if (!is_numeric($itsas_page_count)){
		$itsas_msg[] = "Page Count must Integer";
		$itsas_valid_data = false;
	}
	if (!is_numeric($itsas_post_count)){
		$itsas_msg[] = "Post Count must Integer";
		$itsas_valid_data = false;
	}
	if (empty($itsas_all_result_display)){
		$itsas_msg[] = "Please entry Result Display text";
		$itsas_valid_data = false;
	}
	
	if ($itsas_valid_data){
		/*
		echo 'get_magic_quotes_gpc:'.get_magic_quotes_gpc().'<br>';
		echo 'get_magic_quotes_runtime:'.get_magic_quotes_runtime().'<br>';
		echo 'ini_get(magic_quotes_sybase):'.ini_get('magic_quotes_sybase').'<br>';
		echo '$itsas_page:'.$itsas_page.'<br>';
		echo '$_POST["txtPage"]'.$_POST['txtPage'].'<br>';
		*/
		
		//if (get_magic_quotes_gpc() === 1){
			$itsas_page = stripslashes($itsas_page);
			$itsas_post = stripslashes($itsas_post);
			$itsas_result_text = stripslashes($itsas_result_text);
			$itsas_no_result_text = stripslashes($itsas_no_result_text);
			$itsas_all_result_display = stripslashes($itsas_all_result_display);
		//}
		
		update_option('itsas_search_form_id', $itsas_search_form_id);
		update_option('itsas_search_field_id', $itsas_search_field_id);
		update_option('itsas_top',$itsas_top);
		update_option('itsas_left',$itsas_left);
		update_option('itsas_page',$itsas_page);
		update_option('itsas_post',$itsas_post);
		update_option('itsas_result_text',$itsas_result_text);
		update_option('itsas_no_result_text',$itsas_no_result_text);
		update_option('itsas_displayed_page_count',$itsas_page_count);
		update_option('itsas_displayed_post_count',$itsas_post_count);
		update_option('itsas_all_result_display',$itsas_all_result_display);
		update_option('itsas_theme',$itsas_theme);
		update_option('itsas_display_thumbnails',$itsas_display_thumbnails);
		$itsas_msg[] = "Settings saved.";
	}
}else{
	$itsas_search_form_id = get_option('itsas_search_form_id');
	$itsas_search_field_id = get_option('itsas_search_field_id');
	$itsas_top = get_option('itsas_top');
	$itsas_left = get_option('itsas_left');
	$itsas_page = get_option('itsas_page');
	$itsas_post = get_option('itsas_post');
	$itsas_result_text = get_option('itsas_result_text');
	$itsas_no_result_text = get_option('itsas_no_result_text');
	$itsas_page_count = get_option('itsas_displayed_page_count');
	$itsas_post_count = get_option('itsas_displayed_post_count');
	$itsas_all_result_display = get_option('itsas_all_result_display');
	$itsas_display_thumbnails = get_option('itsas_display_thumbnails');
}
?>
<div class="wrap">
	<h2>Search Light Settings</h2>
	<?php if (count($itsas_msg) > 0):?>
  	<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
 	<?php foreach($itsas_msg as $msg):?>
    	<p><?php echo $msg;?></p>
  	<?php endforeach;?>
  	</div>
  	<?php endif;?>
	<form method="post">
    
		<h3>Theme</h3>
		<p>Select the visual theme for your page.
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Theme:</th>
					<td>
						<select name="sltTheme">
						<?php 
						foreach($itsas_themes as $url=>$name){
							echo '<option value="'.$url.'"';
							if (get_option('itsas_theme') == $url){
								echo ' selected="selected" ';
							}
							echo '>'.htmlspecialchars ($name).'</option>';
						}
						
						?>
						</select>
					</td>
				</tr>				
			</tbody>
		</table>    
    	
    	<h3>Post Thumbnail Images</h3>
		<p>
			Select if you want to display Wordpress Post Thumbnails in your search-results.<br/>
			(Wordpress 2.9 or later and theme-support required.)
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Display Thumbnails:</th>
					<td> <input type="checkbox" name="chbDisplayThumbnails" value="1" <?php if ($itsas_display_thumbnails === '1') echo 'checked="checked"'; ?> /></td>
				</tr>
			</tbody>
		</table>
    	
		<h3>Position</h3>
		<p>Fine-tune the position of the plugin for your page design.</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Top:</th>
					<td><input type="text" name="txtTop" value="<?php echo $itsas_top;?>" /> <span class="description">Pixel</span></td>
				</tr>
				<tr valign="top">
					<th>Left:</th>
					<td><input type="text" name="txtLeft" value="<?php echo $itsas_left;?>" /> <span class="description">Pixel</span></td>
				</tr>
			</tbody>
		</table>
		
		<h3>Translation</h3>
		<p>Change the text displayed in the search-results panel.</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Pages:</th>
					<td><input type="text" name="txtPage" value="<?php echo htmlspecialchars($itsas_page);?>" /> <span class="description"></span></td>
				</tr>
				<tr valign="top">
					<th>Posts:</th>
					<td><input type="text" name="txtPost" value="<?php echo htmlspecialchars($itsas_post);?>" /> <span class="description"></span></td>
				</tr>
				<tr valign="top">
					<th>Search-results:</th>
					<td><input type="text" name="txtResultText" value="<?php echo htmlspecialchars($itsas_result_text);?>" /> <span class="description"></span></td>
				</tr>
				<tr valign="top">
					<th>Empty Search-result:</th>
					<td><input type="text" name="txtNoResultText" value="<?php echo htmlspecialchars($itsas_no_result_text);?>" /> <span class="description"></span></td>
				</tr>
				<tr valign="top">
					<th>View all search results:</th>
					<td>
						<input type="text" name="txtAllDisplayText" value="<?php echo htmlspecialchars($itsas_all_result_display);?>" /> <span class="description"></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h3>Number of results</h3>
		<p>Change the number of results displayed for pages and posts.</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Pages:</th>
					<td>
						<input type="text" name="txtPageCount" value="<?php echo $itsas_page_count;?>" />
					</td>
				</tr>
				<tr valigh="top">
					<th>Posts:</th>
					<td>
						<input type="text" name="txtPostCount" value="<?php echo $itsas_post_count;?>" />
					</td>
				</tr>
			</tbody>
		</table>
        		
		<h3>Advanced</h3>
		<p>We position the result-panel by searching for the ID's of the search form
			and it's input field. If you use the default values, you probably won't need to
			change anything here. If you did, enter the modified values below.
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>Searchform ID:</th>
					<td>
						<input type="text" name="txtSearchFormID" value="<?php echo $itsas_search_form_id;?>" />
					</td>
				</tr>
				<tr valign="top">
					<th>Input-field ID:</th>
					<td>
						<input type="text" name="txtSearchFieldID" value="<?php echo $itsas_search_field_id;?>" />
					</td>
				</tr>
			</tbody>
		</table>
				
		<p class="submit">
			<input class="button-primary" type="submit" name="btnSubmit" value="Save Changes" />
		</p>
		
	</form>
</div>