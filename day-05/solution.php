<?php

function part_1($input)
{
    $password = "";

    $index = 0;
    while (true) {
        $to_hash = $input . (string) $index;
        $hashed = md5($to_hash);

        if (substr($hashed, 0, 5) === "00000") {
            $password .= $hashed[5];

            if (strlen($password) === 8) {
                return $password;
            }
        }

        $index++;
    }
}

function part_2($input)
{
    $password = "ZZZZZZZZ";

    $index = 0;
    while (true) {
        $to_hash = $input . (string) $index;
        $hashed = md5($to_hash);

        if (substr($hashed, 0, 5) === "00000") {
            $pos = $hashed[5];

            if (strpos("abcdef", $pos) === false and $pos < 8 and $password[$pos] === "Z") {
                $password[$pos] = $hashed[6];

                $password_without_z = join(array_filter(str_split($password), fn ($char) => $char !== "Z"));
                if (strlen($password_without_z) === 8) {
                    return $password_without_z;
                }
            }
        }

        $index++;
    }
}

###

assert(part_1("abc") === "18f47a30");
assert(part_2("abc") === "05ace8e3");

###

$input = "reyedfim";

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
