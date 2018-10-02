<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JHtml::_('behavior.core');

/**
 * Generic toolbar button layout to open a modal
 * -----------------------------------------------
 * @param   array   $displayData    Button parameters. Default supported parameters:
 *                                  - selector  string  Unique DOM identifier for the modal. CSS id without #
 *                                  - class     string  Button class
 *                                  - icon      string  Button icon
 *                                  - text      string  Button text
 */

$selector = $displayData['selector'];
$class    = isset($displayData['class']) ? $displayData['class'] : 'btn btn-secondary btn-sm';
$icon     = isset($displayData['icon']) ? $displayData['icon'] : 'external-link';
$text     = isset($displayData['text']) ? $displayData['text'] : '';
?>
<button class="<?php echo $class; ?>" data-toggle="modal" data-target="#<?php echo $selector; ?>">
	<i class="fal fa-<?php echo $icon; ?>"></i> <?php echo $text; ?>
</button>
