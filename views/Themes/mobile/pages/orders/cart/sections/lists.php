<style type="text/css" media="screen">
	.mediaWrapper>*, .mediaWrapper>a>* {
		position: relative;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>

<div class="row clearfix mtl">
	<div class="lfloat"><h3><i class="icon-shopping-basket"></i> <?=$this->item['name_th']?></h3></div>
	<div class="rfloat" role="search">
		<form class="form-search" action="" id="global-nav-search">
			<label class="visuallyhidden" for="search-query">Search query</label>
			<input class="search-input inputtext" type="text" placeholder="ค้นหาสินค้า" name="key" autocomplete="off" value="<?=(!empty($_GET['key']) ? rawurldecode($_GET['key']) : '')?>">
			<button type="submit" class="search-icon js-search-action" tabindex="-1"><i class="icon-search"></i><span class="visuallyhidden"></span>
			</button>
		</form>
	</div>
</div>
<?php
$key = !empty($_GET["key"]) ? '&key='.$_GET["key"] : '';
?>
<div role="main">
	<div class="wrapper web-lists-wrap active posts clearfix" data-plugins="datalistsbox" data-options="<?=$this->fn->stringify( array('url' => URL.'products/lists/'.$this->item['id'].'?cus='.$this->customer['id'].$key) )?>">
		<ul class="ui-list clearfix" role="listsbox" style="padding-bottom: 50mm;"></ul>
		<div class="empty">
			<div class="empty-loader loader-spin-wrap"><div class="loader-spin"></div></div>
			<?php
			if( !empty($_GET["key"]) ){
				echo '<div class="empty-text"></div>';
			}
			?>
			<div class="empty-error js-refresh"></div>
		</div>
		<div class="web-lists-more"><a class="btn btn-jumbo js-more">โหลดเพิ่ม</a></div>
	</div>
</div>
