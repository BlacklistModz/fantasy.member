<?php

$li = '';
foreach ($this->results['lists'] as $key => $value) {

	$image = !empty($value['image_url']) ? '<img src="'.$value['image_url'].'" />':'';
	$li .= '<li class="ui-list-item border-bottom mhs pas anchor clearfix pbl">
				<div class="mediaWrapper lfloat mrm" style="max-width: 100%; height: auto; background-color:transparent;">'.$image.'</div>
				<div class="content">
					<div class="massages">
					<div class="ui-score"></div>
					<div class="title fwb" style="font-size: 18px;">'.$value['pds_name'].'</div>
					<div class="fwb" style="font-size: 20px; color:red;">'. (!empty($value['pricing']['frontend']) ? number_format($value['pricing']['frontend']) : number_format($value['pds_price_frontend'])) .' ฿</div>
				</div>
				<div class="rfloat  icon tac mrm">
					<div class="clearfix">
					<form action="'.URL.'orders/saveCusOrder" class="js-submit-form">
						<div class="form-flex">
							<div>
								<button type="button" class="btn js-remove"><i class="icon-minus"></i> </button>
							</div>
							<div>
								<input type="number" name="quantity" class="inputtext tac js-number" value="0">
							</div>
							<div>
								<button type="button" class="btn js-plus"><i class="icon-plus"></i> </button>
							</div>
						</div>
						<div class="clearfix mts">
							<button class="btn btn-blue js-submit" disabled="1" type="submit"><i class="icon-shopping-cart"></i> สั่งซื้อ</button>
						</div>
						<input type="hidden" name="id" value="'.$value['id'].'">
						<input type="hidden" name="cus_id" value="'.$_GET["cus"].'">
					</form>
					</div>
				</div>
			</li>';
}

echo json_encode( array_merge($this->results, array(
	'$lis'=>$li,
)) );
