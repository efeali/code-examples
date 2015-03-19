<?php
/**
 * @package Vanarts Project Crawler for Wordpress by Ali EFE
 * @version 1.0
 */
/*
Plugin Name: Vanarts Project Crawler for Wordpress by Ali EFE
Plugin URI: 
Description: This plugin works with our vanarts-project-crawler.php file. Crawler should work as its scheduled, suck all project based on their xml files and put them in pending status. At that point slide images also should be set pending status. !Important: You should create a scheduled task for vanarts-project-crawler.php file manually on server. This plugin will set slides to pending status, then when admin publish the project(post) this code here will catch that action and call publishSlide function which will change slide post's status to publish as well
Author: Ali Efe
Version: 1.0
Author URI: http://vanarts.com/
*/

////---- HOOK FOR PUBLISHING POSTS, SO WHEN WE CHANGE POST'S STATUS TO PUBLISH SLIDE RELATED WITH THIS POST WILL BE PUBLISHED AS WELL
function publishSlide($post_ID)
{
	global $wpdb;
	$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'publish' WHERE post_type = 'sliderpost' AND post_status = 'pending' AND post_parent = %d LIMIT 1",$post_ID ));
}
add_action( 'publish_post', 'publishSlide' );
///// END OF HOOK

## Create config.php file for starting path


function createConfigFile()
{
	$path = dirname(WP_CONTENT_DIR);
	$serverPath = $_SERVER['DOCUMENT_ROOT'];
	$content = '<?php define("projectPath",\''.$path.'\'); define("serverPath", \''.$serverPath.'\'); ?>';
	
	file_put_contents(WP_PLUGIN_DIR."\\vanarts-project-crawler\\config.php",$content,LOCK_EX);
}
register_activation_hook(__FILE__, 'createConfigFile');

function removeConfigFile()
{
	unlink(WP_PLUGIN_DIR."\\vanarts-project-crawler\\config.php");
}
register_deactivation_hook(__FILE__, 'removeConfigFile');
?>