<?php

namespace glluch\phaseExpansion;

require_once __DIR__ . "/Interval.php";

/**
 * Description of Positions
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class Positions {

    protected $positions = [];

    public function add($start, $end) {
        $this->positions[$start] = $end;
    }

    public function delete($start) {
        if (isset($this->positions[$start])) {
            unset($this->positions[$start]);
            return true;
        } else {
            return false;
        }
    }

    public function isEmpty() {
        return (\count($this->positions) < 1);
    }

}
