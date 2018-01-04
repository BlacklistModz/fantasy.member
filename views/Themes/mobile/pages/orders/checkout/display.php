<style type="text/css" media="screen">
	.mediaWrapper>*, .mediaWrapper>a>* {
		position: relative;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		max-width: 100%; height: auto; background-color:transparent;
	}
</style>
<div role="main" data-plugins="formCheckout">
	<h1 class="tac fwb mts">กรุณาตรวจสอบ Order</h1>
	<?php 
	if( !empty($this->order) ){
		foreach ($this->order['items'] as $key => $value) {
			// $url_image = !empty($value['image_url']) ? $value['image_url'] : "";
			$image = !empty($value['image_url']) ? '<img src="'.$value['image_url'].'" />':'';
			echo '<div class="uiBoxGray mtm" data-id="'.$value['id'].'">
					<ul class="ui-list clearfix">
						<li class="ui-list-item border-bottom mhs pas anchor clearfix">
							<div class="mediaWrapper lfloat mrm" style="max-width: 100%; height: auto; background-color:transparent;">'.$image.'</div>
							<div class="content">
								<div class="massages">
								<div class="ui-score"></div>
								<div class="title fwb" style="font-size: 18px;">'.$value['products_name'].'</div>
								<div class="" style="font-size: 20px;">ราคา <span style="color:red;">'.number_format($value['price']).' ฿</span></div>
							</div>

							<div class="rfloat  icon tac mrm">
								<div class="clearfix">
									<form class="js-form" method="POST" data-id="'.$value['id'].'">
										<div class="form-flex">
											<div>
												<button type="button" class="btn js-remove"><i class="icon-minus"></i> </button>
											</div>
											<div>
												<input type="number" name="quantity" class="inputtext tac js-number" value="'.$value['quantity'].'">
											</div>
											<div>
												<button type="button" class="btn js-plus"><i class="icon-plus"></i> </button>
											</div>
										</div>
										<div class="clearfix mts">
											<a class="btn btn-green submit" href="#"><i class="icon-retweet"></i> อัพเดต</a>
											<a class="btn btn-red js-del" data-url="'.URL.'orders/del_cus_item"><i class="icon-trash"></i></a>
										</div>
						<input type="hidden" name="id" value="'.$value['id'].'">
					</form>
					</div>
						</li>
					</ul>
				  </div>';
		}
	}
	else{
		echo '<div class="uiBoxGray pal"><h2 class="tac" style="color:red;">ไม่มีการสั่งซื้อสินค้า</h2></div>';
	}

	?>

	<div class="uiBoxLightblue mtm">
		<div class="pam">
			<table>
				<tr>
					<td width="50%" align="left" class="fwb">รวมเงิน : </td>
					<td width="50%" align="left"><span id="total"><?= !empty($this->order) ? number_format($this->order['price']) : "0" ?></span></td>
				</tr>
				<tr>
					<td width="50%" align="left" class="fwb">หักส่วนลด : </td>
					<td width="50%" align="left"><span id="discount"><?= !empty($this->order) ? number_format($this->order['discount']) : "0" ?></td>
				</tr>
				<tr>
					<td width="50%" align="left" class="fwb">ยอดสุทธิ</td>
					<td width="50%" align="left" class="fwb" style="color:red;"><span id="amount"><?= !empty($this->order) ? number_format($this->order['net_price']) : "0" ?></span></td>
				</tr>
			</table>
		</div>
	</div>
	<?php if( !empty($this->order) ) { ?>
	<footer>
		<div class="clearfix mtl">
			<div class="span12 tac">
				<form class="js-submit-form" action="<?=URL?>orders/confirmOrder/<?=$this->order['id']?>">
					<a href="<?=URL?>orders/create/<?=$this->me['id']?>" class="btn btn-red" style="width: 48%">กลับไปเลือกสินค้า</a>
					<button type="submit" class="btn btn-green" style="width: 48%">Confirm</button>
				</form>
			</div>
		</div>
	</footer>
	<?php } ?>
</div>