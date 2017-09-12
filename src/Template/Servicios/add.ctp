<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Servicios'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="servicios form large-9 medium-8 columns content">
    <?= $this->Form->create($servicio) ?>
    <fieldset>
        <legend><?= __('Add Servicio') ?></legend>
        <?php
            echo $this->Form->control('descripcion');
            echo $this->Form->control('dia');
            echo $this->Form->control('monto');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
