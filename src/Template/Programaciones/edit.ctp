<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $programacione->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $programacione->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Programaciones'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Servicios'), ['controller' => 'Servicios', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Servicio'), ['controller' => 'Servicios', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="programaciones form large-9 medium-8 columns content">
    <?= $this->Form->create($programacione) ?>
    <fieldset>
        <legend><?= __('Edit Programacione') ?></legend>
        <?php
            echo $this->Form->control('monto');
            echo $this->Form->control('fecha');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
