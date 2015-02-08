<?php

// Begin to clean up the $_POST submissions

$revision_id = $_POST[revision_id];
$revision_letter = CleanUp($_POST[revision_letter]);
$revision_desc = CleanUp($_POST[revision_desc]);
$revision_date_day = $_POST[revision_date_day];
$revision_date_month = $_POST[revision_date_month];
$revision_date_year = $_POST[revision_date_year];
$revision_author = CleanUp($_POST[revision_author]);
$revision_drawing = CleanUp($_POST[revision_drawing]);

// Check the date input

if (checkdate($revision_date_month, $revision_date_day, $revision_date_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page_redirect = "drawings_detailed";
}

// Check that the required values have been entered, and alter the page to show if these values are invalid

elseif ($revision_desc == "") { $alertmessage = "The drawing number was left empty."; $page_redirect = "drawings_revision_edit"; }

else {

// Convert the date to a time

$revision_date = mktime ( 12, 0, 0, $revision_date_month, $revision_date_day, $revision_date_year );

// Construct the MySQL instruction to add these entries to the database

if ($revision_id > 0) {

		$sql_edit = "UPDATE intranet_drawings_revision SET
		revision_letter = '$revision_letter',
		revision_desc = '$revision_desc',
		revision_date = '$revision_date',
		revision_author = '$revision_author',
		revision_drawing = '$revision_drawing',
		WHERE revision_id = '$revision_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Revision updated successfully.";
		$techmessage = $sql_edit;		
		
} else {

		$sql_add = "INSERT INTO intranet_drawings_revision (
		revision_id,
		revision_letter,
		revision_desc,
		revision_date,
		revision_author,
		revision_drawing
		) values (
		'NULL',
		'$revision_letter',
		'$revision_desc',
		'$revision_date',
		'$revision_author',
		'$revision_drawing'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Revision added successfully.";
		$techmessage = $sql_add;
}






}

?>
