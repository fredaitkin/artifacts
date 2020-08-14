<?php

/**
 * Returns player information
 */

namespace Artifacts\Interfaces;

interface PlayerInterface {

    public function getMostHomeRuns();
    public function getMostRBIs();
    public function getBestAverage();
    public function getMostWins();
    public function getBestERA();

}