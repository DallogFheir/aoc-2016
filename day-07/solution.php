<?php

function parse_ip($ip)
{
    $ip_obj = [
        "supernet" => [],
        "hypernet" => [],
    ];

    $current_seq = "";
    foreach (str_split($ip) as $letter) {
        if ($letter !== "[" and $letter !== "]") {
            $current_seq .= $letter;
        } else if ($letter === "[") {
            array_push($ip_obj["supernet"], $current_seq);
            $current_seq = "";
        } else {
            array_push($ip_obj["hypernet"], $current_seq);
            $current_seq = "";
        }
    }

    if ($current_seq !== "") {
        array_push($ip_obj["supernet"], $current_seq);
    }

    return $ip_obj;
}

function supports_tls($ip_obj)
{
    $abba_in_supernet = false;
    foreach ($ip_obj["supernet"] as $supernet) {
        if (is_abba($supernet)) {
            $abba_in_supernet = true;
            break;
        }
    }
    if (!$abba_in_supernet) {
        return false;
    }

    foreach ($ip_obj["hypernet"] as $hypernet) {
        if (is_abba($hypernet)) {
            return false;
        }
    }

    return true;
}

function is_abba($seq)
{
    if (strlen($seq) < 4) {
        return false;
    }

    for ($i = 0; $i < strlen($seq) - 3; $i++) {
        $cur_pair = substr($seq, $i, 2);
        $next_pair = substr($seq, $i + 2, 2);

        if (
            $cur_pair[0] !== $cur_pair[1]
            and $cur_pair[0] === $next_pair[1] and $cur_pair[1] === $next_pair[0]
        ) {
            return true;
        }
    }

    return false;
}

function supports_ssl($ip_obj)
{
    $abas_in_supernet = [];
    foreach ($ip_obj["supernet"] as $supernet) {
        $res = is_aba($supernet);
        if (!empty($res)) {
            $abas_in_supernet = array_merge($abas_in_supernet, $res);
        }
    }
    if (empty($abas_in_supernet)) {
        return false;
    }

    foreach ($ip_obj["hypernet"] as $hypernet) {
        $res = is_aba($hypernet);
        if (!empty($res)) {
            foreach ($res as $bab) {
                $aba = $bab[1] . $bab[0] . $bab[1];

                if (in_array($aba, $abas_in_supernet)) {
                    return true;
                }
            }
        }
    }

    return false;
}

function is_aba($seq)
{
    if (strlen($seq) < 3) {
        return [];
    }

    $seqs = [];
    for ($i = 0; $i < strlen($seq) - 2; $i++) {
        if (
            $seq[$i] === $seq[$i + 2] and $seq[$i] !== $seq[$i + 1]
        ) {
            array_push($seqs, substr($seq, $i, 3));
        }
    }

    return $seqs;
}

###

function part_1($input)
{
    $count = 0;

    foreach ($input as $ip_obj) {
        if (supports_tls($ip_obj)) {
            $count++;
        }
    }

    return $count;
}

function part_2($input)
{
    $count = 0;

    foreach ($input as $ip_obj) {
        if (supports_ssl($ip_obj)) {
            $count++;
        }
    }

    return $count;
}

###

assert(supports_tls(parse_ip("abba[mnop]qrst")));
assert(!supports_tls(parse_ip("abcd[bddb]xyyx")));
assert(!supports_tls(parse_ip("aaaa[qwer]tyui")));
assert(supports_tls(parse_ip("ioxxoj[asdfgh]zxcvbn")));
assert(supports_ssl(parse_ip("aba[bab]xyz")));
assert(!supports_ssl(parse_ip("xyx[xyx]xyx")));
assert(supports_ssl(parse_ip("aaa[kek]eke")));
assert(supports_ssl(parse_ip("zazbz[bzb]cdb")));

###

$file = fopen("day-7/input.txt", "r") or die("Could not open file.");
$lines = explode("\n", fread($file, filesize("day-7/input.txt")));
fclose($file);

$input = array_map(fn ($line) => parse_ip($line), $lines);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
