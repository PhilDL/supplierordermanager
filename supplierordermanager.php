<?php

/* Security */
if (!defined('_PS_VERSION_'))
    exit;

/* Checking compatibility with older PrestaShop and fixing it */
if (!defined('_MYSQL_ENGINE_'))
    define('_MYSQL_ENGINE_', 'MyISAM');
 
class SupplierOrderManager extends Module
{
    /* @var boolean error */
    protected $_errors = false;
     
    public function __construct()
    {
        $this->author = 'Philippe';
        $this->name = 'supplierordermanager';
        $this->tab = 'administration';
        $this->version = '0.0.1';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);    
        $this->need_instance = 0;
 
        parent::__construct();
 
        $this->displayName = $this->l('Supplier\'s Orders Manager');
        $this->description = $this->l('Manage your orders to your suppliers generate PDF and bills');    
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
    }

    public function install()
    {
        if (!parent::install() OR
            !$this->alterTable('add') OR
            !$this->adminInstall())
            return false;
        return true;
    }

    private function adminInstall() {

        if(!Tab::getIdFromClassName('AdminSupplierOrderManager')){
            $tab = new Tab();
            $tab->class_name = 'AdminSupplierOrderManager';
            $tab->id_parent = 10;
            $tab->module = $this->name;
            $languages = Language::getLanguages(false);
            foreach($languages as $lang){
                $tab->name[$lang['id_lang']] = 'Commandes aux Fournisseurs';
            }
            $tab->save();
        }
        
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() OR !$this->alterTable('remove') OR !$this->adminUninstall())
            return false;
        return true;
    }    

    public function adminUninstall()
    {
        if (! $tab_id = Tab::getIdFromClassName('AdminSupplierOrderManager')) {
            return false;
        }
        else{
            $tab = new Tab($tab_id);
            return $tab->delete();
        }
    }    


    public function alterTable($method)
    {
        switch ($method) {
            case 'add':
                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail ADD `id_suppliers_order` INT ';
                break;
             
            case 'remove':
                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail DROP COLUMN `id_suppliers_order`';
                break;
        }
         
        if(!Db::getInstance()->Execute($sql))
            return false;
        return true;
    }    
         
}