<?php

class IndexController extends Zend_Controller_Action
{
	
    public function indexAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $db = $bootstrap->getResource('db'); 			
		
        $options = $bootstrap->getOption('resources');
        $dbFile  = $options['db']['params']['dbname'];
        if (file_exists($dbFile)) {
            unlink($dbFile);
        }
    }
    
}