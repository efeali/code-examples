<?php
include("includes/functions.php");
include("includes/pic_resize_class.php");

// authentication check comes

$manage = new management();
if($manage->authCheck())
{
	$db = $manage->connect_db();
	
	function goBack($error)
	{
		$_SESSION['error'] = $error;
		if(isset($_POST['action'])  && $_POST['action'] == "edit_register")
		{
			header("location:reg-info.php?fail");
			exit();
		}
		elseif(isset($_POST['action']) && $_POST['action'] == "new_register")
		{
			header("location:add-member.php?fail");
			exit();
		}
	}

	if(isset($_POST['action']) && is_string($_POST['action']))
	{
		if($_POST['action']=="edit_register" && isset($_POST['rid']) && is_numeric($_POST['rid']))
		{
			$rid = $_POST['rid'];
			$reg_status = $_POST['reg_status'];
			if($_POST['password'] == "") $password = NULL;
				else $password = $manage->escape_chars($_POST['password']);
			if($_POST['f_name'] == "") goBack("Name is empty"); 
				else $f_name = $manage->escape_chars(htmlentities($_POST['f_name'],ENT_QUOTES));
			if($_POST['l_name'] == "") goBack("Last name is empty"); 
				else $l_name = $manage->escape_chars(htmlentities($_POST['l_name'],ENT_QUOTES));
			if($_POST['m_name'] != "") $m_name = $manage->escape_chars(htmlentities($_POST['m_name'],ENT_QUOTES)); 
				else $m_name = NULL;
			if($_POST['gender'] == "") goBack("Gender is empty");
				else $gender = $manage->escape_chars($_POST['gender']);
			if($_POST['height'] == "") goBack("Height is empty"); 
				else $height = $manage->escape_chars($_POST['height']);
			if($_POST['birth_month'] == "" || $_POST['birth_year'] == "") goBack("Birth month is empty"); 
				else $birth_date = $manage->escape_chars($_POST['birth_year'])."-".$manage->escape_chars($_POST['birth_month'])."-01";
			if($_POST['birth_city'] == "") goBack("Birth city is empty"); 
				else $birth_city = $manage->escape_chars($_POST['birth_city']);
			if($_POST['birth_province'] == "") $birth_province = ""; 
				else $birth_province = $manage->escape_chars($_POST['birth_province']);
			if($_POST['birth_country'] == "") goBack("Birth country is empty"); 
				else $birth_country = $manage->escape_chars($_POST['birth_country']);
			if($_POST['birth_nationality'] == "") goBack("Birth nationality is empty"); 
				else $birth_nationality = $manage->escape_chars($_POST['birth_nationality']);
				
				
			if($_FILES['photo1']['error'] !=0) $photo1 = NULL; 
				else $photo1 = $_FILES['photo1']['name'];
			if($_FILES['photo2']['error'] !=0) $photo2 = NULL; 
				else $photo2 = $_FILES['photo2']['name'];
				
				
			if($_POST['national_id_num'] =="") goBack("National id number is empty"); 
				else $national_id_num = $manage->escape_chars(htmlentities($_POST['national_id_num'],ENT_QUOTES));
			if($_POST['id_ver_level'] == "") goBack("ID verification level is empty"); 
				else $id_ver_level = $manage->escape_chars($_POST['id_ver_level']);
			if($_POST['email1'] == "") goBack("Email 1 is empty"); 
				else $email1 = $manage->escape_chars($_POST['email1']);
			if($_POST['email2'] != "") $email2 = $manage->escape_chars($_POST['email2']); 
				else $email2 = NULL;
			if($_POST['citizen_country'] == "") goBack("Citizen of country is empty"); 
				else $citizen_country = $manage->escape_chars($_POST['citizen_country']);
			if($_POST['residence_country'] == "") goBack("Residence of country is empty"); 
				else $residence_country = $manage->escape_chars($_POST['residence_country']);
			if($_POST['domicile_city'] == "") goBack("Domicile city is empty"); 
				else {
						$domicile = $manage->escape_chars(htmlentities(($_POST['domicile_city'].",".$_POST['domicile_province']),ENT_QUOTES));
				}
			
			if($_POST['postal_code'] == "") goBack("Postal code is empty"); 
				else $postal_code = $manage->escape_chars(htmlentities($_POST['postal_code'],ENT_QUOTES));
			if($_POST['passport_num'] == "") goBack("Passport number is empty"); 
				else $passport_num = $manage->escape_chars($_POST['passport_num']);
			if($_POST['pass_exp_year'] == "" || $_POST['pass_exp_month'] == "" || $_POST['pass_exp_day'] == "") goBack("Passport expiry date is empty"); 
				else $pass_exp_date = $_POST['pass_exp_year']."-".$_POST['pass_exp_month']."-".$_POST['pass_exp_day'];
			if($_POST['distinguish_facts'] == "") $distinguish_facts=NULL; 
				else $distinguish_facts = $manage->escape_chars(htmlentities($_POST['distinguish_facts'],ENT_QUOTES));
			if($_POST['temp_password'] == "") $temp_password = NULL; 
				else $temp_password = $manage->escape_chars($_POST['temp_password']);
			if($_POST['num_matching'] == "") $num_match = NULL; 
				else $num_match = $manage->escape_chars(htmlentities($_POST['num_matching'],ENT_QUOTES));
			if($_POST['pub_q1'] != "") 
			{
				$pub_q1 = $manage->escape_chars(htmlentities($_POST['pub_q1'],ENT_QUOTES));
				if($_POST['pub_a1'] == "") goBack(); else $pub_a1 = $manage->escape_chars(htmlentities($_POST['pub_a1'],ENT_QUOTES));
			}
			else {$pub_q1 = NULL; $pub_a1 = NULL;}
			if($_POST['pub_q2'] != "") 
			{
				$pub_q2 = $manage->escape_chars(htmlentities($_POST['pub_q2'],ENT_QUOTES));
				if($_POST['pub_a2'] == "") goBack(); else $pub_a2 = $manage->escape_chars(htmlentities($_POST['pub_a2'],ENT_QUOTES));
			}
			else {$pub_q2 = NULL; $pub_a2 = NULL;}
			if($_POST['pub_q3'] != "") 
			{
				$pub_q3 = $manage->escape_chars(htmlentities($_POST['pub_q3'],ENT_QUOTES));
				if($_POST['pub_a3'] == "") goBack(); else $pub_a3 = $manage->escape_chars(htmlentities($_POST['pub_a3'],ENT_QUOTES));
			}
			else {$pub_q3 = NULL; $pub_a3 = NULL;}
			
			if($_POST['pri_q1'] != "") 
			{
				$pri_q1 = $manage->escape_chars(htmlentities($_POST['pri_q1'],ENT_QUOTES));
				if($_POST['pri_a1'] == "") goBack(); else $pri_a1 = $manage->escape_chars(htmlentities($_POST['pri_a1'],ENT_QUOTES));
			}
			else {$pri_q1 = NULL; $pri_a1 = NULL;}
			if($_POST['pri_q2'] != "") 
			{
				$pri_q2 = $manage->escape_chars(htmlentities($_POST['pri_q2'],ENT_QUOTES));
				if($_POST['pri_a2'] == "") goBack(); else $pri_a2 = $manage->escape_chars(htmlentities($_POST['pri_a2'],ENT_QUOTES));
			}
			else {$pri_q2 = NULL; $pri_a2 = NULL;}
			if($_POST['pri_q3'] != "") 
			{
				$pri_q3 = $manage->escape_chars(htmlentities($_POST['pri_q3'],ENT_QUOTES));
				if($_POST['pri_a3'] == "") goBack(); else $pri_a3 = $manage->escape_chars(htmlentities($_POST['pri_a3'],ENT_QUOTES));
			}
			else {$pri_q3 = NULL; $pri_a3 = NULL;}
			
			
			
			//// if it could come until here then I know there is no problem with data coming
			if($password != NULL)
			{
				$pass_data = $manage->encrypt($password);
				$new_pass = $pass_data[0];
				$key = $pass_data[1];
			}
			
			$manage->query("set autocommit=0;");
			$manage->query("start transaction;");
			$manage->query("savepoint beginning;");
					
			$sql = "UPDATE registrant_details SET `name` = '".$f_name."', `last_name` = '".$l_name."', `mid_name` = '".$m_name."', `gender` = '".$gender."', `height` = '".$height."', `birth_date` = '".$birth_date."', `birth_town` = '".$birth_city."', `birth_province` = '".$birth_province."', `birth_country` = '".$birth_country."', `birth_nationality` = '".$birth_nationality."', `national_id_no` = '".$national_id_num."', `id_score` = '".$id_ver_level."', `citizen_of` = '".$citizen_country."', `residence_of` = '".$residence_country."', `residence_domicile` = '".$domicile."', `postal_code` = '".$postal_code."', `pasport_no` = '".$passport_num."' , `passport_exp_date` = '".$pass_exp_date."', `distinguish_facts` = '".$distinguish_facts."', `email1` = '".$email1."', `email2` = '".$email2."', `status` = '".$reg_status."' WHERE rid = '".$rid."' LIMIT 1";
			
			$result = $manage->query($sql);
			
			
			if($result == true )
			{
				
				if($temp_password != NULL)
				{
					$temp_pass_data = $temp_password;
					$temp_pass_data = $manage->encrypt($temp_pass_data);
					$temp_pass = $temp_pass_data[0];
					$temp_pass_key = $temp_pass_data[1];
				}
				
				if($photo1 !=NULL || $photo2 !=NULL)
				{
					$image = new pic_resize();
					if($_FILES['photo1']['error'] == 0)
					{
						$file_type = substr($_FILES['photo1']['name'],strrpos($_FILES['photo1']['name'],"."));
						$time = time();
						$photo1 = $rid."_".$time.$file_type;
					
						if(move_uploaded_file($_FILES['photo1']['tmp_name'],"../members/member_images/tmp/".$photo1))
						{
							$image->load("../members/member_images/tmp/".$photo1);	
							$image->resizeToWidth(200);
							$image->save("../members/member_images/".$photo1);
							copy("../members/member_images/".$photo1,"../images/member_images/".$photo1);
							unlink("../members/member_images/tmp/".$photo1);
						}
						
					}
					if($_FILES['photo2']['error'] == 0)
					{
						$file_type = substr($_FILES['photo2']['name'],strrpos($_FILES['photo2']['name'],"."));
						$photo2 = $rid."_".($time+1).$file_type;
						if(move_uploaded_file($_FILES['photo2']['tmp_name'],"../members/member_images/tmp/".$photo2))
						{
							$image->load("../members/member_images/tmp/".$photo2);
							$image->resizeToWidth(200);
							$image->save("../members/member_images/".$photo2);	
							copy("../members/member_images/".$photo2,"../images/member_images/".$photo2);
							unlink("../members/member_images/tmp/".$photo2);
						}
					}
				}
				
				if($password != NULL)
				{
					$sql_2 = "UPDATE users SET `registrant_pass` = '".$new_pass."', `key` = '".$key."' WHERE registrant_id = '".$rid."' LIMIT 1";
				}
				
				$sql_3 = "UPDATE notices SET `pub_q1` = '".$pub_q1."', `pub_q2` = '".$pub_q2."', `pub_q3` = '".$pub_q3."', `pub_a1` = '".$pub_a1."', `pub_a2` = '".$pub_a2."', `pub_a3` = '".$pub_a3."', `pri_q1` = '".$pri_q1."', `pri_q2` = '".$pri_q2."', `pri_q3` = '".$pri_q3."', `pri_a1` = '".$pri_a1."', `pri_a2` = '".$pri_a2."', `pri_a3` = '".$pri_a3."', `numbers_match` = '".$num_match."'";
				if($temp_password != NULL)
				{
					$sql_3 .= ",`temp_pass` = '".$temp_pass."', `temp_pass_key` = '".$temp_pass_key."'";
				}
				$sql_3 .=" WHERE rid = '".$rid."' LIMIT 1;";
				
				if($photo1 != NULL || $photo2 != NULL)
				{
					
					$sql_4 = "UPDATE registrant_details SET"; 
					if($photo1 != NULL && $photo2 !=NULL)
						$sql_4 .="`photo1` = '".$photo1."', `photo2` = '".$photo2."' WHERE rid = ".$rid." limit 1";
					elseif($photo1 != NULL)
						$sql_4 .="`photo1` = '".$photo1."' WHERE rid = ".$rid." LIMIT 1";
					else
						$sql_4 .="`photo2` = '".$photo2."' WHERE rid = ".$rid." LIMIT 1";
				}
 
 				if(isset($sql_2))
					$result_2 = $manage->query($sql_2);
				else
					$result_2 = TRUE;
				$result_3 = $manage->query($sql_3);
				if(isset($sql_4))
					$result_4 = $manage->query($sql_4);
				else
					$result_4 = TRUE;
					
				if($result_2 == true && $result_3 == true && $result_4 == true)
				{
					$manage->commit();
					header("location:reg-info.php?done=".$rid);
					
				}
				else
				{
					$manage->rollback();
					$_SESSION['error'] = "problem is ".$manage->error;
					header("location:reg-info.php?fail=".$rid);
				}
				
			}
			else
			{
				$_SESSION['error'] = "problem is ".$manage->error;
				header("location:reg-info.php?fail=".$rid);
			}
			
			
		}
		elseif($_POST['action']=="new_register")
		{
			if($_POST['f_name'] == "") goBack("Name is empty"); 
				else $f_name = $manage->escape_chars(htmlentities($_POST['f_name'],ENT_QUOTES));
			if($_POST['l_name'] == "") goBack("Last name is empty"); 
				else $l_name = $manage->escape_chars(htmlentities($_POST['l_name'],ENT_QUOTES));
			if($_POST['m_name'] != "") $m_name = $manage->escape_chars(htmlentities($_POST['m_name'],ENT_QUOTES)); 
				else $m_name = NULL;
			if($_POST['gender'] == "") goBack("Gender is empty");
				else $gender = $manage->escape_chars($_POST['gender']);
			if($_POST['height'] == "") goBack("Height is empty"); 
				else $height = $manage->escape_chars($_POST['height']);
			if($_POST['birth_month'] == "" || $_POST['birth_year'] == "") goBack("Birth month is empty"); 
				else $birth_date = $manage->escape_chars($_POST['birth_year'])."-".$manage->escape_chars($_POST['birth_month'])."-01";
			if($_POST['birth_city'] == "") goBack("Birth city is empty"); 
				else $birth_city = $manage->escape_chars($_POST['birth_city']);
			if($_POST['birth_province'] == "") $birth_province = ""; 
				else $birth_province = $manage->escape_chars($_POST['birth_province']);
			if($_POST['birth_country'] == "") goBack("Birth country is empty"); 
				else $birth_country = $manage->escape_chars($_POST['birth_country']);
			if($_POST['birth_nationality'] == "") goBack("Birth nationality is empty"); 
				else $birth_nationality = $manage->escape_chars($_POST['birth_nationality']);
				
				
			if($_FILES['photo1']['error'] !=0) goBack("Photo 1 couldn't upload correctly");
				else $photo1 = $_FILES['photo1']['name'];
			if($_FILES['photo2']['error'] !=0) goBack("Photo 2 couldn't upload correctly"); 
				else $photo2 = $_FILES['photo2']['name'];
				
				
			if($_POST['national_id_num'] =="") goBack("National id number is empty"); 
				else $national_id_num = $manage->escape_chars(htmlentities($_POST['national_id_num'],ENT_QUOTES));
			if($_POST['id_ver_level'] == "") goBack("ID verification level is empty"); 
				else $id_ver_level = $manage->escape_chars($_POST['id_ver_level']);
			if($_POST['email1'] == "") goBack("Email 1 is empty"); 
				else $email1 = $manage->escape_chars($_POST['email1']);
			if($_POST['email2'] != "") $email2 = $manage->escape_chars($_POST['email2']); 
				else $email2 = NULL;
			if($_POST['citizen_country'] == "") goBack("Citizen of country is empty"); 
				else $citizen_country = $manage->escape_chars($_POST['citizen_country']);
			if($_POST['residence_country'] == "") goBack("Residence of country is empty"); 
				else $residence_country = $manage->escape_chars($_POST['residence_country']);
			if($_POST['domicile_city'] == "") goBack("Domicile city is empty"); 
				else {
						$domicile = $manage->escape_chars(htmlentities(($_POST['domicile_city'].",".$_POST['domicile_province']),ENT_QUOTES));
				}
			
			if($_POST['postal_code'] == "") goBack("Postal code is empty"); 
				else $postal_code = $manage->escape_chars(htmlentities($_POST['postal_code'],ENT_QUOTES));
			if($_POST['passport_num'] == "") goBack("Passport number is empty"); 
				else $passport_num = $manage->escape_chars($_POST['passport_num']);
			if($_POST['pass_exp_year'] == "" || $_POST['pass_exp_month'] == "" || $_POST['pass_exp_day'] == "") goBack("Passport expiry date is empty"); 
				else $pass_exp_date = $_POST['pass_exp_year']."-".$_POST['pass_exp_month']."-".$_POST['pass_exp_day'];
			if($_POST['distinguish_facts'] == "") $distinguish_facts=NULL; 
				else $distinguish_facts = $manage->escape_chars(htmlentities($_POST['distinguish_facts'],ENT_QUOTES));
			if($_POST['temp_password'] == "") $temp_password = NULL; 
				else $temp_password = $manage->escape_chars($_POST['temp_password']);
			if($_POST['num_matching'] == "") $num_match = NULL; 
				else $num_match = $manage->escape_chars(htmlentities($_POST['num_matching'],ENT_QUOTES));
			if($_POST['pub_q1'] != "") 
			{
				$pub_q1 = $manage->escape_chars(htmlentities($_POST['pub_q1'],ENT_QUOTES));
				if($_POST['pub_a1'] == "") goBack(); else $pub_a1 = $manage->escape_chars(htmlentities($_POST['pub_a1'],ENT_QUOTES));
			}
			else {$pub_q1 = NULL; $pub_a1 = NULL;}
			if($_POST['pub_q2'] != "") 
			{
				$pub_q2 = $manage->escape_chars(htmlentities($_POST['pub_q2'],ENT_QUOTES));
				if($_POST['pub_a2'] == "") goBack(); else $pub_a2 = $manage->escape_chars(htmlentities($_POST['pub_a2'],ENT_QUOTES));
			}
			else {$pub_q2 = NULL; $pub_a2 = NULL;}
			if($_POST['pub_q3'] != "") 
			{
				$pub_q3 = $manage->escape_chars(htmlentities($_POST['pub_q3'],ENT_QUOTES));
				if($_POST['pub_a3'] == "") goBack(); else $pub_a3 = $manage->escape_chars(htmlentities($_POST['pub_a3'],ENT_QUOTES));
			}
			else {$pub_q3 = NULL; $pub_a3 = NULL;}
			
			if($_POST['pri_q1'] != "") 
			{
				$pri_q1 = $manage->escape_chars(htmlentities($_POST['pri_q1'],ENT_QUOTES));
				if($_POST['pri_a1'] == "") goBack(); else $pri_a1 = $manage->escape_chars(htmlentities($_POST['pri_a1'],ENT_QUOTES));
			}
			else {$pri_q1 = NULL; $pri_a1 = NULL;}
			if($_POST['pri_q2'] != "") 
			{
				$pri_q2 = $manage->escape_chars(htmlentities($_POST['pri_q2'],ENT_QUOTES));
				if($_POST['pri_a2'] == "") goBack(); else $pri_a2 = $manage->escape_chars(htmlentities($_POST['pri_a2'],ENT_QUOTES));
			}
			else {$pri_q2 = NULL; $pri_a2 = NULL;}
			if($_POST['pri_q3'] != "") 
			{
				$pri_q3 = $manage->escape_chars(htmlentities($_POST['pri_q3'],ENT_QUOTES));
				if($_POST['pri_a3'] == "") goBack(); else $pri_a3 = $manage->escape_chars(htmlentities($_POST['pri_a3'],ENT_QUOTES));
			}
			else {$pri_q3 = NULL; $pri_a3 = NULL;}
			
			
			
			//// if it could come until here then I know there is no problem with data coming
			$pass_data = $manage->random_pass();
			$new_password = $pass_data;
			$pass_data = $manage->encrypt($pass_data);
			$new_pass = $pass_data[0];
			$key = $pass_data[1];
			
			$manage->query("set autocommit=0;");
			$manage->query("start transaction;");
			$manage->query("savepoint beginning;");
			
			$sql = "INSERT INTO registrant_details 
					(`rid`, `name`, `last_name`, `mid_name`, `gender`, `height`, `birth_date`, `birth_town`, `birth_province`, `birth_country`, `birth_nationality`, `national_id_no`, `id_score`, `citizen_of`, `residence_of`, `residence_domicile`, `postal_code`, `pasport_no`, `passport_exp_date`, `distinguish_facts`, `email1`, `email2`) VALUES ('null','".$f_name."','".$l_name."','".$m_name."','".$gender."','".$height."','".$birth_date."','".$birth_city."','".$birth_province."','".$birth_country."','".$birth_nationality."','".$national_id_num."','".$id_ver_level."','".$citizen_country."','".$residence_country."','".$domicile."','".$postal_code."','".$passport_num."','".$pass_exp_date."','".$distinguish_facts."','".$email1."','".$email2."');";
			
			$result = $manage->query($sql);
			
			
			if($result == true )
			{
				$id = $manage->last_insert_id();
				
				
				
				if($temp_password == NULL) $temp_pass_data = $manage->random_pass(); else $temp_pass_data = $temp_password;
				$new_temp_password = $temp_pass_data;
				$temp_pass_data = $manage->encrypt($temp_pass_data);
				$temp_pass = $temp_pass_data[0];
				$temp_pass_key = $temp_pass_data[1];
				
				
				
				$image = new pic_resize();
				$upload_check = 0;
				if($_FILES['photo1']['error'] == 0)
				{
					$file_type = substr($_FILES['photo1']['name'],strrpos($_FILES['photo1']['name'],"."));
					$time = time();
					$photo1 = $id."_".$time.$file_type;
				
					if(move_uploaded_file($_FILES['photo1']['tmp_name'],"../members/member_images/tmp/".$photo1))
					{
						
	
						$image->load("../members/member_images/tmp/".$photo1);
						
						$image->resizeToWidth(200);
						$image->save("../members/member_images/".$photo1);
						copy("../members/member_images/".$photo1,"../images/member_images/".$photo1);
						
						if($_FILES['photo2']['error'] == 0)
						{
							$file_type = substr($_FILES['photo2']['name'],strrpos($_FILES['photo2']['name'],"."));
							$photo2 = $id."_".($time+1).$file_type;
							if(move_uploaded_file($_FILES['photo2']['tmp_name'],"../members/member_images/tmp/".$photo2))
							{
								$image->load("../members/member_images/tmp/".$photo2);
								$image->resizeToWidth(200);
								$image->save("../members/member_images/".$photo2);
								copy("../members/member_images/".$photo2,"../images/member_images/".$photo2);
								
								
								$upload_check++;
							}
						}
					}
					
				}
				
				$sql_2 = "INSERT INTO users (`registrant_id`,`registrant_pass`,`key`) VALUES ('".$id."','".$new_pass."','".$key."');";
				$sql_3 = "INSERT INTO notices (`rid`, `temp_pass`,`temp_pass_key`, `pub_q1`, `pub_q2`, `pub_q3`, `pub_a1`, `pub_a2`, `pub_a3`, `pri_q1`, `pri_q2`, `pri_q3`, `pri_a1`, `pri_a2`, `pri_a3`, `numbers_match`) VALUES 
				('".$id."', '".$temp_pass."', '".$temp_pass_key."', '".$pub_q1."', '".$pub_q2."', '".$pub_q3."', '".$pub_a1."', '".$pub_a2."', '".$pub_a3."', '".$pri_q1."', '".$pri_q2."', '".$pri_q3."', '".$pri_a1."', '".$pri_a2."', '".$pri_a3."','".$num_match."');";
				
				$sql_4 = "UPDATE registrant_details SET `photo1` = '".$photo1."', `photo2` = '".$photo2."' WHERE rid = ".$id." limit 1";
				
 
				$result_2 = $manage->query($sql_2);
				$result_3 = $manage->query($sql_3);
				$result_4 = $manage->query($sql_4);
				
				if($result_2 == true && $result_3 == true && $result_4 == true && $upload_check == 1)
				{
					$manage->commit();
					$_SESSION['user_info']['id'] = $id;
					$_SESSION['user_info']['pass'] = $new_password;
					$_SESSION['user_info']['temp_pass'] = $new_temp_password;
					header("location:add-member.php?done");
					
				}
				else
				{
					
					$manage->rollback();
					$_SESSION['error'] = "There was an error during creating a new account";
					header("location:add-member.php?fail");
				}
				
			}
			else
			{
				$_SESSION['error'] = "problem is ".$manage->error;
				header("location:add-member.php?fail");
			}
			
			
		}
		elseif($_POST['action']=="form")
		{
	
			if(isset($_POST['f_name']) && $_POST['f_name']!="" && isset($_POST['f_description']) && $_POST['f_description']!="")
			{
	
				if(isset($_FILES['f_document']) && $_FILES['f_document']['error']==0)
				{
					$cp = move_uploaded_file($_FILES['f_document']['tmp_name'],"../forms/".$_FILES['f_document']['name']);
					if($cp == true)
					{
						if($_FILES['f_thumb']['error']==0)
						{
							$image = new pic_resize();
							$thumb_name = $_FILES['f_thumb']['name'];
							move_uploaded_file($_FILES['f_thumb']['tmp_name'],"../forms/images/tmp/".$thumb_name);
							$image->load("../forms/images/tmp/".$thumb_name);
							$image->resizeToWidth(250);
							$image->save("../forms/images/".$thumb_name);
							
							unlink("../forms/images/tmp/".$thumb_name);
						}
						else
						{
							$thumb_name = "";
						}
						
						$file_name = $manage->escape_chars($_FILES['f_document']['name']);
						$file_title = $manage->escape_chars($_POST['f_name']);
						$file_desc = $manage->escape_chars($_POST['f_description']);
						
						$sql = "INSERT INTO forms (`form_id`,`form_title`,`form_description`,`form_url`,`form_thumb`,`form_publish`) VALUES ('','".$file_title."','".$file_desc."','".$file_name."','".$thumb_name."','".$_POST['publish']."')";
						
						if($result = $manage->query($sql))
						{
							header("location:forms.php?done");
						}
						else
						{
							$_SESSION['error'] = "Query problem.";
							header("location:forms.php?error");
						}
					}
					else
					{
						$_SESSION['error'] = "File couldn't be created.";
						header("location:forms.php?error");
					}
					
				}
				else
				{
					$_SESSION['error'] = "File sent was not successfull.";
					header("location:forms.php?error");
				}
			}
			else
			{
				
				$_SESSION['error'] = "You must enter form name and description.";
				header("location:forms.php?error");
			}
		}
		elseif($_POST['action']=="form-edit")
		{
	
			if(isset($_POST['f_name']) && $_POST['f_name']!="" && isset($_POST['f_description']) && $_POST['f_description']!="" && isset($_POST['id']) && is_numeric($_POST['id']))
			{
				$manage->connect_db();
				$file_title = $manage->escape_chars($_POST['f_name']);
				$file_desc = $manage->escape_chars($_POST['f_description']);
				if(isset($_POST['publish']) && $_POST['publish']==1)
					$publish = 1;
				else
					$publish = 0;
				$id = $manage->escape_chars($_POST['id']);
				$sql = "SELECT * from forms WHERE form_id ='".$id."'";
				if(!$result = $manage->query($sql))
				{
					$manage->error_message("Query error");
				}
				$data = mysqli_fetch_array($result);
				
				
				if(isset($_FILES['f_document']) && $_FILES['f_document']['error']==0)
				{
					if(is_file("../forms/".$data['form_url']))
					{
						rename("../forms/".$data['form_url'],"../forms/".$data['form_url']."_del");
					}
					$cp = move_uploaded_file($_FILES['f_document']['tmp_name'],"../forms/".$_FILES['f_document']['name']);
					if($cp == true)
					{
						$file_name = $manage->escape_chars($_FILES['f_document']['name']);
						unlink("../forms/".$data['form_url']."_del");
					}
					else
						$file_name = NULL;
					
				}
				else
					$file_name = NULL;
				if($_FILES['f_thumb']['error']==0)
				{
					if(is_file("../forms/images/".$data['form_thumb']))
						{
							rename("../forms/images/".$data['form_thumb'],"../forms/images/".$data['form_thumb']."_del");
							
						}
					$image = new pic_resize();
					$thumb_name = $_FILES['f_thumb']['name'];
					if(move_uploaded_file($_FILES['f_thumb']['tmp_name'],"../forms/images/tmp/".$thumb_name))
					{
						
						unlink("../forms/images/".$data['form_thumb']);
						$image->load("../forms/images/tmp/".$thumb_name);
						$image->resizeToWidth(250);
						$image->save("../forms/images/".$thumb_name);
							
						unlink("../forms/images/tmp/".$thumb_name);
					}
					else
						$thumb_name = NULL;
				}
				else
				{
					$thumb_name = NULL;
				}
						
				$sql = "UPDATE forms SET `form_title` = '".$file_title."', `form_description`='".$file_desc."', `form_publish`='".$publish."'";
				if($file_name !=NULL)
					$sql .=", `form_url`='".$file_name."'";
				if($thumb_name != NULL)
					$sql .=", `form_thumb`='".$thumb_name."'";
				$sql .=" WHERE form_id = '".$id."' LIMIT 1";
						
						
				if($result = $manage->query($sql))
				{
					header("location:forms.php?edit-done");
				}
				else
				{
					$_SESSION['error'] = "Query problem.";
					header("location:forms.php?error");
				}

					
			}
			else
			{
				
				$_SESSION['error'] = "You must enter form name and description.";
				header("location:forms.php?error");
			}
		}
		elseif($_POST['action']=="faq")
		{
			if(!isset($_POST['question']))
			{
				$_SESSION['error'] = "You should enter question.";
				header("location:faq.php?error");
			}
			if(!isset($_POST['answer']))
			{
				$_SESSION['error'] = "You should enter answer.";
				header("location:faq.php?error");
			}
			$question = $manage->escape_chars($_POST['question']);
			$answer = $manage->escape_chars(htmlentities($_POST['answer'],ENT_QUOTES));
			if(isset($_POST['publish']) && $_POST['publish'] ==1)
				$publish = 1;
			else
				$publish = 0;
			if(isset($_FILES['picture']) && $_FILES['picture']['error'] == 0)
			{
				$image = new pic_resize();
				$picture_name = $manage->escape_chars($_FILES['picture']['name']);
				if(move_uploaded_file($_FILES['picture']['tmp_name'],"../images/tmp/".$picture_name))
				{
					$image->load("../images/tmp/".$picture_name);
					$img_width = $image->getWidth();
					if($img_width>250)
						$image->resizeToWidth(250);
					$image->save("../images/".$picture_name);
						
					unlink("../images/tmp/".$picture_name);
				}
				else
					$picture_name = NULL;
			}
			else
				$picture_name = NULL;
			
			$sql = "INSERT INTO faq (`faq_id`,`faq_title`,`faq_content`,`faq_image`,`faq_publish`) VALUES ('','".$question."','".$answer."'";
			if($picture_name != NULL)
				$sql .= ", '".$picture_name."'";
			else
				$sql .= ", NULL";
			
			$sql .=",'".$publish."')";
			if($manage->query($sql))
			{
				header("location:faq.php?done");
			}
			else
			{
				$_SESSION['error'] = "FAQ couldn't insert.Query error.";
				header("location:faq.php?error");
			}
		}
		elseif($_POST['action']=="faq-edit")
		{
			if(!isset($_POST['id']))
			{
				$_SESSION['error'] = "FAQ id is unknown";
				header("location:faq.php?error");
			}
			else
			{
					$id = $_POST['id'];
					if(!isset($_POST['question']))
					{
						$_SESSION['error'] = "You should enter question.";
						header("location:faq.php?error");
					}
					if(!isset($_POST['answer']))
					{
						$_SESSION['error'] = "You should enter answer.";
						header("location:faq.php?error");
					}
					$question = $manage->escape_chars($_POST['question']);
					$answer = $manage->escape_chars(htmlentities($_POST['answer'],ENT_QUOTES));
					if(isset($_POST['publish']) && $_POST['publish'] ==1)
						$publish = 1;
					else
						$publish = 0;
					if(isset($_FILES['picture']) && $_FILES['picture']['error'] == 0)
					{
						$sql_old = "SELECT faq_image FROM faq WHERE faq_id = ".$id;
						if($res_old = $manage->query($sql_old))
						{
							$data = mysqli_fetch_array($res_old);
							if($data['faq_image']!=NULL)
								$old_img_name = $data['faq_image'];
							else
								$old_img_name = NULL;
						}
						else
							$old_img_name = NULL;
						
						$image = new pic_resize();
						$picture_name = $manage->escape_chars($_FILES['picture']['name']);
						if(move_uploaded_file($_FILES['picture']['tmp_name'],"../images/tmp/".$picture_name))
						{
							$image->load("../images/tmp/".$picture_name);
							$img_width = $image->getWidth();
							if($img_width>250)
								$image->resizeToWidth(250);
							$image->save("../images/".$picture_name);
							if($old_img_name !=NULL)
								unlink("../images/".$old_img_name);
							unlink("../images/tmp/".$picture_name);
						}
						else
							$picture_name = NULL;
					}
					else
						$picture_name = NULL;	
						
					$sql = "UPDATE faq SET `faq_title` = '".$question."', `faq_content`='".$answer."', `faq_publish`='".$publish."'";
					if($picture_name != NULL)
						$sql .=", `faq_image` = '".$picture_name."'";
					$sql .=" WHERE faq_id ='".$id."' LIMIT 1";
					if($manage->query($sql))
					{
						header("location:faq.php?edit-done");
					}
					else
					{
						$_SESSION['error'] = "FAQ couldn't update.Query error.";
						header("location:faq.php?error");
					}
			}
		}
	

	}
}
else
{
	header("location:../index.php");
}
?>