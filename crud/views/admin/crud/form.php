<?php if(Request::current()->action() == 'edit'): ?>
<h1>Редактировать</h1>
<?php else: ?>
<h1>Создать</h1>
<?php endif;?>
<? 
$belongs_to = array();
foreach(ORM::factory($_model)->belongs_to() as $key=>$to){
	$belongs_to[$to['foreign_key']] = $key;
};
?>
<form action="<?= Request::current()->url(); ?>" method="post" accept-charset="utf-8" class="uniform model-<?=$_model?>" enctype="multipart/form-data">
<dl>
	<? foreach(Arr::path($data, 'node', (Request::current()->param('node')) ? array('name'=>NULL) : array()) as $key=>$node_value): ?>
		<? if($key == 'name'): ?>
		<dt><label for="<?='node-'.$key;?>"><?= Arr::get(ORM::factory('node')->labels(), $key, $key);?>:</label></dt>
		<dd><input id="<?='node-'.$key;?>" type="text" name="<?='node['.$key.']';?>" value="<?= Arr::path($data, 'node.'.$key); ?>" /></dd>
		<? endif; ?>
	<? endforeach; ?>
    <? foreach(ORM::factory($_model)->table_columns() as $key=>$option): ?>
	<? if($key == ORM::factory($_model)->primary_key()): ?>
		<? if(Request::current()->action() == 'edit'): ?>
			<dt><label for="<?=$_model.'-'.$key;?>"><?= Arr::get(ORM::factory($_model)->labels(), $key, $key);?>:</label></dt>
			<dd><input id="<?=$_model.'-'.$key;?>" type="text" name="<?=$_model.'['.$key.']';?>" value="<?= Arr::path($data, $_model.'.'.$key); ?>" disabled="disabled" /></dd>
		<? endif; ?>

	<? elseif(UTF8::strrpos($option['data_type'], 'text') !== FALSE): ?>
		<dt><label for="<?=$_model.'-'.$key;?>"><?= Arr::get(ORM::factory($_model)->labels(), $key, $key);?>:</label></dt>
		<dd><textarea name="<?=$_model.'['.$key.']';?>" id="<?=$_model.'-'.$key;?>" class="big editor" rows="15"><?= Arr::path($data, $_model.'.'.$key); ?></textarea></dd>

	<? elseif(array_key_exists($key, $belongs_to)): ?>
		<dt><label for="<?=$_model.'-'.$key;?>"><?= Arr::get(ORM::factory($_model)->labels(), $key, $key);?>:</label></dt>
		<dd><input type="hidden" name="<?=$_model.'['.$key.'][form]';?>" value="1" /> 
		<?php 
		echo Form::select(
			$_model.'['.$key.']',
			ORM::factory($belongs_to[$key])->find_all()->as_array('id','title'),
			Arr::path($data, $_model.'.'.$key),
			array('id'=>$_model.'-'.$key)
		);
		?></dd>
	<? else: ?>
		<dt><label for="<?=$_model.'-'.$key;?>"><?= Arr::get(ORM::factory($_model)->labels(), $key, $key);?>:</label></dt>
		<dd><input id="<?=$_model.'-'.$key;?>" type="text" name="<?=$_model.'['.$key.']';?>" value="<?= Arr::path($data, $_model.'.'.$key); ?>" /></dd>

	<? endif; ?>
	<? endforeach; ?>
	<? foreach(ORM::factory($_model)->has_many() as $key=>$item): ?>
		<dt><label for="<?=$_model.'-'.$key;?>"><?= Arr::get(ORM::factory($_model)->labels(), $key, $key);?>:</label></dt>
		<dd><input type="hidden" name="<?=$_model.'['.$key.'][form]';?>" value="1" /> 
		<?php
		echo Form::select(
			$_model.'['.$key.'][]',
			ORM::factory($item['model'])->find_all()->as_array('id','title'),
			ORM::factory($_model, Arr::path($data, $_model.'.id'))->$key->find_all()->as_array(NULL, 'id'),
			Arr::path($data, $_model.'.'.$key),
			array('id'=>$_model.'-'.$key)
		); ?>
		</dd>
	<? endforeach;?>
	
</dl>
<p>
    <?php if( ! isset($version)):?>
    <button type="submit" class="button big">Сохранить</button>
    <?php else: ?>
    <button type="submit" class="button big">Востановить</button>
    <?php endif;?>
</p>
</form>