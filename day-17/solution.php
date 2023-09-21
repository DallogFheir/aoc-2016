<?php

class Node
{
    function __construct($coords, $path)
    {
        $this->coords = $coords;
        $this->path = $path;
    }

    function get_path_length()
    {
        return strlen($this->path);
    }
}

class MinPQ extends SplPriorityQueue
{
    public function compare($priority1, $priority2): int
    {
        return $priority1 > $priority2 ? -1 : 1;
    }
}

function find_shortest_path($password)
{
    $goal = [3, 3];
    $pq = new MinPQ();
    $start = new Node([0, 0], "");
    $pq->insert($start, $start->get_path_length());

    while (true) {
        $node = $pq->extract();

        if ($node->coords[0] === $goal[0] and $node->coords[1] === $goal[1]) {
            return $node->path;
        }

        $door_hashes = substr(md5($password . $node->path), 0, 4);
        $doors = [];
        $dirs = ["U", "D", "L", "R"];
        for ($i = 0; $i < strlen($door_hashes); $i++) {
            $char = $door_hashes[$i];

            if (!is_numeric($char) and $char !== "a") {
                array_push($doors, $dirs[$i]);
            }
        }

        $dir_trans = [
            "U" => [-1, 0],
            "D" => [1, 0],
            "L" => [0, -1],
            "R" => [0, 1]
        ];
        foreach ($doors as $door) {
            $door_coords = $dir_trans[$door];
            $new_coords = [$node->coords[0] + $door_coords[0], $node->coords[1] + $door_coords[1]];

            if ($new_coords[0] >= 0 and $new_coords[0] <= 3 and $new_coords[1] >= 0 and $new_coords[1] <= 3) {
                $new_node = new Node($new_coords, $node->path . $door);
                $pq->insert($new_node, $new_node->get_path_length());
            }
        }
    }
}

function find_longest_path($password)
{
    $goal = [3, 3];
    $start = new Node([0, 0], "");
    $queue = [$start];

    $longest_path = 0;

    while (true) {
        if (empty($queue)) {
            break;
        }

        $node = array_pop($queue);

        if ($node->coords[0] === $goal[0] and $node->coords[1] === $goal[1]) {
            if ($node->get_path_length() > $longest_path) {
                $longest_path = $node->get_path_length();
            }
            continue;
        }

        $door_hashes = substr(md5($password . $node->path), 0, 4);
        $doors = [];
        $dirs = ["U", "D", "L", "R"];
        for ($i = 0; $i < strlen($door_hashes); $i++) {
            $char = $door_hashes[$i];

            if (!is_numeric($char) and $char !== "a") {
                array_push($doors, $dirs[$i]);
            }
        }

        $dir_trans = [
            "U" => [-1, 0],
            "D" => [1, 0],
            "L" => [0, -1],
            "R" => [0, 1]
        ];
        foreach ($doors as $door) {
            $door_coords = $dir_trans[$door];
            $new_coords = [$node->coords[0] + $door_coords[0], $node->coords[1] + $door_coords[1]];

            if ($new_coords[0] >= 0 and $new_coords[0] <= 3 and $new_coords[1] >= 0 and $new_coords[1] <= 3) {
                $new_node = new Node($new_coords, $node->path . $door);
                array_push($queue, $new_node);
            }
        }
    }

    return $longest_path;
}

###

function part_1($input)
{
    return find_shortest_path($input);
}

function part_2($input)
{
    return find_longest_path($input);
}

###

assert(find_shortest_path("ihgpwlah") === "DDRRRD");
assert(find_shortest_path("kglvqrro") === "DDUDRLRRUDRD");
assert(find_shortest_path("ulqzkmiv") === "DRURDRUDDLLDLUURRDULRLDUUDDDRR");
assert(find_longest_path("ihgpwlah") === 370);
assert(find_longest_path("kglvqrro") === 492);
assert(find_longest_path("ulqzkmiv") === 830);

###

$input = "pgflpeqp";

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
