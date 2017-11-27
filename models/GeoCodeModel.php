<?php

/*
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace delahaye;

/**
 * Reads and writes news.
 *
 * @property int $id
 * @property int $tstamp
 * @property string $address
 * @property string $language
 * @property string $region
 * @property float $latitude
 * @property float $longitude
 * @property string $bounds
 * @property array $raw
 *
 * @method static GeoCodeModel|null findById($id, array $opt = [])
 * @method static GeoCodeModel|null findByPk($id, array $opt = [])
 * @method static GeoCodeModel|null findOneBy($col, $val, array $opt = [])
 * @method static GeoCodeModel|null findOneByTstamp($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByAddress($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByLanguage($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByRegion($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByLatitude($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByLongitude($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByBounds($val, array $opt = [])
 * @method static GeoCodeModel|null findOneByRaw($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByPid($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByTstamp($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByLanguage($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByRegion($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByLatitude($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByLongitude($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByBounds($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findByRaw($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findMultipleByIds($val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findBy($col, $val, array $opt = [])
 * @method static \Model\Collection|GeoCodeModel[]|GeoCodeModel|null findAll(array $opt = [])
 * @method static integer countById($id, array $opt = [])
 * @method static integer countByPid($val, array $opt = [])
 * @method static integer countByTstamp($val, array $opt = [])
 * @method static integer countByAddress($val, array $opt = [])
 * @method static integer countByLanguage($val, array $opt = [])
 * @method static integer countByRegion($val, array $opt = [])
 * @method static integer countByLatitude($val, array $opt = [])
 * @method static integer countByLongitude($val, array $opt = [])
 * @method static integer countByBounds($val, array $opt = [])
 * @method static integer countByRaw($val, array $opt = [])
 */
class GeoCodeModel extends \Contao\Model
{
    protected static $strTable = 'tl_dlh_geocode';

    /**
     * Find geo information by address.
     *
     * @param string $address  The address string
     * @param string $region   The region code, specified as a ccTLD ("top-level domain") two-character value
     * @param string $language The language in which to return results
     * @param string $bounds   The bounding box of the viewport within which to bias geocode results more prominently (top left lat & lon|bottom right lat & lon, e.g. 34.172684,-118.604794|34.236144,-118.500938)
     * @param array  $options  Additional query options
     *
     * @return \Contao\Model\Collection|\delahaye\GeoCodeModel[]|\delahaye\GeoCodeModel|null A collection of models or null if there are no geo information for the given address
     */
    public static function findByAddress(string $address, string $region = 'de', string $language = 'de', string $bounds = '', array $options = [])
    {
        $t = static::$strTable;
        $columns[] = "$t.address = ? AND $t.region = ? AND $t.language = ?";
        $values = [$address, $region, $language];

        if ($bounds) {
            $columns[] = "$t.bounds = ?";
            $values[] = $bounds;
        }

        return static::findBy($columns, $values, $options);
    }
}
