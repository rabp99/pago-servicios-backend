<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Servicio $servicio
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Servicio'), ['action' => 'edit', $servicio->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Servicio'), ['action' => 'delete', $servicio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $servicio->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Servicios'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Servicio'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="servicios view large-9 medium-8 columns content">
    <h3><?= h($servicio->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Descripcion') ?></th>
            <td><?= h($servicio->descripcion) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($servicio->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dia') ?></th>
            <td><?= $this->Number->format($servicio->dia) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Monto') ?></th>
            <td><?= $this->Number->format($servicio->monto) ?></td>
        </tr>
    </table>
</div>
