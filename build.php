<?php 
$export = 'noise.html';
$noises = json_decode('noise.json');
file_put_contents($export, $noises);
echo 'success';
