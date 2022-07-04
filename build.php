<?php 
$export = 'index.html';
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
//$output = '<h1>' . date('Y-m-d') . '</h1>';
$day = date('l');
$hour = date('G');
echo 'It is ' . $day . ' at ' . $hour . '<br>';
$output = '';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<h1>' . $zone->name . '</h1>'; 
                foreach($zone->types as $type) {
                        $output .= '<h1>' . $type->type . '</h1>';
                        $output .= $type->description . '<br>';
                        foreach($type->ranges as $range) {
                                $output .= '<h4>' . $range->name . '</h4>';
                                $output .= 'Starting: ' . $range->start . '<br>';
                                $output .= 'Ending: ' . $range->end . '<br>';
                        }
                }
        }
}
file_put_contents($export, $output);
echo $output;