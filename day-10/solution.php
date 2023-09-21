<?php

class Bot
{
    function __construct($number)
    {
        $this->number = $number;
        $this->values = [];
    }

    function get_low_value()
    {
        return $this->values[0];
    }

    function get_high_value()
    {
        return $this->values[1];
    }

    function set_low($number, $type)
    {
        $this->low = ["number" => $number, "type" => $type];
    }

    function set_high($number, $type)
    {
        $this->high = ["number" => $number, "type" => $type];
    }

    function add_value($value)
    {
        array_push($this->values, $value);
        sort($this->values);
    }

    function is_complete()
    {
        return count($this->values) === 2;
    }
}

function simulate_factory($instructions)
{
    $bots = [];

    foreach ($instructions as $line) {
        $value_to_bot = preg_match("/value (\d+) goes to bot (\d+)/", $line, $matches);
        if ($value_to_bot) {
            $value = (int) $matches[1];
            $bot = (int) $matches[2];

            if (!in_array($bot, array_keys($bots))) {
                $bots[$bot] = new Bot($bot);
            }
            $bots[$bot]->add_value($value);
        } else {
            preg_match("/bot (\d+) gives low to (bot|output) (\d+) and high to (bot|output) (\d+)/", $line, $matches);
            $bot = (int) $matches[1];
            $low_type = $matches[2];
            $low = (int) $matches[3];
            $high_type = $matches[4];
            $high = (int) $matches[5];

            if (!in_array($bot, array_keys($bots))) {
                $bots[$bot] = new Bot($bot);
            }
            $bots[$bot]->set_low($low, $low_type);
            $bots[$bot]->set_high($high, $high_type);
        }
    }

    $already_seen = [];
    $outputs = [];
    while (count(array_filter($bots, fn ($bot) => !$bot->is_complete())) !== 0) {
        foreach ($bots as $bot) {
            if ($bot->is_complete() and !in_array($bot, $already_seen)) {
                $number = $bot->low["number"];
                if ($bot->low["type"] === "bot") {
                    $bots[$number]->add_value($bot->get_low_value());
                } else {
                    if (!in_array($number, $outputs)) {
                        $outputs[$number] = [];
                    }
                    array_push($outputs[$number], $bot->get_low_value());
                }

                $number = $bot->high["number"];
                if ($bot->high["type"] === "bot") {
                    $bots[$number]->add_value($bot->get_high_value());
                } else {
                    if (!in_array($number, $outputs)) {
                        $outputs[$number] = [];
                    }
                    array_push($outputs[$number], $bot->get_high_value());
                }

                array_push($already_seen, $bot);
            }
        }
    }

    return ["bots" => $bots, "outputs" => $outputs];
}

function get_comparing_robot($instructions, $value_low, $value_high)
{
    $result = simulate_factory($instructions);

    foreach ($result["bots"] as $bot) {
        $values = $bot->values;
        if ($values[0] === $value_low and $values[1] === $value_high) {
            return $bot->number;
        }
    }
}

###

function part_1($input)
{
    return get_comparing_robot($input, 17, 61);
}

function part_2($input)
{
    $result = simulate_factory($input);

    $product = 1;
    $outputs = [$result["outputs"][0], $result["outputs"][1], $result["outputs"][2]];
    foreach ($outputs as $output) {
        foreach (array_values($output) as $value) {
            $product *= $value;
        }
    }

    return $product;
}

###

$test_input = explode("\n", "value 5 goes to bot 2
bot 2 gives low to bot 1 and high to bot 0
value 3 goes to bot 1
bot 1 gives low to output 1 and high to bot 0
bot 0 gives low to output 2 and high to output 0
value 2 goes to bot 2");
assert(get_comparing_robot($test_input, 2, 5) === 2);

###

$file = fopen("day-10/input.txt", "r") or die("Could not open file.");
$input = explode("\n", fread($file, filesize("day-10/input.txt")));
fclose($file);

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
