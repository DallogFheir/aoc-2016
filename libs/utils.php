<?php

function array_find($haystack, $callback)
{
    foreach ($haystack as $el) {
        if ($callback($el)) {
            return $el;
        }
    }

    return null;
}

function array_find_index($haystack, $callback)
{
    foreach ($haystack as $idx => $el) {
        if ($callback($el)) {
            return $idx;
        }
    }

    return null;
}
