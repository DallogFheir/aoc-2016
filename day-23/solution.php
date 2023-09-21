<?php

function toggle_instruction($instruction)
{
    $trans = [
        "inc" => "dec",
        "dec" => "inc",
        "tgl" => "inc",
        "jnz" => "cpy",
        "cpy" => "jnz",
    ];

    foreach ($trans as $from => $to) {
        if (str_starts_with($instruction, $from)) {
            return str_replace($from, $to, $instruction);
        }
    }

    die("Invalid instruction: $instruction.");
}

function execute_program($instructions, $a_value)
{
    $registers = [
        "a" => $a_value,
        "b" => 0,
        "c" => 0,
        "d" => 0,
    ];

    $i = 0;
    while ($i < count($instructions)) {
        $ins = $instructions[$i];

        if (str_starts_with($ins, "cpy")) {
            $parts = explode(" ", $ins);
            $from = $parts[1];
            $to = $parts[2];

            if (is_numeric($to)) {
                $i++;
                continue;
            }

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

            $value_str = $parts[2];
            $value = is_numeric($value_str) ? (int) $value_str : $registers[$value_str];

            if ((is_numeric($condition) and $condition !== 0)
                or $registers[$condition] !== 0
            ) {
                $i += $value;
            } else {
                $i++;
            }
        } else if (str_starts_with($ins, "tgl")) {
            $value_str = explode(" ", $ins)[1];
            $value = is_numeric($value_str) ? (int) $value_str : $registers[$value_str];

            if ($i + $value < count($instructions)) {
                $instructions[$i + $value] = toggle_instruction($instructions[$i + $value]);
            }

            $i++;
        } else {
            die("Invalid instruction: $ins.");
        }
    }

    return $registers;
}

###

function part_1($input)
{
    $result = execute_program($input, 7);
    return $result["a"];
}

function part_2($input)
{
    $result = execute_program($input, 12);
    return $result["a"];
}

###

$test_input = explode("\n", "cpy 2 a
tgl a
tgl a
tgl a
cpy 1 a
dec a
dec a");
assert(execute_program($test_input, 7)["a"] === 3);

###

$file = fopen("day-23/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-23/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
