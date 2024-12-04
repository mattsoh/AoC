<?php
$day = trim(fgets(STDIN));
$submission_time = new DateTime();
$start_time = new DateTime("2024-12-{$day} 01:00:00");
if ($submission_time < $start_time) {
    echo 'Challenge not yet unlocked.';
    exit;
}
$interval = $start_time->diff($submission_time);
$minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
if ($minutes <= 60) {
    $score = 100 - ($minutes);
} elseif ($minutes <= 300) {
    $score = 40 - (floor(($minutes - 60) / 15)*2);
} else {
    $score = 40 - (floor(($minutes - 300) / 60));
}

if ($score < 5) {
    $score = 5;
}
echo $score;
?>