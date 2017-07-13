<?php

####
## The deactivation process removes the member and posts from the network so that part is redundant 
## here and will have no effect since you have to deactivate before you can issue a delete which runs
## the uninstall however, the linksperpost and headertext are not deleted upon deactivation and the 
## activation process runs the uninstall server code if there is an outdated license type stored in the
## wp db so while there are some redundant functions in the deactivation and uninstall routines, they are
## both needed.
#### 

####
## Get some site info so we can send the extremeseo network notification
####

$extremeseo_licensekey = get_option('extremeseo_licensekey');
$siteurl = get_option('siteurl');
$admin_email = get_option('admin_email');

####
## Notify the extremeseo network staff
####

mail('support@seolinknet.com','Extreme SEO Plugin Client Side Uninstall',"There was an uninstall of the extremeseo plugin on " . $siteurl . " -- " . $admin_email);

####
## Send the site info to the extremeseo server so we can remove the site from the network
####

$info = array(
"key" => $extremeseo_licensekey,
"sitename" => $siteurl,
"admin_email" => $admin_email
);

$ch = curl_init('http://www.seolinknet.com/wp-codebase/extremeseo-uninstall.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $info);
curl_exec($ch);
curl_close($ch);

####
## Remove the plugin options from the database
####

delete_option('extremeseo_headertext');
delete_option('extremeseo_linksperpost');
delete_option('extremeseo_licensekey');

?>
