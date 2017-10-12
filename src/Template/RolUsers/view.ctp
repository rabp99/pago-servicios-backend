<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\RolUser $rolUser
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Rol User'), ['action' => 'edit', $rolUser->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Rol User'), ['action' => 'delete', $rolUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rolUser->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Rol Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rol User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="rolUsers view large-9 medium-8 columns content">
    <h3><?= h($rolUser->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= $rolUser->has('role') ? $this->Html->link($rolUser->role->id, ['controller' => 'Roles', 'action' => 'view', $rolUser->role->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($rolUser->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Id') ?></th>
            <td><?= $this->Number->format($rolUser->user_id) ?></td>
        </tr>
    </table>
</div>
