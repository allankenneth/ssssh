<?php 
$export = 'index.html';
$bylaws = file_get_contents('https://raw.githubusercontent.com/allankenneth/ssssh/main/noise.json');
$zones = json_decode($bylaws);
$output = '<h1>' . date('Y-m-d') . '</h1>';
foreach($zones as $zone) {
        foreach($zone as $z) {
                print_r($z);
                $output .= '<h2>' . $z->name . '</h2>'; 
                foreach($z->types as $type) {
                        $output .= $type->type . '<br>';
                        $output .= $type->description . '<br>';
                        foreach($type->ranges as $range) {
                                $output .= $range->name . '<br>';
                                //print_r($day->Weekdays);
                        }

                }
        }
}
file_put_contents($export, $output);
echo $output;
