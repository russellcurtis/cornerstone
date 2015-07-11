<?php 

if ($_POST[qms_id] > 0) {
	
	$sql_update = "UPDATE intranet_qms SET qms_toc1 = $_POST[qms_toc1], qms_toc2 = $_POST[qms_toc2], qms_toc3 = $_POST[qms_toc3], qms_toc4 = $_POST[qms_toc4], qms_type = '$_POST[qms_type]', qms_text = \"" . trim ( addslashes ( $_POST[qms_text] ) ) . "\", qms_timestamp = " . time() . ", qms_user = $_COOKIE[user] WHERE qms_id = $_POST[qms_id] LIMIT 1";
	$result = mysql_query($sql_update, $conn) or die(mysql_error());
	
	echo "<p>$sql_update</p>";

		
} elseif ($_POST[action] == "add") {
	
	$qms_text = trim ( addslashes($_POST[qms_text] ) );
	
	$sql_update = "INSERT INTO intranet_qms (qms_id, qms_toc1, qms_toc2, qms_toc3, qms_toc4, qms_type, qms_text, qms_timestamp, qms_user) VALUES ( NULL, $_POST[qms_toc1], $_POST[qms_toc2], $_POST[qms_toc3], $_POST[qms_toc4], '$_POST[qms_type]', '$qms_text', " . time() . ", $_COOKIE[user])";
	$result = mysql_query($sql_update, $conn) or die(mysql_error());

		
}


echo "<h1>Edit QMS<h1>";

// First create the form that allows us to switch the section of the QMS


				if ($_GET[s1] == NULL) { $s1 = 0;} else { $s1 = $_GET[s1];}

				if ($_GET[s2] == NULL) { $s2 = 1;} else { $s2 = $_GET[s2];}

				$s1 = intval ($s1);

				$s2 = intval ($s2);

					echo "<form action=\"index2.php\" method=\"get\">";
					
					echo "<p><input type=\"hidden\" name=\"page\" value=\"qms_edit\" />";
					
					
					$sql = "SELECT * FROM intranet_qms WHERE qms_toc1 > 0 AND qms_toc2 = 0 AND qms_toc3 = 0 AND qms_toc4 = 0 ORDER BY qms_toc1";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					echo "<select name=\"s1\" onchange=\"this.form.submit()\">";
					if ($s1 == 0) { $selected = " selected=\"selected\" "; } else { unset($selected); }
					while ($array = mysql_fetch_array($result)) {
						$qms_toc1 = $array['qms_toc1'];
						$qms_text = $array['qms_text'];
						if ($s1 == $qms_toc1) { $selected = " selected=\"selected\" "; } else { unset($selected); }
						echo "<option value=\"$qms_toc1\" $selected >$qms_toc1. $qms_text</option>";
					}
					
					echo "</select>&nbsp;";
					
					$sql = "SELECT * FROM intranet_qms WHERE qms_toc2 > 0 AND qms_toc1 = '$s1' AND qms_toc3 = 0 AND qms_toc4 = 0 ORDER BY qms_toc2";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					if (mysql_num_rows($result) > 0) {
						echo "<select name=\"s2\" onchange=\"this.form.submit()\">";
						if ($s2 == NULL) { $s2 = 1;}
						while ($array = mysql_fetch_array($result)) {
						$qms_toc2 = $array['qms_toc2'];
						$qms_text = $array['qms_text'];
						if ($s2 == $qms_toc2) { $selected = " selected=\"selected\" "; } else { unset($selected); }
						echo "<option value=\"$qms_toc2\" $selected >$qms_toc2. $qms_text</option>";
						}
						echo "</select>";
					
					}
					echo "</form></p>";

			
$sql = "SELECT * FROM intranet_qms WHERE qms_toc1 = '$s1' AND (qms_toc2 = '$s2' OR qms_toc2 = 0) ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4";

$result = mysql_query($sql, $conn) or die(mysql_error());

echo "<table>";


echo "<tr><th colspan=\"8\">Edit Existing Entry</th></tr>";

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
echo "<form action=\"index2.php?page=qms_edit&amp;s1=$s1&amp;s2=$s2#$qms_id\" method=\"post\"><input type=\"hidden\" name=\"qms_id\" value=\"$qms_id\">";
echo "<td>$qms_id</td>";
echo "<td><input type=\"text\" value=\"$qms_toc1\" name=\"qms_toc1\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc2\" name=\"qms_toc2\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc3\" name=\"qms_toc3\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc4\" name=\"qms_toc4\" style=\"width: 40px;\" /></td>";
echo "<td><textarea name=\"qms_text\" style=\"min-width:500px; height: 20px;\">$qms_text</textarea>";
if ($qms_type == "code") { $checked = " checked=\"checked\" ";} else { unset($checked); }
if ($qms_type == "comp") { $checked2 = " checked=\"checked\" ";} else { unset($checked2); }
echo "<td><input type=\"radio\" value=\"code\" name=\"qms_type\" $checked />&nbsp;Code<br /><input type=\"radio\" value=\"comp\" name=\"qms_type\" $checked2 />&nbsp;Complete</td>";
echo "<td><input type=\"submit\" value=\"Update Entry\"/></td>";
echo "</form>";
echo "</tr>";


	
}

if ($_GET[s1] > 0) { $qms_toc1 = intval($_GET[s1]); }
if ($_GET[s2] > 0) { $qms_toc2 = intval($_GET[s2]); }
if ($qms_toc3 > 0) { $qms_toc3 = intval($qms_toc3) + 1; } else { $qms_toc3 = 0; }
$qms_toc4 = intval($qms_toc4) + 1;
echo "<tr><th colspan=\"8\">Add New Entry</th></tr>";
echo "<tr id=\"new\">";
echo "<form action=\"index2.php?page=qms_edit&amp;s1=$s1&amp;s2=$s2#$qms_id\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"add\">";
echo "<td>(New)</td>";
echo "<td><input type=\"text\" value=\"$qms_toc1\" name=\"qms_toc1\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc2\" name=\"qms_toc2\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc3\" name=\"qms_toc3\" style=\"width: 40px;\" /></td>";
echo "<td><input type=\"text\" value=\"$qms_toc4\" name=\"qms_toc4\" style=\"width: 40px;\" /></td>";
echo "<td><textarea name=\"qms_text\" style=\"min-width:500px; height: 100px;\"></textarea>";
echo "<td><input type=\"radio\" value=\"code\" name=\"qms_type\" />&nbsp;Code<br /><input type=\"radio\" value=\"comp\" name=\"qms_type\" />&nbsp;Complete</td>";
echo "<td><input type=\"submit\" value=\"Add New Entry\" /></td>";
echo "</form>";
echo "</tr>";

echo "</table>";


?>