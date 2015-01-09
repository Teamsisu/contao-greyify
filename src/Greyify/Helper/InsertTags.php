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


namespace Teamsisu\Greyify\Helper;

use Teamsisu\Greyify\Provider\Greyify;

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

                if(count($data) > 2){
                    switch(strtolower($data[1]))
                    {

                        case 'src':
                            return Greyify::convert($data[2]);
                            break;

                        case 'tag':
                            return Greyify::getHTML($data[2], false);
                            break;

                        case 'figure':
                            return Greyify::getHTML($data[2], true);
                            break;
                    }

                }else{
                    return Greyify::getHTML($data[1], true);
                }

                break;
        }

        return false;

    }

} 