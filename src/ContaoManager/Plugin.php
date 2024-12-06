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
namespace pronego\MonoslideshowBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use pronego\MonoslideshowBundle\MonoslideshowBundle;


class Plugin implements BundlePluginInterface {

    /**
     * {@inheritdoc}
     */
    public function getBundles( ParserInterface $parser ): array {

        return [
            BundleConfig::create(MonoslideshowBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}