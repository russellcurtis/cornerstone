<?php

$sql_drawing = "SELECT * FROM intranet_drawings WHERE drawing_id = '$_GET[drawing_id]' LIMIT 1";
$result_drawing = mysql_query($sql_drawing, $conn) or die(mysql_error());
		
		$array_drawings = mysql_fetch_array($result_drawing);
		$drawing_number = $array_drawings['drawing_number'];
		$drawing_id = $array_drawings['drawing_id'];
		$drawing_title = $array_drawings['drawing_title'];

$sql_revision = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = $drawing_id ORDER BY revision_letter DESC";
$result_revision = mysql_query($sql_revision, $conn) or die(mysql_error());
$array_revision = mysql_fetch_array($result_revision);
$revision_letter = $array_revision['revision_letter'];

echo "<h1>Add Drawing Revision for $drawing_number</h1>";

$revision_letters = array("-","a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x","y","z","*");
$revision_code = array("-","Alpha","Bravo","Charlie","Delta","Echo","Foxtrot","Golf","Hotel","Juliet","Kilo","Lima","Mike","November","Papa","Quebec","Romeo","Sierra","Tango","Uniform","Victor","Whisky","X-Ray","Yankee","Zulu","Obsolete");

print "<form method=\"post\" action=\"index2.php?page=drawings_detailed&amp;drawing_id=$_GET[drawing_id]&amp;proj_id=$_GET[proj_id]\">";

$rev_count = array_keys($revision_letters, $revision_letter);
$rev_begin = $rev_count[0] + 1;

$rev_total = count($revision_letters);

echo "<p>Revision Letter<br />";
echo "<select name=\"revision_letter\">";
while ($rev_begin < $rev_total) {
echo "<option value=\"$revision_letters[$rev_begin]\">" . strtoupper($revision_letters[$rev_begin]) . " " . ($revision_code[$rev_begin]) . "</option>";
$rev_begin++;
}
echo "</select></p>";

print "<p>";
print "Revision Description<br />";
print "<textarea name=\"revision_desc\" rows=\"4\" cols=\"42\">$revision_desc</textarea>";
print "</p>";

print "<p>";
print "Revision By<br />";
$data_user_var = "revision_author";
if ($drawing_user > 0) { $data_user_id = $drawing_user; } else { $data_user_id = $_COOKIE[user]; }
include("dropdowns/inc_data_dropdown_users.php");
print "</p>";

print "<p>";
print "Date of Revision (DD/MM/YYYY)<br />";
if ($revision_date != NULL) { $revision_date_day = date("j", $revision_date); } else { $revision_date_day = date("j", time()); }
if ($revision_date != NULL) { $revision_date_month = date("n", $revision_date); } else { $revision_date_month = date("n", time()); }
if ($revision_date != NULL) { $revision_date_year = date("Y", $revision_date); } else { $revision_date_year = date("Y", time()); }

print "<input type=\"text\" name=\"revision_date_day\" value=\"$revision_date_day\" maxlength=\"2\" size=\"4\" />&nbsp;Day&nbsp;"; 
print "<input type=\"text\" name=\"revision_date_month\" value=\"$revision_date_month\" maxlength=\"2\" size=\"4\" />&nbsp;Month&nbsp;"; 
print "<input type=\"text\" name=\"revision_date_year\" value=\"$revision_date_year\" maxlength=\"4\" size=\"4\" />&nbsp;Year"; 
print "</p>";

print "<p>";
print "<input type=\"submit\" />";
print "<input type=\"hidden\" name=\"action\" value=\"revision_edit\"  />";

if ($_GET[drawing_id] != NULL) {
	print "<input type=\"hidden\" name=\"revision_drawing\" value=\"$_GET[drawing_id]\"  />";
}

print "</form>";



?>