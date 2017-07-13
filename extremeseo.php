<?php
/*
Plugin Name: Extreme SEO
Plugin URI: http://www.seolinknet.com
Description: By activating this plugin in your wordpress software, you join a network of sites with the plugin installed. The plugin automatically finds posts in th network related to your posts and creates non reciprocal links between the posts of network members. The links are one way, dofollow, and are filtered so that no "spammy" posts are allowed. This provides quality inbound links for SEO purposes to the post pages of all of our network member's sites. The inbound links also provide cross site traffic to increase your readership. This is an effortless way to link building and climb the search engine ranks while also increasing your Google Page Rank.
Version: 1.3.4
Author: Stephen Madison
*/

$GLOBALS['plugin_version'] = '1.3.4';

ini_set('memory_limit','500M');

register_activation_hook( __FILE__, 'extremeseo_activate' );
register_deactivation_hook( __FILE__, 'extremeseo_deactivate' );

add_action('publish_post', 'extremeseo_addpost');
add_action('trash_post', 'extremeseo_deletepost');
add_action('wp_head', 'extremeseo_googleverify');

add_filter('the_content', 'extremeseo_postcontent');

global $GLOBALS, $wpdb, $wp_version;

include("extremeseo_options.php");
include("extremeseo_stats.php");

function extremeseo_activate() {

global $GLOBALS, $wpdb, $wp_version;

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');

if (version_compare($wp_version, "2.8.0", "<")) {

mail('support@seolinknet.com','Extreme SEO Plugin Client Side Activation Error',"There was a WP Version number error while activating the plugin on " . $siteurl . " -- " . $admin_email . " -- " . $wp_version);

$error = "Your version of Wordpress is " . $wp_version . " and this plugin requires at least version 2.8.0 -- Please use your browser's back button and then upgrade your version of Wordpress";

wp_die($error);

}#End of version check if.

####
## Since the automatic upgrade process does not seem to be running the deactivation function
## we are just going to wipe out the license key from the local wp db if there is one, remove the member
## from the network server side, assign a new key, and rebuild the member server side on every activation
## to defeat licensing and duplicate member issues
####

####
## Get the previous license if it exists so we can use it to purge the old records
####

$previous_key = get_option("extremeseo_licensekey");

if(empty($previous_key)) {

####
## Create a license key for the site. 
####

$stamp = date("Ymdhis");
$ip = $_SERVER['SERVER_ADDR'];
$key = $stamp . "-" . $ip;
$key = str_replace(".", "", "$key");

add_option("extremeseo_licensekey", "$key", '', 'yes');

}else {

####
## We had a previous key so lets purge the records from the server for the previous key
## and then rebuild using the previous key so that stats tracking will not get reset
####

$info = array(
"key" => $previous_key,
"sitename" => $siteurl,
"admin_email" => $admin_email
);

$ch = curl_init('http://www.seolinknet.com/wp-codebase/extremeseo-purge.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $info);
$purge_response = curl_exec($ch);
curl_close($ch);

if (empty($purge_response) or $purge_response == 'error') {

$purge_answer = print_r($purge_response, TRUE);

mail('support@seolinknet.com','Extreme SEO Plugin Client Side Activation Error',"There was an error where the wordpress site did not get a valid response from the purge curl call" . $purge_answer);

$error = "There was a problem purging your previous records from our index before we begin the re-indexing process. We have been notified of the error. Please try again later";

wp_die($error);

}#End of purge_response if.

####
## If we made it this far, lets set the key value to the previous key.
####

$key = $previous_key;

}#End of previous key exists if.

####
## Add option does nothing if the option already exists so this will add the options on initial
## activation and do nothing to the exsiting values on reactivations
####

add_option("extremeseo_linksperpost", '3', '', 'yes');
add_option("extremeseo_headertext", 'Related Web Articles', '', 'yes');

####
## Now that the necessary info for activation has been set, lets check to see that everything we need is available
####

if (empty($siteurl) or empty($admin_email)) {

mail('support@seolinknet.com','Extreme SEO Plugin Client Side Activation Error',"There was an error where the wordpress site did not have a siteurl or admin email option set");

$error = "Your Wordpress site is missing some info required by this plugin. Please make sure that your Wordpress options are set for BOTH siteurl and admin email. Once you have set these values then this error will go away and you can activate this plugin. Thank you.";

wp_die($error);

}#End of siteurl and admin_email exists check.

####
## Get the current posts from the wp db
####

$sql = "SELECT ID, post_title, post_content, guid FROM $wpdb->posts WHERE post_status='publish' AND post_type='post'";
$results = $wpdb->get_results($sql);

foreach ($results as $postobject) {

$posttags = get_the_tags($postobject->ID);

$tag_string = "|";

if (is_array($posttags)) {

foreach ($posttags as $taginfo) {

$tag_string .= $taginfo->name;
$tag_string .= "|";

}#End of posttag foreach.

}else {

$tag_string = "|none|";

}#End of is_array if.

$postobject->tags = $tag_string;

}#End of $postobject foreach.

####
## Result is an array of objects so lets send the array to the central server and run through it there
## along with other activation information
####

$activationinfo = array(
"function" => 'activate',
"sitename" => $siteurl,
"key" => $key,
"admin_email" => $admin_email,
"plugin_version" => $GLOBALS['plugin_version'],
"currentposts" => serialize($results),
"language" => WPLANG
);

$ch = curl_init('http://www.seolinknet.com/wp-codebase/extremeseo-activation.php');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $activationinfo);
$activate_notice = curl_exec($ch);
curl_close($ch);

if ($activate_notice !== 'activated') {

wp_die("There was an error activating your account, please contact us at http://www.seolinknet.com with the following error " . $activate_notice);

}#End of activate notice if.

}#End of activation function. 


function extremeseo_deactivate() {

global $GLOBALS, $wpdb;

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');
$extremeseo_licensekey = get_option('extremeseo_licensekey');

$deactivationinfo = array(
"function" => 'deactivate',
"sitename" => $siteurl,
"admin_email" => $admin_email,
"plugin_version" => $GLOBALS['plugin_version'],
"key" => $extremeseo_licensekey
);

$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-activation.php');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $deactivationinfo);
$deactivate_notice = curl_exec($ch);
curl_close($ch);

####
## Remove the plugin option for licensekey from the database so a new key is issued for the new indexing an reactivation
####

delete_option('extremeseo_licensekey');

if ($deactivate_notice !== 'deactivated') {

wp_die("There was an error deactivating your account, please contact us at http://www.seolinknet.com with the following error " . $deactivate_notice);

}#End of deactivate notice if.

}#End of deactivation function.


function extremeseo_addpost($post_id) {

global $GLOBALS, $wpdb;

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');
$extremeseo_licensekey = get_option('extremeseo_licensekey');

####
## Skip it if it is an attachment posting or whatever other than a post
####

if (get_post_type($post_id) != 'post') {

return;

}#End of post type check.

####
## Get the new post's info from the wp db
####

$sql = "SELECT ID, post_title, post_content, guid FROM $wpdb->posts WHERE ID='$post_id'";
$results = $wpdb->get_results($sql);

$postobject = $results[0];

$posttags = get_the_tags($postobject->ID);

$tag_string = "|";

if (is_array($posttags)) {

foreach ($posttags as $taginfo) {

$tag_string .= $taginfo->name;
$tag_string .= "|";

}#End of posttag foreach.

}else {

$tag_string = "|none|";

}#End of is_array if.

$postobject->tags = $tag_string;

####
## Result is an array of objects so lets send the array to the central server and run through it there
## along with license key
####

$postinfo = array(
"key" => $extremeseo_licensekey,
"newpost" => serialize($results),
"sitename" => $siteurl,
"admin_email" => $admin_email,
"language" => WPLANG
);


$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-addpost.php');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true); ## To stop the curl response from going to stdout and causing a header print problem.
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_exec($ch);
curl_close($ch);

}#End of add post function. 

function extremeseo_deletepost($post_id) {

global $GLOBALS, $wpdb;

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');
$extremeseo_licensekey = get_option('extremeseo_licensekey');

####
## Send the post_id with the key to the network server so it can be deleted from the network
####

$postinfo = array(
"key" => $extremeseo_licensekey,
"post_id" => $post_id,
"sitename" => $siteurl,
"admin_email" => $admin_email
);

$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-deletepost.php');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true); ## To stop the curl response from going to stdout and causing a header print problem.
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_exec($ch);
curl_close($ch);

}#End of delete post function.

function extremeseo_postcontent($content) {

global $GLOBALS, $wpdb;

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');

if (is_single()) {

$pagetype = "single";

}else {

$pagetype = "multiple";

return $content;

}#End of is_single if.

$extremeseo_licensekey = get_option('extremeseo_licensekey');
$extremeseo_linksperpost = get_option('extremeseo_linksperpost');
$headertext = get_option("extremeseo_headertext");
$postid = get_the_ID();
$posttags = get_the_tags();

$postobject = get_post($postid);
$posttitle = $postobject->post_title;

$postinfo = array(
"key" => $extremeseo_licensekey,
"postid" => $postid,
"linklimit" => $extremeseo_linksperpost,
"pagetype" => $pagetype,
"posttags" => serialize($posttags),
'posttitle' => serialize($posttitle),
"postcontent" => serialize($content),
"sitename" => $siteurl,
"agent" => $_SERVER['HTTP_USER_AGENT'],
"admin_email" => $admin_email,
"headertext" => $headertext,
"language" => WPLANG
);

$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-replypost.php');
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, '2');
curl_setopt ($ch, CURLOPT_TIMEOUT, '4');
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
$response = curl_exec($ch);
curl_close($ch);

$content .= $response;

return $content;

}#End of content filter function.

function extremeseo_googleverify() {

global $GLOBALS, $wpdb;

if (empty($_SERVER[REQUEST_URI]) or $_SERVER[REQUEST_URI] == '/') {

####
## Only run this function if we are displaying the home page
####

$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');
$extremeseo_licensekey = get_option('extremeseo_licensekey');

####
## Send a request to the network server asking for the google verification
## meta tag. We are setting very short timeouts here so we do not cause any
## delays in loading the web page
####

$postinfo = array(
"key" => $extremeseo_licensekey,
"sitename" => $siteurl,
"admin_email" => $admin_email
);

$ch = curl_init ('http://www.seolinknet.com/wp-codebase/extremeseo-googleverify.php');
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, '1');
curl_setopt ($ch, CURLOPT_TIMEOUT, '2');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
$googleverify = curl_exec($ch);
curl_close($ch);

if (!empty($googleverify) and $googleverify != 'error' and $googleverify != 'none') {

print $googleverify;

}#End of googleverify print if.
}#End of REQUEST_URI if.

}#End of googleverify action function.

?>
