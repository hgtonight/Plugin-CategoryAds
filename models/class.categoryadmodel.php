<?php if(!defined('APPLICATION')) exit();
/* Copyright 2014 Zachary Doll */
class CategoryAdModel extends Gdn_Model {

  public function __construct() {
    parent::__construct('CategoryAd');
  }
  
  public function GetAd($CategoryID = -1) {
    if($CategoryID > 0) {
      $Ad = $this->SQL
              ->Select()
              ->From('CategoryAd')
              ->Where('CategoryID', $CategoryID)
              ->OrderBy('DisplayCount', 'asc')
              ->Get()
              ->FirstRow();
      
      if($Ad) {
        $this->IncrementDisplayCount($Ad->CategoryAdID);
        return $Ad;
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }
  
  public function IncrementDisplayCount($AdID) {
    $this->SQL
            ->Update('CategoryAd')
            ->Set('DisplayCount', 'DisplayCount + 1', FALSE)
            ->Where('CategoryAdID', $AdID)
            ->Put();
  }
}
