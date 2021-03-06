<?php
    $this->assign('title', __d('notifications', 'notification contents.headline'));
?>
<h2 class="page-header">
	<?= __d('notifications', 'notification contents.headline') ?>
	<div class="pull-right">
		<?= $this->CkTools->addButton( __d('notifications', 'notification_contents.add')) ?>
	</div>
</h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('notification_identifier', __d('notifications', 'notification_content.notification_identifier')) ?></th>
			<th><?= $this->Paginator->sort('created', __d('notifications', 'notification_content.created')) ?></th>
			<th><?= $this->Paginator->sort('modified', __d('notifications', 'notification_content.modified')) ?></th>
			<th class="actions"><?= __d('notifications', 'notification_content.lists.actions') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($notificationContents as $notificationContent): ?>
		<tr>
			<td><?= h($notificationContent->notification_identifier) ?></td>
			<td><?= h($notificationContent->created) ?></td>
			<td><?= h($notificationContent->modified) ?></td>
			<td class="actions">
				<?= $this->Html->link('<span class="fa fa-pencil"></span><span class="sr-only">Bearbeiten</span>', ['action' => 'edit', $notificationContent->id], ['escape' => false, 'class' => 'btn btn-xs btn-default', 'title' => __d('notifications', 'notification_content.lists.edit')]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?= $this->Paginator->numbers() ?>
