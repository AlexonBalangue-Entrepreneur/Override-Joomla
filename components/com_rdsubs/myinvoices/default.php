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

// This is only available in the Pro version.

/* >>> [PRO] >>> */

use RDMedia\Date;

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/rdsubs.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_MYINVOICES'));

## Obtain user information.
$user   = JFactory::getUser();
$userid = $user->id;

## Get document type and add it.
$document = JFactory::getDocument();

if ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), JText::_('COM_RDSUBS_MYINVOICES'));
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', JText::_('COM_RDSUBS_MYINVOICES'), $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = JText::_('COM_RDSUBS_MYINVOICES');
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
?>

	<div class="rdsubs rdsubs-myinvoices">

		<?php
		$layout = new JLayoutFile('menu');
		echo $layout->render(['position' => 'above']);
		?>

		<h1><?php echo JText::_('COM_RDSUBS_MYINVOICES'); ?></h1>

		<?php
		echo RDSubsMessage::getInstance('myinvoices-header')
			->user(JFactory::getUser()->id)
			->getBody();
		?>
		<?php
		$layout = new JLayoutFile('menu');
		echo $layout->render(['position' => 'under']);
		?>

		<table class="table table-striped">

			<thead>
				<th width="10%">
					<div align="center">#ID</div>
				</th>
				<th width="13%">
					<div align="center"><?php echo JText::_('COM_RDSUBS_INVOICE_DATE'); ?></div>
				</th>
				<th width="13%">
					<div align="center"><?php echo JText::_('COM_RDSUBS_ORDER_ID'); ?></div>
				</th>
				<th width="15%">
					<div align="center"><?php echo JText::_('COM_RDSUBS_PAYMENT_AMOUNT'); ?></div>
				</th>
				<th width="19%">
					<div align="center"><?php echo JText::_('COM_RDSUBS_PAYMENT_STATE'); ?></div>
				</th>
				<th width="15%">
					<div align="center">
				</th>
			</thead>

			<?php
			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{

				## Give give $row the this->item[$i]
				$row      = $this->items[$i];
				$download = JRoute::_('index.php?option=com_rdsubs&view=download&task=invoice&id=' . (int) $row->id);
				$invoice  = JPATH_ADMINISTRATOR . '/components/com_rdsubs/invoices/' . $row->id . '.pdf';

				?>

				<tr>
					<td>
						<div align="center"><?php echo ! empty($row->invoice_no) ? $row->invoice_no : $row->id; ?></div>
					</td>
					<td>
						<div align="center"><?php echo Date::_($row->invoicedate); ?></div>
					</td>
					<td>
						<div align="center"><?php echo $row->ordercode; ?></div>
					</td>
					<td>
						<div align="center"><?php echo RDSubsPrice::_($row->net_price); ?></div>
					</td>
					<td>
						<div align="center"><?php echo RDSubsHelper::getPaymentState($row->paid); ?></div>
					</td>
					<td><a href="<?php echo $download; ?>" class="btn btn-mini btn-success"><?php echo JText::_('COM_RDSUBS_DOWNLOAD'); ?></a></td>
				</tr>

			<?php } ?>

		</table>

		<div class="alert alert-success" style="font-size:90%;">
			<?php echo JText::_('COM_RDSUBS_MY_INVOICE_INFO'); ?>
		</div>
	</div>

<?php
/* >>> [PRO] >>> */
