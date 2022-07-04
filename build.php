<?php 
/**
 * City of Victoria, BC Noise Bylaws 
 * BYLAW NO. 03-012
 * https://www.victoria.ca/assets/City~Hall/Bylaws/bylaw-03-012.pdf
 * 
 * These noise bylaws are difficult to read and are not 
 * presented in an easy format if your question is simply
 * "May I make noise right now?" in your neighbourhood.
 * 
 * Ssssh! takes the gist of the bylaws, takes the current 
 * date and hour in mind, and tells you what kinds of noise
 * that you are currently allowed to make in an easy to
 * see way.
 * 
 **/
date_default_timezone_set('America/Los_Angeles');
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
/**
 * Holidays
 * 
 * (a) New Year’s Day, Good Friday, Easter Monday, Victoria Day, Canada Day, 
 * British Columbia Day, Labour Day, Thanksgiving Day, Remembrance Day, 
 * Christmas Day and December 26, and
 * (b) the day following a day that is named in paragraph (a) and that falls on a Sunday;
 * 
 **/

$year = date('Y');
$easter = date("Y-m-d", easter_date($year));
//$easter date('Y-m-d', strtotime("last sunday of march $currentYear"));
$newyearsday = $year . '-01-01';
//$victoriaday = $year . '-01-01'; // observed on the Monday before May 25th each year
//$holidays = [''];

$day = date('l');
$dayrange = '';
if($day == 'Monday' || $day == 'Tuesday' || $day == 'Wednesday' || $day == 'Thursday' || $day == 'Friday') {
        $dayrange = 'Weekdays';
} elseif ($day == 'Saturday') {
        $dayrange = 'Saturday';
} elseif ($day == 'Sunday') {
        $dayrange = 'Holiday';
}
$rawhour = date('GS');
$hour = date('G:i');
$header = <<<EOD
<!doctype HTML>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<div class="row justify-content-md-center">
<h1>May I Make Noise?</h1>
<h2>Victoria BC Quiet District Bylaws Made Plain</h2>
EOD;
$output = $header;
$output .= '<div>It is a ' . strtolower(rtrim($dayrange,'s')) . ' in the ' . $rawhour . ' hour</div>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                //$output .= '<h1>' . $zone->name . '</h1>';
                foreach($zone->types as $type) {
			$times = '';
			$status = '';
                        $output .= '<div class="col-md-12">';
                        $output .= '<div class="p-6">';
                        foreach($type->ranges as $range) {
				if($dayrange == $range->name) {     
                                        $times = '<div>' . $range->start . ' - ';
                                        $times .= '' . $range->end . '</div>';
                                        if($hour > $range->start && $hour < $range->end) {
                                                $status = '<div class="alert alert-success">Yes, you may.</div>';
                                        } else {
                                                $status = '<div class="alert alert-danger">Please wait for another time.</div>';
                                        }
                                }
                        }
                        $output .= '<h2>' . $type->type . ' <span class="inline fs-5">' . $times . '</span></h2>';
                        $output .= $status;
                        if(!empty($type->description)) {
                                //$output .= '<div>' . $type->description . '</div>';
                        }
                        $output .= '</div>';
                        $output .= '</div>';
                }
        }
}
$footer = <<<EOD
</div>
</div>
</body>
</html>
EOD;
$output .= $footer;
$export = 'index.html';
file_put_contents($export, $output);
echo $output;
