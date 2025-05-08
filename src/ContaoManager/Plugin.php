<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace VHUG\DLHGeoCodeBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use VHUG\DLHGeoCodeBundle\DLHGeoCodeBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;



/**
 * @internal
 */
class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(DLHGeoCodeBundle::class)
            ->setLoadAfter([
                ContaoCoreBundle::class,
            ])
        ];
    }

}
