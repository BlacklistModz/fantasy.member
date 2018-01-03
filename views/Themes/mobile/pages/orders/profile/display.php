<div class="web-profile">

	<div class="web-profile-header">
		<h1 class="fwb"><i class="icon-shopping-cart"></i> <?=$this->item['code']?></h1>
	</div>

	<div class="web-profile-content post">

		<table class="table-meta">
			<?php
			$a = array();
			$a[] = array('key'=>'user_code', 'icon'=>'address-book-o', 'label'=>'รหัส');
			$a[] = array('key'=>'user_name', 'icon'=>'user', 'label'=>'ชื่อร้านค้า');
			$a[] = array('key'=>'net_price', 'icon'=>'money', 'label'=>'ราคารวม');

			foreach ($a as $key => $value) {
				if( $value['key']=='net_price' ) {
					$this->item[$value['key']] = '<span style="color:red;">'.number_format($this->item[$value['key']]).' ฿</span>';
				}
				echo '<tr>
						<td class="label">
							<i class="icon-'.$value['icon'].'"></i> '.$value['label'].'
						</td>
						<td class="fwb">'.$this->item[$value['key']].'</td>
					  </tr>';
			}
			?>
		</table>

		<div class="web-profile-header">
			<h1>รายการสินค้า</h1>
			<table class="table table-bordered mtl" width="100%">
				<thead>
					<tr>
						<th class="name" width="40%">สินค้า</th>
						<th class="status" width="15%">จำนวน</th>
						<th class="price" style="color:green" width="15%">ราคา</th>
						<th class="price" width="15%">ส่วนลด</th>
						<th class="price" style="color:red" width="15%">รวม</th>
					</tr>
				</thead>
				<tbody>
					<?php $num=0; foreach ($this->item['items'] as $key => $value) { $num++ ?>
						<tr>
							<td><?=$num?>. <?=$value['name']?></td>
							<td class="tac"><?=number_format($value['qty'])?></td>
							<td class="tac"><?=number_format($value['price'])?></td>
							<td class="tac"><?=number_format($value['discount'])?></td>
							<td class="tar"><?=number_format($value['balance'])?>&nbsp;</td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="tac fwb">ยอดรวมเงิน <?=$num?> รายการ</td>
						<td colspan="4" class="tac fwb" style="font-size:20px;"><?=$this->item['net_price']?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
