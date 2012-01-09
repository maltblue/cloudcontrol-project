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
$cacheKey = 'listresults';

$view = new Zend_View();

$manager = new Zend_Cache_Manager;
 
/*$dbCache = array(
    'frontend' => array(
        'name' => 'Core',
        'options' => array(
            'lifetime' => 7200,
            'automatic_serialization' => true
        )
    ),
    'backend' => array(
        'name' => 'Libmemcached',
        'options' => array(
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'persistent' => true,
                )
            )
        ),
    )
);*/
 
//$manager->setCacheTemplate('database', $dbCache);
$m = new Memcached();
$m->setOption(Memcached::OPT_BINARY_PROTOCOL, 1);
$m->setSaslData(
    $config->memcache->params->username, 
    $config->memcache->params->password
);
$m->addServer($config->memcache->params->hostname, 11211);

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

function profileQueries($db)
{
    $profiler = $db->getProfiler();
    $queryProfiles = $profiler->getQueryProfiles();
    $totalTime = 0;
    $queryCount = 0;
    $longestTime = 0;
    $longestQuery = '';
    
    foreach ($queryProfiles as $query) {
        if ($query->getElapsedSecs() > $longestTime) {
            $totalTime += $query->getElapsedSecs();
            $queryCount++;
            $longestTime  = $query->getElapsedSecs();
            $longestQuery = $query->getQuery();
        }
    }

    echo '<br />Executed ' . $queryCount . ' queries in ' . $totalTime .
         ' seconds' . "<br />";
    echo 'Average query length: ' . $totalTime / $queryCount .
         ' seconds' . "<br />";
    echo 'Queries per second: ' . $queryCount / $totalTime . "<br />";
    echo 'Longest query length: ' . $longestTime . "<br />";
    echo "Longest query: <br />" . $longestQuery . "<br />";
    
    $profiler->clear();
}

function clearCache($manager, $cacheKey)
{
    if ($manager->hasCache('database')) {
        $databaseCache = $manager->getCache('database');
        if (($databaseCache->test($cacheKey)) !== false) {
            $databaseCache->remove($cacheKey);
        }
    }
    echo "<br />cache cleared<br />";
}

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