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
namespace pronego\MonoslideshowBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\File;
use Contao\FilesModel;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Contao\Validator;
use pronego\MonoslideshowBundle\Controller\MonoslideshowTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @FrontendModule("monoslideshow",
 *   category="miscellaneous",
 *   template="mod_monoslideshow",
 * )
 */
class MonoslideshowController extends AbstractFrontendModuleController {

    use MonoslideshowTrait;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var Contao\CoreBundle\InsertTag\InsertTagParser
     */
    private $insertTagParser;

    /**
     * @var Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;


    public function __construct( string $rootDir, InsertTagParser $insertTagParser, TranslatorInterface $translator ) {

        $this->rootDir = $rootDir;
        $this->insertTagParser = $insertTagParser;
        $this->translator = $translator;
    }


    /**
     * {@inheritdoc}
     */
    protected function getResponse( FragmentTemplate $template, ModuleModel $model, Request $request ): Response {

        $monoslideshowMedia = StringUtil::deserialize($model->monoslideshowMedia, true);
        $monoslideshowSize = StringUtil::deserialize($model->monoslideshowSize, true);

        // Set sizes
        $intWidth = $monoslideshowSize[0];
        $intHeight = $monoslideshowSize[1];

        // Return if there are no files
        if( empty($monoslideshowMedia) ) {
            return new Response('');
        }

        // Get the file entries from the database
        $objFiles = FilesModel::findMultipleByUuids($monoslideshowMedia);

        if( $objFiles === null ) {
            if( !Validator::isUuid($monoslideshowMedia[0]) ) {
                return '<p class="error">'.$this->translator->trans('ERR.version2format', [], 'contao_default').'</p>';
            }

            return new Response('');
        }

        $strXml = $this->generateXml($objFiles, $model);
        $strShowLogo = $model->monoslideshowShowLogo ? "true" : "false";
        $strParameters = ", {showLogo: " . $strShowLogo . "}";

        // Add javascript files
        $GLOBALS['TL_JAVASCRIPT'][] = $this->getMssPath() . 'monoslideshow.js';

        // Add javascript
        $GLOBALS['TL_BODY'][] = "<script>
            var mss = new Monoslideshow('monoslideshow-" . $model->id . "'" . $strParameters . ");
            mss.loadXMLString('" . $strXml . "');
        </script>";

        // Add css
        $GLOBALS['TL_HEAD'][] = "<style>
            #monoslideshow-" .  $model->id . "
            {
                background-color: #" . ($model->monoslideshowBgColor?:'000000') . ";
                width: " . $intWidth . "px;
                height: " . $intHeight . "px;
            }
        </style>";

        return $template->getResponse();
    }
}