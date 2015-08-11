<?php

/**
* 
*/
class AdminSupplierOrderManagerController extends ModuleAdminController
{
    protected $message = "Hello World";

    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();        
        $this->className = 'Order';
        parent::__construct();
    }


}