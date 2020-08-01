<?php

use Sim\Crypt\Crypt;
use Sim\Crypt\Exceptions\CryptException;
use Sim\Session\Session;

include_once '../../vendor/autoload.php';

$main_key = 'fDhIL1dmU2swMyl+VEUxR3gkJWRJO0RQNUxRUks2aFZZKDJsOVhVYzdCNE52eiEreU9fPkA=';
$assured_key = 'eCtYfHRDOFVsOSV6aTZBNyk6Lyg+MGc0MTI8NTNKTXk=';
try {
    $crypt = new Crypt($main_key, $assured_key);
} catch (CryptException $e) {
    echo $e;
}

$session = new Session($crypt);
$session->set('key1.key2', 'hello');
$hello = $session->get('key1.key2');
echo PHP_EOL . $hello . PHP_EOL;

$session->setFlash('key2', 'I am flash data');
$flash_data = $session->getFlash('key2');
$no_longer_exists = $session->getFlash('key2');
echo PHP_EOL . $flash_data . PHP_EOL;
echo PHP_EOL . 'And now flash data is gone, current value is: ' .
    (is_null($no_longer_exists) ? 'null' : $no_longer_exists) . PHP_EOL;

echo PHP_EOL . 'I add a session with global session variable' . PHP_EOL;
$_SESSION['val'] = 'simply added';

echo PHP_EOL;
// get all sessions
echo 'All sessions:' . PHP_EOL;
var_dump($session->get());
echo PHP_EOL;

echo PHP_EOL . 'test timed session (after 10 seconds it will expire):' . PHP_EOL;
// uncomment me if you want set a 10 seconds session
//$session->setTimed('timed', 'timed sessions are awesome', 10);
if ($session->hasTimed('timed')) {
    echo $session->getTimed('timed') . PHP_EOL;
} else {
    echo 'timed session has been ended! cool' . PHP_EOL;
}
