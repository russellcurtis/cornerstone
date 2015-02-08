<?php

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

if ($_POST[item_id] > 0 && $_POST[item_notes] != NULL) {

	$item_id = $_POST[item_id];
	$item_notes = trim ( addslashes ($_POST[item_notes]) );
	$sql_update = "UPDATE intranet_project_checklist_items SET item_notes = \"" . $item_notes . "\" WHERE item_id = $item_id LIMIT 1";
	$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
	
}

$proj_id = $_GET[proj_id];

$sql_project = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id";
$result_project = mysql_query($sql_project, $conn) or die(mysql_error());
$array_project = mysql_fetch_array($result_project);
$proj_num = $array_project['proj_num'];
$proj_name = $array_project['proj_name'];

echo "<h1>Project Checklist for $proj_num $proj_name</h1>";

echo "<p><a href=\"pdf_project_checklist.php?proj_id=$proj_id\">Click here for PDF version <img src=\"images/button_pdf.png\" /></a></p>";

$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id ORDER BY item_group, item_order, item_name";
$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());

echo "

<script type=\"text/javascript\">

	function hideRow(row, hideVal) {
    if (document.getElementById(row)) {
      var displayStyle = (hideVal!=true)? '' : 'none' ;
      document.getElementById(row).style.display = displayStyle;
    }
  }

	
</script>
	
";

if (!$item) {
	echo "<form action=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"checklist_update\" />";
}



echo "<table>";
echo "<tr><th>Item</th><th>Required</th><th>Date Acquired</th><th>Comment</th><th colspan=\"3\">Link to File</th></tr>";

if (mysql_num_rows($result_checklist) > 0) {

	$group = NULL;

	while ($array_checklist = mysql_fetch_array($result_checklist)) {
	$item_id = $array_checklist['item_id'];
	$item_name = $array_checklist['item_name'];
	$item_date = $array_checklist['item_date'];
	$item_group = $array_checklist['item_group'];
	$item_required = $array_checklist['item_required'];
	$item_notes = nl2br( $array_checklist['item_notes']);
	
	$checklist_id = $array_checklist['checklist_id'];
	$checklist_required = $array_checklist['checklist_required'];
	$checklist_date	= $array_checklist['checklist_date'];
	$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
	$checklist_user = $_COOKIE[user];
	$checklist_link	= $array_checklist['checklist_link'];
	$checklist_timestamp = time();
	$checklist_project = $_GET[proj_id];
	
	if ($item_group != $group) { echo "<tr><td colspan=\"7\"><strong>$item_group</strong></td></tr>"; }
	
	
	echo "<tr><td>" . $item_name . "</td>";
	
	echo "<td>";
	
	if (!$item) {
	
	echo "
		<input type=\"hidden\" name=\"item_id[]\" value=\"$item_id\" />
		<input type=\"hidden\" name=\"checklist_id[]\" value=\"$checklist_id\" />
		<input type=\"hidden\" name=\"checklist_user[]\" value=\"$checklist_user\" />
		<input type=\"hidden\" name=\"checklist_timestamp[]\" value=\"$checklist_timestamp\" />
		<input type=\"hidden\" name=\"checklist_project[]\" value=\"$checklist_project\" />
		";

	
		echo "<select name=\"checklist_required[]\">";
		if ($checklist_required == NULL) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"0\" $checked>-</option>";
		if ($checklist_required == 1) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"1\" $checked>No</option>";
		if ($checklist_required == 2) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"2\" $checked>Yes</option>";
		echo "</select>";
	
	} else {
		
		if ($checklist_required == 1) { echo "Not Required"; }
		elseif ($checklist_required == 2) { echo "Required"; }
		else { echo "-"; }
	
	}
	
	echo "</td>";

	if (!$item) {
		echo "<td><input name=\"checklist_date[]\" type=\"date\" value=\"$checklist_date\" /></td>";
		echo "<td><input name=\"checklist_comment[]\" value=\"$checklist_comment\" /></td>";
		echo "<td><input name=\"checklist_link[]\" value=\"$checklist_link\" /></td>";
		echo "<td style=\"min-width: 20px;\">";
			if ($checklist_link) { echo "<a href=\"$checklist_link\"><img src=\"images/button_internet.png\" /></a>"; }
		echo "</td>";
	} else {	
		if ($checklist_date == 0) { $checklist_date = "-";}
		echo "<td>$checklist_date</td>";
		echo "<td>$checklist_comment</td>";
		if ($checklist_link) {
			echo "<td colspan=\"2\" style=\"min-width: 20px;\"><a href=\"$checklist_link\"><img src=\"images/button_internet.png\" /></a></td>";
		} elseif ($_GET[item] == $item_id) {
			echo "<td colspan=\"3\">-</td>";
		} else {
			echo "<td colspan=\"2\">-</td>";
		}
	}

	
	if ($item_notes != NULL) {
	
		if ($_GET[item] != $item_id AND $_POST[item] != $item_id) { $hidden = "none"; } else { unset($hidden); }
	
		if (!$item) {
			echo "<td><a href=\"javascript:void(0);\" onclick=\"hideRow($item_id, false);\">Help</a></td>";
		} else {
			echo "<td></td>";
		}
		echo "</tr>";
		
		if ($_GET[item] == $item_id) { TextAreaEdit(); $item_notes = "<form action=\"index2.php?page=project_checklist&proj_id=$proj_id\" method=\"post\"><textarea style=\"width: 99%;height: 500px;\" name=\"item_notes\">$item_notes</textarea>"; }
	
		echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"6\" style=\"padding: 12px;\">$item_notes</td><td>";
		if ($_GET[item] == $item_id) { 
			echo "<input type=\"hidden\" name=\"item_id\" value=\"$item_id\" /><input type=\"submit\" value=\"Update\" /></form>";
		} elseif ( $user_usertype_current > 3 ) {
			echo "<a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;item=$item_id\"><img src=\"images/button_edit.png\" /></a>";
		}
		echo "</td></tr>";
		
	} elseif ($item_id > 0 && $_GET[item] == $item_id) {
		
		TextAreaEdit(); echo "<tr><td colspan=\"6\"><form action=\"index2.php?page=project_checklist&proj_id=$proj_id\" method=\"post\"><textarea style=\"width: 99%;height: 500px;\" name=\"item_notes\">$item_notes</textarea></td><td><input type=\"hidden\" name=\"item_id\" value=\"$item_id\" /><input type=\"submit\" value=\"Update\" /></form></td></tr>";
	
	} else { 
	
		echo "<td><a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;item=$item_id\">+</a></td>";
		echo "</tr>";
	
	
	}
	
	$group = $item_group;

	}








}


echo "</table>";

echo "<input type=\"submit\" />";

echo "</form>";









?>