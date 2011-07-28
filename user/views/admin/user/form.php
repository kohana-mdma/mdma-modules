<?php if(Request::current()->action() == 'edit'): ?>
<h1>Редактировать пользователя</h1>
<?php else: ?>
<h1>Создать пользователя</h1>
<?php endif;?>
<form action="<?= Request::current()->url(); ?>" method="post" accept-charset="utf-8" class="uniform">
<dl>
    <dt><label for="username">Логин (без пробелов и спецсимволов):</label></dt>
    <dd><input id="username" type="text" name="username" value="<?= Arr::get($data, 'username'); ?>" /></dd>
    <dt><label for="email">E-mail:</label></dt>
    <dd><input id="email" type="text" name="email" value="<?= Arr::get($data, 'email'); ?>" /></dd>
    <dt><label for="password">Пароль:</label></dt>
    <dd><input id="password" type="password" name="password" value="" /></dd>
    <dt>Роли:</dt>
	<? foreach(ORM::factory('role')->find_all() as $role): ?>
    <dd><label><input id="role_<?= $role->id; ?>" type="checkbox" name="roles[<?= $role->id; ?>]" value="<?= $role->id; ?>" <?= (Arr::path($data, 'roles.'.$role->id))?'checked':''; ?> /><?= $role->name; ?> &mdash; <?= $role->description; ?></label></dd>
	<? endforeach;?>
</dl>
<p>
    <button type="submit" class="button big">Сохранить</button>
</p>
</form>