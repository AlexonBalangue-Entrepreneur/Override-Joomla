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

defined('_JEXEC') or die();

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/amount.php';

$amount = new RDSubsAmount;
?>

<h2 class="well-header">
	<?php echo JText::_('COM_RDSUBS_CHECKOUT'); ?>
</h2>

<?php if ($amount->netprice() > 0) : ?>

	<?php if ($this->processors_amount > 1 || $this->config->payment_choice_on_one_processor): ?>
		<h4><?php echo JText::_('COM_RDSUBS_CHOOSE_PAYMENT'); ?></h4>
	<?php endif; ?>

	<?php echo $this->processors; ?>

	<?php if (RDSubsConfig::get('require_tos_accept')):
		$layout = new JLayoutFile('cart_terms_conditions');
		echo $layout->render();
	endif; ?>

	<button name="makePayment" id="makePayment" class="btn btn-large btn-block btn-success"
	        onclick="submitCheckoutForm();">
		<?php echo JText::_('COM_RDSUBS_PROCEED_TO_PAYMENT'); ?>
	</button>
<?php else: ?>
	<input type="hidden" name="payment_method" id="payment_method" value="freecheckout" />

	<p>
		<?php echo JText::_('COM_RDSUBS_COMPLETE_ORDER_FREE_DESC'); ?>
	</p>

	<button name="makePayment" id="makePayment" class="btn btn-success btn-large btn-block"
	        onclick="submitCheckoutForm();">
		<?php echo JText::_('COM_RDSUBS_COMPLETE_ORDER'); ?>
	</button>
<?php endif; ?>

<br />

<a href="<?php echo JRoute::_('index.php?option=com_rdsubs&view=products'); ?>" class="btn btn-default pull-right">
	<?php echo JText::_('COM_RDSUBS_CONTINUE_SHOPPING'); ?></a>

<div class="clearfix"></div>
