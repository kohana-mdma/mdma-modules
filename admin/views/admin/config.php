<form action="<?= Request::current()->url(); ?>" method="post" accept-charset="utf-8" class="uniform">
<dl>
    <? foreach($items as $key => $value): ?>
	<dt><label for="<?=$key;?>"><?=$key;?></label></dt>
    <dd><input id="<?=$key;?>" type="text" name="config[<?=strtr($key, '.','_');?>]" value="<?=$value;?>" /></dd>
	<? endforeach; ?>
</dl>
<p>
    <button type="submit" class="button big">Сохранить</button>
</p>
</form>