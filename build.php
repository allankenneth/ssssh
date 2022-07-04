<?php 
$export = 'index.html';
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
$day = date('l');
$hour = date('G:i');
$output = 'It is ' . $day . ' ' . $hour . ' hundred hours<br>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<h1>' . $zone->name . '</h1>'; 
                foreach($zone->types as $type) {
                        $output .= '<h1>' . $type->type . '</h1>';
                        $output .= $type->description . '<br>';
                        foreach($type->ranges as $range) {
                                $status = '';
                                if($hour > $range->start && $hour < $range->end) {
                                        $status = 'YESSA';
                                } else {
                                        $status = 'HELL NO';
                                }
                                $output .= $status;
                                $output .= '<h4>' . $range->name . '</h4>';
                                $output .= 'Starting: ' . $range->start . '<br>';
                                $output .= 'Ending: ' . $range->end . '<br>';
                        }
                }
        }
}
file_put_contents($export, $output);
echo $output;