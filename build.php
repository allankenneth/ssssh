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
$output = 'It is ' . $day . ' at ' . $hour . '<br>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<h1>' . $zone->name . '</h1>';
                foreach($zone->types as $type) {
                        $output .= '<h1>' . $type->type . '</h1>';
                        //$output .= $type->description . '<br>';
                        foreach($type->ranges as $range) {
                                $status = '';
                                if($dayrange == $range->name) {
                                        //$output .= '<h4>' . $range->name . '</h4>';
                                        //$output .= 'Starting: ' . $range->start . '<br>';
                                        //$output .= 'Ending: ' . $range->end . '<br>';
                                        if($hour > $range->start && $hour < $range->end) {
                                                $status = 'YESSA';
                                        } else {
                                                $status = 'HELL NO';
                                        }
                                        $output .= '<div>' . $status . '</div>';
                                }
                        }
                }
        }
}
file_put_contents($export, $output);
echo $output;