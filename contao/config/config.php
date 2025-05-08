<?php

/*
 * Copyright (c) 2025 Von Helden Und Gestalten GmbH
 *
 * @license LGPL-3.0+
 */

/**
 * Back end modules.
 */
$GLOBALS['BE_MOD']['system']['dlh_geocode'] = [
    'tables' => ['tl_dlh_geocode'],
    'icon' => 'system/modules/dlh_geocode/assets/icon.gif',
];

/*
 * Register models
 */
$GLOBALS['TL_MODELS']['tl_dlh_geocode'] = '\\Vhug\\GeoCodeModel';