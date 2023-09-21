<?php

function find_dragon_curve($state, $disk_length)
{
    while (strlen($state) < $disk_length) {
        $b = strrev($state);

        $b_replaced = "";
        foreach (str_split($b) as $char) {
            if ($char === "1") {
                $b_replaced .= "0";
            } else {
                $b_replaced .= "1";
            }
        }

        $state = $state . "0" . $b_replaced;
    }

    return substr($state, 0, $disk_length);
}

function find_checksum($state)
{
    $checksum = "";

    for ($i = 0; $i < strlen($state) - 1; $i += 2) {
        if ($state[$i] === $state[$i + 1]) {
            $checksum .= "1";
        } else {
            $checksum .= "0";
        }
    }

    if (strlen($checksum) % 2 === 0) {
        return find_checksum($checksum);
    }
    return $checksum;
}

###

function part_1($input)
{
    $curve = find_dragon_curve($input, 272);
    return find_checksum($curve);
}

function part_2($input)
{
    $curve = find_dragon_curve($input, 35651584);
    return find_checksum($curve);
}

###

$test_input = "10000";
assert(find_checksum(find_dragon_curve($test_input, 20)) === "01100");

###

$input = "01000100010010111";

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
