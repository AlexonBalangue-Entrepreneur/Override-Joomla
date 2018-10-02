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

use RDMedia\Date;
use RDMedia\Image;

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/rdsubs.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/image.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';

$app      = JFactory::getApplication();
$document = JFactory::getDocument();

$category_id   = $app->getUserState('category_id', '');
$category_name = $app->getUserState('category_name', '');

$pathway = $app->getPathway();
$pathway->addItem($category_name, JRoute::_('index.php?option=com_rdsubs&view=category&id=' . $category_id));
$pathway->addItem($this->item->filename);

if (empty($this->item->filename))
{
	$sitetitle = $app->get('sitename');
}
elseif ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), $this->item->filename);
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $this->item->filename, $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = $this->item->filename;
}

## Setting the page title.
$document->setTitle($sitetitle);

## Obtain user information.
$user   = JFactory::getUser();
$userid = $user->id;

if ($this->config->load_bootstrap)
{
	JHtml::_('bootstrap.framework');
	JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');

$has_access = false;
if ($this->item->ispublic || in_array($this->item->id, $this->access, true))
{
	$download   = 'index.php?option=com_rdsubs&view=download&task=file&id=' . (int) $this->item->id;
	$has_access = true;
}
?>
<div class="rdsubs rdsubs-file">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above', 'active' => 'categories']);
	?>

	<?php
	echo RDSubsMessage::getInstance('file-header')
		->user(JFactory::getUser()->id)
		->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under', 'active' => 'categories']);
	?>

	<div class="rows">
		<div class="col-sm-4 span4">
			<div class="rdsubs-image-container">
				<img src="<?php echo Image::getUrl($this->item->id, 'file'); ?>" class="rdsubs-image">
			</div>
		</div>

		<div class="col-sm-8 span8">

			<?php if ($has_access) : ?>
				<div class="pull-right">
					<a href="<?php echo $download; ?>" class="btn btn-success btn-large">
						<span class="icon-download icon-white"></span>
						<?php echo JText::_('COM_RDSUBS_DOWNLOAD'); ?>
					</a>
				</div>
			<?php endif; ?>

			<h1><?php echo $this->item->filename; ?></h1>

			<table class="table">
				<tr>
					<td style="width:20%" class="nowrap">
						<?php echo JText::_('COM_RDSUBS_VERSION'); ?>
					</td>
					<td>
						<?php echo $this->item->version; ?>
					</td>
				</tr>
				<tr>
					<td class="nowrap">
						<?php echo JText::_('COM_RDSUBS_MATURITY'); ?>
					</td>
					<td>
						<?php echo RDSubsHelper::getStabilityLevel($this->item->stability_level); ?>
					</td>
				</tr>
				<tr>
					<td class="nowrap">
						<?php echo JText::_('COM_RDSUBS_RELEASE_DATE'); ?>
					</td>
					<td>
						<?php if ($this->item->created == $this->item->release_date || $this->item->release_date == '0000-00-00'):
							echo Date::_($this->item->created);
						else:
							echo Date::_($this->item->release_date);
						endif; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="clearfix"></div>

	<?php if ( ! $has_access) : ?>
		<div class="alert alert-warning">
			<?php echo JText::_('COM_RDSUBS_FILE_NO_ACCESS'); ?>
		</div>
	<?php endif; ?>

	<hr class="hr_styling">

	<?php if ($this->item->releasenotes || $this->item->installnotes): ?>
		<ul class="nav nav-tabs" id="myTab">
			<?php if ($this->item->releasenotes): ?>
				<li class="active">
					<a href="#release_notes"><?php echo JText::_('COM_RDSUBS_RELEASE_NOTES'); ?></a>
				</li>
			<?php endif; ?>

			<?php if ($this->item->installnotes): ?>
				<li>
					<a href="#installation_notes"><?php echo JText::_('COM_RDSUBS_INSTALL_NOTES'); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<div class="tab-content">

			<?php if ($this->item->releasenotes): ?>
				<div class="tab-pane active" id="release_notes">
					<?php echo $this->item->releasenotes; ?>
				</div>
			<?php endif; ?>

			<?php if ($this->item->installnotes): ?>
				<div class="tab-pane" id="installation_notes">
					<?php echo $this->item->installnotes; ?>
				</div>
			<?php endif; ?>

		</div>

		<script>
			jQuery(document).ready(function() {
				jQuery('#myTab a').click(function(e) {
					e.preventDefault();
					jQuery(this).tab('show');
				});
			});
		</script>
	<?php endif; ?>
</div>
