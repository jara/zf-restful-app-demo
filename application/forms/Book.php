<?php

class Default_Form_Book extends Zend_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        $this->addElement('text', 'name', array(
            'label'      => 'Name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(new Zend_Validate_Alnum(true))
        ));

        $this->addElement('text', 'price', array(
            'label'      => 'Price:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array('Float')
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Save Book',
        ));
    }
}
