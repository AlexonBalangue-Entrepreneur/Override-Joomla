<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_syndicate
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<a href="<?php echo $link; ?>">
	<i class="fal fa-rss-square"></i>
	<?php if ($params->get('display_text', 1)) : ?>
		<?php if (str_replace(' ', '', $text) !== '') : ?>
			<?php echo $text; ?>
		<?php else : ?>
			<?php echo JText::_('MOD_SYNDICATE_DEFAULT_FEED_ENTRIES'); ?>
		<?php endif; ?>
	<?php endif; ?>
</a>
