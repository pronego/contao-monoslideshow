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
namespace pronego\MonoslideshowBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MonoslideshowExtension extends Extension {


    public function load(array $mergedConfig, ContainerBuilder $container): void {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        $loader->load('controller.yml');
    }
}