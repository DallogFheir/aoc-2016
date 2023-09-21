<?php

function part_1($input)
{
    $keys = [];
    $keys_to_add = [];
    $potential_keys = [];

    $i = 0;
    while (count($keys) < 100) {
        $hash = md5($input . (string) $i);

        $new_potential_keys = [];
        foreach ($potential_keys as $index => $potential_key) {
            $pattern = "/" . str_repeat($potential_key["digit"], 5) . "/";

            if (preg_match($pattern, $hash)) {
                array_push($keys_to_add, $potential_key);
            } else {
                $new_potential_keys[$index] = $potential_key;
            }
        }
        $potential_keys = $new_potential_keys;

        $new_keys_to_add = [];
        foreach ($keys_to_add as $key) {
            if ($key["index"] < array_values($potential_keys)[0]["index"]) {
                array_push($keys, $key);
            } else {
                array_push($new_keys_to_add, $key);
            }
        }
        $keys_to_add = $new_keys_to_add;

        if (isset($potential_keys[$i])) {
            unset($potential_keys[$i]);
        }

        $matched = preg_match('/(.)\1\1/', $hash, $matches);
        if ($matched) {
            $potential_keys[$i + 1000] = ["index" => $i, "digit" => $matches[1], "hash" => $hash];
        }

        $i++;
    }

    return $keys[63]["index"];
}

function part_2($input)
{
    $keys = [];
    $keys_to_add = [];
    $potential_keys = [];

    $i = 0;
    while (count($keys) < 100) {
        $hash = md5($input . (string) $i);

        for ($j = 0; $j < 2016; $j++) {
            $hash = md5($hash);
        }

        $new_potential_keys = [];
        foreach ($potential_keys as $index => $potential_key) {
            $pattern = "/" . str_repeat($potential_key["digit"], 5) . "/";

            if (preg_match($pattern, $hash)) {
                array_push($keys_to_add, $potential_key);
            } else {
                $new_potential_keys[$index] = $potential_key;
            }
        }
        $potential_keys = $new_potential_keys;

        $new_keys_to_add = [];
        foreach ($keys_to_add as $key) {
            if ($key["index"] < array_values($potential_keys)[0]["index"]) {
                array_push($keys, $key);
            } else {
                array_push($new_keys_to_add, $key);
            }
        }
        $keys_to_add = $new_keys_to_add;

        if (isset($potential_keys[$i])) {
            unset($potential_keys[$i]);
        }

        $matched = preg_match('/(.)\1\1/', $hash, $matches);
        if ($matched) {
            $potential_keys[$i + 1000] = ["index" => $i, "digit" => $matches[1], "hash" => $hash];
        }

        $i++;
    }

    return $keys[63]["index"];
}

###

assert(part_1("abc") === 22728);
assert(part_2("abc") === 22551);

###

$input = "zpqevtbw";

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
