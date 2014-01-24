<?php if (!defined('APPLICATION')) exit();

echo Wrap($this->Data('Title'), 'h1');

echo $this->Form->Open();
echo $this->Form->Errors();

echo '<div class="P">' . T('Are you sure you want to delete this category ad?') . '</div>';

echo '<div class="Buttons Buttons-Confirm">';
echo $this->Form->Button('OK', array('class' => 'Button Primary'));
echo $this->Form->Button('Cancel', array('type' => 'button', 'class' => 'Button Close'));
echo '<div>';
echo $this->Form->Close();
