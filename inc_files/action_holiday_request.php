<?php

echo "<p>Holiday Request Form</p>";

function CheckHolidays($start, $back, $insert, $user, $length, $paid) {

GLOBAL $conn;
	
$counter_time = $start;
	$counter_days = 0;
	echo "<ul>";
	while ($counter_time < $back) {
	
		$sql_bankholidays = "SELECT bankholiday_timestamp FROM intranet_user_holidays_bank WHERE bankholiday_timestamp BETWEEN " . $counter_time . " AND " . ($counter_time + 86400) . " ORDER BY bankholiday_timestamp LIMIT 1";
		$result_bankholidays = mysql_query($sql_bankholidays, $conn);
		$holidays_checked = mysql_num_rows($result_bankholidays);
		
		$sql_existingholidays = "SELECT holiday_timestamp FROM intranet_user_holidays WHERE holiday_timestamp BETWEEN " . $counter_time . " AND " . ($counter_time + 86400) . " AND holiday_user = $user LIMIT 1";
		$result_existingholidays = mysql_query($sql_existingholidays, $conn);
		$holidays_existingholidays = mysql_num_rows($result_existingholidays);
		
		if ( $holidays_checked  > 0 ) { $bank_holiday = "yes"; } else { $bank_holiday = "no"; }
		if ( $holidays_existingholidays  > 0 ) { $existing_holiday = "yes"; } else { $existing_holiday = "no"; }
		if ( date("w",$counter_time) == 0 OR date("w",$counter_time) == 6) { $weekend = "yes"; } else { $weekend = "no"; }
		
		if ($weekend == "no" AND $bank_holiday == "no" AND $holidays_existingholidays == "no" AND $insert == "yes") {
			$sql_holidays = "INSERT INTO intranet_user_holidays (holiday_id, holiday_user, holiday_date, holiday_month, holiday_year, holiday_approved, holiday_timestamp, holiday_length, holiday_paid) VALUES ( NULL, $user, " . date("j",$counter_time) . "," . date("n",$counter_time) . "," . date("Y",$counter_time) . ", NULL," . $counter_time . "," . $length . "," . $paid .  ")";
			
			$result_holidays = mysql_query($sql_holidays, $conn);
			echo "<li>" . Date("l j F Y", $counter_time) . " </li>";
		} elseif ($weekend == "no" AND $bank_holiday == "no" AND $holidays_existingholidays == "no" AND $insert != "yes") { 
			echo "<li>" . Date("l j F Y", $counter_time) . "</li>";
		}
		
		if ($weekend == "no" AND $bank_holiday == "no") { $counter_days++; }
		
		$counter_time = $counter_time + 86400;
	}
	
	echo "</ul>";
	
	return $counter_days;

}

$holiday_begin = $_POST[holiday_day_start];
$time_begin = AssessDays($holiday_begin);

$holiday_back = $_POST[holiday_day_back];
$time_back = AssessDays($holiday_back);



?>