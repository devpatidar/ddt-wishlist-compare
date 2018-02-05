<?php
/**
 * Dwc Plugin Settins Page
 */

/**
 * Register a custom menu page.
 */
if (!function_exists('dwc_register_my_custom_menu_page')) {
	function dwc_register_my_custom_menu_page(){
		global $submenu;
		add_menu_page( __( 'DDT Settings', 'dwc' ),'DDT Settings','manage_options','dwc_home','dwc_settings_page','dashicons-admin-settings');
		add_submenu_page( 'dwc_home', 'DWC Compare', 'DDT Compare','manage_options', 'dwc_compare_page', 'dwc_compare_page_func');
		$submenu['dwc_home'][0][0] = 'DWC Wishlist'; // for change first submenu name k2Dashboard to Register
	}
	add_action( 'admin_menu', 'dwc_register_my_custom_menu_page' );
}else{
	function dwc_register_my_custom_menu_pages(){
		add_submenu_page( 'dwc_home', 'DDT Wishlist', 'DDT Wishlist','manage_options', 'dwc_wishlist_page', 'dwc_settings_page');
		add_submenu_page( 'dwc_home', 'DDT Compare', 'DDT Compare','manage_options', 'dwc_compare_page', 'dwc_compare_page_func');
	}
	add_action( 'admin_menu', 'dwc_register_my_custom_menu_pages' );
}

 
/**
 * Display a custom menu page
 */
function dwc_settings_page(){

	$reseted_plugin_data = '';
	if (isset($_POST['dwc_wishlist_submit_btn']) && $_POST['dwc_wishlist_submit_btn'] ) {
		
		$dwc_shop_wishlist_btn 			= (isset($_POST['dwc_shop_wishlist_btn'])) ? $_POST['dwc_shop_wishlist_btn'] : '';
		$dwc_shop_wishlist_btn_text 	= (isset($_POST['dwc_shop_wishlist_btn_text'])) ? $_POST['dwc_shop_wishlist_btn_text'] : '';

		$dwc_single_wishlist_btn 		= (isset($_POST['dwc_single_wishlist_btn'])) ? $_POST['dwc_single_wishlist_btn'] : '';
		$dwc_single_wishlist_btn_text 	= (isset($_POST['dwc_single_wishlist_btn_text'])) ? $_POST['dwc_single_wishlist_btn_text'] : '';
		$dwc_shop_wishlist_browse_page 	= (isset($_POST['dwc_shop_wishlist_browse_page'])) ? $_POST['dwc_shop_wishlist_browse_page'] : '';
		
		$dwc_wishlist_options_data = array(
			
			'dwc_shop_wishlist_btn' 			=>	$dwc_shop_wishlist_btn,
			'dwc_shop_wishlist_btn_text' 		=>	$dwc_shop_wishlist_btn_text,
			'dwc_single_wishlist_btn' 			=>	$dwc_single_wishlist_btn,
			'dwc_single_wishlist_btn_text' 		=>	$dwc_single_wishlist_btn_text,
			'dwc_shop_wishlist_browse_page' 	=>	$dwc_shop_wishlist_browse_page,
		);

		// Update Theme Options
		update_option( 'dwc_wishlist_options', $dwc_wishlist_options_data, '', 'yes' );

		//$reseted_plugin_data = '<p>Data Saved!</p>';

	} // End process of save data

    ?>
    <div class="wrap">
		<h1>Dwc Plugins Settings</h1>

			<div class="widefat">
				<?php if ($reseted_plugin_data) { ?>
					<!-- Update Notificatiion -->
					<div id="message" class="updated notice notice-success is-dismissible">
						<?php echo $reseted_plugin_data; ?>
						<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>
				
				<form class="dwc_setting_form" method="post" action="">

					<?php settings_fields( 'dwc_wishlist_options_group' ); ?>
					<?php do_settings_sections( 'dwc_wishlist_options_group' ); ?>

					<table class="widefat striped">
						<tbody>
							
							<!-- Shop Page Options -->
							
							<tr><td class="row-title">DWC Shop Page Options</td></tr>
							<tr>
								<th scope="row"><label>Show Wishlist Button On Shop Page</label></th>
								<?php $shop_wishlist_chk = dwc_get_wishlist_options('dwc_shop_wishlist_btn'); ?>
								<?php $shop_wishlist_chk = ($shop_wishlist_chk) ? 'checked' : ''; ?>
								<td><input type="checkbox" class="regular-text" <?php echo $shop_wishlist_chk; ?> name="dwc_shop_wishlist_btn"></td>
							</tr>
							<tr>
								<th scope="row"><label>Wishlist Button Text</label></th>
								<td><input type="text" class="regular-text" name="dwc_shop_wishlist_btn_text" value="<?php echo dwc_get_wishlist_options('dwc_shop_wishlist_btn_text'); ?>"></td>
							</tr>

							<!-- Single Page Options -->
							
							<tr><td class="row-title">Single Product Page Options</td></tr>
							<tr>
								<th scope="row"><label>Show Wishlist Button on Single Page</label></th>
								<?php $single_wishlist_chk = dwc_get_wishlist_options('dwc_single_wishlist_btn'); ?>
								<?php $single_wishlist_chk = ($single_wishlist_chk) ? 'checked' : ''; ?>
								<td><input type="checkbox" class="regular-text" <?php echo $single_wishlist_chk; ?> name="dwc_single_wishlist_btn"></td>
							</tr>
							<tr>
								<th scope="row"><label>Wishlist Button Text</label></th>
								<td><input type="text" class="regular-text" name="dwc_single_wishlist_btn_text" value="<?php echo dwc_get_wishlist_options('dwc_single_wishlist_btn_text'); ?>"></td>
							</tr>

							<!-- Other Options -->
							<tr><td class="row-title">DWC Other Options</td></tr>

							<tr>
								<th scope="row"><label>Select Page for browse Wishlist</label></th>
								<td>
									<select name="dwc_shop_wishlist_browse_page">
										<option><?php echo esc_attr( __( 'Select page' ) ); ?></option>
										<?php 
										$pages = get_pages(); 
										foreach ( $pages as $page ) {
											if (dwc_get_wishlist_options('dwc_shop_wishlist_browse_page') == $page->ID) {
												echo '<option value="' .$page->ID. '" selected >'.$page->post_title.'</option>';	
											}else{
												echo '<option value="' .$page->ID. '">'.$page->post_title.'</option>';
											}
										}
										?>
									</select><p>[dwc_wishlist]</p>
								</td>
							</tr>
							
							<tr class="submit">
								<td>
									<input type="submit" name="dwc_wishlist_submit_btn" class="button button-primary" value="Save Changes" />
								</td>
							</tr>

						</tbody>
					</table>
				</form>
			</div>
		</div>
<?php
	
	add_action( 'admin_init', 'dwc_register_settings' );
	function dwc_register_settings() { // whitelist options
		register_setting( 'dwc_wishlist_options_group', 'dwc_wishlist_options' );
	}	

}

/*
 * Compare admin setting function
 */

function dwc_compare_page_func(){

	$reseted_plugin_data = '';
	if (isset($_POST['dwc_compare_submit_btn']) && $_POST['dwc_compare_submit_btn'] ) {
		
		$dwc_shop_compare_btn 			= (isset($_POST['dwc_shop_compare_btn'])) ? $_POST['dwc_shop_compare_btn'] : '';
		$dwc_shop_compare_btn_text 		= (isset($_POST['dwc_shop_compare_btn_text'])) ? $_POST['dwc_shop_compare_btn_text'] : '';
		$dwc_single_compare_btn 		= (isset($_POST['dwc_single_compare_btn'])) ? $_POST['dwc_single_compare_btn'] : '';
		$dwc_single_compare_btn_text 	= (isset($_POST['dwc_single_compare_btn_text'])) ? $_POST['dwc_single_compare_btn_text'] : '';
		$dwc_shop_compare_browse_page 	= (isset($_POST['dwc_shop_compare_browse_page'])) ? $_POST['dwc_shop_compare_browse_page'] : '';

		$dwc_compare_options_data = array(
			
			'dwc_shop_compare_btn' 			=>	$dwc_shop_compare_btn,
			'dwc_shop_compare_btn_text' 	=>	$dwc_shop_compare_btn_text,
			'dwc_single_compare_btn' 		=>	$dwc_single_compare_btn,
			'dwc_single_compare_btn_text' 	=>	$dwc_single_compare_btn_text,
			'dwc_shop_compare_browse_page' 	=>	$dwc_shop_compare_browse_page,
			
		);
		// Update Theme Options
		update_option( 'dwc_compare_options', $dwc_compare_options_data, '', 'yes' );
		//$reseted_plugin_data = '<p>Data Saved!</p>';

	} // End process of save data

    ?>
    <div class="wrap">
		<h1>Dwc Plugin Settings</h1>

			<div class="widefat">
				<?php if ($reseted_plugin_data) { ?>
					<!-- Update Notificatiion -->
					<div id="message" class="updated notice notice-success is-dismissible">
						<?php echo $reseted_plugin_data; ?>
						<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>

				<form class="dwc_setting_form" method="post" action="">

					<?php settings_fields( 'dwc_compare_options_group' ); ?>
					<?php do_settings_sections( 'dwc_compare_options_group' ); ?>

					<table class="widefat striped">
						<tbody>
							
							<!-- Compare Page on shop page -->
							<tr><td class="row-title">Dwc Shop Page Options</td></tr>
							<tr>
								<th scope="row"><label>Show Compare Button</label></th>
								<?php $shop_compare_chk = dwc_get_compare_options('dwc_shop_compare_btn'); ?>
								<?php $shop_compare_chk = ($shop_compare_chk) ? 'checked' : ''; ?>
								<td><input type="checkbox" class="regular-text" <?php echo $shop_compare_chk; ?> name="dwc_shop_compare_btn"></td>
							</tr>
							<tr>
								<th scope="row"><label>Compare Button Text </label></th>
								<td><input type="text" class="regular-text" name="dwc_shop_compare_btn_text" value="<?php echo dwc_get_compare_options('dwc_shop_compare_btn_text'); ?>"></td>
							</tr>


							<!-- Compare on single page -->
							<tr><td class="row-title">Dwc Single Page Options</td></tr>
							<tr>
								<th scope="row"><label>Show Compare Button on Single Page</label></th>
								<?php $single_compare_chk = dwc_get_compare_options('dwc_single_compare_btn'); ?>
								<?php $single_compare_chk = ($single_compare_chk) ? 'checked' : ''; ?>
								<td><input type="checkbox" class="regular-text" <?php echo $single_compare_chk; ?> name="dwc_single_compare_btn"></td>
							</tr>
							<tr>
								<th scope="row"><label>Compare Button Text</label></th>
								<td><input type="text" class="regular-text" name="dwc_single_compare_btn_text" value="<?php echo dwc_get_compare_options('dwc_single_compare_btn_text'); ?>"></td>
							</tr>


							<!-- Other options -->
							<tr><td class="row-title">Other Options</td></tr>
							
							<tr>
								<th scope="row"><label>Select Page for browse Wishlist</label></th>
								<td>
									<select name="dwc_shop_compare_browse_page">
										<option><?php echo esc_attr( __( 'Select page' ) ); ?></option>
										<?php 
										$pages = get_pages(); 
										foreach ( $pages as $page ) {
											if (dwc_get_compare_options('dwc_shop_compare_browse_page') == $page->ID) {
												echo '<option value="' .$page->ID. '" selected >'.$page->post_title.'</option>';
											}else{
												echo '<option value="' .$page->ID. '">'.$page->post_title.'</option>';
											}
										}
										?>
									</select>
							</tr>


							
							
							
							
							<tr class="submit">
								<td>
									<input type="submit" name="dwc_compare_submit_btn" class="button button-primary" value="Save Changes" />
									<!--<input type="submit" name="dwc_reset_options" class="button button-primary" value="Reset Plugin Data" />-->
								</td>
							</tr>

						</tbody>
					</table>
				</form>
			</div>
		</div>
<?php
	
	add_action( 'admin_init', 'dwc_compare_register_settings' );
	
	function dwc_compare_register_settings() { // whitelist options
		register_setting( 'dwc_compare_options_group', 'dwc_compare_options' );
	}	

}