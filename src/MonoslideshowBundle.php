<?php

/**
 * Monoslideshow wrapper for Contao Open Source CMS, 
 * separate Monoslideshow license is required.
 *
 * @author    Daniel Ritter (DESIGNR)
 * @author    Dr. Manuel Lamotte-Schubert <mls@pronego.com>
 * @license   LGPL-3.0-or-later
 * @copyright 2013, DESIGNR - https://www.designr.ch
 * @copyright 2024, PRONEGO - https://www.pronego.com
 */
namespace pronego\MonoslideshowBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MonoslideshowBundle extends Bundle {

    public function getPath(): string {

        return \dirname(__DIR__);
    }
}