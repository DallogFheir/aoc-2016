<?php

function part_1($input)
{
    $numpad = [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ];
    $start = [1, 1];
    $code = "";

    foreach ($input as $line) {
        foreach ($line as $instruction) {
            switch ($instruction) {
                case "U":
                    $start[0] -= 1;

                    if ($start[0] < 0 or $start[0] >= count($numpad)) {
                        $start[0] += 1;
                    }

                    break;
                case "D":
                    $start[0] += 1;

                    if ($start[0] < 0 or $start[0] >= count($numpad)) {
                        $start[0] -= 1;
                    }

                    break;
                case "L":
                    $start[1] -= 1;

                    if ($start[1] < 0 or $start[1] >= count($numpad[0])) {
                        $start[1] += 1;
                    }

                    break;
                case "R":
                    $start[1] += 1;

                    if ($start[1] < 0 or $start[1] >= count($numpad[0])) {
                        $start[1] -= 1;
                    }

                    break;
                default:
                    die("Unknown instruction $instruction.");
            }
        }


        $code .= (string) $numpad[$start[0]][$start[1]];
    }

    return $code;
}

function part_2($input)
{
    $numpad = [
        [0, 0, 1, 0, 0],
        [0, 2, 3, 4, 0],
        [5, 6, 7, 8, 9],
        [0, "A", "B", "C", 0],
        [0, 0, "D", 0, 0],
    ];
    $start = [2, 0];
    $code = "";

    foreach ($input as $line) {
        foreach ($line as $instruction) {
            switch ($instruction) {
                case "U":
                    $start[0] -= 1;

                    if ($start[0] < 0 or $start[0] >= count($numpad) or $numpad[$start[0]][$start[1]] === 0) {
                        $start[0] += 1;
                    }

                    break;
                case "D":
                    $start[0] += 1;

                    if ($start[0] < 0 or $start[0] >= count($numpad) or $numpad[$start[0]][$start[1]] === 0) {
                        $start[0] -= 1;
                    }

                    break;
                case "L":
                    $start[1] -= 1;

                    if ($start[1] < 0 or $start[1] >= count($numpad[0]) or $numpad[$start[0]][$start[1]] === 0) {
                        $start[1] += 1;
                    }

                    break;
                case "R":
                    $start[1] += 1;

                    if ($start[1] < 0 or $start[1] >= count($numpad[0]) or $numpad[$start[0]][$start[1]] === 0) {
                        $start[1] -= 1;
                    }

                    break;
                default:
                    die("Unknown instruction $instruction.");
            }
        }


        $code .= (string) $numpad[$start[0]][$start[1]];
    }

    return $code;
}

###

$test_input = array_map(fn ($line) => str_split($line), ["ULL", "RRDDD", "LURDL", "UUUUD"]);
assert(part_1($test_input) === "1985");
assert(part_2($test_input) === "5DB3");

###

$file = fopen("day-2/input.txt", "r") or die("Could not open file.");
$lines = explode("\n", fread($file, filesize("day-2/input.txt")));
fclose($file);

$input = array_map(fn ($line) => str_split($line), $lines);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
