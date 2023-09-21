<?php

function part_1($input)
{
    $rotations_left = [
        "N" => "W",
        "E" => "N",
        "S" => "E",
        "W" => "S"
    ];
    $rotations_right = [
        "N" => "E",
        "E" => "S",
        "S" => "W",
        "W" => "N"
    ];

    $position = [0, 0];
    $direction = "N";

    foreach ($input as $ins) {
        $rotation = $ins[0];
        $steps = (int) substr($ins, 1);

        if ($rotation === "L") {
            $direction = $rotations_left[$direction];
        } else if ($rotation === "R") {
            $direction = $rotations_right[$direction];
        } else {
            die("Unknown rotation: $rotation.");
        }

        switch ($direction) {
            case "N":
                $position[1] += $steps;
                break;
            case "S":
                $position[1] -= $steps;
                break;
            case "W":
                $position[0] -= $steps;
                break;
            case "E":
                $position[0] += $steps;
                break;
            default:
                die("Unknown direction: $direction.");
        }
    }

    return abs($position[0] + $position[1]);
}

function part_2($input)
{
    $visited = [];

    $rotations_left = [
        "N" => "W",
        "E" => "N",
        "S" => "E",
        "W" => "S"
    ];
    $rotations_right = [
        "N" => "E",
        "E" => "S",
        "S" => "W",
        "W" => "N"
    ];

    $position = [0, 0];
    $direction = "N";

    foreach ($input as $ins) {
        $rotation = $ins[0];
        $steps = (int) substr($ins, 1);

        if ($rotation === "L") {
            $direction = $rotations_left[$direction];
        } else if ($rotation === "R") {
            $direction = $rotations_right[$direction];
        } else {
            die("Unknown rotation: $rotation.");
        }

        switch ($direction) {
            case "N":
                for ($i = 1; $i <= $steps; $i++) {
                    $position[1] += 1;

                    if (in_array($position, $visited)) {
                        return abs($position[0] - $position[1]);
                    }

                    array_push($visited, $position);
                }
                break;
            case "S":
                for ($i = 1; $i <= $steps; $i++) {
                    $position[1] -= 1;

                    if (in_array($position, $visited)) {
                        return abs($position[0] - $position[1]);
                    }

                    array_push($visited, $position);
                }
                break;
            case "W":
                for ($i = 1; $i <= $steps; $i++) {
                    $position[0] -= 1;

                    if (in_array($position, $visited)) {
                        return abs($position[0] - $position[1]);
                    }

                    array_push($visited, $position);
                }
                break;
            case "E":
                for ($i = 1; $i <= $steps; $i++) {
                    $position[0] += 1;

                    if (in_array($position, $visited)) {
                        return abs($position[0] - $position[1]);
                    }

                    array_push($visited, $position);
                }
                break;
            default:
                die("Unknown direction: $direction.");
        }
    }
}

###

assert(part_1(["R2", "L3"]) === 5);
assert(part_1(["R2", "R2", "R2"]) === 2);
assert(part_1(["R5", "L5", "R5", "R3"]) === 12);
assert(part_2(["R8", "R4", "R4", "R8"]) === 4);

###

$file = fopen("day-01/input.txt", "r") or die("Could not open file.");
$input = explode(", ", fread($file, filesize("day-01/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
