<?php

try {
    throw new \LogicException();
} catch (\Throwable $e) {
    if ($e->getLine() > 1) {
        echo $e;
    } else {
        dd($e);
    }
}
