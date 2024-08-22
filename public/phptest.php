
<?php
$radius = 100;
$cx = 100;
$cy = 100;

$hours = date('h');
$minutes = date('i');
$seconds = date('s');

$hour_angle = ($hours / 12) * 360 + ($minutes / 60) * (360 / 12);
$minute_angle = ($minutes / 60) * 360;
$second_angle = ($seconds / 60) * 360;

$hour_hand_length = $radius * 0.5;
$minute_hand_length = $radius * 0.8;
$second_hand_length = $radius * 0.9;

$hour_hand_x = $cx + $hour_hand_length * sin(deg2rad($hour_angle));
$hour_hand_y = $cy - $hour_hand_length * cos(deg2rad($hour_angle));

$minute_hand_x = $cx + $minute_hand_length * sin(deg2rad($minute_angle));
$minute_hand_y = $cy - $minute_hand_length * cos(deg2rad($minute_angle));

$second_hand_x = $cx + $second_hand_length * sin(deg2rad($second_angle));
$second_hand_y = $cy - $second_hand_length * cos(deg2rad($second_angle));

$svg = <<<EOT
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
    <circle cx="$cx" cy="$cy" r="$radius" stroke="black" stroke-width="2" fill="white" />
    <line x1="$cx" y1="$cy" x2="$hour_hand_x" y2="$hour_hand_y" style="stroke:rgb(0,0,0);stroke-width:2" />
    <line x1="$cx" y1="$cy" x2="$minute_hand_x" y2="$minute_hand_y" style="stroke:rgb(0,0,0);stroke-width:2" />
    <line x1="$cx" y1="$cy" x2="$second_hand_x" y2="$second_hand_y" style="stroke:rgb(255,0,0);stroke-width:2" />
EOT;

for ($i = 0; $i < 60; $i++) {
    $angle = ($i / 60) * 360;
    $start_length = $radius * 0.9;
    $end_length = $radius;

    if ($i % 5 == 0) {
        $start_length = $radius * 0.8;
    }

    $start_x = $cx + $start_length * sin(deg2rad($angle));
    $start_y = $cy - $start_length * cos(deg2rad($angle));

    $end_x = $cx + $end_length * sin(deg2rad($angle));
    $end_y = $cy - $end_length * cos(deg2rad($angle));

    $svg .= <<<EOT
    <line x1="$start_x" y1="$start_y" x2="$end_x" y2="$end_y" style="stroke:rgb(0,0,0);stroke-width:1" />
EOT;
}

$svg .= "</svg>";

echo $svg;
?>

<?php
$date = new DateTime('now');
echo "標準時間(UTC)";
echo $date->format('Y/m/d H:i:s');
echo "[目盛り]";
?>
