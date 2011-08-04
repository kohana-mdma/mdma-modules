<?php if(Request::current()->action() == 'edit'): ?>
<h1>Редактировать страницу</h1>
<?php else: ?>
<h1>Создать страницу</h1>
<?php endif;?>
<form action="<?= Request::current()->url(); ?>" method="post" accept-charset="utf-8" class="uniform">
<dl>
    <dt><label for="page-title">Название:</label></dt>
    <dd><input id="page-title" type="text" name="page[title]" value="<?= Arr::path($data, 'page.title'); ?>" /></dd>

	<dt><label for="page-body">Текст:</label></dt>
    <dd><textarea name="page[body]" id="page-body" class="big editor" rows="15"><?= Arr::path($data, 'page.body'); ?></textarea></dd>

	<dt><label for="node-title">Заголовок окна:</label></dt>
    <dd><input id="node-title" type="text" name="node[title]" value="<?= Arr::path($data, 'node.title'); ?>" /></dd>

	<dt><label for="node-menu_title">Название пункта меню:</label></dt>
    <dd><input id="node-menu_title" type="text" name="page[menu_title]" value="<?= Arr::path($data, 'node.menu_title'); ?>" /></dd>

	<dt><label for="node-name">Псевдоним (название в URL; латиница, без пробелов и спецсимволов):</label></dt>
    <dd><input id="node-name" type="text" name="node[name]" value="<?= Arr::path($data, 'node.name'); ?>" /></dd>
    
	<dt><label for="page-keywords">Ключевые слова:</label></dt>
    <dd><input id="page-keywords" type="text" name="page[keywords]" value="<?= Arr::path($data, 'page.keywords'); ?>" /></dd>

	<dt><label for="page-description">Описание:</label></dt>
    <dd><input id="page-description" type="text" name="page[description]" value="<?= Arr::path($data, 'page.description'); ?>" /></dd>
</dl>
<p>
    <?php if( ! isset($version)):?>
    <button type="submit" class="button big">Сохранить</button>
    <?php else: ?>
    <button type="submit" class="button big">Восстановить</button>
    <?php endif;?>
</p>
</form>