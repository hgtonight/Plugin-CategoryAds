<?php if (!defined('APPLICATION')) exit();
/*	Copyright 2014 Zachary Doll
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
$PluginInfo['CategoryAds'] = array( // You put whatever you want to call your plugin folder as the key
	'Name' => 'Category Ads', // User friendly name, this is what will show up on the garden plugins page
	'Description' => 'A skeleton plugin that adds its resources to every page, creates a settings page, and creates a stub minicontroller.', // This is also shown on the garden plugins page. Will be used as the first line of the description if uploaded to the official addons repository at vanillaforums.org/addons
	'Version' => '0.1', // Anything can go here, but it is suggested that you use some type of naming convention; will appear on the garden vanilla plugins page
	'RequiredApplications' => array('Vanilla' => '2.0.18.8'), // Can require multiple applications (e.g. Vanilla and Conversations)
	'RequiredTheme' => FALSE, // Any prerequisite themes
	'RequiredPlugins' => FALSE, // Any prerequisite plugins
	'MobileFriendly' => FALSE, // Should this plugin be run on mobile devices?
	'HasLocale' => TRUE, // Does this plugin have its own local file?
	'RegisterPermissions' => FALSE, // E.g. array('Plugins.CategoryAds.Manage') will register this permissions automatically on enable
    'SettingsUrl' => '/settings/categoryads', // A settings button linked to this URL will show up on the garden plugins page when enabled
	'SettingsPermission' => 'Garden.Settings.Manage', // The permissions required to visit the settings page. Garden.Settings.Manage is suggested.
	'Author' => 'Zachary Doll', // This will appear in the garden plugins page
	'AuthorEmail' => 'hgtonight@daklutz.com',
	'AuthorUrl' => 'http://www.daklutz.com',
	'License' => 'GPLv3' // Specify your license to prevent ambiguity
);

class CategoryAds extends Gdn_Plugin {

	// add a Category Ads page on the settings controller
	public function SettingsController_CategoryAds_Create($Sender) {
		// add the admin side menu
		$Sender->AddSideMenu('settings/categoryads');
    $this->Dispatch($Sender, $Sender->RequestArgs);
	}
  
  public function Controller_Index($Sender) {
    $Sender->Title($this->GetPluginName() . ' ' . T('Settings'));
    
    $CategoryAdModel = new CategoryAdModel();
    $CategoryAds = $CategoryAdModel->Get();
    
    $Sender->SetData('CategoryAds', $CategoryAds);
		$Sender->Render($this->GetView('settings.php'));
  }
  
  public function Controller_Add($Sender) {
    $this->Controller_Edit($Sender);
  }
	
  public function Controller_Edit($Sender) {
    $Sender->Permission('Garden.Settings.Manage');
    
    $CategoryAdModel = new CategoryAdModel();
    $Sender->Form->SetModel($CategoryAdModel);

    $Sender->Title(T('Add Category Ad'));
    $Edit = FALSE;
    $AdID = GetValue(1, $Sender->RequestArgs, FALSE);
    if($AdID) {
      $Sender->CategoryAd = $CategoryAdModel->GetID($AdID);
      $Sender->Form->AddHidden('CategoryAdID', $AdID);
      $Edit = TRUE;
      $Sender->Title(T('Edit Category Ad'));
    }

    if($Sender->Form->IsPostBack() == FALSE) {
      if(property_exists($Sender, 'CategoryAd')) {
        $Sender->Form->SetData($Sender->CategoryAd);
      }
    }
    else {
      if($Sender->Form->Save()) {
        if($Edit) {
          $Sender->InformMessage(T('Category Ad updated successfully!'));
        }
        else {
          $Sender->InformMessage(T('Category Ad added successfully!'));
        }
        Redirect('/settings/categoryads');
      }
    }

    $Sender->Render($this->GetView('edit.php'));
  }
  
  public function Controller_Delete($Sender) {
    $CategoryAdModel = new CategoryAdModel();
    
    $AdID = GetValue(1, $Sender->RequestArgs, FALSE);
    $CategoryAd = $CategoryAdModel->GetID($AdID);

    if(!$CategoryAd) {
      throw NotFoundException(T('Category Ad'));
    }

    $Sender->Permission('Garden.Settings.Manage');

    $Sender->SetData('Title', T('Delete Category Ad'));
    if($Sender->Form->IsPostBack()) {
      if($CategoryAdModel->Delete($AdID)) {
        $Sender->Form->AddError(T('Unable to delete category ad!'));
      }

      if($Sender->Form->ErrorCount() == 0) {
        if($Sender->_DeliveryType === DELIVERY_TYPE_ALL) {
          Redirect('settings/categoryads');
        }

        $Sender->JsonTarget('#CategoryAdID_' . $AdID, NULL, 'SlideUp');
      }
    }
    $Sender->Render($this->GetView('delete.php'));
  }
  
  public function CategoriesController_Render_Before($Sender) {
    $this->AttachAdModule($Sender);
  }
  
  public function DiscussionController_Render_Before($Sender) {
    $this->AttachAdModule($Sender);
  }
  
  protected function AttachAdModule($Sender) {
    $CategoryID = $Sender->CategoryID;
    
    if($CategoryID) {
      $Module = new CategoryAdsModule($CategoryID);
      $Sender->AddModule($Module);
    }
  }
  
	private function _AddResources($Sender) {
		$Sender->AddJsFile($this->GetResource('js/categoryads.js', FALSE, FALSE));
		$Sender->AddCssFile($this->GetResource('design/categoryads.css', FALSE, FALSE));
	}
	
	public function Setup() {
    $this->Structure();
  }

  public function Structure() {
    $Database = Gdn::Database();
    $Construct = $Database->Structure();

    $Construct->Table('CategoryAd');
    $Construct
            ->PrimaryKey('CategoryAdID')
            ->Column('Name', 'varchar(100)', FALSE, 'fulltext')
            ->Column('Body', 'text', FALSE, 'fulltext')
            ->Column('CategoryID', 'int', FALSE)
            ->Column('DisplayCount', 'int', 0)
            ->Set();
  }

	public function OnDisable() {
		return TRUE;
	}
}
