<h1>Главная панель</h1>
<p>Добро пожаловать в административную панель!</p>

<? $icons = Kohana::config('admin.dashbord');?>
<? if($icons):?>
<h2>Быстрые кнопки</h2>
<section class="icons">
    <ul>
        <? foreach($icons as $icon):?>
		<li>
            <a href="<?=URL::site('admin/'.$icon['href']);?>">
                <img src="<?=URL::site($icon['image']);?>">
                <span><?=$icon['title'];?></span>
            </a>
        </li>
		<? endforeach; ?>
    </ul>
</section>
<? endif; ?>
<? foreach($widgets as $widget): ?>
	<div class="widget"><div><?=$widget->title;?></div><?=$widget->render();?></div>
<? endforeach; ?>