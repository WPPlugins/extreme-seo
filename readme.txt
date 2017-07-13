=== Extreme SEO  ===
Contributors: cartmod
Donate link: http://www.seolinknet.com
Tags: google,links,seo,backlinks,network
Requires at least: 2.8.0
Tested up to: 2.9.2
Stable tag: 1.3.4

This plugin registers your site with the Extreme SEO service, indexes all of your posts, and delivers content related links to/from your site.

== Description ==
This plugin has several SEO functions that are all run without any effort on your part.

1. Provides your site with one way, content related, dofollow inbound links from other sites in the Extreme SEO network.
2. Provides links to related posts under your post content that open in a new browser window so you dont lose your visitors upon clicking the related post links.
3. Increases your site's search engine ranking and Google Page Rank by providing static inbound links to your posts from related posts on other sites within the Extreme SEO network.

Why should I install this plugin?

We have several years of experience as SEO specialists and we know what it takes to get to the top of Google, Yahoo, and Bing.

This plugin provides inbound links to your site's post pages from post pages on other unique domains within the Extreme SEO network that have content related to your content.

Why do search engines care about inbound links from related sites?

The answer is easy. Because anyone can stuff their pages full of keywords and search engines know that but it is MUCH harder to get other sites to link to you and it is even harder when their content is related to yours since you are likely to be a competitor of theirs in some way. For this reason, search engines have adopted the policy that an inbound link from another site is viewed as a "vote" for your site and if the site that is linked to you is about content related to yours then that is an even more valuable "vote" for your site. The more "votes" your site has via inbound links, the more important your site is for the keywords contained in your site titles, descriptions, content and of course, the anchor text of the links to your site from other sites. Our plugin gets your site the important "votes" that are needed for you to get to the top of the search engine results.

What is the nofollow tag?

Because the number one factor that Google uses to rank sites is the number of inbound links and because spammers are creative, spammers started to embed links to their sites in forum signatures, blog post comments, and basically anywhere that they could so Google's Matt Cutts (head of anti spam for Google) came up with the nofollow attribute to allow these types of links to be ignored in the Google ranking algorithm.

Will my inbound links be set as nofollow from your plugin?

Absolutely not. A nofollow inbound link does you very little good. All of the inbound links that we deliver to your site will be dofollow so they WILL COUNT when Google crawls them.

Why would you build this plugin and give it to me for free?

Simple. Our service will keep track of your Google Page Rank and as your inbound links from the network cause your site's popularity to increase, your Google Page Rank will rise. Blogs with a high Google Page Rank are valuable to advertisers and when we see that your page rank is high enough, we will put you in touch with advertisers that want to pay you to put a post about their product or service in your blog with a link to their site. We get paid by the advertiser, you get paid by the advertiser. Everybody wins and it costs you nothing. All you have to do is maintain an active blog.

What about spammy sites or "bad neighborhoods"?

We are not running a quick buck, low class network. As a result, your site will not be linked to/from spammy sites. Our algorithm filters any posts containing common spam related words from ever getting indexed in our network.

There is nothing left to do but download the plugin and install it to be on your way to the top of the search engine pages.

Please do not install this plugin on sites that contain content related to adult, gambling, or pharmacys.

== Installation ==
1. Download the plugin file extremeseo.zip
2. Unzip extremeseo.zip file on your computer.
3. Upload the extremeseo folder to the `/wp-content/plugins/` directory
4. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
See the plugin description for questions and answers.

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial Distribution
= 1.0.1 =
* Removed check for cURL that was returning false positives and causing install errors.
= 1.0.2 =
* Added curl connection timeout of 4 seconds to replypost so blogs are not delayed if server is under heavy load.
= 1.0.3 =
* Added no adult sites notice to the plugin description
= 1.0.4 =
* Just svn stuff
= 1.0.5 =
* Stops the activation from the upgrade process from generarting a new license key
= 1.0.6 =
* Added notification and error reporting support for possible server response issues during new license key validation requests
= 1.0.7 = 
* Added the curl timeout option to stop site loading delays
= 1.0.8 =
* svn stuff
= 1.0.9 = 
* fixed license check
= 1.1.0 =
* Activation error notices are delivered to the plugin response now
= 1.1.1 =
* Added user agent detection
= 1.1.2 =
* Fixed errors with license generation duplicates
= 1.1.3 =
* Removed server side check for unique key since key generation method was changed
= 1.1.4 =
* Finished error reporting for activation and deactivation
= 1.1.5 =
* Added support for network uninstalls via the wordpress plugin manager delete function
= 1.1.6 =
* Fixed error with plugin version checking
= 1.1.7 =
* Fixed issue with network server side record deletes during client side uninstall
= 1.1.8 =
* Set the cURL timeout from 2 seconds to 10 seconds so there is enough time to get a response. It takes ~2 seconds per link returned
= 1.1.9 =
* Set the plugin to only call the server for single post pages
= 1.2.0 =
* Added the content to the return statement in the content filter function for multiple post displays
= 1.2.1 =
* Changed site registration to avoid duplicates when using subdomains
= 1.2.2 =
* Fixed uninstall bug after 1.2.1 changes
= 1.2.3 =
* Fixed blank line that caused error to be reported where servers were set to display php errors to the browser.
= 1.2.4 =
* Added activation check to make sure that siteurl and admin email are both set
= 1.2.5 =
* Added support for automatic license updates via activation.
= 1.2.6 =
* Changed ereg to preg_match from 1.2.5 since ereg is deprecated and highly discouraged by PHP
= 1.2.7 =
* Revisions to activation/deactivation/uninstall routines as well as added support for site level control over network links header text.
= 1.2.8 =
* Changed activation routine to always delete previous license key and purge server side records since the automatic update appearently does not run the deactivation function properly
= 1.2.9 =
* Added language detection, revised activation process, added google sitemap validation.
= 1.3.0 =
* Added output from purge response to diagnostic email message
= 1.3.1 =
* Changed the comparison operator for the purged response to correct false positives on errors.
= 1.3.2 =
* Added more diagnostics to the purge response since if fails during auto upgrades but works during manual installs.
= 1.3.4 =
* Changed the purge response check again to debug the false positives.
 
== Upgrade Notice ==

Please upgrade to the latest version
