<?php

// php cron.php -s site_admin -e lhcinsult -c lhcinsult/test

$insult = new erLhcoreClassLhcinsultWorker();
$insult->args = ['id' => 126749];
$insult->perform();

?>