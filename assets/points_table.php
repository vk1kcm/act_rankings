<?php
// -------------------------------------------------------------------------
// points_table.php
// 25th April, 2016
// v0.1
// Constants for calculating the points to be allocated for each competition
// -------------------------------------------------------------------------

// Valid results category for each category
$validages['U9'] = ['U9', 'U11'];
$validages['U11'] = ['U11', 'U13'];
$validages['U13'] = ['U13', 'U15'];
$validages['U15'] = ['U15', 'U17'];
$validages['U17'] = ['U17', 'U20'];
$validages['U20'] = ['U20', 'U23', 'Open'];
$validages['U23'] = ['U23', 'Open'];
$validages['Open'] = ['Open'];
$validages['Veteran'] = ['Veteran', 'Open'];
$validages['Novice'] = ['Novice', 'Age'];

// Valid levels for category
$validlevels['U9'] = ['Club', 'State'];
$validlevels['U11'] = ['Club', 'State'];
$validlevels['U13'] = ['Club', 'State'];
$validlevels['U15'] = ['Club', 'State', 'National'];
$validlevels['U17'] = ['Club', 'State', 'National'];
$validlevels['U20'] = ['Club', 'State', 'National'];
$validlevels['U23'] = ['Club', 'State', 'National'];
$validlevels['Open'] = ['Club', 'State', 'National'];
$validlevels['Veteran'] = ['Club', 'State', 'National'];
$validlevels['Novice'] = ['Club', 'State'];

// number of valid results for each level
$numlevel['Club'] = 3;  // includes Regional
$numlevel['State'] = 2;
$numlevel['National'] = 1; // includes international


// The actual points tables
// Novice
$points['Novice'][1] = [30,35,35,40];
$points['Novice'][2] = [28,33,33,38];
$points['Novice'][3] = [25,30,30,35];
$points['Novice'][4] = [25,30,30,35];
foreach (range(5, 8) as $num) {
  $points['Novice'][$num] = [21,26,26,31];
}
foreach (range(9, 16) as $num) {
  $points['Novice'][$num] = [16,21,21,26];
}
foreach (range(17, 32) as $num) {
  $points['Novice'][$num] = [10,15,15,20];
}
foreach (range(33, 64) as $num) {
  $points['Novice'][$num] = [3,8,8,13];
}
foreach (range(65, 128) as $num) {
  $points['Novice'][$num] = [1,1,1,5];
}

// U9
$points['U9'][1] = [30,35,35,40];
$points['U9'][2] = [28,33,33,38];
$points['U9'][3] = [25,30,30,35];
$points['U9'][4] = [25,30,30,35];
foreach (range(5, 8) as $num) {
  $points['U9'][$num] = [21,26,26,31];
}
foreach (range(9, 16) as $num) {
  $points['U9'][$num] = [16,21,21,26];
}
foreach (range(17, 32) as $num) {
  $points['U9'][$num] = [10,15,15,20];
}
foreach (range(33, 64) as $num) {
  $points['U9'][$num] = [3,8,8,13];
}
foreach (range(65, 128) as $num) {
  $points['U9'][$num] = [1,1,1,5];
}

// U11
$points['U11'][1] = [30,35,35,40];
$points['U11'][2] = [28,33,33,38];
$points['U11'][3] = [25,30,30,35];
$points['U11'][4] = [25,30,30,35];
foreach (range(5, 8) as $num) {
  $points['U11'][$num] = [21,26,26,31];
}
foreach (range(9, 16) as $num) {
  $points['U11'][$num] = [16,21,21,26];
}
foreach (range(17, 32) as $num) {
  $points['U11'][$num] = [10,15,15,20];
}
foreach (range(33, 64) as $num) {
  $points['U11'][$num] = [3,8,8,13];
}
foreach (range(65, 128) as $num) {
  $points['U11'][$num] = [1,1,1,5];
}

// U13
$points['U13'][1] = [30,35,35,40];
$points['U13'][2] = [28,33,33,38];
$points['U13'][3] = [25,30,30,35];
$points['U13'][4] = [25,30,30,35];
foreach (range(5, 8) as $num) {
  $points['U13'][$num] = [21,26,26,31];
}
foreach (range(9, 16) as $num) {
  $points['U13'][$num] = [16,21,21,26];
}
foreach (range(17, 32) as $num) {
  $points['U13'][$num] = [10,15,15,20];
}
foreach (range(33, 64) as $num) {
  $points['U13'][$num] = [3,8,8,13];
}
foreach (range(65, 128) as $num) {
  $points['U13'][$num] = [1,1,1,5];
}

// U15
$points['U15'][1] = [30,35,35,40,55,60];
$points['U15'][2] = [28,33,33,38,53,58];
$points['U15'][3] = [25,30,30,35,50,55];
$points['U15'][4] = [25,30,30,35,50,55];
foreach (range(5, 8) as $num) {
  $points['U15'][$num] = [21,26,26,31,46,51];
}
foreach (range(9, 16) as $num) {
  $points['U15'][$num] = [16,21,21,26,41,46];
}
foreach (range(17, 32) as $num) {
  $points['U15'][$num] = [10,15,15,20,33,38];
}
foreach (range(33, 64) as $num) {
  $points['U15'][$num] = [3,8,8,13,21,26];
}
foreach (range(65, 128) as $num) {
  $points['U15'][$num] = [1,1,1,5,5,10];
}


// U17 (Cadet)
$points['U17'][1] = [22,24,24,26,56,60];
$points['U17'][2] = [20,22,22,24,54,58];
$points['U17'][3] = [18,20,20,22,51,55];
$points['U17'][4] = [18,20,20,22,51,55];
foreach (range(5, 8) as $num) {
  $points['U17'][$num] = [14,16,16,18,47,51];
}
foreach (range(9, 16) as $num) {
  $points['U17'][$num] = [11,13,13,15,42,46];
}
foreach (range(17, 32) as $num) {
  $points['U17'][$num] = [8,10,10,13,36,40];
}
foreach (range(33, 64) as $num) {
  $points['U17'][$num] = [5,7,7,10,24,28];
}
foreach (range(65, 128) as $num) {
  $points['U17'][$num] = [3,5,5,8,8,12];
}

// U20 (Junior)
$points['U20'][1] = [30,35,40,35,40,45,60,65,70];
$points['U20'][2] = [28,33,38,33,38,43,58,63,68];
$points['U20'][3] = [25,30,35,30,35,40,55,60,65];
$points['U20'][4] = [25,30,35,30,35,40,55,60,65];
foreach (range(5, 8) as $num) {
  $points['U20'][$num] = [21,26,31,26,31,36,51,56,61];
}
foreach (range(9, 16) as $num) {
  $points['U20'][$num] = [16,21,26,21,26,31,46,51,56];
}
foreach (range(17, 32) as $num) {
  $points['U20'][$num] = [10,15,20,15,20,25,38,43,46];
}
foreach (range(33, 64) as $num) {
  $points['U20'][$num] = [3,8,13,8,13,18,26,31,36];
}
foreach (range(65, 128) as $num) {
  $points['U20'][$num] = [1,1,5,3,5,10,10,15,20];
}

// U23
$points['U23'][1] = [30,35,35,40,60,65];
$points['U23'][2] = [28,33,33,38,58,63];
$points['U23'][3] = [25,30,30,35,55,60];
$points['U23'][4] = [25,30,30,35,55,60];
foreach (range(5, 8) as $num) {
  $points['U23'][$num] = [21,26,26,31,51,56];
}
foreach (range(9, 16) as $num) {
  $points['U23'][$num] = [16,21,21,26,46,51];
}
foreach (range(17, 32) as $num) {
  $points['U23'][$num] = [10,15,15,20,38,43];
}
foreach (range(33, 64) as $num) {
  $points['U23'][$num] = [3,8,8,13,26,31];
}
foreach (range(65, 128) as $num) {
  $points['U23'][$num] = [1,1,1,5,10,15];
}

// Open
$points['Open'][1] = [30,35,50];
$points['Open'][2] = [28,33,48];
$points['Open'][3] = [25,30,45];
$points['Open'][4] = [25,30,45];
foreach (range(5, 8) as $num) {
  $points['Open'][$num] = [21,26,41];
}
foreach (range(9, 16) as $num) {
  $points['Open'][$num] = [16,21,36];
}
foreach (range(17, 32) as $num) {
  $points['Open'][$num] = [10,15,28];
}
foreach (range(33, 64) as $num) {
  $points['Open'][$num] = [3,8,16];
}
foreach (range(65, 128) as $num) {
  $points['Open'][$num] = [1,1,1];
}

// Veteran
$points['Veteran'][1] = [30,35,35,50,55,65];
$points['Veteran'][2] = [28,33,33,48,53,63];
$points['Veteran'][3] = [25,30,30,45,50,60];
$points['Veteran'][4] = [25,30,30,45,50,60];
foreach (range(5, 8) as $num) {
  $points['Veteran'][$num] = [21,26,26,41,46,56];
}
foreach (range(9, 16) as $num) {
  $points['Veteran'][$num] = [16,21,21,35,41,51];
}
foreach (range(17, 32) as $num) {
  $points['Veteran'][$num] = [10,15,15,27,33,43];
}
foreach (range(33, 64) as $num) {
  $points['Veteran'][$num] = [3,8,8,17,21,31];
}
foreach (range(65, 128) as $num) {
  $points['Veteran'][$num] = [1,1,1,5,5,18];
}


?>
