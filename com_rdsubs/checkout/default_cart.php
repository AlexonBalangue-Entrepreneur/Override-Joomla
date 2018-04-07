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

$quantity_layout = $this->config->cart_quantity_layout ?: 'select';

?>

<h2 class="text-primary">
	<?php echo JText::_('COM_RDSUBS_YOUR_CART'); ?>
</h2>

<div class="table-responsive">
<table class="table">
	<?php foreach ($this->items as $item): ?>
		<?php
		$layout = new JLayoutFile($item->product_id ? 'cart_item' : 'cart_discount');

		$nr_of_rows = $quantity_layout == 'separate' ? $item->count_products : 1;
		for ($i = 1; $i <= $nr_of_rows; $i++)
		{
			echo $layout->render(['item' => $item, 'quantity_layout' => $quantity_layout]);
		}
		?>
	<?php endforeach; ?>

	<?php
	$layout = new JLayoutFile('cart_totals');
	echo $layout->render(null);
	?>
</table>
</div>
