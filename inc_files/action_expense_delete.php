<?php


$ts_expense_id = CleanNumber($_POST[ts_expense_id]);

if ($ts_expense_id == NULL) {
	$page_redirect = "timesheet_expense_list";
	$alertmessage = "The expense item you tried to delete does not exist.";
} elseif

	($user_usertype_current < 4) {
	$page_redirect = "timesheet_expense_list";
	$alertmessage = "You do not have sufficient rights to remove expenses from the system.";
} else {

$nowtime = TimeFormat(time());

$sql_edit = "UPDATE intranet_timesheet_expense SET
		ts_expense_project = '',
		ts_expense_value = '0',
		ts_expense_date = '$_POST[ts_expense_date]',
		ts_expense_desc = '-Void-',
		ts_expense_user = '$_POST[ts_expense_user]',
		ts_expense_vat = '0',
		ts_expense_receipt = '',
		ts_expense_invoiced = '',
		ts_expense_reimburse = '',
		ts_expense_notes = 'Deleted $nowtime',
		ts_expense_category = '',
		ts_expense_disbursement = '',
		ts_expense_p11d = '',
		ts_expense_verified = '0'
		WHERE ts_expense_id = '$_POST[ts_expense_id]' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Expense <strong>$ts_expense_id</strong> deleted successfully.";
		$techmessage = $sql_edit;
		
		echo $sql_edit;

}

?>