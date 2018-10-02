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

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/config.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/rdsubs.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/amount.php';

$amount = new RDSubsAmount;

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_YOUR_CART'));

## Get document type and add it.
$document = JFactory::getDocument();

if ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), JText::_('COM_RDSUBS_YOUR_CART'));
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', JText::_('COM_RDSUBS_YOUR_CART'), $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = JText::_('COM_RDSUBS_YOUR_CART');
}

## Setting the page title.
$document->setTitle($sitetitle);

if ($this->config->load_bootstrap)
{
	JHtml::_('bootstrap.framework');
	JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

$script = "
	var rdsubs_states = " . $this->config->show_state . ";
	var rdsubs_root = '" . JUri::base() . "';
	var vat_countries = " . json_encode($this->vat_countries) . ";
	var rdsubs_lang_vat_number_checking = '" . addslashes(JText::_('COM_RDSUBS_VAT_NUMBER_CHECKING')) . "';
	var rdsubs_lang_vat_number_invalid = '" . addslashes(JText::_('COM_RDSUBS_VAT_NUMBER_INVALID')) . "';
";
JFactory::getDocument()->addScriptDeclaration($script);

JHtml::_('jquery.framework');

JHtml::script('com_rdsubs/script.js', false, true);
JHtml::script('com_rdsubs/profile.js', false, true);
JHtml::script('com_rdsubs/checkout.js', false, true);

?>

<div class="rdsubs rdsubs-checkout">
	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above']);
	?>

	<?php
	echo RDSubsMessage::getInstance('checkout-header')
	                  ->user(JFactory::getUser()->id)
	                  ->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under']);
	?>

	<div class="row-fluid rdsubs-minwidth-800">
		<div class="col-md-6 span6">
			<div class="well rdsubs-checkout-account">
				<?php echo $this->loadTemplate('account'); ?>
			</div>
		</div>

		<div class="col-md-6 span6">

			<?php if ( ! count($this->items)): ?>

				<div class="well rdsubs-checkout-cart">
					<?php
					$layout = new JLayoutFile('cart_empty');
					echo $layout->render(null);
					?>
				</div>

			<?php else: ?>

				<div class="well rdsubs-checkout-cart">
					<?php echo $this->loadTemplate('cart'); ?>

					<?php if (isset($this->config->show_coupon) && $this->config->show_coupon && $amount->nettoprice() > 0): ?>
						<div class="rdsubs-checkout-coupon">
							<?php echo $this->loadTemplate('coupon'); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="well rdsubs-checkout-checkout">
					<?php echo $this->loadTemplate('checkout'); ?>
				</div>

			<?php endif; ?>
		</div>
	</div>
</div>
