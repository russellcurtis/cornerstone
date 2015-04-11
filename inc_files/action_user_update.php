<?php

if ($_POST[user_id] != "") { $user_id = $_POST[user_id]; }
else { $user_id = ""; }

	$sql = "UPDATE intranet_user_details SET
	user_address_county = '$_POST[user_address_county]',
	user_address_postcode = '$_POST[user_address_postcode]',
	user_address_town = '$_POST[user_address_town]',
	user_address_3 = '$_POST[user_address_3]',
	user_address_2 = '$_POST[user_address_2]',
	user_address_1 = '$_POST[user_address_1]',
	user_name_first = '$_POST[user_name_first]',
	user_name_second = '$_POST[user_name_second]',
	user_num_extension = '$_POST[user_num_extension]',
	user_num_mob = '$_POST[user_num_mob]',
	user_num_home = '$_POST[user_num_home]',
	user_email = '$_POST[user_email]',
	user_usertype = '$_POST[user_usertype]',
	user_active = '$_POST[user_active]',
	user_username = '$_POST[user_username]',
	user_user_rate = '$_POST[user_user_rate]',
	user_user_added = '$_POST[user_user_added]',
	user_user_timesheet = '$_POST[user_user_timesheet]',
	user_holidays = '$_POST[user_holidays]',
	user_initials = '$_POST[user_initials]',
	user_prop_target = '$_POST[user_prop_target]',
	user_user_timesheet = '$_POST[user_user_timesheet]'
	WHERE user_id = '$user_id' LIMIT 1";
	
	
	$result = mysql_query($sql, $conn) or die(mysql_error());	
	




?>