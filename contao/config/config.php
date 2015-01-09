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


/**
 * Inserttag Hook
 */

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Teamsisu\Greyify\Helper\InsertTags', 'parseInsertTags');