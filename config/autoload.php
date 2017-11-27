<?php

/*
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

/**
 * Register the classes.
 */
ClassLoader::addClasses([
    // Classes
    'delahaye\GeoCode' => 'system/modules/dlh_geocode/classes/GeoCode.php',

    // Models
    'delahaye\GeoCodeModel' => 'system/modules/dlh_geocode/models/GeoCodeModel.php',
]);
