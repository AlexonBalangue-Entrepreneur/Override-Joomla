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

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/price.php';

$app = JFactory::getApplication();

## Set state for the details page as products can be in multiple categories.
$app->setUserState('category_id', $this->category->id);
$app->setUserState('category_name', $this->category->name);

$pathway = $app->getPathway();
$pathway->addItem($this->category->name);

## Obtain user information.
$user   = JFactory::getUser();
$userid = $user->id;
$access = false;

## Get document type and add it.
$document = JFactory::getDocument();

if (empty($this->category->name))
{
	$sitetitle = $app->get('sitename');
}
elseif ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), $this->category->name);
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $this->category->name, $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = $this->category->name;
}

## Setting the page title.
$document->setTitle($sitetitle);

if ($this->config->load_bootstrap)
{
	//JHtml::_('bootstrap.framework');
	//JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

JHtml::script('com_rdsubs/script.js', false, true);
?>

<div class="rdsubs rdsubs-category">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above', 'active' => 'categories']);
	?>

	<h1><?php echo $this->category->name . ' ' . JText::_('COM_RDSUBS_DOWNLOADS'); ?></h1>

	<?php
	echo RDSubsMessage::getInstance('category-header')
		->user(JFactory::getUser()->id)
		->getBody();
	?>

	<p><?php echo $this->category->description; ?></p>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under', 'active' => 'categories']);
	?>

	<div class="clearfix"></div>

	<?php if (count($this->subcategories) > 0): ?>

		<form>
			<div class="input-group-append">

				<a class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
				 
					<?php echo JText::_('COM_RDSUBS_DOWNLOADS_SUBCATEGORY'); ?>
					<span class="caret" style="margin-left:5px;"></span>
				</a>

				<?php if (count($this->subcategories)): ?>

					<div class="dropdown-menu">

						<!-- dropdown menu links -->
						<?php if ($this->category->parentid): ?>
							<?php $subcat_url = JRoute::_('index.php?option=com_rdsubs&view=category&id=' . $this->category->parentid . ':component-overview'); ?>

							<a class="dropdown-item" href="<?php echo $subcat_url; ?>"><?php echo JText::_('COM_RDSUBS_COMPONENT'); ?></a>

						<?php endif; ?>

						<?php foreach ($this->subcategories as $subcat): ?>
							<?php
							$subcat_url = JRoute::_('index.php?option=com_rdsubs&view=category&id=' . $subcat->id);
							?>

							<a class="dropdown-item" href="<?php echo $subcat_url; ?>"><?php echo $subcat->name; ?></a>

						<?php endforeach; ?>

					</div>

				<?php endif; ?>

			</div>
		</form>

		<div class="clearfix"></div>

	<?php endif; ?>

	<?php
	$layout = new JLayoutFile('file_list');
	echo $layout->render([
		'items'       => $this->items,
		'num_columns' => $this->params->get('num_columns', $this->config->num_columns),
		'accesslist'  => $this->accesslist,
	]);
	?>

	<?php if ($this->pagination->get('pages.total') > 1) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>

	<?php
	$has_access = false;
	foreach ($this->items as $item)
	{
		if (in_array($item->file_id, $this->accesslist, true))
		{
			$has_access = true;
			break;
		}
	}
	?>

	<?php if ( ! $has_access && ! empty($this->product->id)): ?>
		<?php
		$link     = 'index.php?option=com_rdsubs&view=order&task=product&id=' . $this->product->id;
		$overview = JRoute::_('index.php?option=com_rdsubs&view=categories');
		?>

		<div class="clearfix"></div>

		<div class="order_now_box">

			<h4><?php echo JText::_('COM_RDSUBS_NO_ACCESS_TO_DOWNLOADS'); ?></h4>

			<p><?php echo JText::_('COM_RDSUBS_NO_SUB_ORDER_NOW'); ?></p>

			<a href="<?php echo $link; ?>" class="btn btn-outline-success btn-sm">
				<?php echo JText::_('COM_RDSUBS_SUBSCRIBE_NOW'); ?>
				<?php echo RDSubsPrice::_($this->product->price); ?>
			</a>

		</div>
	<?php endif; ?>

</div>
