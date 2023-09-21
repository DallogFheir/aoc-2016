<?php

function generate_rows($first_row, $number)
{
    $rows = [$first_row];

    for ($i = 1; $i < $number; $i++) {
        $prev_row = $rows[$i - 1];
        $new_row = "";

        for ($j = 0; $j < strlen($prev_row); $j++) {
            $left = $j - 1 >= 0 ? $prev_row[$j - 1] : ".";
            $center = $prev_row[$j];
            $right = $j + 1 < strlen($prev_row) ? $prev_row[$j + 1] : ".";

            if (($left === "^" and $center === "^" and $right === ".") or
                ($left === "." and $center === "^" and $right === "^") or
                ($left === "^" and $center === "." and $right === ".") or
                ($left === "." and $center === "." and $right === "^")
            ) {
                $new_row .= "^";
            } else {
                $new_row .= ".";
            }
        }

        array_push($rows, $new_row);
    }

    return $rows;
}

###

function part_1($input)
{
    $rows = generate_rows($input, 40);

    $count = 0;
    foreach ($rows as $row) {
        $count += substr_count($row, ".");
    }

    return $count;
}

function part_2($input)
{
    $rows = generate_rows($input, 400000);

    $count = 0;
    foreach ($rows as $row) {
        $count += substr_count($row, ".");
    }

    return $count;
}

###

$file = fopen("day-18/input.txt", "r") or die("Could not open file.");
$input = fread($file, filesize("day-18/input.txt"));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
