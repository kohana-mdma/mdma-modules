<h1>Пользователи</h1>
<form>
    <table id="table1" class="gtable sortable">
        <thead>
            <tr>
                <th><input type="checkbox" class="checkall" /></th>
                <th>Логин</th>
				<th>E-mail</th>
				<th>Последний логин</th>
				<th>Группы</th>
				<th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><input type="checkbox" /></td>
                <td><?= $user->username ?></td>
				<td><?= $user->email ?></td>
				<td><?= date(Date::$timestamp_format, $user->last_login); ?></td>
				<td><?= implode(', ', $user->roles->find_all()->as_array('id', 'name')); ?></td>
                <td>
                    <?php echo HTML::anchor('admin/user/edit/'.$user->id,'<img src="/images/icons/edit.png" alt="Редактировать" />', array('title'=>'Редактировать')); ?>
                    <?php echo HTML::anchor('admin/user/delete/'.$user->id, '<img src="/images/icons/cross.png" alt="Удалить" />', array('title'=>'Удалить')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="tablefooter clearfix">
        <div class="actions">
            <select>
                <option>Действие:</option>
                <option value="edit">Редактировать</option>
                <option value="delete">Удалить</option>
            </select>
            <button class="button small">Применить</button>
        </div>
    </div>
</form>
<div><?php echo HTML::anchor('admin/user/add/', 'Добавить нового пользователя'); ?></div>