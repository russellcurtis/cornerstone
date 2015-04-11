<?php 

if ($_POST[qms_id] > 0) {
	
	$sql_update = "UPDATE intranet_qms SET qms_toc1 = $_POST[qms_toc1], qms_toc2 = $_POST[qms_toc2], qms_toc3 = $_POST[qms_toc3], qms_toc4 = $_POST[qms_toc4], qms_type = '$_POST[qms_type]', qms_text = \"" . trim ( addslashes ( $_POST[qms_text] ) ) . "\", qms_timestamp = " . time() . ", qms_user = $_COOKIE[user] WHERE qms_id = $_POST[qms_id] LIMIT 1";
	$result = mysql_query($sql_update, $conn) or die(mysql_error());

		
} elseif ($_POST[action] == "add") {
	
	$qms_text = trim ( addslashes($_POST[qms_text] ) );
	
	$sql_update = "INSERT INTO intranet_qms (qms_id, qms_toc1, qms_toc2, qms_toc3, qms_toc4, qms_type, qms_text, qms_timestamp, qms_user) VALUES ( NULL, $_POST[qms_toc1], $_POST[qms_toc2], $_POST[qms_toc3], $_POST[qms_toc4], '$_POST[qms_type]', '$qms_text', " . time() . ", $_COOKIE[user])";
	$result = mysql_query($sql_update, $conn) or die(mysql_error());

		
}


echo "<h1>Edit QMS<h1>";


$sql = "SELECT * FROM intranet_qms ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4";
$result = mysql_query($sql, $conn) or die(mysql_error());

echo "<table>";

while ($array = mysql_fetch_array($result)) {
	
$qms_id = $array['qms_id'];
$qms_toc1 = $array['qms_toc1'];
$qms_toc2 = $array['qms_toc2'];
$qms_toc3 = $array['qms_toc3'];
$qms_toc4 = $array['qms_toc4'];
$qms_type = $array['qms_type'];
$qms_text = $array['qms_text'];
$qms_timestamp = $array['qms_timestamp'];


echo "<tr id=\"$qms_id\">";
echo "<form action=\"index2.php?page=qms_edit#$qms_id\" method=\"post\"><input type=\"hidden\" name=\"qms_id\" value=\"$qms_id\">";
echo "<td>$qms_id</td>";
echo "<td><input type=\"text\" value=\"$qms_toc1\" name=\"qms_toc1\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc2\" name=\"qms_toc2\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc3\" name=\"qms_toc3\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc4\" name=\"qms_toc4\" style=\"width: 40px;\" /></td>";
echo "<td><textarea name=\"qms_text\" style=\"min-width:500px; height: 20px;\">$qms_text</textarea>";
if ($qms_type == "code") { $checked = " checked=\"checked\" ";} else { unset($checked); }
echo "<td><input type=\"checkbox\" value=\"code\" name=\"qms_type\" $checked />&nbsp;Code</td>";
echo "<td><input type=\"submit\" value=\"Update Entry\"/></td>";
echo "</form>";
echo "</tr>";


	
}

$qms_toc4_next = $qms_toc4 + 1;

echo "<tr id=\"new\">";
echo "<form action=\"index2.php?page=qms_edit#$qms_id\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"add\">";
echo "<td>(New)</td>";
echo "<td><input type=\"text\" value=\"$qms_toc1\" name=\"qms_toc1\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc2\" name=\"qms_toc2\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc3\" name=\"qms_toc3\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc4_next\" name=\"qms_toc4\" style=\"width: 40px;\" /></td>";
echo "<td><textarea name=\"qms_text\" style=\"min-width:500px; height: 100px;\"></textarea>";
echo "<td><input type=\"checkbox\" value=\"code\" name=\"qms_type\" />&nbsp;Code</td>";
echo "<td><input type=\"submit\" value=\"Add New Entry\" /></td>";
echo "</form>";
echo "</tr>";

echo "</table>";


?>