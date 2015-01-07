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


namespace Greyify\Helper;

use Greyify\Provider\Greyify;

class InsertTags extends \Controller
{

    /**
     * Parse the given Inserttag
     *
     * @param string $strTag
     * @return bool|string - The image in grey
     */
    public function parseInsertTags($strTag)
    {

        $data = explode('::', $strTag);

        switch ($data[0]) {

            case 'greyify':

                    return Greyify::getHTML($data[1], true);

                break;
        }

        return false;

    }

} 