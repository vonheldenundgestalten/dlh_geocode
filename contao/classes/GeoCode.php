<?php

/*
 * Copyright (c) 2025 Von Helden Und Gestalten GmbH
 *
 * @license LGPL-3.0+
 */

/**
 * Run in a custom namespace, so the class can be replaced.
 */

namespace VHUG\DLHGeoCodeBundle;

/**
 * Class GeoCode.
 *
 * Get geocoordinates for a given address by Google
 *
 * @copyright  2014 de la Haye
 * @author     Christian de la Haye
 */
class GeoCode
{
    /**
     * Get instance.
     *
     * @return object
     */
    protected static $instance;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get geo coordinates from an address.
     *
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public static function getCoordinates($strAddress, $strCountry = 'de', $strLang = 'de', $key = null)
    {
        $strValue = '';

        if (null === $key) {
            $key = \Config::get('dlh_googlemaps_apikey');
        }

        if ($strAddress) {
            $arrCoords = self::getInstance()->geoCode($strAddress, $strLang, $strCountry, null, $key);

            if ($arrCoords) {
                $strValue = $arrCoords['lat'].','.$arrCoords['lng'];
            }
        }

        return ',' === $strValue ? '' : $strValue;
    }

    /**
     * Geo code using file_get_contents (requires allow_url_fopen=1).
     *
     * @param string $url The google maps geocode url
     *
     * @return array|null The geo information or null if curl is not installed or curl options not supported
     */
    public static function geoCodeUrl($url)
    {
        if (!ini_get('allow_url_fopen')) {
            return null;
        }

        $geo = json_decode(file_get_contents($url), true);

        return $geo;
    }

    /**
     * Geo code using curl.
     *
     * @param string $url The google maps geocode url
     *
     * @return array|null The geo information or null if curl is not installed or curl options not supported
     */
    public static function geoCodeCurl($url)
    {
        if (!function_exists('curl_init')) {
            return null;
        }

        $curl = curl_init();

        if (!$curl) {
            return null;
        }

        if (!curl_setopt($curl, CURLOPT_URL, $url) || !curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1) || !curl_setopt($curl, CURLOPT_HEADER, 0)) {
            return null;
        }

        $data = curl_exec($curl);
        curl_close($curl);
        $geo = json_decode($data, true);

        return $geo;
    }

    /**
     * Provides a method that can be used for determining coordinates for a given address
     * via DCA-callback in other modules, e.g. metamodels.
     *
     * @return string
     */
    public function callbackCoordinates()
    {
        $strAction = $GLOBALS['dlh_geocode']['address']['fieldformat']['action'];
        $strIdParam = $GLOBALS['dlh_geocode']['address']['fieldformat']['name'];

        $arrAddress = [];

        foreach ($GLOBALS['dlh_geocode']['address']['fields_address'] as $strField) {
            if ($strIdParam) {
                if (!\Input::$strAction($strIdParam)) {
                    // how Metamodels do it on creation, otherwise save twice to get coords
                    $arrAddress[] = 'create' === \Input::get('act') ? \Input::post(sprintf($strField, 'b')) : '';
                } else {
                    $arrAddress[] = \Input::post(sprintf($strField, \Input::$strAction($strIdParam)));
                }
            } else {
                $arrAddress[] = \Input::post($strField);
            }
        }

        if (!trim(implode('', $arrAddress))) {
            $geocode = \Input::post(sprintf($GLOBALS['dlh_geocode']['address']['field_geocode'], \Input::$strAction($strIdParam)));

            return $geocode;
        }

        $strAddress = vsprintf($GLOBALS['dlh_geocode']['address']['format'], $arrAddress);
        $strCountry = $GLOBALS['dlh_geocode']['address']['field_country'];
        $strLang = $GLOBALS['dlh_geocode']['address']['field_language'];

        return self::getCoordinates($strAddress, $strCountry, $strLang);
    }

    /**
     * Get geo coordinates from address, thanks to Oliver Hoff <oliver@hofff.com>.
     *
     * @param array
     * @param string
     * @param string
     * @param array
     * @param string
     *
     * @return array|null
     */
    protected function geoCode($address, $language = 'de', $region = 'de', array $arrBounds = null, $key = null)
    {
        if (is_array($address)) {
            $address = implode(' ', $address);
        }

        $address = trim($address);

        if (!strlen($address) || !strlen($language)) {
            return null;
        }

        if (null !== $region && !strlen($region)) {
            return null;
        }

        $language = strtolower($language);
        $region = strtolower($region);

        if (null !== $arrBounds) {
            if (!is_array($arrBounds) || !is_array($arrBounds['tl']) || !is_array($arrBounds['br'])
                || !is_numeric($arrBounds['tl']['lat'])
                || !is_numeric($arrBounds['tl']['lng'])
                || !is_numeric($arrBounds['br']['lat'])
                || !is_numeric($arrBounds['br']['lng'])
            ) {
                return null;
            }
        }

        $bounds = $arrBounds ? implode(',', $arrBounds['tl']).'|'.implode(',', $arrBounds['br']) : '';

        if (null !== ($model = GeoCodeModel::findByAddress($address, $language, $region, $bounds))) {
            $model = $model->current();

            return [
                'lat' => $model->latitude,
                'lng' => $model->longitude,
            ];
        }

        $url = sprintf(
            'https://maps.googleapis.com/maps/api/geocode/json?address=%s&language=%s&region=%s&bounds=%s',
            urlencode($address),
            urlencode($language),
            strlen($region) ? urlencode($region) : '',
            $arrBounds ? implode(',', $arrBounds['tl']).'|'.implode(',', $arrBounds['br']) : ''
        );

        if (null !== $key) {
            $url .= '&key='.$key;
        }

        $geo = static::geoCodeUrl($url) ?: static::geoCodeCurl($url);
        self::errorHandler($geo['status'], $geo['error_message']);

        if (empty($geo) || 'OK' !== $geo['status']) {
            return null;
        }

        // save location in database
        $model = new GeoCodeModel();
        $model->tstamp = time();
        $model->address = $address;
        $model->language = $language;
        $model->region = $region;
        $model->latitude = $geo['results'][0]['geometry']['location']['lat'];
        $model->longitude = $geo['results'][0]['geometry']['location']['lng'];
        $model->bounds = $bounds;
        $model->save();

        return [
            'lat' => $model->latitude,
            'lng' => $model->longitude,
        ];
    }

    /**
     * handle the google maps api error states and show them in the backend.
     *
     * @param $strStatus
     * @param $strMessage
     */
    protected static function errorHandler($strStatus, $strMessage)
    {
        if (!$strStatus || 'OK' === $strStatus) {
            return;
        }

        $arrErrorMessages = ['ZERO_RESULTS', 'OVER_QUERY_LIMIT', 'REQUEST_DENIED', 'INVALID_REQUEST'];

        if (in_array($strStatus, $arrErrorMessages, true)) {
            \Message::addError($strStatus.($strMessage ? ' ('.$strMessage.')' : ''));
        }
    }
}
