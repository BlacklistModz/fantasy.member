<div class="web-profile">

	<div class="web-profile-header">
		<h1 class="fwb"><i class="icon-handshake-o"></i> ติดต่อเรา</h1>
	</div>

	<div class="web-profile-content post">
		<table class="table-meta">
			<?php
			// print_r($this->system);die;
			$a = array();
			$a[] = array('key'=>'name', 'icon'=>'address-book-o', 'label'=>'ชื่อบริษัท');
			$a[] = array('key'=>'title', 'icon'=>'', 'label'=>'');
			$a[] = array('key'=>'address', 'icon'=>'map-marker', 'label'=>'ที่อยู่');
			$a[] = array('key'=>'phone', 'icon'=>'phone', 'label'=>'เบอร์โทรศัพท์');

			foreach ($a as $key => $value) {
				if( empty($value) ) continue;
				if( $value['key'] == 'phone' ){
					if( !empty($this->system['phone_2']) ){
						$this->system[$value['key']].=' ,'.$this->system['phone_2'];
					}
				}
				echo '<tr>
						<td class="label" valign="top">
							<i class="icon-'.$value['icon'].'"></i> '.$value['label'].'
						</td>
						<td class="fwb">'.$this->system[$value['key']].'</td>
					  </tr>';
			}
			?>
		</table>
		<table class="table-meta tac mtl">
			<tr>
				<td width="33.33%">
					<ul>
						<li><a href="tel:<?=$this->system['mobile_phone']?>" class="btn btn-green btn-jumbo"><i class="icon-phone"></i></a></li>
						<li><label for="phone_1" class="fwb">ฝ่ายบัญชี</label></li>
					</ul>
				</td>
				<td width="33.33%">
					<a class="btn btn-blue btn-jumbo"><i class="icon-phone"></i></a>
				</td>
				<td width="33.33%">
					<a class="btn btn-blue btn-jumbo"><i class="icon-phone"></i></a>
				</td>
			</tr>
		</table>
	</div>
</div>