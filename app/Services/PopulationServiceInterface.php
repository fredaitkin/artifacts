<?php

/**
 * Returns population services such as population statistics.
 * 
 * The Population Service does not need to be a service or have an interface,
 * this has been done was a way to practice dependency injection
 */

namespace Artifacts\Services;

interface PopulationServiceInterface {

    public function getUSStatistics();

}
