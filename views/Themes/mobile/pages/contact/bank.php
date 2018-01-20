<div class="web-profile">

	<div class="web-profile-header">
		<h1 class="fwb"><i class="icon-money"></i> วิธีชำระเงิน</h1>
	</div>

	<div class="web-profile-content post">
		<table class="table-bordered">
			<thead>
				<tr>
					<th width="35%">เลขที่</th>
					<th width="35%">ชื่อบัญชี</th>
					<th width="30%">ธนาคาร</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if( !empty($this->results) ){
					foreach ($this->results as $key => $value) {
						?>
						<tr>
							<td class="fwb"><?=$value['number']?></td>
							<td><?=$value['name']?></td>
							<td class="tac"><?=$value['bank_name']?></td>
						</tr>
						<?php
					}
				}
				else{
					echo '<tr>
							<td colspan="4" class="tac">
								<san class="fwb" style="color:red;">ไม่มีข้อมูล</span>
							</td>
						</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>