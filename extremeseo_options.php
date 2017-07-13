<?php

add_action('admin_init', 'extremeseo_options_init' );
add_action('admin_menu', 'extremeseo_options_add_page');

// Init plugin options to white list our options
function extremeseo_options_init(){
	register_setting( 'extremeseo_options', 'extremeseo_linksperpost', '' );
        register_setting( 'extremeseo_options', 'extremeseo_headertext', '' );

}

function extremeseo_options_add_page() {
	add_options_page('ExtremeSEO Options', 'ExtremeSEO Options', 'manage_options', 'extremeseo_options', 'extremeseo_optionspage');
}

function extremeseo_optionspage() {
	?>
	<div class="wrap">
		<h2>ExtremeSEO Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('extremeseo_options'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Links Per Post: (We recommend 3 and a max of 5)</th>
					<td><input type="text" name="extremeseo_linksperpost" value="<?php echo get_option('extremeseo_linksperpost'); ?>" /></td>
				</tr>
			<tr valign="top"><th scope="row">Header Text: (Heading that displays over network links)</th>
                                        <td><input type="text" name="extremeseo_headertext" value="<?php echo get_option('extremeseo_headertext'); ?>" /></td>
                                </tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}


