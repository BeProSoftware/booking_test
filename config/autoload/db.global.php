<?php
/**
 * Created by PhpStorm.
 * User: webmaster
 * Date: 28/03/17
 * Time: 07:35 PM
 */

define('DBHOST','localhost');
define('DBNAME','booking_test');
define('DBUSER','root');
define('DBPASS','');


##################################
### RETURN ARRAY CONFIGURATION ###
##################################

return [
    'db' => [
        'driver' => 'Pdo_Mysql',
        'dsn'    => 'mysql:dbname='.DBNAME.';host='.DBHOST,
        'username' => DBUSER,
        'password' => DBPASS,
    ],
];