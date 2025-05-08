<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace VHUG\DLHGeoCodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;


class DLHGeoCodeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }



}
