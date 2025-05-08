<?php
use Contao\DC_Table;
use Contoa\Date;
use Contao\Config;
use Contao\Backend;
/*
 * Copyright (c) 2025 Von Helden Und Gestalten GmbH
 *
 * @license LGPL-3.0+
 */

/**
 * Table tl_log.
 */
$GLOBALS['TL_DCA']['tl_dlh_geocode'] = [
    // Config
    'config' => [
        'dataContainer' => DC_Table::class,
        'closed' => true,
        'notEditable' => true,
        'notCopyable' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'address' => 'index',
                'address,language,region' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['address ASC'],
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['address', 'region', 'tstamp'],
            'format' => '%s, %s <span style="color:#999;padding-right:3px">[%s]</span>',
            'label_callback' => ['tl_dlh_geocode', 'generateLabel'],
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['tstamp'],
            'filter' => true,
            'sorting' => true,
            'flag' => 6,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'address' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['address'],
            'flag' => 1,
            'filter' => true,
            'sorting' => true,
            'search' => true,
            'reference' => &$GLOBALS['TL_LANG']['tl_log'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'language' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['language'],
            'filter' => true,
            'sorting' => true,
            'search' => true,
            'sql' => "varchar(5) NOT NULL default ''",
        ],
        'region' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['region'],
            'filter' => true,
            'sorting' => true,
            'search' => true,
            'sql' => "varchar(2) NOT NULL default ''",
        ],
        'latitude' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['latitude'],
            'search' => true,
            'sql' => "float(10,6) unsigned NOT NULL default '0.000000'",
        ],
        'longitude' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['longitude'],
            'search' => true,
            'sql' => "float(10,6) unsigned NOT NULL default '0.000000'",
        ],
        'bounds' => [
            'label' => &$GLOBALS['TL_LANG']['tl_dlh_geocode']['bounds'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
    ],
];

class tl_dlh_geocode
{
    /**
     * Generate the entries label.
     *
     * @param array  $row
     * @param string $label
     *
     * @return string
     */
    public function generateLabel($row, $label)
    {
        $country = System::getCountries()[$row['region']];

        return '<div class="ellipsis">'.sprintf(
                '%s, %s <span style="color:#999;padding-right:3px">[%s]</span>',
                $row['address'], $country, Date::parse(Config::get('datimFormat'), $row['tstamp'])
            ).'</div>';
    }
}
