<?php

function rotate($string, $steps, $dir)
{
    if ($dir === "left") {
        for ($times = 0; $times < $steps; $times++) {
            $new_string = "";

            $first_letter = $string[0];
            for ($i = 0; $i < strlen($string) - 1; $i++) {
                $new_string .= $string[$i + 1];
            }
            $new_string .= $first_letter;

            $string = $new_string;
        }
    } else if ($dir === "right") {
        for ($times = 0; $times < $steps; $times++) {
            $new_string = "";

            $last_letter = $string[-1];
            for ($i = 1; $i < strlen($string); $i++) {
                $new_string .= $string[$i - 1];
            }
            $new_string = $last_letter . $new_string;

            $string = $new_string;
        }
    } else {
        die("Invalid direction: $dir.");
    }

    return $string;
}

function scramble($instructions, $password)
{
    foreach ($instructions as $ins) {
        if (preg_match("/swap position (\d+) with position (\d+)/", $ins, $matches)) {
            $index_1 = (int) $matches[1];
            $index_2 = (int) $matches[2];

            $tmp = $password[$index_1];
            $password[$index_1] = $password[$index_2];
            $password[$index_2] = $tmp;
        } else if (preg_match("/swap letter (\w) with letter (\w)/", $ins, $matches)) {
            $letter_1 = $matches[1];
            $letter_2 = $matches[2];

            $index_1 = strpos($password, $letter_1);
            $index_2 = strpos($password, $letter_2);

            $tmp = $password[$index_1];
            $password[$index_1] = $password[$index_2];
            $password[$index_2] = $tmp;
        } else if (preg_match("/rotate (left|right) (\d+) steps?/", $ins, $matches)) {
            $dir = $matches[1];
            $steps = (int) $matches[2];

            $password = rotate($password, $steps, $dir);
        } else if (preg_match("/rotate based on position of letter (\w)/", $ins, $matches)) {
            $letter = $matches[1];
            $index = strpos($password, $letter);

            $rotate_times = 1 + $index + ($index >= 4 ? 1 : 0);
            $password = rotate($password, $rotate_times, "right");
        } else if (preg_match("/reverse positions (\d+) through (\d+)/", $ins, $matches)) {
            $index_1 = (int) $matches[1];
            $index_2 = (int) $matches[2];

            $reversed_substr = strrev(substr($password, $index_1, $index_2 - $index_1 + 1));
            for ($i = 0; $i < strlen($reversed_substr); $i++) {
                $password[$index_1 + $i] = $reversed_substr[$i];
            }
        } else if (preg_match("/move position (\d+) to position (\d+)/", $ins, $matches)) {
            $index_1 = (int) $matches[1];
            $letter = $password[$index_1];
            $index_2 = (int) $matches[2];

            $password = str_replace($letter, "", $password);
            $password = substr($password, 0, $index_2) . $letter . substr($password, $index_2);
        } else {
            die("Invalid instruction: $ins.");
        }
    }

    return $password;
}

# credit to https://stackoverflow.com/a/27160465
function permutations($elements)
{
    if (count($elements) <= 1) {
        yield $elements;
    } else {
        foreach (permutations(array_slice($elements, 1)) as $permutation) {
            foreach (range(0, count($elements) - 1) as $i) {
                yield array_merge(
                    array_slice($permutation, 0, $i),
                    [$elements[0]],
                    array_slice($permutation, $i)
                );
            }
        }
    }
}

###

function part_1($input)
{
    return scramble($input, "abcdefgh");
}

function part_2($input)
{
    $password = str_split("abcdefgh");
    foreach (permutations($password) as $permutation) {
        $result = scramble($input, join($permutation));

        if ($result === "fbgdceah") {
            return join($permutation);
        }
    }
}

###

$test_input = explode("\n", "swap position 4 with position 0
swap letter d with letter b
reverse positions 0 through 4
rotate left 1 step
move position 1 to position 4
move position 3 to position 0
rotate based on position of letter b
rotate based on position of letter d");
assert(scramble($test_input, "abcde") === "decab");

###

$file = fopen("day-21/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-21/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
