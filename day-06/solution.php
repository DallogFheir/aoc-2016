<?php

function part_1($input)
{
    $most_frequent = [];
    for ($i = 0; $i < strlen($input[0]); $i++) {
        array_push($most_frequent, []);
    }

    foreach ($input as $message) {
        for ($i = 0; $i < strlen($message); $i++) {
            $letter = $message[$i];

            if (in_array($letter, array_keys($most_frequent[$i]))) {
                $most_frequent[$i][$letter] += 1;
            } else {
                $most_frequent[$i][$letter] = 1;
            }
        }
    }

    $decoded_message = "";
    foreach ($most_frequent as $letter_counts) {
        arsort($letter_counts);
        $decoded_message .= array_keys($letter_counts)[0];
    }

    return $decoded_message;
}

function part_2($input)
{
    $least_frequent = [];
    for ($i = 0; $i < strlen($input[0]); $i++) {
        array_push($least_frequent, []);
    }

    foreach ($input as $message) {
        for ($i = 0; $i < strlen($message); $i++) {
            $letter = $message[$i];

            if (in_array($letter, array_keys($least_frequent[$i]))) {
                $least_frequent[$i][$letter] += 1;
            } else {
                $least_frequent[$i][$letter] = 1;
            }
        }
    }

    $decoded_message = "";
    foreach ($least_frequent as $letter_counts) {
        asort($letter_counts);
        $decoded_message .= array_keys($letter_counts)[0];
    }

    return $decoded_message;
}

###
$test_input = explode("\n", "eedadn
drvtee
eandsr
raavrd
atevrs
tsrnev
sdttsa
rasrtv
nssdts
ntnada
svetve
tesnvt
vntsnd
vrdear
dvrsen
enarar");

assert(part_1($test_input) === "easter");
assert(part_2($test_input) === "advent");

###

$file = fopen("day-06/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-06/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
