<?php

$profit = 1.25;

function InsertPage($input) {
}

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_actions_functions.php";

if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {

$remove_symbols = array("Â","Ã");
$swap_1 = array("â‚¬", "\n", "&amp;");
$replace_1 = array("€", ", ", "&");

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

// Define the colours for the bars
function SetBarDBlue() { GLOBAL $pdf; $pdf->SetFillColor(120, 190, 240); }
function SetBarOrange() { GLOBAL $pdf; $pdf->SetFillColor(255, 200, 0); }
function SetBar2($alert) { GLOBAL $pdf; if ($alert == "red") { $pdf->SetFillColor(250, 0, 0); } elseif ($alert == "orange") { $pdf->SetFillColor(250, 190, 0); } else { $pdf->SetFillColor(200, 225,115); } }
function SetBarLBlue() { GLOBAL $pdf; $pdf->SetFillColor(190, 220, 240); }
function SetBar4() { GLOBAL $pdf; $pdf->SetFillColor(180, 250, 100); }
function SetBarLGray() { GLOBAL $pdf; $pdf->SetFillColor(240, 240, 240); }
function SetBarRed() { GLOBAL $pdf; $pdf->SetFillColor(255, 0, 0); }
function SetBar7($alert) { GLOBAL $pdf; if ($alert == "red") { $pdf->SetFillColor(255, 180,180); } elseif ($alert == "orange") { $pdf->SetFillColor(250, 220, 130); } else { $pdf->SetFillColor(220, 240, 150); }  }

// Functions for page separations
function PageBreak() {
// GLOBAL $pdf; $y = $pdf->GetY(); if ($y > 250) { $pdf->addPage(); $pdf->SetY(10); }
}

//if ($settings_pdffont != NULL) {
//$format_font = $settings_pdffont;
//$format_font_2 = $settings_pdffont.".php";
//} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
//}

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);
$pdf->addPage();
$pdf->useTemplate($tplidx);

$pdf->AddFont($format_font,'',$format_font_2);

// Page headers

	$project_counter = 1;
	$page_count = 1;

	$pdf->SetY(35);
	$pdf->SetFont('Helvetica','b',24);

	$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
	$pdf->Cell(0,10,"Timesheet Analysis");

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetY(50);
	$pdf->SetFont('Helvetica','b',18);

	$print_title = "Generated " . TimeFormatDetailed(time());
	$print_envelope = "Timesheet Datum: " . TimeFormat($settings_timesheetstart);
	
	$pdf->SetFillColor(220, 220, 220);
	$pdf->MultiCell(0,8,$print_title,0, L, 0);
	
	$pdf->SetFont('Helvetica','b',12);
	$pdf->MultiCell(0,5,$print_envelope,0, L, 0);
	$pdf->MultiCell(0,5,$print_profit_text,0, L, 0);
	$pdf->Cell(0,5,'',0,2);
	
	$pdf->SetFont($format_font,'',10);
	$pdf->SetTextColor(0, 0, 0);

// Array through the projects and fee stages

	$bar_width_standard = 130;
	$effectively_zero = 0.1; // Sets the minimum bar size if the quantity is zero
	$page_number = 0;
	$complete_profit_total = 0;
	$complete_fee_total = 0;
	$complete_cost_total = 0;
	
// Establish the maximum fee for all projects by sorting an array

	$sql_fee_max = "SELECT SUM(ts_fee_value) FROM intranet_timesheet_fees WHERE ts_fee_value > 0 GROUP BY ts_fee_project ORDER BY `SUM(ts_fee_value)` DESC";
	$result_fee_max_array = mysql_query($sql_fee_max, $conn) or die(mysql_error());
	$array_fee_max = mysql_fetch_array($result_fee_max_array);
	$maximum_total_fee = $array_fee_max[0];
	
// Now construct the main array through the projects and fee stages	

	$sql_projects = "SELECT * FROM intranet_projects LEFT JOIN intranet_timesheet_fees ON ts_fee_project = proj_id WHERE proj_fee_track = 1 AND proj_active = 1 ORDER BY proj_num, ts_fee_time_begin";
	$result_projects = mysql_query($sql_projects, $conn) or die(mysql_error());

	$project_cost_total = 0;
	$project_fee_total_target = 0;
	$project_fee_total_profit = 0;
	
	while ($array_projects = mysql_fetch_array($result_projects)) {
	PageBreak();
	
	$proj_id = $array_projects['proj_id'];
	$ts_fee_id = $array_projects['ts_fee_id'];
	$ts_fee_text = $array_projects['ts_fee_text'];
	$ts_fee_value = $array_projects['ts_fee_value'];
	$ts_fee_target = $array_projects['ts_fee_target'];
	
	$proj_title = $array_projects['proj_num'] . " - " . $array_projects['proj_name'];
	$proj_riba = $array_projects['proj_riba'];
	
	$sql_fee_total = "SELECT SUM(ts_fee_value) FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id GROUP BY ts_fee_project";
	$result_fee_total = mysql_query($sql_fee_total, $conn) or die(mysql_error());
	$array_fee_total = mysql_fetch_array($result_fee_total);
	$fee_total = $array_fee_total['SUM(ts_fee_value)'];
	
	$sql_cost_total = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_project = '$proj_id'";
	$result_cost_total = mysql_query($sql_cost_total, $conn) or die(mysql_error());
	$array_cost_total = mysql_fetch_array($result_cost_total);
	$cost_total = $array_cost_total['SUM(ts_cost_factored)'];
	
	if ($cost_total > $fee_total) { $this_bar_width = $bar_width_standard * ( $cost_total / $maximum_total_fee); } else { $this_bar_width = $bar_width_standard * ( $fee_total / $maximum_total_fee); } 
	
	$project_profit = $fee_total * $profit;
	
	if ($cost_total > $fee_total) {
		$alert = "orange";
	} else {
		
		unset($alert);
	}
	
	if (($cost_total * (1 - $profit)) >= $fee_total) {
		$alert = "red";
	}
	
	// $maximum_total_fee : The largest fee from the database
	// $bar_width_standard : The maximum bar width (currently 130mm)
	// $fee_total : The total fees available for the project
	// $cost_total : The costs so far
	// $this_bar_width : The datum level using the standard width as a maximum
	// $bar_width_fee : Overall bar width for the total fee (mm)
	
	
	$bar_width_fee = ( $fee_total / $maximum_total_fee ) * $this_bar_width; // 
	$bar_width_cost = ( $cost_total / $maximum_total_fee) * $this_bar_width;
	
	
	// if ($cost_total > 0 && $fee_total > 0) {
	if ($cost_total > 0 && $fee_total > 0) {
	
		$cost_total_print = str_replace( "&pound;" , "£" , MoneyFormat($cost_total) );
		$fee_total_print = str_replace( "&pound;" , "£" , MoneyFormat($fee_total) );
		
		

		
	
		if ($current_project != $proj_id) {
		
			// Check that all of the project data will fit on this page by establishing the number of fee stages
			$sql_fee_stage_quantity = "SELECT ts_fee_stage FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id";
			$result_fee_stage_quantity = mysql_query($sql_fee_stage_quantity, $conn) or die(mysql_error());
			$fee_stage_quantity = mysql_num_rows($result_fee_stage_quantity);
			$height_fee_stage = 30 + ( $fee_stage_quantity * 8) ;
			$current_location_y = $pdf->GetY();
			if (($height_fee_stage + $current_location_y) > 290) {
			$height_fee_stage = 0;
			$pdf->addPage();			
			}
			
			$proj_link = "http://intranet.rcka.co.uk/timesheet_pdf_2.php?proj_id=" . $proj_id;
			
			$pdf->Cell(0,8,'',0,1);
			$current_y = $pdf->GetY() + 1;
			
			// Create light grey bar
			SetBarLGray();
			$pdf->SetDrawColor(175,175,175);
			$pdf->SetTextColor(175,175,175);
			$pdf->Cell(0,7,'',BT,1,L,true);
			
			if ($cost_total > $fee_total) { $bar_scale = $cost_total; } else { $bar_scale = $fee_total; }
			
			// Scale bar for costs
			if ($bar_scale< 10000) { $unit = 1000; } elseif ($bar_scale< 20000) { $unit = 2000; } elseif ($bar_scale< 50000) { $unit = 5000; } elseif ($bar_scale< 50000) { $unit = 10000; } elseif ($bar_scale< 100000) { $unit = 20000; } else { $unit = 25000; }
			$unit_scale = 0;
			// Establish whether scale bar should be related to profit or cost
			$separator = ($bar_width_standard / $bar_scale) * $unit;
			$separator_count = 0;
			$pdf->SetFont('Helvetica','',5);
			$pdf->Cell(13,3,'Fee',R,0);
			while ($separator_count < $bar_width_standard) {
				$unit_scale_print = "£" . number_format($unit_scale);
				$pdf->Cell($separator,3,$unit_scale_print,R,0);
				$separator_count = $separator_count + $separator;
				$unit_scale = $unit_scale + $unit;
			}
			$unit_scale_print = "£" . number_format($unit_scale);
			$pdf->Cell(0,3,$unit_scale_print,0,1);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetTextColor(0,0,0);
			
			$pdf->SetY($current_y);
			$pdf->SetFont($format_font,'',9);
			$pdf->Cell(0,5,$proj_title,0, 1, L, 0, $proj_link);
			$pdf->SetFont($format_font,'',6);

			$pdf->SetX(10);
			$pdf->Cell(0,6,'',0,1);
			SetBar2("no");
			
			
			
// Insert a line showing the PROJECT TOTAL if this is a new project we're about to start	
//	if ($current_project != $proj_id AND $current_project != NULL) {
	
	
//			$bar_width_fee = $project_fee_total_target * $bar_units;
//			$bar_width_actual_cost = $project_cost_total * $bar_units;
			
//				$fee_total_print = str_replace( "&pound;" , "£" , MoneyFormat($project_fee_total_target + $project_fee_total_profit) );
	
	
//				$project_cost_total = str_replace( "&pound;" , "£" , MoneyFormat($project_cost_total) );
	
//				$pdf->SetFont($format_font,'',6);
//				SetBarDBlue();
//				$pdf->SetX(11);
//				$pdf->Cell(12,3.5,'Total Fee',0,0,L,false);
//				$pdf->Cell($bar_width_fee,3.5,'',0,0,L,true);
//				$pdf->Cell(0,3.5,$fee_total_print,0,1);
//				SetBar2($alert);
//				$pdf->Cell(0,0.5,'',0,2);
//				$pdf->SetX(11);
//				$pdf->Cell(12,3.5,'Total Cost',0,0,L,false);
//				$pdf->Cell($bar_width_actual_cost,3.5,'',0,0,R,true);
//				$pdf->Cell(0,3.5,$project_cost_total,0,1);
				
//				$project_cost_total = 0;
//				$project_fee_total_target = 0;
//				$project_fee_total_profit = 0;

		
//	}
			
			
			
			
			
			
			// Now a bar which shows the unassigned work
			
			$sql_cost_unassigned = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = 0 AND `ts_project`= $proj_id";
				$result_cost_unassigned = mysql_query($sql_cost_unassigned, $conn) or die(mysql_error());
				$array_cost_unassigned = mysql_fetch_array($result_cost_unassigned);
				$cost_unassigned = $array_cost_unassigned['SUM(ts_cost_factored)'];
			
			// if ($cost_unassigned = 0) { $cost_unassigned = $effectively_zero; }
			
			if ($cost_unassigned > 0) {
			
				$bar_width = ($cost_unassigned / $bar_scale) * $bar_width_standard;
				
		
				$pdf->SetTextColor(150,150,150);
				
				$cost_unassigned = str_replace( "&pound;" , "£" , MoneyFormat($cost_unassigned) )  . " [Unassigned]";
				$pdf->SetFont($format_font,'',6);
				$pdf->Cell(0,0.5,'',0,1);
				$pdf->Cell(13,4,'',0,0);		
				$pdf->Cell($bar_width,4,'',0, 0, R, 1, true);
				$pdf->Cell(0,4,$cost_unassigned,0, 1, L); 
				$pdf->Cell(0,1,'',0,1);
			
			} else {
			
			$pdf->Cell(0,1,'',0,1);
			
			}
	
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			// This prints the fee stages
			// First establish the width of the fee stage bar
			$sql_fee_stage = "SELECT ts_fee_value, ts_fee_stage FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_id ";
			$result_fee_stage = mysql_query($sql_fee_stage, $conn) or die(mysql_error());
			$array_fee_stage = mysql_fetch_array($result_fee_stage);
			$fee_stage = $array_fee_stage['ts_fee_value'];
			$fee_stage_id = $array_fee_stage['ts_fee_stage'];
			
		
			
			// Now establish the width of the timesheet hours to date for this fee stage only
			$sql_cost_total = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = $ts_fee_id AND `ts_project` = $proj_id";
			$result_cost_total = mysql_query($sql_cost_total, $conn) or die(mysql_error());
			$array_cost_total = mysql_fetch_array($result_cost_total);
			$cost_total = $array_cost_total['SUM(ts_cost_factored)'];	

			$sql_cost_stage = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = $ts_fee_id AND `ts_project` = $proj_id ";
			$result_cost_stage = mysql_query($sql_cost_stage, $conn) or die(mysql_error());
			$array_cost_stage = mysql_fetch_array($result_cost_stage);
			$cost_stage = $array_cost_stage['SUM(ts_cost_factored)'];
			
			$cost_total = $cost_stage;
			
			$bar_units = $bar_width_standard / $bar_scale;
			$this_bar_width = $fee_stage * $bar_units;
			$cost_bar_width = $cost_total * $bar_units;
			
			if ($this_bar_width == 0) { $this_bar_width = $effectively_zero; }
			if ($cost_bar_width == 0) { $cost_bar_width = $effectively_zero; }
			
			if ($this_bar_width > 0 OR $cost_bar_width > 0) {
			
			$fee_stage_print = str_replace( "&pound;" , "£" , MoneyFormat($fee_stage) ); 
			$cost_total = str_replace( "&pound;" , "£" , MoneyFormat($cost_total) )  . " [" . $ts_fee_text . "]";
			// $cost_total = $ts_fee_text . "(";
			$cost_total = RemoveShit($cost_total);
			SetBarLBlue();
			
			if ($proj_riba == $ts_fee_id) { $pdf->SetTextColor(0,0,0); } else { $pdf->SetTextColor(150,150,150); }
			$pdf->Cell(13,3,$fee_stage_print,0,R,0);
			
			$this_bar_width_1 = $this_bar_width / $ts_fee_target;
			$this_bar_width_2 = $this_bar_width - $this_bar_width_1;
			
			if ($this_bar_width_1 > 0 AND $this_bar_width_2 > 0) {
			$pdf->Cell($this_bar_width_1,4,'',0, L, 0, true);
			$pdf->SetDrawColor(255,255,255);
			$pdf->Cell($this_bar_width_2,4,'',L, L, 0, true);
			} else {
			$pdf->Cell($this_bar_width,4,'',0, L, 0, true);
			}
			
			
			
			$bar_maximum = $pdf->GetX();
			//$pdf->Cell(0,4,$cost_total,0,L,1);
			// if ($cost_bar_width > 0) {
				$current_y = $pdf->GetY(); // + 2;
				$pdf->SetY($current_y);
				$pdf->SetX(23);
				if ($cost_bar_width > $this_bar_width) { SetBarRed(); }
				elseif ($cost_bar_width > $this_bar_width_1 ) { SetBarOrange(); }
				else { SetBarDBlue(); }
				$pdf->Cell($cost_bar_width,2,'',0, L, 0, true);
				$cost_bar_location = $pdf->GetX();
				$pdf->Cell('',2,'',0, L, 0);
				if ($cost_bar_location > $bar_maximum) { $pdf->SetX($cost_bar_location); } else { $pdf->SetX($bar_maximum); }
				//$new_y = $pdf->GetY() - 2;
				//$pdf->SetY($new_y);
				// Add an extra box at the end if there's a cost overrun
				$pdf->Cell(0,3,$cost_total,0,L,1);
				$current_y = $current_y + 6;
				$pdf->SetY($current_y);
			// }  else {
			//	$pdf->SetX(10);
			//	$current_y = $pdf->GetY() + 6;
			//	$pdf->SetY($current_y);
			//}
			$pdf->SetX($bar_maximum);
			$current_y = $pdf->GetY();
			$current_y = $current_y - 2;
			$pdf->SetY($current_y);
			$pdf->Cell(0,0.5,'',0,2);
			unset($cost_bar_width);
			
			// Update the project totals
			$project_cost_total = $project_cost_total + $cost_stage;
			$project_fee_total_target = $project_fee_total_target + ($fee_stage / $ts_fee_target);
			$project_fee_total_profit = $project_fee_total_profit + $fee_stage;
			
					
			// Now make sure the project header appears if necessary on the next loop
			$current_project = $proj_id;
			
			
			
			
			
			}
			

			
		}
		

	}
	
$pdf->addPage();

// Now create a new page which shows how complete people's timesheets are

$pdf->SetFont('Helvetica','b',24);
$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
$pdf->MultiCell(0,10,"Timesheet Completion",0,1,L);

	$sql_userhours = "SELECT *, SUM(ts_hours) FROM intranet_timesheet LEFT JOIN intranet_user_details ON ts_user = user_id WHERE user_active = 1 AND user_active = 1 AND ts_entry > $settings_timesheetstart GROUP BY ts_user ORDER BY user_name_second, user_name_first";
	$result_userhours = mysql_query($sql_userhours, $conn) or die(mysql_error());
	

	
	while ($array_userhours = mysql_fetch_array($result_userhours)) {
		
		$user_id = $array_userhours['user_id'];
		$user_name_first = $array_userhours['user_name_first'];
		$user_name_second = $array_userhours['user_name_second'];
		$user_user_added = $array_userhours['user_user_added'];
		$user_user_rate = $array_userhours['user_user_rate'];
		$ts_hours = $array_userhours['SUM(ts_hours)'];
		
				if ($user_user_added > $settings_timesheetstart) { $timesheet_datum_user = $user_user_added; } else { $timesheet_datum_user = $settings_timesheetstart; }
		
		$percent_complete = TimeSheetHours($user_id,$display) / 100 ;
	
		
		// Calculate how much time has been spent on non-project hours
		
			$sql_nonproject = "SELECT SUM(ts_hours) FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_user = $user_id AND proj_fee_track != 1 AND ts_entry >= $timesheet_datum_user";
			$result_nonproject = mysql_query($sql_nonproject, $conn) or die(mysql_error());
			$array_nonproject = mysql_fetch_array($result_nonproject);
			$hours_nonproject = $array_nonproject['SUM(ts_hours)'];
			
			$sql_total = "SELECT SUM(ts_hours) FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_user = $user_id AND ts_entry >= $timesheet_datum_user";
			$result_total = mysql_query($sql_total, $conn) or die(mysql_error());
			$array_total = mysql_fetch_array($result_total);
			$hours_total = $array_total['SUM(ts_hours)'];
			
			$percent_nonproject = $hours_nonproject / $hours_total;
			
			if ($percent_nonproject > 1) { $percent_nonproject = 1; }
		
		if ($percent_complete > 1) { $percent_complete = 1; }
		if ($percent_complete == 0) { $percent_complete = 0.01; }
		
		$name_width = 35;
		
		$bar_width_available = $bar_width_standard - $name_width;
		$bar_width_user = $bar_width_available * $percent_complete;
		$bar_hours_nonproject = $bar_width_available * $percent_nonproject;
		$percent_nonproject_print = number_format($percent_nonproject * 100) . "%";
		
		SetBarLBlue();
		
		$print_username = $user_name_first . " " . $user_name_second;

		$percent_complete_print = number_format (( 100 * $percent_complete ) ) . "% Complete | Hourly rate: £" . number_format($user_user_rate)  ;
		
				$pdf->SetFont($format_font,'',9);
				$pdf->SetTextColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->Cell($name_width,5,$print_username,0,0,R);
				$pdf->Cell($bar_width_available,5,'',0,0,R,1);
				$pdf->SetFont($format_font,'',6);
				$pdf->Cell(0,5,$percent_complete_print,0,1,L,0);
				$pdf->SetY($y + 2.5);
				$x = $pdf->GetX() + $name_width;
				$pdf->SetX($x);
				SetBar2(1);
				$pdf->Cell($bar_width_user,2.5,'',0,1,R,1);
				$pdf->Cell(0,0.5,'',0,1,L);
				if ($percent_nonproject > 0) {
					SetBar4();
					$pdf->Cell($name_width,2.5,'Non-Project Hours',0,0,R);
					$pdf->Cell($bar_hours_nonproject,2.5,'',0,0,R,1);
					$pdf->Cell(0,2.5,$percent_nonproject_print,0,1,L,0);
					}
				$pdf->Cell(0,5,'',0,1,L);

	}


// and send to output

$file_name = "timesheet_analysis.pdf";


$pdf->Output($file_name,I);

}
?>
