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
namespace pronego\MonoslideshowBundle\Controller;

use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\File;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\FrontendTemplate;
use Contao\Model;
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


trait MonoslideshowTrait {

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


    /**
     * Generate the xml data
     *
     * @var string
     */
    protected function generateXml( Collection $objFiles, Model $model ): string {

        $objPage = $this->getPageModel();

        $basename_natcasecmp = function($a, $b) {
            return strnatcasecmp(basename($a), basename($b));
        };
        $basename_natcasercmp = function($a, $b) {
            return - strnatcasecmp(basename($a), basename($b));
        };

        $arrImages = [];
        $strTag = 'image';

        // Get all images
        foreach( $objFiles as $objFile ) {

            // Continue if the files has been processed or does not exist
            if( isset($arrImages[$objFile->path]) || !file_exists($this->rootDir . '/' . $objFile->path) ) {
                continue;
            }

            // Single files
            if( $objFile->type == 'file' ) {

                $objFile = new File($objFile->path);

                // XML Tag
                if( $objFile->extension == 'mp4' || $objFile->extension == 'ogv' || $objFile->extension == 'webm' ) {
                    $strTag = 'video';
                } else if( $objFile->isGdImage ) {
                    $strTag = 'image';
                }

                $arrMeta = Frontend::getMetaData($objFile->meta, $objPage->language);

                if( empty($arrMeta) && $objPage->rootFallbackLanguage !== null ) {
                    $arrMeta = Frontend::getMetaData($objFile->meta, $objPage->rootFallbackLanguage);
                }

                // Use the file name as title if none is given
                if( empty($arrMeta['title']) ) {
                    $arrMeta['title'] = StringUtil::specialchars($objFile->basename);
                }

                $strPath = $this->getPath($objFile->path);

                // Add the image
                $arrImages[$strPath][$objFile->path][] = [
                    'id'            => $objFile->id,
                    'tag'           => $strTag,
                    'name'          => $objFile->basename,
                    'path'          => $strPath,
                    'extension'     => $objFile->extension,
                    'type'          => $objFile->extension != 'ogv' ? $objFile->extension : 'ogg',
                    'singleSRC'     => $objFile->path,
                    'title'         => $this->insertTagParser->replaceInline($arrMeta['title'] ?? ''),
                    'link'          => $this->insertTagParser->replaceInline($arrMeta['link'] ?? ''),
                    'description'   => $this->insertTagParser->replaceInline($arrMeta['caption'] ?? '')
                ];

            // Folders
            } else {

                $objSubfiles = FilesModel::findByPid($objFile->uuid, ['order' => 'name']);

                if( $objSubfiles === null ) {
                    continue;
                }

                foreach( $objSubfiles as $objSubfile ) {

                    // Skip subfolders
                    if ($objSubfile->type == 'folder')
                    {
                        continue;
                    }

                    $objFile = new File($objSubfile->path);

                    // XML Tag
                    if( $objFile->extension == 'mp4' || $objFile->extension == 'ogv' || $objFile->extension == 'webm' ) {
                        $strTag = 'video';
                    } else if( $objFile->isGdImage ) {
                        $strTag = 'image';
                    }

                    $arrMeta = Frontend::getMetaData($objFile->meta, $objPage->language);

                    if( empty($arrMeta) && $objPage->rootFallbackLanguage !== null ) {
                        $arrMeta = Frontend::getMetaData($objFile->meta, $objPage->rootFallbackLanguage);
                    }

                    // Use the file name as title if none is given
                    if( empty($arrMeta['title']) ) {
                        $arrMeta['title'] = StringUtil::specialchars($objFile->basename);
                    }

                    $strPath = $this->getPath($objFile->path);

                    // Add the image
                    $arrImages[$strPath][$objFile->path][] = [
                        'id'            => $objFile->id,
                        'tag'           => $strTag,
                        'name'          => $objFile->basename,
                        'path'          => $strPath,
                        'extension'     => $objFile->extension,
                        'type'          => $objFile->extension != 'ogv' ? $objFile->extension : 'ogg',
                        'singleSRC'     => $objFile->path,
                        'title'         => $this->insertTagParser->replaceInline($arrMeta['title'] ?? ''),
                        'link'          => $this->insertTagParser->replaceInline($arrMeta['link'] ?? ''),
                        'description'   => $this->insertTagParser->replaceInline($arrMeta['caption'] ?? '')
                    ];
                }
            }
        }

        // Sort array
        switch( $model->monoslideshowSortBy ) {

            default:
            case 'name_asc':
                foreach( $arrImages as $k => $v ) {
                    uksort($arrImages[$k], $basename_natcasecmp);
                    $arrImages[$k] = array_values($arrImages[$k]);
                }
                break;

            case 'name_desc':
                foreach( $arrImages as $k => $v ) {
                    uksort($arrImages[$k], $basename_natcasercmp);
                    $arrImages[$k] = array_values($arrImages[$k]);
                }
                break;

            case 'random':
                foreach( $arrImages as $k => $v ) {
                    shuffle($arrImages[$k]);
                    $arrImages[$k] = array_values($arrImages[$k]);
                }
                break;
        }

        $arrImages = array_values($arrImages);

        // Limit the total number of items (see #2652)
        if( $model->numberOfItems > 0 ) {
            $arrImages = array_slice($arrImages, 0, $model->numberOfItems);
        }

        $strTemplate = 'monoslideshow_default';

        // Single album
        $blnSingleAlbum = true;
        if( count($arrImages) > 1 ) {
            $blnSingleAlbum = false;
        }

        $objTemplate = new FrontendTemplate($model->monoslideshowTemplate?:'monoslideshow_default');

        $objTemplate->albums = $arrImages;
        $objTemplate->singleAlbum = $blnSingleAlbum;
        $objTemplate->path = $strPath;
        $objTemplate->showTitle = $model->monoslideshowShowTitle;
        $objTemplate->showDescription = $model->monoslideshowShowDescription;

        return $this->compressXml($objTemplate->parse());
    }


    /**
     * Get path
     *
     * @var string
     */
    private function getPath( string $strPath ): string {

        if( !file_exists($strPath) ) {
            return $strPath;
        }

        $arrPath = explode('/', $strPath);
        $strPath = '';
        $intCount = count($arrPath);

        for( $i=0; $i < $intCount - 1; $i++ ) {
            $strPath .= $arrPath[$i] . (($i != $intCount - 2) ? '/' : '');
        }

        return $strPath;
    }


    /**
     * Compress xml file
     *
     * @var string
     */
    private function compressXml( string $strXml ): string {

        return trim(preg_replace('~>\s*\n\s*<~', '><', $strXml));
    }


    /**
     * Get monoslideshow path
     *
     * @var String
     */
    protected function getMssPath( string $strDir='' ): string {
        return 'bundles/monoslideshow/' . (strlen($strDir) ? $strDir . '/' : '');
    }
}