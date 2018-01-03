<?php

$form = new Form();
$form = $form->create()
	->url( URL ."me/change_password")
	->addClass('js-submit-form form-insert mbl clearfix')
	->method('post');

$form 	->field("new_password")
		->label("รหัสผ่านใหม่*")
		->addClass('inputtext')
		->autocomplete('off')
		->value( '' );

$form 	->field("new_password2")
		->label("ยืนยัน-รหัสผ่านใหม่*")
		->addClass('inputtext')
		->autocomplete('off')
		->value( '' );


$form->submit()
		->addClass("btn-submit btn btn-blue rfloat")
		->value("บันทึก");

?>

<section class="setting-section settings-accordion">
	<h3 class="fwb mbm">เปลี่ยนรหัสผ่าน</h3>
	<?=$form->html()?>
</section>