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
						$this->system[$value['key']] = '<a href="tel:'.$this->system[$value['key']].'">'.$this->system[$value['key']].'</a>';
					if( !empty($this->system['phone_2']) ){
						$this->system[$value['key']].=' ,<a href="tel:'.$this->system['phone_2'].'">'.$this->system['phone_2'].'</a>';
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
						<li><a href="tel:<?=$this->system['mobile_phone']?>" class="btn btn-green btn-jumbo" style="border-radius: 5px;"><i class="icon-phone"></i></a></li>
						<li><label for="phone_1" class="fwb">ฝ่ายบัญชี</label></li>
					</ul>
				</td>
				<td width="33.33%">
					<ul>
						<li><a href="tel:<?=$this->system['mobile_phone_2']?>" class="btn btn-blue btn-jumbo" style="border-radius: 5px;"><i class="icon-phone"></i></a></li>
						<li><label for="phone_2" class="fwb">ลูกค้าสัมพันธ์</label></li>
					</ul>
				</td>
				<td width="33.33%">
					<ul>
						<li><a href="tel:<?=$this->system['mobile_phone_3']?>" class="btn btn-red btn-jumbo" style="border-radius: 5px;"><i class="icon-phone"></i></a></li>
						<li><label for="phone_3" class="fwb">ช่าง</label></li>
					</ul>
				</td>
			</tr>

			<!-- สำหรับเว้นบรรทัด -->
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>

			<tr>
				<td width="33.33%">
					<ul>
						<li><a href="http://line.me/ti/p/~<?=$this->system['line_1']?>"><img src="<?=IMAGES?>/icon/line-me.png" style="height:60px; width:auto;"></a></li>
						<li><label for="phone_1" class="fwb">ฝ่ายบัญชี</label></li>
					</ul>
				</td>
				<td width="33.33%">
					<ul>
						<li><a href="http://line.me/ti/p/~<?=$this->system['line_2']?>"><img src="<?=IMAGES?>/icon/line-me.png" style="height:60px; width:auto;"></a></li>
						<li><label for="phone_2" class="fwb">ลูกค้าสัมพันธ์</label></li>
					</ul>
				</td>
				<td width="33.33%">
					<ul>
						<li><a href="http://line.me/ti/p/~<?=$this->system['line_3']?>"><img src="<?=IMAGES?>/icon/line-me.png" style="height:60px; width:auto;"></a></li>
						<li><label for="phone_3" class="fwb">ช่าง</label></li>
					</ul>
				</td>
			</tr>

			<!-- สำหรับเว้นบรรทัด -->
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>

			<tr>
				<td width="100%" colspan="3">
					<ul>
						<li><a href="<?=$this->system['facebook']?>" class="btn btn-blue btn-jumbo" style="border-radius: 5px; width: 50%; margin: 0px 10px 0px 10px;"><i class="icon-facebook"></i></a></li>
						<li><label for="phone_3" class="fwb">Facebook</label></li>
					</ul>
				</td>
			</tr>

		</table>
	</div>
</div>
