<?php
set_time_limit(120);
require_once("pic_resize_class.php");
if(isset($messages,$dirs)) unset($messages,$dirs);
/* benchmark: 
	-with first search approach searching within entire theiamzone site was taking about 6 seconds
	-current approach for search took about 2 seconds to complete search within entire theiamzone

LOG:
	- currently is creating category successfully. 
	Next steps:
		- if post not existed and if existed add necessary code for preparing post or quit etc
		- upload images
		- preparing posts

*/ 			



// THIS SECTION FOR LOADING WORDPRESS ENGINE
define('WP_ADMIN',true);
define('DOING_CRON', true);
define('logfile',"project-crawler-log.txt");

if ( !defined('ABSPATH') ) {
	/** Set up WordPress environment */
	require_once('./wp-load.php');
	require_once(ABSPATH . 'wp-admin/includes/admin.php');
	echo "wp files loaded <br/>";
}

// END OF LOADING WORDPRESS ENGINE

// MY CODE
if($_SERVER['DOCUMENT_ROOT']!="")
{
	define("output",0); // output to webpage
	define("origin",$_SERVER['DOCUMENT_ROOT']);
}
else
{
	define("output",1); // output to file
	define("origin","C:\\inetpub\\theiamzone_com\\");
}
define("source_file","vanartsProject.xml");


$dir_pattern = "/^[\w\-]+$/";
$time_total1 = time();
$messages = array(); // to store all messages
$wp_upload_dir = wp_upload_dir();
$picture = new pic_resize();
$dirs = array();


function hunt_project_xml($root)
{
	global $dir_pattern,$dirs;
	$list = preg_grep($dir_pattern,scandir($root));

	if(file_exists($root."\\\\".source_file))
	{
		array_push($dirs,$root);
	}
	foreach($list as $i=>$name)
	{
		$path = $root."\\\\".$name;
		if(is_dir($path) && ($name != "." && $name != "..") )
		{
			hunt_project_xml($path);
		}
	}
}
function log_result()
{
	global $messages;
	$content = "";
	foreach($messages as $value):
		$content .= $value;
		if(output==0) $content .="<br />"; else $content .="\r\n";  // output to file or page
	endforeach;
	if(output ==0) echo $content; 
	else
	{
		$content = "=============================================================\r\n\r\nLog Date: ".date("H:m:i d-m-Y")."\r\n\r\n".$content;
		$content .="\r\n\r\n";
		file_put_contents(logfile,$content,FILE_APPEND | LOCK_EX);
	}
}
function populate_wordpress($work)
{
	
	global $wpdb, $wp_upload_dir, $messages;
	$time1 = time();
	

	$cat_id = get_cat_ID($work['student']);
	if($cat_id===0) // checks if there is any category with this student's name existed or not (if not returns 0)
	{
		$cat_id = wp_create_category($work['student'],12);
		array_push($messages, "<br />Category ".$work['student']." created");
	}
	else // this condition we don't need
	{
		array_push($messages,"Category ".$work['student']." is already exist cat id is ".$cat_id);
		
	}
	
	// $work['link'] fix for possible mistakes
	$path = pathinfo($work['link']);
	if(!isset($path['extension']))
	{ 
		$work['link'] = trim($work['link'],"/"); // remove possible / at the end
		$work['link'] .= "/index.php";	// concetinate with /index.php
	}
	// end $work['link'] fix

	$wpdb->query($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title ='%s'",(string)$work['name']));

	if($wpdb->num_rows ==0)
	{//create post
		array_push($messages,"Creating project");	
		
		// save images
		$projectImages = saveImages($work);
		// end of save images
		
		/*** PREPARING AND SAVE POST */	
		
		// $work['term'] fix for possible mistakes
		if(strtolower($work['term'])=="term 1" || strtolower($work['term'])=="term1") $work['term'] = 1;
		if(strtolower($work['term'])=="term 2" || strtolower($work['term'])=="term2") $work['term'] = 2;
		if(strtolower($work['term'])=="term 3" || strtolower($work['term'])=="term3") $work['term'] = 3;
		if(strtolower($work['term'])=="term 4" || strtolower($work['term'])=="term4") $work['term'] = 4;
		// end of $work['term'] fix	
			
			
		$term_cat_id = get_cat_ID('Term '.$work['term']);  // Getting Term's category id
		$content = $work['description'].' <br /><br />';//<a title="'.$work['name'].'" href="'.$work['link'].'" target="_blank">Click here</a> to view it<br /><br />';

		if($projectImages !== false)
		{
			$x=1;
			foreach($projectImages as $key => $image)
			{
				$content.='<a href="'.$image['url'].'" rel="'.sanitize_title_with_dashes($work['name']).'"><img class="alignleft size-medium wp-image-'.$image['thumbWidth'].'" title="'.$work['name'].'" src="'.$wp_upload_dir['url']."/".$image['thumbName'].'" alt="" width="'.$image['thumbWidth'].'" height="'.$image['thumbHeigth'].'" /></a>&nbsp;';
				if($x==3)
				{
					$content .='<div class="clear"></div>';
					$x=1;
				}
				$x++;
			}
		
			
			$link = home_url("/").sanitize_title($work['name'])."/";
			$post = array(
				'comment_status' => 'closed',
				'ping_status' => 'open',
				'post_author' => 1,  
				'post_category' => array($cat_id,$term_cat_id),
				'post_content' => $content,
				'post_title' => wp_strip_all_tags($work['name']),
				'post_name'	=> sanitize_title($work['name']),
				'post_status' => 'pending',
				'post_type' => 'post',
				'guid' => $link
			);
		}// end if if there is no image
			
		// Insert post into database
		$post_id = wp_insert_post($post);
		
		add_post_meta($post_id,'linkToSite',$work['link']);
		
		// if there were some images then link them, set thumbnail
		if($projectImages !==false)
		{ 
			set_post_thumbnail($post_id, $projectImages[0]['id']);
			foreach($projectImages as $image)
			{
				$mypost = array();
				$mypost['ID'] = $image['id'];
				$mypost['post_parent'] = $post_id;
				wp_update_post($mypost);
			}
		}
		
		if($meta = saveSlide($work,$post_id,$link))
			slideMetaFix($meta);
			
			
		/** --- END OF PREPARE AND SAVE POST */
			
	}
	else// project post is already existed
	{
		array_push($messages,"Post for ".$work['name']." is already existed");
	}
	
	$time2 = time();
	array_push($messages,"Populate wordpress process took ".($time2-$time1)." seconds");
	
}


function saveImages($data)
{
	
	$pattern = "/http:\/\/(www\.)?theiamzone.com/";
		$sourceUrl = pathinfo($data['link']); //ex. http://theiamzone.com/student_name/project
		$projectImages = array();
		global $wp_upload_dir,$messages;
		global $picture;
		$counter = count($data['images']);
		
		
		/**** SAVING IMAGES ***/
		if($counter >0)
		{
			$time1 = time();
			$i=0;
			foreach($data['images'] as $key => $image)
			{
				/** Preparing path info */
				$sourcefileName = basename($image); // ex. image.jpg
			
				$sourceFull = $sourceUrl['dirname']."/".$image; // ex. http://theiamzone.com/student_name/project/img/image.jpg
				
				$sourcePath = preg_replace($pattern,$_SERVER['DOCUMENT_ROOT'],$sourceFull,1);
				$sourcePath = preg_replace("/\//",DIRECTORY_SEPARATOR,$sourcePath); // ex. C:\inetpub\theiamzone_com\kim_alvarez\arata\imgs\Desert.jpg
				
				/** Figuring out filename */	
				$sourcefileName = wp_unique_filename($wp_upload_dir['path']."/",$sourcefileName); // make sure filename is unique
				/* -- end of filename */
				
				$targetFull = $wp_upload_dir['path']."/".$sourcefileName;  // ex . C:\inetpub\theiamzone_com\ali_efe\wordpress/wp-content/uploads/2012/11/Desert.jpg
				$targetUrl = $wp_upload_dir['url']."/".$sourcefileName; // ex. http://theiamzone.com/ali_efe/wordpress/wp-content/uploads/2012/11/Desert.jpg
				
				//$file = pathinfo($targetUrl);
				$file_name = pathinfo($targetUrl, PATHINFO_FILENAME);
				$file_type = pathinfo($targetUrl, PATHINFO_EXTENSION);
				
				if(copy($sourcePath,$targetFull))
				{
								
					$wp_filetype = wp_check_filetype($sourcefileName,null);
					$attachment = array(
						'guid' => $wp_upload_dir['url']."/".$sourcefileName,
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($sourcefileName))." screenshot",
						'post_name' => '',
						'post_content' => '',
						'post_status' => 'inherit',
						'post_author' => 1,
						'ping_status' => 'open',
						'comment_status' => 'closed'
					);
					
					
				
					wp_insert_post($attachment);
					$attach_id = wp_insert_attachment($attachment, $targetFull);
					
					$picture->load($targetFull);
					$original_width = $picture->getWidth();
					$original_height = $picture->getHeight();
					$original_file = substr($wp_upload_dir['subdir']."/".$sourcefileName,1);

					$picture->load($targetFull);
					$picture->resizeToWidth(200);
					$med_height = $picture->getHeight();
					$med_filefullname = "wp-content/uploads".$wp_upload_dir['subdir']."/".$file_name."-200x".$med_height.".".$file_type;
					$picture->save($med_filefullname,$picture->image_type,70);
					
					$picture->load($targetFull);
					$picture->resizeToWidth(150);
					$thumb_height = $picture->getHeight();
					$thumb_filefullname = "wp-content/uploads".$wp_upload_dir['subdir']."/".$file_name."-150x".$thumb_height.".".$file_type;
					$picture->save($thumb_filefullname,$picture->image_type,70);
					
					$picture->load($targetFull);
					$picture->resizeToWidth(48);
					$thumb_recent_height = $picture->getHeight();
					$thumb_recent_filefullname = "wp-content/uploads".$wp_upload_dir['subdir']."/".$file_name."-48x".$thumb_recent_height.".".$file_type;
					$picture->save($thumb_recent_filefullname,$picture->image_type,70);
					
					$picture->load($targetFull);
					$picture->resizeToWidth(125);
					$small_height = $picture->getHeight();
					$small_filefullname = "wp-content/uploads".$wp_upload_dir['subdir']."/".$file_name."-125x".$small_height.".".$file_type;
					$picture->save($small_filefullname,$picture->image_type,70);
					
					$attach_data = array(
						"width"=>$original_width,
						"height" => $original_height,
						"file" => $original_file,
						"sizes" => array(
								"thumbnail" => array(
												"file" => $file_name."-150x".$thumb_height.".".$file_type,
												"width" => 150,
												"height" => (int)$thumb_height
											),
								"medium" => array(
												"file" => $file_name."-200x".$med_height.".".$file_type,
												"width" => 200,
												"height" => (int)$med_height
											),
								"thumbnail-recent-posts" => array(
												"file" => $file_name."-48x".$thumb_recent_height.".".$file_type,
												"width" => 48,
												"height" => (int)$thumb_recent_height
											),
								"small" => array(
												"file" => $file_name."-125x".$small_height.".".$file_type,
												"width" => 125,
												"height" => (int)$small_height
											)
						),
						"image_meta" => array(
								"aperture" => 0,
								"credit" => "Vanarts",
								"camera" => "",
								"caption" => "",
								"created_timestamp" => 0,
								"copyright" => "Property of Vanarts",
								"focal_length" => 0,
								"iso" => 0,
								"shutter_speed" => 0,
								"title" => ""
						)
						
					);
					
					// DEFAULT WORDPRESS FUNCTION FOR THUMBNAILS AND METAS IS NOT USED BECAUSE ITS TOO SLOW AND CREATING SO MANY IMAGES
					//$attach_data = wp_generate_attachment_metadata($attach_id, $targetFull);
					wp_update_attachment_metadata($attach_id,$attach_data);
					
					
					array_push($projectImages,array("id"=>$attach_id, "url"=>$targetUrl,"thumbWidth"=>$attach_data['sizes']['medium']['width'],"thumbHeigth"=>$attach_data['sizes']['medium']['height'],"thumbName"=>$attach_data['sizes']['medium']['file']));
					$i++;
				}
				else
				{
					array_push($messages,"Copy error: filename ".$sourcePath."<br/>".$sourcePath." ".$targetFull);
				}

			}// end of foreach
			$time2 = time();
			array_push($messages, "		saving images took ".($time2-$time1)." seconds");
		} //end of if($counter>0)
		else
		{
			array_push($messages," There is no image");
		}
if($projectImages != array()) 
	return $projectImages;
else
	return false; 
}

function saveSlide($data,$id,$post_link=NULL)
{
	
	
	$pattern = "/http:\/\/(www\.)?theiamzone.com/";
	$sourceUrl = pathinfo($data['link']); //ex. http://theiamzone.com/student_name/project
	global $wp_upload_dir,$messages,$wpdb;
	
	if($post_link==NULL) {
		$post_link = $wpdb->get_var($wpdb->prepare("SELECT post_name FROM $wpdb->posts WHERE post_title = '%s' AND post_status != 'inherit'",(string)$data['title'])); 
		echo($post_link);
		return false;
	}
	
	if(isset($data['slide']) && $data['slide'] !="")
	{
		$time1 = time();
		$image = $data['slide'];
	
		/**** SAVING IMAGES ***/
		/** Preparing path info */
		$sourcefileName = basename($image); // ex. image.jpg
			
		$sourceFull = $sourceUrl['dirname']."/".$image; // ex. http://theiamzone.com/student_name/project/img/image.jpg
		$sourcePath = preg_replace($pattern,$_SERVER['DOCUMENT_ROOT'],$sourceFull,1);
		$sourcePath = preg_replace("/\//",DIRECTORY_SEPARATOR,$sourcePath); // ex. C:\inetpub\theiamzone_com\kim_alvarez\arata\imgs\Desert.jpg
		
		/** Figuring out filename */	
		$sourcefileName = wp_unique_filename($wp_upload_dir['path']."/",$sourcefileName); // make sure filename is unique
		/* -- end of filename */
		
		$targetFull = $wp_upload_dir['path']."/".$sourcefileName;  // ex . C:\inetpub\theiamzone_com\ali_efe\wordpress/wp-content/uploads/2012/11/Desert.jpg
		$targetUrl = $wp_upload_dir['url']."/".$sourcefileName; // ex. http://theiamzone.com/ali_efe/wordpress/wp-content/uploads/2012/11/Desert.jpg
		$title = $data['name']." by ".$data['student'];
		if(copy($sourcePath,$targetFull))
		{
			$wp_filetype = wp_check_filetype($sourcefileName,null);
			$slide_attachment = array(
				'post_title' => $title,
				'post_content' => '',
				'post_author' => 1,
				'ping_status' => 'closed',
				'comment_status' => 'closed',
				'post_parent' => (int)$id
			);
		
			$slide_attach_id = wp_insert_attachment($slide_attachment, $targetFull);	
			
			
			$slide_attach_data = wp_generate_attachment_metadata($slide_attach_id, $targetFull);
			wp_update_attachment_metadata($slide_attach_id,$slide_attach_data);
			wp_publish_post($slide_attach_id);
			$slide_meta = array("caption"=>$title,"link"=>$post_link,"image"=>$targetUrl,"id"=>$slide_attach_id);
		
		}	
		$time2 = time();
		array_push($messages,"	saving slide took ".($time2-$time1)." seconds");
		return $slide_meta;
	}// end of checking $data['slide']
	else
	{
		array_push($messages,"	there is no slide defined");
		return false;
	}
}

function slideMetaFix($meta)
{
	add_post_meta($meta['id'],'boldys_slidecaption',$meta['caption']);
	add_post_meta($meta['id'],'boldys_slidelink',$meta['link']);
	add_post_meta($meta['id'],'boldys_slideimage_src',$meta['image']);
	add_post_meta($meta['id'],'_edit_lock',(string)time().":1");
	add_post_meta($meta['id'],'_edit_last',"1");
/*	delete_post_meta($meta['id'],'_wp_attached_file');
	delete_post_meta($meta['id'],'_wp_attachment_metadata');*/
	$my_post = array();
	$my_post['ID'] = $meta['id'];
 	$my_post['post_type'] = 'sliderpost';
	$my_post['post_status'] = 'pending';
	$my_post['guid'] = home_url('/').'?post_type=sliderpost&#038;p='.$meta['id'];	
// Update the post into the database
  wp_update_post( $my_post );
}


$time1 = time();
hunt_project_xml(origin);
$time2 = time();
array_push($messages,"Crawling process took ".($time2-$time1)." seconds");
// at this point $dirs has all folders which has source file
array_push($messages,(string)(count($dirs)." Waiting projects found"));

foreach($dirs as $i=>$site_url)
{
	$project = array("name"=>NULL,"student"=>NULL,"description"=>NULL,"term"=>NULL,"link"=>NULL,"images"=>array(),"slide"=>NULL);
	$feed = simplexml_load_file($site_url."\\\\".source_file);

	if(count($feed)>=6) // in xml file there should be at least 6 nodes defined
	{
		foreach($feed->children() as $node)
		{
			switch($node->getName())
			{
				case "name":
						$project['name']= (string)$node;
						break;
				case "studentName":
						$project['student']= (string)$node;
						break;
				case "description":
						$project['description'] = (string)$node;
						break;
				case "term":
						$project['term'] = (string)$node;
						break;
				case "link":
						$project['link'] = (string)$node;
						break;
				case "images":
						foreach($node->children() as $image){
							array_push($project['images'],(string)$image);
						}
						break;
				case "slide":
						$project['slide'] = (string)$node;
						break;
			}
		}
		// here project should be populated
		if($project['name']!=NULL && $project['student']!=NULL && $project['term']!=NULL && $project['link']!=NULL && $project['images']!=array())
		{
			populate_wordpress($project);
		}
		else
		{
			array_push($messages,"Some project parameters are missing");
		}
		
	}
	else
	{
		array_push($messages,"Missing parameters in xml file");
		//log_error(); // I should define this function which should create a log
	}
	
}

$time_total2 = time();
array_push($messages, "<br/>All process took total ".($time_total2-$time_total1)." seconds<br/>");
log_result();
global $wpdb;
$wpdb->flush();
unset($messages,$dirs);

?>