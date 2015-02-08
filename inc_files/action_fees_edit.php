<?php

unset($alertmessage);

// Begin to clean up the $_POST submissions

		$ts_fee_id = CleanNumber($_POST[ts_fee_id]);
		$ts_fee_project = CleanNumber($_POST[ts_fee_project]);
		$ts_fee_text = CleanUp($_POST[ts_fee_text]);
		$ts_fee_stage = CleanUp($_POST[ts_fee_stage]);
		$ts_fee_target = CleanNumber($_POST[ts_fee_target]);
		$ts_fee_comment = CleanUp($_POST[ts_fee_comment]);
		
		$choose = CleanNumber($_POST[choose]);
		
		if ($choose == "value") {
		$ts_fee_value = CleanNumber($_POST[ts_fee_value]);
		$ts_fee_percentage = "";
		} else {
		$ts_fee_percentage = CleanNumber($_POST[ts_fee_percentage]);
		$ts_fee_value = "";
		}

		$ts_fee_duration = CleanNumber($_POST[ts_fee_duration]);
		$ts_fee_pre = CleanNumber($_POST[ts_fee_pre]);
		
		$ts_fee_duration = $ts_fee_duration * 604800;
		

// Check that the required values have been entered, and alter the page to show if these values are invalid

// elseif ($ts_fee_text == "" ) { $alertmessage = "The description was left empty."; $page_redirect = "timesheet_fees_edit"; }
// elseif ($ts_fee_value < 1 AND $ts_fee_percentage < 1) { $alertmessage = "The fee amount was left empty."; $page_redirect = "timesheet_fees_edit";}
if ($ts_fee_percentage > 100) { $alertmessage = "The fee percentage is greater than 100."; $page_redirect = "timesheet_fees_edit";}

if ($alertmessage == NULL) {

// Construct the MySQL instruction to add these entries to the database

if ($ts_fee_id > 0) {

		$sql_edit = "UPDATE intranet_timesheet_fees SET
		ts_fee_stage = '$ts_fee_stage',
		ts_fee_time_begin = '',
		ts_fee_time_end = '$ts_fee_duration',
		ts_fee_text = '$ts_fee_text',
		ts_fee_value = '$ts_fee_value',
		ts_fee_project = '$ts_fee_project',
		ts_fee_percentage = '$ts_fee_percentage',
		ts_fee_pre = '$ts_fee_pre',
		ts_fee_prospect = '$ts_fee_prospect',
		ts_fee_target = '$ts_fee_target',
		ts_fee_comment = '$ts_fee_comment'
		WHERE ts_fee_id = '$ts_fee_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Fee stage updated successfully.";
		$techmessage = $sql_edit;		
		
} else {

		$sql_add = "INSERT INTO intranet_timesheet_fees (
		ts_fee_id,
		ts_fee_stage,
		ts_fee_time_begin,
		ts_fee_time_end,
		ts_fee_text,
		ts_fee_value,
		ts_fee_project,
		ts_fee_percentage,
		ts_fee_pre,
		ts_fee_prospect,
		ts_fee_target,
		ts_fee_comment
		) values (
		'NULL',
		'$ts_fee_stage',
		'',
		'$ts_fee_duration',
		'$ts_fee_text',
		'$ts_fee_value',
		'$ts_fee_project',
		'$ts_fee_percentage',
		'$ts_fee_pre',
		'$ts_fee_prospect',
		'$ts_fee_target',
		'$ts_fee_comment'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Fee stage added successfully.";
		$techmessage = $sql_add;
}






}

?>
