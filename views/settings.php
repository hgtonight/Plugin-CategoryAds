<?php if (!defined('APPLICATION')) exit();
/* Copyright 2014 Zachary Doll */

echo Wrap($this->Data('Title'), 'h1');

$Categories = $this->Data('Categories');

echo $this->Form->Open();
echo $this->Form->Errors();

echo Wrap(Wrap(T('Plugin.CategoryAds.Desc'), 'div'), 'div', array('class' => 'Wrap'));
echo Wrap(Anchor(T('Add Category Ad'), 'settings/categoryads/add', array('class' => 'SmallButton')), 'div', array('class' => 'Wrap'));

?>
<table id="Actions" class="AltRows">
  <thead>
    <tr>
      <th><?php echo T('Category'); ?></th>
      <th><?php echo T('Name'); ?></th>
      <th><?php echo T('Body'); ?></th>
      <th><?php echo T('Display Count'); ?></th>
      <th><?php echo T('Options'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $Alt = 'Alt';
    foreach($this->Data('CategoryAds') as $CategoryAd) {
      $Alt = $Alt ? '' : 'Alt';
      $Row = '';
      $Row .= Wrap($Categories[$CategoryAd->CategoryID]['Name'], 'td');
      $Row .= Wrap($CategoryAd->Name, 'td');
      $Row .= Wrap($CategoryAd->Body, 'td');
      $Row .= Wrap($CategoryAd->DisplayCount, 'td');
      $Row .= Wrap(Anchor(T('Edit'), 'settings/categoryads/edit/' . $CategoryAd->CategoryAdID, array('class' => 'SmallButton')) . Anchor(T('Delete'), 'settings/categoryads/delete/' . $CategoryAd->CategoryAdID, array('class' => 'Danger PopConfirm SmallButton')), 'td');
      echo Wrap($Row, 'tr', array('id' => 'CategoryAdID_' . $CategoryAd->CategoryAdID, 'data-categoryadid' => $CategoryAd->CategoryAdID, 'class' => $Alt));
    }
    ?>
  </tbody>
</table>
