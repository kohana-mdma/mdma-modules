<h1>Блоки</h1>
<form>
    <table id="table1" class="gtable sortable">
        <thead>
            <tr>
                <th><input type="checkbox" class="checkall" /></th>
                <th>Название</th>
                <th>Заголовок</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blocks as $block): ?>

            <tr>
                <td><input type="checkbox" /></td>
                <td><?= $block->id ?></td>
                <td><?= $block->name ?></td>
                <td>
                    <?php echo HTML::anchor('admin/block/edit/'.$block->id,'<img src="/images/icons/edit.png" alt="Редактировать" />', array('title'=>'Редактировать')); ?>
                    <?php echo HTML::anchor('admin/block/delete/'.$block->id, '<img src="/images/icons/cross.png" alt="Удалить" />', array('title'=>'Удалить')); ?>
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
<div><?php echo HTML::anchor('admin/block/add/', 'Добавить новый блок'); ?></div>