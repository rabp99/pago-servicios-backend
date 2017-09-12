<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Programacione $programacione
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Programacione'), ['action' => 'edit', $programacione->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Programacione'), ['action' => 'delete', $programacione->id], ['confirm' => __('Are you sure you want to delete # {0}?', $programacione->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Programaciones'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Programacione'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Servicios'), ['controller' => 'Servicios', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Servicio'), ['controller' => 'Servicios', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="programaciones view large-9 medium-8 columns content">
    <h3><?= h($programacione->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Servicio') ?></th>
            <td><?= $programacione->has('servicio') ? $this->Html->link($programacione->servicio->id, ['controller' => 'Servicios', 'action' => 'view', $programacione->servicio->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($programacione->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Monto') ?></th>
            <td><?= $this->Number->format($programacione->monto) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fecha') ?></th>
            <td><?= h($programacione->fecha) ?></td>
        </tr>
    </table>
</div>
