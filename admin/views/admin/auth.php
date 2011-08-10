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

</head>
<body class="fluid">

<header id="top">
    <div class="container_12 clearfix">
        <div id="logo" class="grid_8">
            <a id="site-title" href="#"><span>Административный</span> раздел</a>
            <a id="view-site" href="/">Перейти на <?= URL::base('http'); ?></a>
        </div>
    </div>
</header>

<div id="login" class="box">
    <h2>Авторизация</h2>
    <section>
        <?php echo Message::render();?>
        <?=form::open('admin/auth/login')?>
        <dl>
            <dt><label for="username">Пользователь</label></dt>
            <dd><input id="username" type="text" name="username"/></dd>
            <dt><label for="password">Пароль</label></dt>
            <dd><input id="password" type="password" name="password"/></dd>
        </dl>
        <label for="autologin"><input type="checkbox" name="autologin"/>Запомнить меня</label>
        <p>
        <button type="submit" class="button gray" id="loginbtn">Войти</button>
        </p>
        <?=form::close()?>
    </section>
</div>

</body>
</html>