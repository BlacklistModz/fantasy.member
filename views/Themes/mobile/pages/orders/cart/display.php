<?php 
$options = $this->fn->stringify( array(
					'category' => $this->cate['id'],
					'customer' => $this->me['id'],
					'key' => isset($_GET["key"]) ? $_GET["key"] : ''
				) );
?>
<div class="js-control" data-plugins="formOrder" data-options="<?=$options?>">
	<?php 
	include("sections/category.php");
	?>
	<div id="subMenu"></div>
	<div role="main" class="list-products">
		<div id="productsLists">
			<div class="empty">
				<div class="empty-loader loader-spin-wrap">
					<div class="loader-spin"></div>
				</div>
			</div>
		</div>
	</div>
</div>