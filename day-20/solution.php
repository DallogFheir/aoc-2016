<?php

function prepare_ips($ip_ranges)
{
    $ips = [];
    foreach ($ip_ranges as $ip_range) {
        preg_match("/(\d+)\-(\d+)/", $ip_range, $matches);
        $low = (int) $matches[1];
        $high = (int) $matches[2];

        array_push($ips, ["low" => $low, "high" => $high]);
    }

    usort($ips, function ($a, $b) {
        return $a["low"] - $b["low"];
    });

    return $ips;
}

function merge_ips($ips)
{
    $merged_ips = [];
    $current_range = ["low" => $ips[0]["low"], "high" => $ips[0]["high"]];
    foreach ($ips as $ip) {
        if ($ip["low"] > $current_range["high"]) {
            array_push($merged_ips, $current_range);
            $current_range["low"] = $ip["low"];
            $current_range["high"] = $ip["high"];
        } else if ($ip["high"] > $current_range["high"]) {
            $current_range["high"] = $ip["high"];
        }
    }
    array_push($merged_ips, $current_range);

    return $merged_ips;
}

###

function part_1($input)
{
    $ips = prepare_ips($input);

    $lowest_ip = 0;
    foreach ($ips as $ip) {
        if ($lowest_ip >= $ip["low"] and $lowest_ip <= $ip["high"]) {
            $lowest_ip = $ip["high"] + 1;
        }
    }

    return $lowest_ip;
}

function part_2($input)
{
    $ips = merge_ips(prepare_ips($input));
    $allowed = 0;

    for ($i = 0; $i < count($ips) - 1; $i++) {
        $cur = $ips[$i];
        $next = $ips[$i + 1];

        if ($cur["high"] < $next["low"] - 1) {
            $diff = $next["low"] - $cur["high"] - 1;
            $allowed += $diff;
        }
    }

    return $allowed;
}

###

$file = fopen("day-20/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-20/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
