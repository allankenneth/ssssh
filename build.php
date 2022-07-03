<?php 
$export = 'noise.html';
$noises = json_decode('noise.json');
$noise += '<h1>Can I make noise outside right now?</h1>';
file_put_contents($export, $noises);
echo 'success';
