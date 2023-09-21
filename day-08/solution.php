<?php

function light_pixels($input)
{
    $lit = [];

    foreach ($input as $instruction) {
        if (str_starts_with($instruction, "rect")) {
            preg_match("/rect (\d+)x(\d+)/", $instruction, $matches);
            $wide = (int) $matches[1];
            $tall = (int) $matches[2];

            for ($i = 0; $i < $wide; $i++) {
                for ($j = 0; $j < $tall; $j++) {
                    array_push($lit, [$j, $i]);
                }
            }
        } else if (str_starts_with($instruction, "rotate row")) {
            preg_match("/rotate row y=(\d+) by (\d+)/", $instruction, $matches);
            $row = (int) $matches[1];
            $by = (int) $matches[2];

            foreach ($lit as &$pixel) {
                if ($pixel[0] === $row) {
                    $new_column = ($pixel[1] + $by) % 50;
                    $pixel[1] = $new_column;
                }
            }
        } else if (str_starts_with($instruction, "rotate column")) {
            preg_match("/rotate column x=(\d+) by (\d+)/", $instruction, $matches);
            $column = (int) $matches[1];
            $by = (int) $matches[2];

            foreach ($lit as &$pixel) {
                if ($pixel[1] === $column) {
                    $new_row = ($pixel[0] + $by) % 6;
                    $pixel[0] = $new_row;
                }
            }
        } else {
            die("Invalid instruction: $instruction.");
        }
    }

    return $lit;
}

###

function part_1($input)
{
    return count(light_pixels(($input)));
}

function part_2($input)
{
    $screen = [[], [], [], [], [], []];
    foreach ($screen as &$row) {
        for ($i = 0; $i < 50; $i++) {
            array_push($row, "-");
        }
    }

    $lit_pixels = light_pixels($input);
    foreach ($lit_pixels as $pixel) {
        $screen[$pixel[0]][$pixel[1]] = "#";
    }

    $screen_string = join("\n", array_map(fn ($row) => join("", $row), $screen));
    $file = fopen("day-8/output.txt", "w") or die("Could not open file.");
    fwrite($file, $screen_string);
    fclose($file);
}

###

$file = fopen("day-8/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-8/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
