<?php

function execute_program($instructions, $c_value)
{
    $registers = [
        "a" => 0,
        "b" => 0,
        "c" => $c_value,
        "d" => 0,
    ];

    $i = 0;
    while ($i < count($instructions)) {
        $ins = $instructions[$i];

        if (str_starts_with($ins, "cpy")) {
            $parts = explode(" ", $ins);
            $from = $parts[1];
            $to = $parts[2];

            if (is_numeric($from)) {
                $registers[$to] = (int) $from;
            } else {
                $registers[$to] = $registers[$from];
            }

            $i++;
        } else if (str_starts_with($ins, "inc")) {
            $register = explode(" ", $ins)[1];

            $registers[$register]++;

            $i++;
        } else if (str_starts_with($ins, "dec")) {
            $register = explode(" ", $ins)[1];

            $registers[$register]--;

            $i++;
        } else if (str_starts_with($ins, "jnz")) {
            $parts = explode(" ", $ins);
            $condition = $parts[1];
            $value = (int) $parts[2];

            if ((is_numeric($condition) and $condition !== 0)
                or $registers[$condition] !== 0
            ) {
                $i += $value;
            } else {
                $i++;
            }
        } else {
            die("Invalid instruction: $ins.");
        }
    }

    return $registers;
}

###

function part_1($input)
{
    $result = execute_program($input, 0);
    return $result["a"];
}

function part_2($input)
{
    $result = execute_program($input, 1);
    return $result["a"];
}

###

$test_input = explode("\n", "cpy 41 a
inc a
inc a
dec a
jnz a 2
dec a");
assert(part_1($test_input) === 42);

###

$file = fopen("day-12/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-12/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
