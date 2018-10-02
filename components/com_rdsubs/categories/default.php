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

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_CATEGORIES'), 'index.php?option=com_rdsubs');

## Obtain user information.
$user   = JFactory::getUser();
$userid = $user->id;

## Get document type and add it.
$document = JFactory::getDocument();

if ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), JText::_('COM_RDSUBS_DOWNLOADCATEGORIES'));
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', JText::_('COM_RDSUBS_DOWNLOADCATEGORIES'), $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = JText::_('COM_RDSUBS_DOWNLOADCATEGORIES');
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

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';
?>

<div class="rdsubs rdsubs-categories">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above']);
	?>

	<h1><?php echo JText::_('COM_RDSUBS_DOWNLOADCATEGORIES'); ?></h1>

	<?php
	echo RDSubsMessage::getInstance('categories-header')
		->user(JFactory::getUser()->id)
		->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under']);

	$dispatcher = JEventDispatcher::getInstance();
	JPluginHelper::importPlugin('rdmedia');
	$dispatcher->trigger('onBeforeShowDownloadCategories', []);
	?>

	<table class="table table-striped">

		<thead>
			<th>
				<?php echo JText::_('COM_RDSUBS_DOWNLOAD_CATEGORY'); ?>
			</th>
			<th width="10%" class="center nowrap">
				<?php echo JText::_('COM_RDSUBS_DOWNLOADS'); ?>
			</th>
		</thead>

		<?php foreach ($this->items as $product) : ?>
			<?php
			$url = JRoute::_('index.php?option=com_rdsubs&view=category&id=' . $product->id);
			?>

			<tr>
				<td><a href="<?php echo $url; ?>">
						<?php echo $product->name; ?>
				</td>
				<td class="center nowrap">
					<a href="<?php echo $url; ?>" class="btn btn-small pull-right">
						<?php echo JText::_('COM_RDSUBS_DOWNLOADS'); ?>
					</a>
				</td>
			</tr>

		<?php endforeach; ?>

	</table>
</div>
