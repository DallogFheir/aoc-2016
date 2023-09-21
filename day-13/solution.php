<?php

class MinPQ extends SplPriorityQueue
{
    public function compare($priority1, $priority2): int
    {
        return $priority1 > $priority2 ? -1 : 1;
    }
}

class Node
{
    function __construct($coords, $steps)
    {
        $this->coords = $coords;
        $this->steps = $steps;
    }
}

function is_wall($coords, $favorite_number)
{
    $x = $coords[0];
    $y = $coords[1];

    $sum = $x * $x + 3 * $x + 2 * $x * $y + $y + $y * $y + $favorite_number;
    $bin = decbin($sum);

    $count = 0;
    foreach (str_split($bin) as $digit) {
        if ($digit === "1") {
            $count++;
        }
    }

    return $count % 2 === 1;
}

function get_to_goal($favorite_number, $goal)
{
    $pq = new MinPQ();
    $start = new Node([1, 1], 0);
    $pq->insert($start, $start->steps);

    $already_visited = [];
    while (true) {
        $node = $pq->extract();

        if ($node->coords[0] === $goal[0] and $node->coords[1] === $goal[1]) {
            return $node->steps;
        }

        $addends = [[-1, 0], [0, 1], [1, 0], [0, -1]];
        foreach ($addends as $addend) {
            $new_coords = [$node->coords[0] + $addend[0], $node->coords[1] + $addend[1]];
            $is_wall = is_wall($new_coords, $favorite_number);

            if (!$is_wall and !in_array($new_coords, $already_visited) and $new_coords[0] >= 0 and $new_coords[1] >= 0) {
                $new_node = new Node($new_coords, $node->steps + 1);
                $pq->insert($new_node, $new_node->steps);
            }
        }

        array_push($already_visited, $node->coords);
    }
}

###

function part_1($input)
{
    return get_to_goal($input, [31, 39]);
}

function part_2($input)
{
    $visited = [];
    $queue = new SplQueue();
    $start = new Node([1, 1], 0);
    $queue->enqueue($start);

    while (!$queue->isEmpty()) {
        $node = $queue->dequeue();

        if ($node->steps < 50) {
            $addends = [[-1, 0], [0, 1], [1, 0], [0, -1]];
            foreach ($addends as $addend) {
                $new_coords = [$node->coords[0] + $addend[0], $node->coords[1] + $addend[1]];
                $is_wall = is_wall($new_coords, $input);

                if (!$is_wall and !in_array($new_coords, $visited) and $new_coords[0] >= 0 and $new_coords[1] >= 0) {
                    $queue->enqueue(new Node($new_coords, $node->steps + 1));
                }
            }
        }

        array_push($visited, $node->coords);
    }

    $visited_set = [];
    foreach ($visited as $coords) {
        if (!in_array($coords, $visited_set)) {
            array_push($visited_set, $coords);
        }
    }

    return count($visited_set);
}

###

assert(get_to_goal(10, [7, 4]) === 11);

###

$input = 1358;

###

echo "Part 1: " . part_1($input) . "\n";
echo "Part 2: " . part_2($input);
