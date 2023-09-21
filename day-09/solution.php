<?php

function part_1($input)
{
    $output_length = 0;
    $current_marker = "";
    $marker_opened = false;
    $i = 0;

    while ($i < strlen($input)) {
        $char = $input[$i];

        if (!$marker_opened) {
            if ($char === "(") {
                $marker_opened = true;
            } else {
                $output_length++;
            }
        } else {
            if ($char === ")") {
                preg_match("/(\d+)x(\d+)/", $current_marker, $matches);
                $how_many_chars = (int) $matches[1];
                $how_many_times = (int) $matches[2];

                $output_length += $how_many_chars * $how_many_times;
                $i = $i + $how_many_chars;

                $marker_opened = false;
                $current_marker = "";
            } else {
                $current_marker .= $char;
            }
        }

        $i++;
    }

    return $output_length;
}

function part_2($input)
{
    $output_length = 0;
    $current_marker = "";
    $marker_opened = false;
    $i = 0;

    while ($i < strlen($input)) {
        $char = $input[$i];

        if (!$marker_opened) {
            if ($char === "(") {
                $marker_opened = true;
            } else {
                $output_length++;
            }
        } else {
            if ($char === ")") {
                preg_match("/(\d+)x(\d+)/", $current_marker, $matches);
                $how_many_chars = (int) $matches[1];
                $how_many_times = (int) $matches[2];

                $chars_to_add = substr($input, $i + 1, $how_many_chars);
                $output_length += part_2($chars_to_add) * $how_many_times;
                $i = $i + $how_many_chars;

                $marker_opened = false;
                $current_marker = "";
            } else {
                $current_marker .= $char;
            }
        }

        $i++;
    }

    return $output_length;
}

###

assert(part_1("ADVENT") === 6);
assert(part_1("A(1x5)BC") === 7);
assert(part_1("(3x3)XYZ") === 9);
assert(part_1("A(2x2)BCD(2x2)EFG") === 11);
assert(part_1("(6x1)(1x3)A") === 6);
assert(part_1("X(8x2)(3x3)ABCY") === 18);
assert(part_2("(3x3)XYZ") === 9);
assert(part_2("X(8x2)(3x3)ABCY") === 20);
assert(part_2("(27x12)(20x12)(13x14)(7x10)(1x12)A") === 241920);
assert(part_2("(25x3)(3x3)ABC(2x3)XY(5x2)PQRSTX(18x9)(3x2)TWO(5x7)SEVEN") === 445);

###

$file = fopen("day-09/input.txt", "r") or die("Could not open file.");
$input = fread($file, filesize("day-09/input.txt"));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
