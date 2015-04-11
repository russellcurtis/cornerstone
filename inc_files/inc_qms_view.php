<?php



if ($_GET[s1] == NULL) { $s1 = 0;} else { $s1 = $_GET[s1];}

if ($_GET[s2] == NULL) { $s2 = 1;} else { $s2 = $_GET[s2];}

$s1 = intval ($s1);

$s2 = intval ($s2);

	echo "<h1>Quality Management System</h1>";
	
	$beginweek = BeginWeek(time());
	
	echo "<p>Beginning of Week: " . $beginweek . " (" . date("r", $beginweek) . ")</p>";

	echo "<form action=\"index2.php\" method=\"get\">";
	
	echo "<input type=\"hidden\" name=\"page\" value=\"qms_view\" />";
	
	
	$sql = "SELECT * FROM intranet_qms WHERE qms_toc1 > 0 AND qms_toc2 = 0 AND qms_toc3 = 0 AND qms_toc4 = 0 ORDER BY qms_toc1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	echo "<select name=\"s1\" onchange=\"this.form.submit()\">";
	if ($s1 == 0) { $selected = " selected=\"selected\" "; } else { unset($selected); }
	echo "<option value=\"0\" $selected >Contents</option>";
	while ($array = mysql_fetch_array($result)) {
		$qms_toc1 = $array['qms_toc1'];
		$qms_text = $array['qms_text'];
		if ($s1 == $qms_toc1) { $selected = " selected=\"selected\" "; } else { unset($selected); }
		echo "<option value=\"$qms_toc1\" $selected >$qms_toc1. $qms_text</option>";
	}
	
	echo "</select>";
	
	$sql = "SELECT * FROM intranet_qms WHERE qms_toc2 > 0 AND qms_toc1 = $s1 AND qms_toc3 = 0 AND qms_toc4 = 0 ORDER BY qms_toc2";
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
	echo "</form>";
	



if ($s1 == 0) {
	
	
			$sql_contents = "SELECT * FROM intranet_qms WHERE qms_toc4 = 0 ORDER BY qms_toc1, qms_toc2, qms_toc3";
			$result_contents = mysql_query($sql_contents, $conn) or die(mysql_error());
			
			echo "<h2>Contents</h2>";
			
			echo "<table>";
			
			while ($array_contents = mysql_fetch_array($result_contents)) {
				
					$qms_id = $array_contents['qms_id'];
					$qms_toc1 = $array_contents['qms_toc1'];
					$qms_toc2 = $array_contents['qms_toc2'];
					$qms_toc3 = $array_contents['qms_toc3'];
					$qms_toc4 = $array_contents['qms_toc4'];
					$qms_text = "<a href=\"index2.php?page=qms_view&amp;s1=$qms_toc1&amp;s2=$qms_toc2&amp;sub=compguide&amp;qms_id=$qms_id#$qms_id\">" . $array_contents['qms_text'] . "</a>";
				
					if ($qms_toc3 > 0) { echo "<tr><td style=\"width: 20px;\"></td><td style=\"width: 20px;\"></td><td>" . $qms_toc1. "." . $qms_toc2. "." . $qms_toc3 . "</td><td>$qms_text</td></tr>"; }

					elseif ($qms_toc2 > 0) { echo "<tr><td style=\"width: 20px;\"></td><td colspan=\"2\">" . $qms_toc1. "." . $qms_toc2 . "</td><td>$qms_text</td></tr>"; }

					elseif ($qms_toc1 > 0) { echo "<tr><td colspan=\"3\">" . $qms_toc1 . "</td><td>$qms_text</td></tr>"; }	
				
			}
			
			echo "</table>";
	
	
} else {
	


			$sql = "SELECT * FROM intranet_qms WHERE qms_toc1 = $s1 AND qms_toc2 = $s2 ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4";
			
			$result = mysql_query($sql, $conn) or die(mysql_error());

			while ($array = mysql_fetch_array($result)) {
				
					$qms_id = $array['qms_id'];
					$qms_toc1 = $array['qms_toc1'];
					$qms_toc2 = $array['qms_toc2'];
					$qms_toc3 = $array['qms_toc3'];
					$qms_toc4 = $array['qms_toc4'];
					$qms_type = $array['qms_type'];
					$qms_text = $array['qms_text'];
					$qms_timestamp = $array['qms_timestamp'];
					
					if ($qms_id == $_GET[qms_id]) { $bg = " style=\"background: rgba(20, 201, 201, 0.25); padding: 6px 6px 6px 6px;\" "; } else { unset($bg); }

					if ($qms_toc4 > 0 && $qms_type == "code") { echo "<pre id=\"$qms_id\" $bg>" . nl2br ( $qms_text ) . "</pre>"; }
					
					elseif ($qms_toc4 > 0 && $qms_type == NULL) { echo "<p id=\"$qms_id\" $bg>" . $qms_text . "</p>"; }

					elseif ($qms_toc3 > 0) { echo "<h4 id=\"$qms_id\" $bg>" . $qms_toc1. "." . $qms_toc2. "." . $qms_toc3 . " " . $qms_text . "</h4>"; }

					elseif ($qms_toc2 > 0) { echo "<h3 id=\"$qms_id\" $bg>" . $qms_toc1. "." . $qms_toc2. " " . $qms_text . "</h3>"; }

					elseif ($qms_toc1 > 0) { echo "<h2 id=\"$qms_id\" $bg>" . $qms_toc1. " " . $qms_text . "</h2>"; }	
				
				
			}
		
			
			$sql_next = "SELECT * FROM intranet_qms WHERE qms_toc1 = $s1 AND qms_toc2 > $s2 ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4 LIMIT 1";
			$result_next = mysql_query($sql_next, $conn) or die(mysql_error());
			if (mysql_num_rows($result_next) > 0) {
				$array_next = mysql_fetch_array($result_next);
				$qms_id = $array_next['qms_id'];
				$qms_toc1 = $array_next['qms_toc1'];
				$qms_toc2 = $array_next['qms_toc2'];
				$qms_text = $array_next['qms_text'];
				echo "<p style=\"margin: bottom: 25px; text-align: right;\"><i><a href=\"index2.php?page=qms_view&amp;s1=$s1&amp;s2=$qms_toc2\">Next: $qms_toc1.$qms_toc2. $qms_text</i></a></p>";
			} else {
			$s1_next = $s1 + 1;
			$sql_next = "SELECT * FROM intranet_qms WHERE qms_toc1 = $s1_next ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4 LIMIT 1";
			$result_next = mysql_query($sql_next, $conn) or die(mysql_error());
				if (mysql_num_rows($result_next) > 0) {
					$array_next = mysql_fetch_array($result_next);
					$qms_id = $array_next['qms_id'];
					$qms_toc1 = $array_next['qms_toc1'];
					$qms_toc2 = $array_next['qms_toc2'];
					$qms_text = $array_next['qms_text'];
					echo "<p style=\"margin: bottom: 25px; text-align: right;\"><i><a href=\"index2.php?page=qms_view&amp;s1=$qms_toc1&amp;s2=$qms_toc2\"><i>Next: $qms_toc1.$qms_toc2. $qms_text</i></a></p>";
				}
			}

}



?>
