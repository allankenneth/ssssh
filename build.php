<?php 
$export = 'index.html';
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
$output = '<h1>' . date('Y-m-d') . '</h1>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<h2>' . $zone->name . '</h2>'; 
                foreach($zone->types as $type) {
                        $output .= '<h3>' . $type->type . '</h3>';
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