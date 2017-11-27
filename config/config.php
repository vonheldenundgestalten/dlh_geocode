<?php

/*
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

/**
 * Back end modules.
 */
array_insert($GLOBALS['BE_MOD']['system'], 1, [
    'dlh_geocode' => [
        'tables' => ['tl_dlh_geocode'],
        'icon' => 'system/modules/dlh_geocode/assets/icon.gif',
    ],
]);

/*
 * Register models
 */

$GLOBALS['TL_MODELS']['tl_dlh_geocode'] = '\\delahaye\\GeoCodeModel';
