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

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/rdsubs.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_MYSUBSCRIPTIONS'));

## Get document type and add it.
$document = JFactory::getDocument();

if ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), JText::_('COM_RDSUBS_MYSUBSCRIPTIONS'));
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', JText::_('COM_RDSUBS_MYSUBSCRIPTIONS'), $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = JText::_('COM_RDSUBS_MYSUBSCRIPTIONS');
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

## Obtain user information.
$user   = JFactory::getUser();
$userid = $user->id;

JHtml::script('com_rdsubs/script.js', false, true);
?>

<div class="rdsubs rdsubs-mysubscriptions">

	<?php
	if ($this->params->get('show_submenu', $this->config->show_submenu)
		&& $this->params->get('submenu_position', $this->config->submenu_position) == 'above'
	)
	{
		$layout = new JLayoutFile('menu');
		echo $layout->render('');
	}
	?>

	<h1><?php echo JText::_('COM_RDSUBS_MYSUBSCRIPTIONS'); ?></h1>

	<?php
	echo RDSubsMessage::getInstance('mysubscriptions-header')
		->user(JFactory::getUser()->id)
		->getBody();
	?>

	<?php
	if ($this->params->get('show_submenu', $this->config->show_submenu)
		&& $this->params->get('submenu_position', $this->config->submenu_position) == 'under'
	)
	{
		$layout = new JLayoutFile('menu');
		echo $layout->render('');
	}
	?>

	<?php if ($this->config->watchful_updater) : ?>
		<div class="rdsubs watchful-block">
			<h4 class="watchful-header"><?php echo JText::_('COM_RDSUBS_USING_WATCHFUL'); ?></h4>
			<p><?php echo JText::sprintf('COM_RDSUBS_YOUR_WATCHFUL_KEY', md5(JFactory::getUser()->id)); ?></p>
		</div>
	<?php endif; ?>

	<?php if (count($this->items) > 0): ?>

		<?php
		$layout = new JLayoutFile('mysubscriptions_' . $this->params->get('page_type', 'table'));
		echo $layout->render([
			'items'            => $this->items,
			'config'           => $this->config,
			'num_columns'      => $this->params->get('num_columns', $this->config->num_columns),
			'column_alignment' => $this->params->get('column_alignment', 'right'),
		]);
		?>

		<?php if ($this->config->show_renewal_message): ?>
			<div class="alert alert-success" style="font-size:90%;">
				<?php echo JText::_('COM_RDSUBS_RENEW_MESSAGE'); ?>
			</div>
		<?php endif; ?>

	<?php else: ?>

		<div class="alert alert-info">
			<h4><?php echo JText::_('COM_RDSUBS_INFORMATION'); ?></h4>
			<?php echo JText::_('COM_RDSUBS_NO_SUBSCRIPTIONS'); ?>
		</div>

	<?php endif; ?>
</div>
