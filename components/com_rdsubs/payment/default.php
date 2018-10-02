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

// Requires the amount class to check if the price is still > 0
// Otherwise the session has probably been expired and the customer needs to be redirected.

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/amount.php';

$amount    = new RDSubsAmount(JFactory::getSession()->get('ordercode'));
$processor = JFactory::getApplication()->input->getString('processor', 'paypal');

if ($processor != 'freecheckout')
{
	// When a user clicks the cancel button when arriving at the payment provider pages.
	if (JFactory::getApplication()->input->getWord('task') == 'cancel')
	{
		JFactory::getApplication()->enqueueMessage(JText::_('COM_RDSUBS_PAYMENT_HAS_BEEN_CANCELLED'), 'warning');
		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_rdsubs&view=checkout'));
	}
	// No task has been set, so we can process the payment:
	else if (JFactory::getApplication()->input->getWord('task') != 'return')
	{
		if ($amount->netprice() == 0)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RDSUBS_SESSION_HAS_BEEN_EXPIRED'), 'error');
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_rdsubs&view=products'));
		}

		$parts     = explode('.', $processor);
		$processor = $parts['0'];
		$task      = isset($parts['1']) ? $parts['1'] : 'cancel';

		JPluginHelper::importPlugin('rdmedia_payment', $processor);
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('process', [$task]);
	}
	// Customer comes back from the payment provider.
	else
	{
		JPluginHelper::importPlugin('rdmedia_payment', strtolower($processor));
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('response');
	}
}
else
{
	echo '<h1>' . $this->message['subject'] . '</h1>';
	echo $this->message['message'];
}

?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#paymentButton").trigger("click");
	});
</script>
