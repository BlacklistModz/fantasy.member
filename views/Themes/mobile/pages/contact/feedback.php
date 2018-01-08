<?php 
$form = new Form();
$form = $form 	->create()
				->elem('div')
				->addClass('form-insert');

$form 	->field("feed_title")
		->label("หัวข้อ/Title*")
		->addClass("inputtext")
		->autocomplete('off')
		->value('');

$form 	->field("feed_desc")
		->label("รายละเอียด")
		->addClass("inputtext")
		->autocomplete('off')
		->type('textarea')
		->attr('data-plugins', 'autosize');
?>
<div class="web-profile">

	<div class="web-profile-header">
		<h1 class="fwb"> กรอกคำติชม</h1>
	</div>

	<div class="web-profile-content post">
		<form class="js-submit-form" action="<?=URL?>contact/feedback">
			<?=$form->html()?>
			<div class="clearfix mtm">
				<button type="submit" class="js-submit btn btn-blue rfloat">ส่งคำติชม</button>
			</div>
		</form>
	</div>

	<div class="web-profile-content post">
		<h4 class="mts mbs"><i class="icon-list"></i> ประวัติการติชม</h4>
		<table class="table-bordered">
			<thead>
				<tr style="background-color: blue; color:white;">
					<th width="20%">วันที่</th>
					<th width="30%">หัวข้อ</th>
					<th width="50%">รายละเอียด</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if( !empty($this->results) ) { 
					foreach ($this->results as $key => $value) {
				?>
				<tr>
					<td valign="top" class="fwn"><?=date("d/m/Y", strtotime($value['created']))?></td>
					<td valign="top" class="fwb"><span class="mls"><?=$value['title']?></span></td>
					<td valign="top" class="fwb"><?=nl2br($value['desc'])?></td>
				</tr>
				<?php
					}
				}else{
					echo '<td colspan="3" class="tac"><span class="fwb" style="color:red;">ไม่มีข้อมูล</span></td>';
				} ?>
			</tbody>
		</table>
	</div>

</div>