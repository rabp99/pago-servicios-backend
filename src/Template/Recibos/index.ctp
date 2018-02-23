<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Reciboe[]|\Cake\Collection\CollectionInterface $recibos
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Reciboe'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Servicios'), ['controller' => 'Servicios', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Servicio'), ['controller' => 'Servicios', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="recibos index large-9 medium-8 columns content">
    <h3><?= __('Recibos') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('servicio_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('monto') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fecha') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recibos as $programacione): ?>
            <tr>
                <td><?= $this->Number->format($programacione->id) ?></td>
                <td><?= $programacione->has('servicio') ? $this->Html->link($programacione->servicio->id, ['controller' => 'Servicios', 'action' => 'view', $programacione->servicio->id]) : '' ?></td>
                <td><?= $this->Number->format($programacione->monto) ?></td>
                <td><?= h($programacione->fecha) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $programacione->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $programacione->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $programacione->id], ['confirm' => __('Are you sure you want to delete # {0}?', $programacione->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
