<?php
/**
 * @package		Joomla
 * @subpackage  RD-Subscriptions
 * @version	    2.7.2 
 * @release	    2018-07-10 
 *
 * @copyright	2018 RD-Media || RDProductions All rights reserved.
 * @author	    Robert Dam
 * @license		GNU GENERAL PUBLIC LICENSE V2
 * @license	    http://rd-media.org/license.php 
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/image.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/price.php';

$app    = JFactory::getApplication();

if ($this->config->load_bootstrap)
{
	JHtml::_('bootstrap.framework');
	JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

JHtml::script('com_rdsubs/script.js', false, true);

// Getting the active product for the logged in user.
$active_products = (new \RDMedia\User)->getActiveSubsscriptions();

?>
<div class="rdsubs rdsubs-rdsubs">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above']);
	?>

	<h1><?php echo ! empty($this->params->get('custom_pagetitle', null)) ? $this->params->get('custom_pagetitle') : JText::_('COM_RDSUBS_OUR_PRODUCTS'); ?></h1>

	<?php
	echo RDSubsMessage::getInstance('products-header')
	                  ->user(JFactory::getUser()->id)
	                  ->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under']);
	?>

	<p><?php echo JText::_('COM_RDSUBS_OUR_PRODUCTS_DESC'); ?></p>

	<?php
	$layout = new JLayoutFile('product_list');
	echo $layout->render([
		'items'           => $this->items,
		'num_columns'     => $this->params->get('num_columns', $this->config->num_columns),
		'active_products' => $active_products,
	]);
	?>

	<div style="clear:both"></div>

	<?php if ($this->pagination->get('pages.total') > 1) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>


	<?php if ($this->config->show_vat_notice != 0) : ?>
		<div class="notice_rdsubs_box"><?php echo JText::_('COM_RDSUBS_VAT_NOTICE'); ?></div>
	<?php endif; ?>
</div>
