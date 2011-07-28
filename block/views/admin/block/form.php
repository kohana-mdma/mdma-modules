<?php if(Request::current()->action() == 'edit'): ?>
<h1>Редактировать блок</h1>
<?php else: ?>
<h1>Создать блок</h1>
<?php endif;?>
<form action="<?= Request::current()->url(); ?>" method="post" accept-charset="utf-8" class="uniform model-block">
<dl>
    <dt><label for="id">Название (без пробелов и спецсимволов):</label></dt>
    <dd><input id="id" type="text" name="id" value="<?= Arr::get($data, 'id'); ?>" <?php if(isset($version)){?>disabled="disabled" <?php }?>/></dd>
    <dt><label for="name">Заголовок:</label></dt>
    <dd><input id="name" type="text" name="name" value="<?= Arr::get($data, 'name'); ?>" /></dd>
    <dt><label for="body">Текст:</label></dt>
    <dd><textarea name="body" id="body" class="big editor id-<?= Arr::get($data, 'id'); ?>" rows="15"><?= Arr::get($data, 'body'); ?></textarea></dd>
</dl>
<p>
    <?php if( ! isset($version)):?>
    <button type="submit" class="button big">Сохранить</button>
    <?php else: ?>
    <button type="submit" class="button big">Восстановить</button>
    <?php endif;?>
</p>
</form>