<?php

namespace Artifacts\Baseball\Player;

/**
 * Returns player information
 */

interface PlayerInterface {

    public function getMostHomeRuns();
    public function getMostRBIs();
    public function getBestAverage();
    public function getMostWins();
    public function getBestERA();
    public function getAllPlayers();
    public function getTabulatedPlayers();
    public function getPlayerByID(int $id);
    public function getPlayersByIDs(array $ids);
    public function getPlayerByLink(string $link);
    public function create(array $fields);
    public function updateCreate(array $keys, array $fields);
    public function search(string $q);
    public function deleteByID(int $id);
    public function getPlayerCityCount();

}