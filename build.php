<?php 
date_default_timezone_set('America/Los_Angeles');
$export = 'index.html';
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
$day = date('l');
$dayrange = '';
if($day == 'Monday' || $day == 'Tuesday' || $day == 'Wednesday' || $day == 'Thursday' || $day == 'Friday') {
        $dayrange = 'Weekday';
} elseif ($day == 'Saturday') {
        $dayrange = 'Saturday';
} elseif ($day == 'Sunday') {
        $dayrange = 'Holiday';
}
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
EOD;
$output = $header;
$output .= '<div>It is a ' . $dayrange . '</div>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<div class="col">';
                $output .= '<h1>' . $zone->name . '</h1>';
                foreach($zone->types as $type) {
                        $output .= '<h1>' . $type->type . '</h1>';
                        if(!empty($type->description)) {
                                $output .= '<div>' . $type->description . '</div>';
                        }
                        foreach($type->ranges as $range) {
                                $status = '';
                                if($dayrange == $range->name) {     
                                        $output .= '<div>' . $range->start . ' - ';
                                        $output .= '' . $range->end . '</div>';
                                        if($hour > $range->start && $hour < $range->end) {
                                                $status = 'Yes, you may.';
                                        } else {
                                                $status = 'Please wait for another time.';
                                        }
                                        $output .= '<div>' . $status . '</div>';
                                }
                        }
                }
                $output .= '</div>';
        }
}
$footer = <<<EOD
</div>
</div>
</body>
</html>
EOD;
$output .= $footer;
file_put_contents($export, $output);
echo $output;