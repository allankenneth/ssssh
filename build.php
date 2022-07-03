<?php 
$export = 'index.html';
$data = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$bylaws = json_decode($data);
$output = '<h1>' . date('Y-m-d') . '</h1>';
foreach($bylaws as $zones) {
        foreach($zones as $zone) {
                $output .= '<h2>' . $zone->name . '</h2>'; 
                foreach($zone->types as $type) {
                        $output .= $type->type . '<br>';
                        $output .= $type->description . '<br>';
                        foreach($type->ranges as $range) {
                                $output .= $range->name . '<br>';
                        }
                }
        }
}
file_put_contents($export, $output);
echo $output;