<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));
    

$applicationEnv = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $applicationEnv));

define('TABLE_STAFF', 'staff');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();

// database configuration
$config = new Zend_Config_Ini(
    APPLICATION_PATH . '/config/application.ini',
    APPLICATION_ENV
);
 
$db = Zend_Db::factory($config->database);
$db->getConnection();
$db->setFetchMode(Zend_Db::FETCH_OBJ);

$view = new Zend_View();

function getManageForm()
{
    $firstName = new Zend_Form_Element_Text('firstName', 
        array('label' => 'first name: ', 'required' => true)
    );
    $lastName = new Zend_Form_Element_Text('lastName', array('label' => 'last name: '));
    $emailAddress = new Zend_Form_Element_Text('emailAddress', 
        array('label' => 'email address: ', 'required' => true)
    );
    $occupation = new Zend_Form_Element_Text('occupation', array('label' => 'occupation: '));    
    $id = new Zend_Form_Element_Hidden('id', array('required' => true));
    $submit = new Zend_Form_Element_Submit('submit', 
        array('label' => 'Create User', 'ignore' => true)
    );
    
    $form = new Zend_Form;
    $form->setMethod('post')
         ->setAttrib('id', 'manage_user')
         ->addElement($firstName)
         ->addElement($lastName)
         ->addElement($emailAddress)
         ->addElement($occupation)
         ->addElement($submit)
         ->addElement($id);
         
    return $form;
}

function getDeleteForm()
{
    $id = new Zend_Form_Element_Text('id', 
        array('required' => true, 'readonly' => true, 'label' => 'user id: ')
    );
    $submit = new Zend_Form_Element_Submit('submit', 
        array('label' => 'Delete User', 'ignore' => true)
    );
    
    $form = new Zend_Form;
    $form->setMethod('post')
         ->setAttrib('id', 'delete_user')
         ->addElement($id)
         ->addElement($submit);
         
    return $form;
}