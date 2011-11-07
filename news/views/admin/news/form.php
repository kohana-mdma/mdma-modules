<?php if(Request::current()->action() == 'edit'): ?>
<h1>Редактировать</h1>
<?php else: ?>
<h1>Создать</h1>
<?php endif;?>
<form action="<?= Request::initial()->url(); ?>" method="post" accept-charset="utf-8" class="uniform model-<?=$_model?>" enctype="multipart/form-data">
	<dl>
	<dt><label for="news-title">Заголовок:</label></dt>
		<dd><input id="news-title" type="text" name="news[title]" value="<?= Arr::path($data, 'news.title'); ?>" /></dd>
		<dt><label for="news-description">Описание:</label></dt>
		<dd><textarea name="news[description]" id="news-description" class="big editor" rows="15"><?= Arr::path($data, 'news.description'); ?></textarea></dd>
		<dt><label for="news-text">Текст:</label></dt>
		<dd><textarea name="news[text]" id="news-text" class="big editor" rows="15"><?= Arr::path($data, 'news.text'); ?></textarea></dd>
		<dt><label for="news-created_on">Дата публикация (оставить пустым если опубликовать сейчас):</label></dt>
		<dd><input id="news-created_on" type="text" name="news[created_on]" value="<?= Arr::path($data, 'news.created_on'); ?>" /></dd>
		<dt><label for="node-title">Заголовок окна:</label></dt>
		<dd><input id="node-title" type="text" name="node[title]" value="<?= Arr::path($data, 'node.title'); ?>" /></dd>
		<dt><label for="node-name">Псевдоним (название в URL; латиница, без пробелов и спецсимволов):</label></dt>
		<dd><?=Form::input('node[name]', Arr::path($data, 'node.name'), array('id'=>'node-name'));?></dd>
    	<dt><?=Form::label('node-keywords', 'Ключевые слова:');?></dt>
		<dd><?=Form::textarea('node[keywords]', Arr::path($data, 'node.keywords'), array('id'=>'node-keywords'));?></dd>
		<dt><label for="node-description">Описание:</label></dt>
		<dd><?=Form::textarea('node[description]', Arr::path($data, 'node.description'), array('id'=>'node-description'));?></dd>
	</dl>
	<p>
        <button type="submit" class="button big">Сохранить</button>
    </p>
</form>