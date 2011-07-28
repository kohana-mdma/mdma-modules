<h1>Виджеты</h1>
<form>
    <table id="table1" class="gtable sortable">
        <thead>
            <tr>
                <th><input type="checkbox" class="checkall" /></th>
                <th>Название</th>
				<th>Активность</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><input type="checkbox" /></td>
                <td><?= $item->title ?></td>
				<td><?= ($item->enable==1)?'Вкл':'Выкл'; ?></td>
                <td>
                    <?php echo HTML::anchor('admin/widget/enable/'.$item->id,'<img src="/images/icons/enable.png" alt="Вкл/Выкл" />', array('title'=>'Вкл/Выкл')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="tablefooter clearfix">
    </div>
</form>