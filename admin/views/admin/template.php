<!DOCTYPE HTML>
<html lang="en">
<head>
<title>Админка</title>
<meta charset="utf-8">

<link rel="stylesheet" type="text/css" href="/css/admin/style.css">

<link rel="stylesheet" type="text/css" href="/css/admin/superfish.css">
<link rel="stylesheet" type="text/css" href="/css/admin/uniform.default.css">
<link rel="stylesheet" type="text/css" href="/css/admin/smoothness/jquery-ui-1.8.8.custom.css">

<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="/js/superfish.js"></script>
<script type="text/javascript" src="/js/jquery.flot.min.js"></script>
<script type="text/javascript" src="/js/custom.js"></script>
<script type="text/javascript" src="/js/editor/editor.js"></script>
<link rel="stylesheet" type="text/css" href="/js/editor/css/editor.css">

<script type="text/javascript" src="/js/jstree/jquery.jstree.js"></script>
<script type="text/javascript" src="/js/jstree/_lib/jquery.cookie.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jstree/themes/default/style.css">

</head>
<body class="fluid">

<header id="top">
    <div class="container_12 clearfix">
        <div id="logo" class="grid_8">
            <a id="site-title" href="/admin/"><span>Административный</span> раздел</a>
            <a id="view-site" href="/">Перейти на <?= URL::base('http'); ?></a>
        </div>

        <div id="userinfo" class="grid_4">
            Здравствуйте, <a href="/admin/user/"><?= Auth::instance()->get_user()->username;?></a>
        </div>
    </div>
</header>

<nav id="topmenu">
    <div class="container_12 clearfix">
        <div class="grid_12">
            <ul id="mainmenu" class="sf-menu">
				<? foreach($top_menu as $item): ?>
				<li<?=(Request::detect_uri()=='admin/'.trim(Arr::get($item, 'url'),'/'))?' class="current"':'';?>>
					<?=Html::anchor('admin/'.Arr::get($item, 'url'), Arr::get($item, 'label'), Arr::get($item, 'attr'));?>
					<? if(Arr::get($item, 'childrens')): ?>
					<ul>
					<? foreach($item['childrens'] as $children): ?>
						<li<?=(Request::detect_uri()=='admin/'.trim(Arr::get($children, 'url'),'/'))?' class="current"':'';?>>
							<?=Html::anchor('admin/'.Arr::get($children, 'url'), Arr::get($children, 'label'), Arr::get($children, 'attr'));?>
						</li>
					<? endforeach; ?>
					</ul>
					<? endif; ?>
				</li>
				<? endforeach; ?>
            </ul>
            <ul id="usermenu">
                <li><a href="/admin/auth/logout/">Выйти</a></li>
            </ul>
        </div>
    </div>
</nav>

<section id="content">
    <section class="container_12 clearfix">
        <section id="main" class="grid_9 push_3">
            <article>
                <?php echo Message::display(); ?>
                <?php echo $content; ?>
            </article>
        </section>

        <aside id="sidebar" class="grid_3 pull_9">
            <div class="box menu">
                <h2>Страницы</h2>
                <section>
                <?php
				$nodes = ORM::factory('node')->load_tree();
				$render = function ($nodes, $render) {
					$result = "";
					foreach ($nodes->children as $node) {
						$link = '';
						if($node->type == 'root'){
							$link = 'admin/'.$node->model;
						}elseif($node->type == 'add'){
							$link = 'admin/'.$node->model.'/add/node/'.$node->id;
						}elseif($node->model_id){
							$link = 'admin/'.$node->model.'/edit/'.$node->model_id;
						}
						$result  .= '<li id="node_'.$node->id.'" rel="'.$node->model.'.'.$node->type.'">'.HTML::anchor($link, $node->menu_title).$render($node, $render).'</li>';
					}
					if($result) $result = '<ul>'.$result.'</ul>';
					return $result;
				};
				echo '<ul><li id="node_root" rel="root"><a href="/admin/config">'.URL::base('http').'</a>'.$render($nodes, $render).'</li></ul>';
				?>
                </section>
            </div>

            <?php foreach((array) $sidebar as $item):?>
				<?= $item; ?>
			<?php endforeach;?>

            <!-- <div class="box search">
                <form>
                    <label for="s">Поиск:</label>
                    <input id="s" type="text" size="20" />
                    <button class="button small">Go</button>
                </form>
            </div> -->
        </aside>
    </section>
</section>

<footer id="bottom">
    <section class="container_12 clearfix">
        <div class="grid_6">&nbsp;
            <!-- <a href="#">Menu 1</a>
            &middot; <a href="#">Menu 2</a>
            &middot; <a href="#">Menu 3</a>
            &middot; <a href="#">Menu 4</a>-->
        </div>
        <div class="grid_6 alignright">
            Copyright &copy; 2011
        </div>
    </section>
</footer>

</body>
</html>