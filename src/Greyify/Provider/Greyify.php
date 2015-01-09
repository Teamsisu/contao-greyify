<?php
/**
 * Greyify
 * The contao image to greyscale converter
 *
 * PHP version 5
 *
 * @package    Greyify
 * @author     Martin Treml <martin.treml@teamsisu.at>
 * @copyright  Team sisu GmbH
 * @license    LGPL.
 */


namespace Teamsisu\Greyify\Provider;


class Greyify
{


    /**
     * Convert an existing image to the greyscale version
     *
     * @param string $data The image path or an UUID including url params for with/height/mode
     * @return string image path
     */
    public static function convert($data)
    {

        $params = array(
            'width'  => 0,
            'height' => 0,
            'mode'   => '',
        );

        if (strpos($data, '?') !== false) {
            // parse additonal tags
            $data = parse_url($data);
            $params = array();
            parse_str($data['query'], $params);

            if (!isset($params['width'])) {
                $params['width'] = false;
            }

            if (!isset($params['height'])) {
                $params['height'] = false;
            }

            if (!isset($params['mode'])) {
                $params['mode'] = '';
            }

            $objFile = self::checkFile($data['path']);
        } else {
            $objFile = self::checkFile($data);
        }

        /**
         * Check if file size is not greater then the contao max gd editing size #1
         */
        if($objFile->width > $GLOBALS['TL_CONFIG']['gdMaxImgWidth'] || $objFile->height > $GLOBALS['TL_CONFIG']['gdMaxImgHeight']){
            \System::log('Image "' . $objFile->path . '" could not be converted to greyscale cause its size is to large for the gd editing', __METHOD__, TL_ERROR);
            return null;
        }

        /**
         * Resize the image with the default contao image function
         */
        $image = \Image::get($objFile->path, $params['width'], $params['height'], $params['mode']);

        if(is_null($image)){
            return null;
        }

        /**
         * Create cache name
         */
        $strCacheKey = substr(md5('-w' . $params['width'] . '-h' . $params['height'] . '-' . $image . '-' . $params['mode'] . '-' . $objFile->mtime), 0, 8);
        $strCacheName = 'assets/images/' . substr($strCacheKey, -1) . '/' . $objFile->filename . '-grey' . '-' . $strCacheKey . '.' . $objFile->extension;

        /**
         * Check if file already exists and if it was modified
         */
        if (!file_exists(TL_ROOT . '/' . $strCacheName) || $objFile->mtime >= filemtime(TL_ROOT . '/' . $strCacheName)) {

            /**
             * Copy original file to always display anything
             */
            \Files::getInstance()->copy($objFile->path, $strCacheName);

            /**
             * Read image from file
             */
            switch ($objFile->extension) {
                case 'png':
                    $strImage = imagecreatefrompng($objFile->path);
                    break;
                case 'jpg':
                case 'jpeg':
                    $strImage = imagecreatefromjpeg($objFile->path);
                    break;
                case 'gif':
                    $strImage = imagecreatefromgif($objFile->path);
                    break;
            }

            /**
             * Convert colors
             */
            if (imagefilter($strImage, IMG_FILTER_GRAYSCALE)) {
                switch ($objFile->extension) {
                    case 'png':
                        imagepng($strImage, $strCacheName);
                        break;
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($strImage, $strCacheName,
                            (!$GLOBALS['TL_CONFIG']['jpgQuality'] ? 80 : $GLOBALS['TL_CONFIG']['jpgQuality']));
                        break;
                    case 'gif':
                        imagegif($strImage, $strCacheName);
                        break;
                }
            } else {
                \System::log('Image "' . $image . '" could not be converted to greyscale', __METHOD__, TL_ERROR);
            }

        }

        return $strCacheName;

    }


    /**
     * Converts the given image path/uuid/numeric id to greyscale
     * and returns an img tag - optional with figure wrap
     *
     * @param mixed  $filePath
     * @param bool   $withFigure
     * @param string $alt
     * @return string
     */
    public static function getHTML($filePath, $withFigure = false, $alt = '')
    {

        $filePath = self::convert($filePath);

        $htmlIMG = '<img src="' . $filePath . '" ' . ($alt ? 'alt="' . $alt . '"' : '') . ' />';

        if ($withFigure) {
            return '<figure class="image_container grey">' . $htmlIMG . '</figure>';
        }

        return $htmlIMG;
    }


    /**
     * Check the given file path string if it is a regular file an UUID or an numeric ID
     *
     * @param string $filePath
     * @return \File
     */
    protected static function checkFile($filePath)
    {

        if (\Validator::isUuid($filePath)) {
            // Handle UUIDs
            $objFile = \FilesModel::findByUuid($filePath);
            $filePath = $objFile->path;
        } elseif (is_numeric($filePath)) {
            // Handle numeric IDs (see #4805)
            $objFile = \FilesModel::findByPk($filePath);
            $filePath = $objFile->path;
        } else {
            // Check the path
            if (\Validator::isInsecurePath($filePath)) {
                throw new \RuntimeException('Invalid path ' . $filePath);
            }


        }

        return new \File($filePath, true);

    }

} 