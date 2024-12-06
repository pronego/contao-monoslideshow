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
use Contao\Controller;
use Contao\System;

/**
 * Add palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['monoslideshow'] = '{type_legend},type,headline;{source_legend},monoslideshowMedia,monoslideshowSortBy;{player_legend},monoslideshowSize,monoslideshowBgColor,monoslideshowShowTitle,monoslideshowShowDescription,monoslideshowShowLogo;{template_legend:hide},customTpl,monoslideshowTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowMedia'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['multiSRC']
,   'exclude'                 => true
,   'inputType'               => 'fileTree'
,   'eval'                    => ['mandatory'=>true, 'multiple'=>true, 'fieldType'=>'checkbox', 'files'=>true, 'isGallery'=>true, 'extensions'=> System::getContainer()->getParameter('contao.image.valid_extensions')+['mp4', 'ogv', 'ogg', 'webm']]
,   'sql'                     => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowSortBy'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortBy']
,   'exclude'                 => true
,   'inputType'               => 'select'
,   'options'                 => ['name_asc', 'name_desc', 'random']
,   'reference'               => &$GLOBALS['TL_LANG']['tl_content']
,   'eval'                    => ['tl_class'=>'w50']
,   'sql'                     => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowSize'] = [
    'exclude'                 => true
,   'inputType'               => 'text'
,   'eval'                    => ['mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50']
,   'sql'                     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowBgColor'] = [
    'inputType'               => 'text'
,   'eval'                    => ['maxlength'=>6, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard']
,   'sql'                     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowShowTitle'] = [
    'exclude'                 => true
,   'inputType'               => 'checkbox'
,   'eval'                    => ['tl_class'=>'w50']
,   'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowShowDescription'] = [
    'exclude'                 => true
,   'inputType'               => 'checkbox'
,   'eval'                    => ['tl_class'=>'w50']
,   'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowShowLogo'] = [
    'exclude'                 => true
,   'inputType'               => 'checkbox'
,   'eval'                    => ['tl_class'=>'clr']
,   'sql'                     => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['monoslideshowTemplate'] = [
    'exclude'                 => true
,   'inputType'               => 'select'
,   'default'                 => 'monoslideshow_default'
,   'options_callback' => static function () {
        return Controller::getTemplateGroup('monoslideshow_');
    }
,   'eval'                    => ['tl_class'=>'w50']
,   'sql'                     => "varchar(255) NOT NULL default ''"
];