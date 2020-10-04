<?php

/**
 * Returns player information
 */

namespace Artifacts\Player;

interface PlayerInterface {

    public function getMostHomeRuns();
    public function getMostRBIs();
    public function getBestAverage();
    public function getMostWins();
    public function getBestERA();

}