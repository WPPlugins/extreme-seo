<?php

add_action('admin_menu', 'extremeseo_stats_add_page');

function extremeseo_stats_add_page() {
	add_options_page('ExtremeSEO Stats', 'ExtremeSEO Stats', 'manage_options', 'extremeseo_stats', 'extremeseo_statspage');
}

function extremeseo_statspage() {
	?>
	<div class="wrap">
		<h2>ExtremeSEO Stats</h2>
	
<?php

$postinfo = array(
"key" => get_option('extremeseo_licensekey')
);

$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-stats.php');
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
$response = curl_exec($ch);
curl_close($ch);

print $response;

?>

</div>
	<?php	
}


