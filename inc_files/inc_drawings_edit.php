<?php

if ($_GET[drawing_id] != NULL && $_GET[drawing_edit] == "yes") {
print "<h2>Edit Existing Drawing</h2>";
$sql = "SELECT * FROM intranet_drawings, intranet_projects WHERE drawing_id = '$_GET[drawing_id]' AND drawing_project = proj_id LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);
$drawing_id = $array['drawing_id'];
$drawing_name = $array['drawing_name'];
$drawing_project = $array['drawing_project'];
$drawing_number = $array['drawing_number'];
$drawing_title = $array['drawing_title'];
$drawing_author = $array['drawing_author'];
$drawing_scale = $array['drawing_scale'];
$drawing_orientation = $array['drawing_orientation'];
$drawing_date = $array['drawing_date'];
$drawing_paper = $array['drawing_paper'];
$drawing_packages = $array['drawing_packages'];
$proj_id = $drawing_project;
$proj_num = $array['proj_num'];
} else {
print "<h2>Add New Drawing</h2>";

$sql = "SELECT * FROM intranet_projects WHERE proj_id = '$_GET[proj_id]' LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);
$proj_num = $array['proj_num'];

unset($drawing_id);
unset($drawing_name);
unset($drawing_project);
$drawing_number = $proj_num;
unset($drawing_title);
unset($drawing_author);
unset($drawing_scale);
unset($drawing_orientation);
unset($drawing_date);
unset($drawing_paper);
unset($drawing_packages);
$proj_id = $_GET[proj_id];
}

if ($_GET[proj_id] == NULL && $drawing_project == NULL) { echo "<p>No project selected</p>"; } else {

if ($drawing_project == NULL) { $proj_id = $_GET[proj_id]; }

print "<form method=\"post\" action=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\" id=\"form1\">";

print "<p>";
print "Drawing Number<br />";

if ($_GET[drawing_id] == NULL) {
	echo "<input type=\"radio\" name=\"choose_drawing_name\" id=\"radio_preset\" value=\"preset\" onChange=\"disablefield();\" checked=\"checked\" />&nbsp;";
}
		if ($_GET[drawing_id] == NULL) {
		echo "<input type=\"text\" value=\"$drawing_number\" name=\"drawing_number_1\" maxlength=\"50\" style=\"text-align: right; width: 45px;\" id=\"text1.1\" disabled=\"disabled\" />";
		echo "-";
		echo "<select name=\"drawing_number_2\" id=\"text1.2\" disabled=\"disabled\" >";
			echo "<option value=\"SK\">SK (Sketch)</option>";
			echo "<option value=\"PL\">PL (Planning)</option>";
			echo "<option value=\"CT\">CN (Contract)</option>";
			echo "<option value=\"CT\">CT (Construction)</option>";
			echo "<option value=\"FD\">FD (Final Design)</option>";
		echo "</select>";
		echo "-";
		echo "<select name=\"drawing_number_3\"  id=\"text1.3\" disabled=\"disabled\">";
			echo "<option value=\"\">- None -</option>";
			echo "<option value=\"SV\">SV (Survey)</option>";
			echo "<option value=\"ST\">ST (Site / Location)</option>";
			echo "<option value=\"GA\" selected=\"selected\">GA (General Arrangement)</option>";
			echo "<option value=\"AS\">AS (Assembly)</option>";
			echo "<option value=\"DE\">DE (Detail)</option>";
		echo "</select>";
		echo "-";
		echo "<select name=\"drawing_number_4\"  id=\"text1.4\" disabled=\"disabled\">";
			echo "<option value=\"\">- None -</option>";
			echo "<option value=\"099\">099 (Basement Plan - As Existing)</option>";
			echo "<option value=\"100\">100 (Ground Floor Plan - As Existing)</option>";
			echo "<option value=\"101\">101 (First Floor Plan - As Existing)</option>";
			echo "<option value=\"102\">102 (Second Floor Plan - As Existing)</option>";
			echo "<option value=\"103\">103 (Third Floor Plan - As Existing)</option>";
			echo "<option value=\"104\">104 (Fourth Floor Plan - As Existing)</option>";
			echo "<option value=\"104\">105 (Fifth Floor Plan - As Existing)</option>";
			echo "<option value=\"106\">106 (Sixth Floor Plan - As Existing)</option>";
			echo "<option value=\"107\">107 (Seventh Floor Plan - As Existing)</option>";
			echo "<option value=\"108\">108 (Eighth Floor Plan - As Existing)</option>";
			echo "<option value=\"109\">109 (Ninth Floor Plan - As Existing)</option>";
			echo "<option value=\"110\">110 (Tenth Floor Plan - As Existing)</option>";
			
			echo "<option value=\"599\">599 (Basement Plan - As Proposed)</option>";
			echo "<option value=\"600\">600 (Ground Floor Plan - As Proposed)</option>";
			echo "<option value=\"601\">601 (First Floor Plan - As Proposed)</option>";
			echo "<option value=\"602\">602 (Second Floor Plan - As Proposed)</option>";
			echo "<option value=\"603\">603 (Third Floor Plan - As Proposed)</option>";
			echo "<option value=\"604\">604 (Fourth Floor Plan - As Proposed)</option>";
			echo "<option value=\"604\">605 (Fifth Floor Plan - As Proposed)</option>";
			echo "<option value=\"606\">606 (Sixth Floor Plan - As Proposed)</option>";
			echo "<option value=\"607\">607 (Seventh Floor Plan - As Proposed)</option>";
			echo "<option value=\"608\">608 (Eighth Floor Plan - As Proposed)</option>";
			echo "<option value=\"609\">609 (Ninth Floor Plan - As Proposed)</option>";
			echo "<option value=\"610\">610 (Tenth Floor Plan - As Proposed)</option>";
		echo "</select>";
		
		echo "<script type=\"text/javascript\"> 
			function disablefield(){ 
				if (document.getElementById('radio_custom').checked == 1){ 
						document.getElementById('text1.1').disabled='disabled';
						document.getElementById('text1.2').disabled='disabled';
						document.getElementById('text1.3').disabled='disabled';
						document.getElementById('text1.4').disabled='disabled';
						document.getElementById('text3').disabled='disabled';
						document.getElementById('text2').disabled='';
				} else if (document.getElementById('radio_standard').checked == 1){ 
						document.getElementById('text1.1').disabled='disabled';
						document.getElementById('text1.2').disabled='disabled';
						document.getElementById('text1.3').disabled='disabled';
						document.getElementById('text1.4').disabled='disabled';
						document.getElementById('text2').disabled='disabled';
						document.getElementById('text3').disabled='';
				} else {
						document.getElementById('text1.1').disabled='';
						document.getElementById('text1.2').disabled='';
						document.getElementById('text1.3').disabled='';
						document.getElementById('text1.4').disabled='';
						document.getElementById('text2').disabled='disabled';
						document.getElementById('text3').disabled='disabled'; 
				} 
			} 
		</script>";
		
		if ($_GET[drawing_id] == NULL) {
			echo "<br /><input type=\"radio\" name=\"choose_drawing_name\"  checked=\"checked\" id=\"radio_custom\" value=\"custom\" onChange=\"disablefield();\" />&nbsp;";
			echo "<input type=\"text\" name=\"drawing_number\" required=\"required\" id=\"text2\" />";
		}
		
		if ($_GET[drawing_id] == NULL) {
			echo "<br /><input type=\"radio\" name=\"choose_drawing_name\"  id=\"radio_standard\" value=\"standard\" onChange=\"disablefield();\" />&nbsp;";
			echo "<select name=\"standard_drawing_name\" id=\"text3\" value=\"preset\"  disabled=\"disabled\" />";
			echo "<option disabled>Planning Documents</option>";
			echo "<option value=\"$proj_num-PL-DOC-001\" id=\"radio_preset\" />$proj_num-PL-DOC-001 (Design &amp; Access Statement)</option>";
			echo "<option disabled></option><option disabled>Other Documents</option>";
			echo "<option value=\"$proj_num-DOC-100\" id=\"radio_preset\" />$proj_num-DOC-001 (Accommodation Schedule)</option>";
			echo "</select>";
		}	
		
		} else { echo "<input type=\"text\" name=\"drawing_number\" value=\"$drawing_number\" required=\"required\" />"; }
print "</p>";

//	Drawing packages

		echo "<div style=\"float: right; width: 60%\">";

		echo "<fieldset><legend>Work Packages</legend>";
		
		$array_code = array("D20,D30,D40,D50", "F10,F30", "F31", "G20", "H30", "H51, H60", "J42", "K10,M20", "K40", "N10,N11", "R10","R11","R12", "S12", "T32", "U10", "V90","W40,W44,W90", "X10");
		$array_desc = array("Groundworks", "Carpentry / Timber Framing / First Fixing","Brick / Block Masonry", "Precast Concrete Sills, Lintels, Copings, Features" , "Cladding", "Roofing", "Single-Ply Membranes", "Dry Linings / Partitions / Plastering", "Suspended Ceilings", "Joinery / Fixtures / Kitches","General Lighting &amp; Power", "Rainwater Pipework / Gutters", "Foul Drainage Above Ground","Foul Drainage Below Ground", "Hot &amp; Cold Water", "Low-Temperature Hot Water Heating", "General Ventilation", "Access Control, CCTV, Communications &amp; Security", "Vertical Transport Systems");
		$counter = 1;
		$total = count($array_code);
		
		if (strpos($drawing_packages,"all") > 0) { $checked = "checked=\"checked\""; } else { unset($checked); }
		
		//Search the string for the relevant NBS code and return "checked" if found.
		
		while ($counter < $total) {
		
			if (strpos($drawing_packages,$array_code[$counter]) === false) { unset($checked); } else { $checked = "checked=\"checked\""; }
		
			echo "<input type=\"checkbox\" name=\"drawing_package_list[$counter]\" $checked value=\"$array_code[$counter]\" />&nbsp;$array_desc[$counter]&nbsp; ($array_code[$counter])<br />";	
			$counter++;
		}
		echo "<input type=\"hidden\" name=\"drawing_total_packages\" value=\"" . count($array_code) . "\" />";
		echo "</fieldset>";

		echo "</div>"; 

print "<p>";
print "Drawing Title<br />";
print "<textarea name=\"drawing_title\" rows=\"4\" cols=\"42\" required=\"required\">$drawing_title</textarea>";
print "</p>";

print "<p>";
print "Drawing By<br />";
$data_user_var = "drawing_author";
if ($drawing_author > 0) { $data_user_id = $drawing_author; } else { $data_user_id = $_COOKIE[user]; }
include("dropdowns/inc_data_dropdown_users.php");
print "</p>";

print "<p>";
print "Drawing Scale<br />";
$result_data = "drawing_scale";
include("dropdowns/inc_data_dropdown_drawings_scale.php");
print "</p>";

print "<p>";
print "Drawing Paper<br />";
$result_data = "drawing_paper";
include("dropdowns/inc_data_dropdown_drawings_paper.php");
print "</p>";

print "<p>";
print "Drawing Orientation<br />";
print "<input type=\"radio\" name=\"drawing_orientation\" value=\"l\"";
	if ($drawing_orientation == "l" OR $drawing_orientation != "p") { print " checked "; }
print " />&nbsp;Landscape<br />";
print "<input type=\"radio\" name=\"drawing_orientation\" value=\"p\"";
	if ($drawing_orientation == "p") { print " checked ";}
print "/>&nbsp;Portrait";
print "</p>";

print "<p>";
print "Date of Drawing (DD/MM/YYYY)<br />";
if ($drawing_date != NULL) { $drawing_date_day = date("j", $drawing_date); } else { $drawing_date_day = date("j", time()); }
if ($drawing_date != NULL) { $drawing_date_month = date("n", $drawing_date); } else { $drawing_date_month = date("n", time()); }
if ($drawing_date != NULL) { $drawing_date_year = date("Y", $drawing_date); } else { $drawing_date_year = date("Y", time()); }
print "<input type=\"text\" name=\"drawing_date_day\" value=\"$drawing_date_day\" maxlength=\"2\" size=\"4\" required=\"required\" />&nbsp;Day&nbsp;"; 
print "<input type=\"text\" name=\"drawing_date_month\" value=\"$drawing_date_month\" maxlength=\"2\" size=\"4\" required=\"required\" />&nbsp;Month&nbsp;"; 
print "<input type=\"text\" name=\"drawing_date_year\" value=\"$drawing_date_year\" maxlength=\"4\" size=\"4\" required=\"required\" />&nbsp;Year"; 
print "</p>";

print "<p>";
print "<input type=\"submit\" />";
print "<input type=\"hidden\" name=\"action\" value=\"drawing_edit\"  />";
print "<input type=\"hidden\" name=\"drawing_project\" value=\"$proj_id\"  />";

if ($drawing_id != NULL) {
	print "<input type=\"hidden\" name=\"drawing_id\" value=\"$drawing_id\"  />";
}

print "</form>";

}

?>