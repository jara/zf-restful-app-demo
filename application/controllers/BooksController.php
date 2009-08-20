<?php

class BooksController extends Zend_Rest_Controller
{
	
	private $_booksTable;
	private $_form;
	
    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $db = $bootstrap->getResource('db'); 			
		
		$options = $bootstrap->getOption('resources');
		$dbFile  = $options['db']['params']['dbname'];
		if (!file_exists($dbFile)) {
		    $createTable = "CREATE TABLE IF NOT EXISTS books (
    					id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    					name VARCHAR(32) NOT NULL,
					    price DECIMAL(5,2) NOT NULL
					)";
			$db->query($createTable);
			
			$insert1 = "INSERT INTO books (name, price) VALUES ('jQuery in Action', 39.99)";
			$insert2 = "INSERT INTO books (name, price) VALUES ('PHP in Action', 45.99)";
			$db->query($insert1);
	        $db->query($insert2);
		}
		
		$this->_booksTable = new Zend_Db_Table('books');
		$this->_form = new Default_Form_Book();
    }
	
    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */ 
    public function indexAction()
    {
        $this->view->books = $this->_booksTable->fetchAll();
    }
    
 	/**
     * The list action is the default for the rest controller
     * Forward to index
     */ 
    public function listAction()
    {
        $this->_forward('index');
    }
    
    /**
     * The get action handles GET requests and receives an 'id' parameter; it 
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */ 
    public function getAction()
    {
    	$this->view->book = $this->_booksTable->find($this->_getParam('id'))->current();
    }
    
	/**
     * Show the new book form
     */  
    public function newAction() {   	
    	$this->view->form = $this->_form;    	
    }
    
    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */  
    public function postAction() {    	
    	if ($this->_form->isValid($this->_request->getParams())) {
    		$this->_booksTable->createRow($this->_form->getValues())->save();      		
       		$this->_redirect('books');
    	} else {
    		$this->view->form = $this->_form;  
    		$this->render('new');
    	}
    }
    
 	/**
     * Show the edit book form. Url format: /books/edit/2
     */  
    public function editAction() {    	 
    	$book = $this->_booksTable->find($this->_getParam('edit'))->current(); 
    	$this->_form->populate($book->toArray());	
    	$this->view->form = $this->_form;
    	$this->view->book = $book;
    }
    
    /**
     * The put action handles PUT requests and receives an 'id' parameter; it 
     * should update the server resource state of the resource identified by 
     * the 'id' value.
     */  
    public function putAction() {
    	$book = $this->_booksTable->find($this->_getParam('id'))->current();
    	if ($this->_form->isValid($this->_request->getParams())) {    		
    		$book->setFromArray($this->_form->getValues())->save();      		
       		$this->_redirect('books');
    	} else {
    		$this->view->book = $book;
    		$this->view->form = $this->_form;  
    		$this->render('edit');
    	}
    }
    
    /**
     * The delete action handles DELETE requests and receives an 'id' 
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */  
    public function deleteAction() {
    	$book = $this->_booksTable->find($this->_getParam('id'))->current();
    	$book->delete();
    	$this->_redirect('books');
    }

}