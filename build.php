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
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/bylaws.json');
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
$newyearsday = $year . '-01-01';
$easter = date("Y-m-d", easter_date($year));
$gf = new DateTime($easter);
$goodf = $gf->sub(new DateInterval('P3D'));
$goodfriday = $goodf->format('Y-m-d');
$victoriaday = date('Y-m-d', strtotime("last monday before may 25 $year"));
$canadaday = $year . '-07-01';
$bcday = date('Y-m-d', strtotime("first monday of august $year"));
$labourday = date('Y-m-d', strtotime("first monday of september $year"));
$thanksgiving = date('Y-m-d', strtotime("second monday of october $year"));
$rememberanceday = $year . '-11-11';
$xmasday = $year . '-12-25';
$boxingday = $year . '-12-26';
//$victoriaday = $year . '-01-01'; // observed on the Monday before May 25th each year
$holidays = [
                $newyearsday,
                $goodfriday,
                $easter,
                $victoriaday,
                $canadaday,
                $bcday,
                $labourday,
                $thanksgiving,
                $rememberanceday,
                $xmasday,
                $boxingday
        ];

$day = date('l');
$dayrange = '';
if($day == 'Monday' || $day == 'Tuesday' || $day == 'Wednesday' || $day == 'Thursday' || $day == 'Friday') {
        $dayrange = 'Weekdays';
} elseif ($day == 'Saturday') {
        $dayrange = 'Saturday';
} elseif ($day == 'Sunday') {
        $dayrange = 'Sunday';
}
$rawhour = date('GS');
$hour = date('G:i');
$header = <<<EOD
<!doctype HTML>
<html lang="en">
<head>
<title>Victoria BC Quiet District Bylaws Made Plain</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script>
    var current = new Date();
    var future = new Date();
    future.setTime(future.getTime() + 3600000); //3600000 = 1 hour
    future.setMinutes(0);
    future.setSeconds(0);
    var timeout = (future.getTime() - current.getTime());
    setTimeout(function() { window.location.reload(true); }, timeout);
</script>
</head>
<body>
<h1 class="fs-4 bg-black text-white w-100 px-5 py-3 mb-3 text-center">May I Make Noise?</h1>
<div class="container">
<div class="row justify-content-md-center">
<h2 class="text-center">Victoria BC Quiet District Bylaws Made Plain</h2>
EOD;
$output = $header;
$output .= '<div class="mb-3 text-center">Last updated: ' . date('M d') . ' at ' . $hour . '.</div>';
$count = 0;
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                //$output .= '<h1>' . $zone->name . '</h1>';
                foreach($zone->types as $type) {
                        $count++;
			$times = '';
			$status = '';
                        $output .= '<div class="col-md-6">';
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
                        $output .= '<h2>' . $type->type . ' ';
                        $output .= '<span class="inline fs-5">' . $times . ' ';
                        $output .= '<a class="btn btn-sm btn-secondary" data-bs-toggle="collapse" href="#info-'.$count.'" role="button" aria-expanded="false" aria-controls="info-'.$count.'">Info</a></span>';
                        $output .= '</h2>';
                        $output .= $status;
                        if(!empty($type->description)) {
                                $output .= '<div id="info-'.$count.'" class="collapse">' . $type->description . '</div>';
                        }
                        $output .= '</div>';
                }
        }
}
$footer = <<<EOD
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>
EOD;
$output .= $footer;
$export = 'index.html';
file_put_contents($export, $output);
echo $output;