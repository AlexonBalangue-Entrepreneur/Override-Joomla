<?php
/**
 * @package		Joomla
 * @subpackage  RD-Subscriptions
 * @version	    2.5.3 
 * @release	    2017-11-07 
 *
 * @copyright	2017 RD-Media || RDProductions All rights reserved.
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
	//JHtml::_('bootstrap.framework');
	//JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

//JHtml::_('bootstrap.framework');
//JHtml::_('jquery.framework');

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

	<div class="row">
		<div class="col col-12 col-md-4">
				<img src="<?php echo Image::getUrl($this->item->id, 'file'); ?>" class="img-fluid  img-thumbnail rounded mx-auto d-block">
		</div>

		<div class="col col-12 col-md-8">

			<?php if ($has_access) : ?>
				<div class="pull-right">
					<a href="<?php echo $download; ?>" class="btn btn-outline-success btn-lg">
						<i class="fal fa-download fa-2x"></i>
						<?php echo JText::_('COM_RDSUBS_DOWNLOAD'); ?>
					</a>
				</div>
			<?php endif; ?>

			<h1><?php echo $this->item->filename; ?></h1>
			<div class="table-responsive">
			<table class="table">
				<tr>
					<td>
						<?php echo JText::_('COM_RDSUBS_VERSION'); ?>
					</td>
					<td>
						<?php echo $this->item->version; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('COM_RDSUBS_MATURITY'); ?>
					</td>
					<td>
						<?php echo RDSubsHelper::getStabilityLevel($this->item->stability_level); ?>
					</td>
				</tr>
				<tr>
					<td>
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
	</div>

	<div class="clearfix"></div>

	<?php if ( ! $has_access) : ?>
		<div class="alert alert-warning">
			<?php echo JText::_('COM_RDSUBS_FILE_NO_ACCESS'); ?>
		</div>
	<?php endif; ?>

	<hr />

	<?php if ($this->item->releasenotes || $this->item->installnotes): ?>
		<ul class="nav nav-tabs" id="TabsRD" role="tablist">
			<?php if ($this->item->releasenotes): ?>
				<li class="nav-item">
					<a class="nav-link active" id="release-tab" data-toggle="tab" href="#release" role="tab" aria-controls="release" aria-selected="true"><?php echo JText::_('COM_RDSUBS_RELEASE_NOTES'); ?></a>
				</li>
			<?php endif; ?>

			<?php if ($this->item->installnotes): ?>
				<li class="nav-item">
					<a hclass="nav-link" id="installation-tab" data-toggle="tab" href="#installation" role="tab" aria-controls="installation" aria-selected="false"><?php echo JText::_('COM_RDSUBS_INSTALL_NOTES'); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<div class="tab-content">
			<?php if ($this->item->releasenotes): ?>
				<div class="tab-pane fade show active" id="release" role="tabpanel" aria-labelledby="release-tab">
					<?php echo $this->item->releasenotes; ?>
				</div>
			<?php endif; ?>

			<?php if ($this->item->installnotes): ?>
				<div class="tab-pane fade show" id="installation" role="tabpanel" aria-labelledby="installation-tab">
					<?php echo $this->item->installnotes; ?>
				</div>
			<?php endif; ?>

		</div>

		<script>
		$('#TabsRD a').on('click', function (e) {
			e.preventDefault()
			$(this).tab('show')
		})
		</script>
	<?php endif; ?>
</div>
