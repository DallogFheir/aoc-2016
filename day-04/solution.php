<?php

function parse_room($line)
{
    $parts = explode("-", $line);

    preg_match('/(\d+)\[(\w+)\]/', end($parts), $matches);
    $sector_id = (int) $matches[1];
    $checksum = $matches[2];

    $letters = array_slice($parts, 0, -1);

    return [
        "sector_id" => $sector_id,
        "checksum" => $checksum,
        "letters" => $letters,
    ];
}

function is_real_room($room)
{
    $letter_count = [];

    foreach ($room["letters"] as $letter_group) {
        foreach (str_split($letter_group) as $letter) {
            if (in_array($letter, array_keys($letter_count))) {
                $letter_count[$letter] += 1;
            } else {
                $letter_count[$letter] = 1;
            }
        }
    }

    uksort($letter_count, function ($a, $b) use ($letter_count) {
        $a_val = $letter_count[$a];
        $b_val = $letter_count[$b];

        if ($a_val !== $b_val) {
            return $b_val - $a_val;
        }

        return $a > $b ? 1 : -1;
    });

    $letters_ordered = join("", array_slice(array_keys($letter_count), 0, 5));

    return $letters_ordered === $room["checksum"];
}

function decipher_room_name($words, $sector_id)
{
    $alphabet = str_split("abcdefghijklmnopqrstuvwxyz");

    $words_deciphered = [];
    foreach ($words as $word) {
        $new_word = "";

        foreach (str_split($word) as $letter) {
            $letter_index = array_search($letter, $alphabet);
            $shifted_index = ($letter_index + $sector_id) % count($alphabet);
            $new_word .= $alphabet[$shifted_index];
        }

        array_push($words_deciphered, $new_word);
    }

    return join(" ", $words_deciphered);
}

###

function part_1($input)
{
    $sum = 0;

    foreach ($input as $room) {
        if (is_real_room($room)) {
            $sum += $room["sector_id"];
        }
    }

    return $sum;
}

function part_2($input)
{
    $real_rooms = array_filter($input, fn ($room) => is_real_room($room));
    $rooms_deciphered = [];
    $northpole_storage_sector_id = -1;

    foreach ($real_rooms as $room) {
        $room_deciphered = decipher_room_name($room["letters"], $room["sector_id"]);
        array_push($rooms_deciphered, $room_deciphered);

        if ($room_deciphered === "northpole object storage") {
            $northpole_storage_sector_id = $room["sector_id"];
        }
    }

    $file = fopen("day-4/output.txt", "w") or die("Could not open file.");
    fwrite($file, join("\n", $rooms_deciphered));
    fclose($file);

    return $northpole_storage_sector_id;
}

###

assert(is_real_room(parse_room("aaaaa-bbb-z-y-x-123[abxyz]")));
assert(is_real_room(parse_room("a-b-c-d-e-f-g-h-987[abcde]")));
assert(is_real_room(parse_room("not-a-real-room-404[oarel]")));
assert(!is_real_room(parse_room("totally-real-room-200[decoy]")));

assert(decipher_room_name(["qzmt", "zixmtkozy", "ivhz"], 343) === "very encrypted name");

###

$file = fopen("day-4/input.txt", "r") or die("Could not open file.");
$lines = explode("\n", fread($file, filesize("day-4/input.txt")));
fclose($file);

$input = array_map("parse_room", $lines);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
