<?php

function part_1($input)
{
}

function part_2($input)
{
}

###

$file = fopen("day-X/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-X/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
