<?php if (!defined('APPLICATION')) exit();
/*	Copyright 2013 Zachary Doll
*	This program is free software: you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License
*	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class CategoryAdsModule extends Gdn_Module {
	private $_CategoryAdData;
  
	public function __construct($CategoryID = -1) {
    $CategoryAdModel = new CategoryAdModel();
    
		$this->_CategoryAdData = $CategoryAdModel->GetAd($CategoryID);
    parent::__construct('');
	}

	// Required for modules. Tells the controller where to render the module.
	public function AssetTarget() {
		return 'Panel';
	}

	// Required for module to render something
	public function ToString() {
    if($this->_CategoryAdData) {
      $Ad = $this->_CategoryAdData;
      $String = Wrap($Ad->Name, 'h1');
      $String .= $Ad->Body;
      echo Wrap($String, 'div', array('class' => 'Box BoxCategoryAd'));
    }
    else {
      return '';
    }
	}
}