<?php

print "<h1>Search</h1>";

// Construct search terms

if ($_GET[keywords] != NULL) {$keywords = $_GET[keywords]; }
elseif ($_POST[keywords] != NULL) {$keywords = CleanUp($_POST[keywords]); }

if (strlen($keywords) > 2) {

$keywords_array = explode(" ", $keywords);

function SearchTerms($search_text,$search_field) {
		$counter = 0;
		$max_count = count($search_text);
		while($counter < $max_count) {
		if ($counter > 0) { $searching_blog = $searching_blog." AND $search_field LIKE "; }
		$searching_blog = $searching_blog."'%".$search_text[$counter]."%'";
		$counter++;
		}
		$searching_blog = "$search_field LIKE ".$searching_blog;
		return($searching_blog);
}

// Begin printing the results tables

print "<h2>Searching: $keywords</h2>";
print "<table summary=\"List of results for search terms chosen\">";
$firstcol_width = " width=\"140\" ";
// Journal Entries

print "<tr><td colspan=\"2\"><strong>Journal Entries</strong></td></tr>";

$sql = "SELECT blog_id, blog_title, blog_date FROM intranet_projects_blog WHERE ".SearchTerms($keywords_array, "blog_text")." OR ".SearchTerms($keywords_array, "blog_title")." AND blog_view != 1 ORDER BY blog_date";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		print "<tr><td colspan=\"2\">No results found for Journal Entries</td></tr>";
	} else {
			while ($array = mysql_fetch_array($result)) {
			$blog_id = $array['blog_id'];
			$blog_title = $array['blog_title'];	
			$blog_date = $array['blog_date'];
			print "<tr><td $firstcol_width><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td><td style=\"width: 75%;\"><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
	}
}

// Contact Entries

print "<tr><td colspan=\"2\"><strong>Contacts</strong></td></tr>";

$sql = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist WHERE ".SearchTerms($keywords_array, "contact_namefirst")." OR ".SearchTerms($keywords_array, "contact_namesecond")." OR ".SearchTerms($keywords_array, "contact_reference")." ORDER BY contact_namesecond";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		print "<tr><td colspan=\"2\">No results found for Contacts</td></tr>";
	} else {
			while ($array = mysql_fetch_array($result)) {
			$contact_id = $array['contact_id'];
			$contact_namefirst = $array['contact_namefirst'];
			$contact_namesecond = $array['contact_namesecond'];
			$contact_company = $array['contact_company'];
			print "
			<tr><td $firstcol_width";
			if ($contact_company == NULL OR $contact_company == 0) { print " colspan=\"2\" "; }
			print "><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst&nbsp;$contact_namesecond</a></td>";
			if ($contact_company > 0) { print "<td>";$id = $contact_company; include("dropdowns/inc_data_contact_company.php"); print "</td>"; }
			print "</tr>";
	}
}

// Company Entries

print "<tr><td colspan=\"2\"><strong>Company</strong></td></tr>";

$sql = "SELECT company_id, company_name, company_postcode FROM contacts_companylist WHERE ".SearchTerms($keywords_array, "company_name")." OR ".SearchTerms($keywords_array, "company_address")." OR ".SearchTerms($keywords_array, "company_web")." OR ".SearchTerms($keywords_array, "company_notes")." OR ".SearchTerms($keywords_array, "company_web")." ORDER BY company_name";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		print "<tr><td colspan=\"2\">No results found for Companies</td></tr>";
	} else {
			while ($array = mysql_fetch_array($result)) {
			$company_id = $array['company_id'];
			$company_name = $array['company_name'];
			if ($array['company_postcode'] != NULL) $company_postcode = " <a href=\"".PostCodeFinder($array['company_postcode'])."\">(".$array['company_postcode'].")</a>";
			
			// 
			
			print "
			<tr><td colspan=\"2\"";
			print "><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a>$company_postcode</td>";
			print "</tr>";
	}
}

// Tasks

print "<tr><td colspan=\"2\"><strong>Tasks</strong></td></tr>";

$sql = "SELECT tasklist_id, tasklist_notes, tasklist_person, tasklist_percentage, proj_id, proj_num FROM intranet_tasklist, intranet_projects WHERE  tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_notes")." OR tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_comment")." ORDER BY tasklist_due";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		print "<tr><td colspan=\"2\">No results found for Tasks</td></tr>";
	} else {
			while ($array = mysql_fetch_array($result)) {
			$tasklist_id = $array['tasklist_id'];
			$tasklist_notes = $array['tasklist_notes'];
			$tasklist_percentage = $array['tasklist_percentage'];
			$tasklist_due = $array['tasklist_due'];
			$proj_id = $array['proj_id'];
			$proj_num = $array['proj_num'];
			print "<tr><td $firstcol_width><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">";
			print $proj_num;
			print "</a></td><td><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">";
			if ($tasklist_percentage == 100) { print "<span style=\"text-decoration: line-through;\">"; }
			elseif ($tasklist_due < time()) { print "<span style=\"background-color: #$settings_alertcolor;\">"; }
			print $tasklist_notes;
			if ($tasklist_percentage == 100) { print "</span>"; }
			elseif ($tasklist_due < time()) { print "</span>"; }
			print "</a></td></tr>";
	}
}

// Expenses

print "<tr><td colspan=\"2\"><strong>Expenses</strong></td></tr>";

$sql = "SELECT * FROM intranet_timesheet_expense WHERE ".SearchTerms($keywords_array, "ts_expense_desc")." OR ".SearchTerms($keywords_array, "ts_expense_notes")." OR ".SearchTerms($keywords_array, "ts_expense_desc")." ORDER BY ts_expense_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		print "<tr><td colspan=\"2\">No results found for Expenses</td></tr>";
	} else {
			while ($array = mysql_fetch_array($result)) {
			$ts_expense_id = $array['ts_expense_id'];
			$ts_expense_desc = $array['ts_expense_desc'];
			$ts_expense_date = $array['ts_expense_date'];
			$ts_expense_notes = $array['ts_expense_notes'];
			$ts_expense_vat = $array['ts_expense_vat'];
			print "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a><td style=\"width: 75%;\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_desc</a> [ID: $ts_expense_id]";
			if ($ts_expense_notes != NULL) { echo "<br />($ts_expense_notes)"; }
			print "</tr>";
	}
}

echo "</table>";

// Expenses (by value)


$value_lower = $_POST[keywords] - 0.01;
$value_upper = $_POST[keywords] + 0.01;

$sql = "SELECT * FROM intranet_timesheet_expense, intranet_user_details WHERE ts_expense_vat BETWEEN '$value_lower' && '$value_upper' AND ts_expense_vat > 0 AND user_id = ts_expense_user ORDER BY ts_expense_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
			print "<h2>Expenses with this value</h2>";
			echo "<table>";
			echo "<tr><th>Date</th><th>Verified</th><th>Description</th><th>Value</th><th>User</th></tr>";
			while ($array = mysql_fetch_array($result)) {
			$ts_expense_id = $array['ts_expense_id'];
			$ts_expense_desc = $array['ts_expense_desc'];
			$ts_expense_date = $array['ts_expense_date'];
			$ts_expense_notes = $array['ts_expense_notes'];
			$ts_expense_vat = $array['ts_expense_vat'];
			$ts_expense_verified = $array['ts_expense_verified'];
			if ($ts_expense_verified > 0) {
			$ts_expense_verified = "<a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a>"; } else { $ts_expense_verified = "--"; }
			$user_initials = $array['user_initials'];
			print "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a></td><td>$ts_expense_verified</td><td style=\"width: 50%;\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_desc</a>";
			if ($ts_expense_notes != NULL) { echo "<br />Notes: $ts_expense_notes"; }
			echo "</td><td>".MoneyFormat($ts_expense_vat)." [ID: $ts_expense_id]";
			print "<td>$user_initials</td></tr>";
	}
	echo "</table>";
}

// Tender submissions

$sql = "SELECT answer_id, answer_response, answer_tender_id, answer_ref, tender_name, tender_date FROM intranet_tender_answers, intranet_tender WHERE ".SearchTerms($keywords_array, "answer_response" ) . " AND tender_id = answer_tender_id ORDER BY tender_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
	echo "<table>";
	if (mysql_num_rows($result) == 0) {
		print "<tr><td>No results found for Tenders</td></tr>";
	} else {
			print "<tr><td><strong>Tender Submissions</strong></td></tr>";
			while ($array = mysql_fetch_array($result)) {
			$answer_id = $array['answer_id'];
			$answer_response = strip_tags($array['answer_response']);
			$answer_tender_id = $array['answer_tender_id'];
			$answer_tender_id = $array['answer_tender_id'];
			$answer_ref = $array['answer_ref'];
			$tender_name = $array['tender_name'];
			$tender_date = $array['tender_date'];
			echo "<tr><td>";
			echo "<a href=\"index2.php?page=tender_view&amp;tender_id=$answer_tender_id&amp;answer_id=$answer_id\">" . substr ( $answer_response ,0 , 200 ) . "...</a><br /><span class=\"minitext\">From $tender_name, " . TimeFormat($tender_date) . ", question $answer_ref</span>";
			print "</td></tr>";
			}
	}

echo "</table>";

} else {

print "<p>Invalid Search Term</p>";

}
		
?>