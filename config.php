<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APPLICATION_PATH', '/');
define('TABLE_STAFF', 'staff');

$path = APPLICATION_PATH . 'library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once('Zend/Loader/Autoloader.php');

$autoloader = Zend_Loader_Autoloader::getInstance();

// database configuration
$options = array(Zend_Db::AUTO_QUOTE_IDENTIFIERS => false);
$pdoParams = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true);
$config = new Zend_Config(
    array(
        'database' => array(
            'adapter' => 'Pdo_Mysql',
            'params'  => array(
                'host'     => '127.0.0.1',
                'username' => 'dep5jfkw8gf',
                'password' => 'grSyvSLuShGyHhp',
                'dbname'   => 'dep5jfkw8gf',
                'options'  => $options,
                'driver_options' => $pdoParams,
            )
        )
    )
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