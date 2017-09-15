#!/usr/bin/env php
<?php
/*
* LibreNMS
*
* Copyright (c) 2015 Søren Friis Rosiak <sorenrosiak@gmail.com>
* This program is free software: you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the
* Free Software Foundation, either version 3 of the License, or (at your
* option) any later version.  Please see LICENSE.txt at the top level of
* the source code distribution for details.
*/

$init_modules = array();
require realpath(__DIR__ . '/..') . '/includes/init.php';

?>

+ LibreNMS
menu = LibreNMS Generated Targets
title = LibreNMS Generated Smokeping Targets

<?php

foreach (dbFetchRows("SELECT `type` FROM `devices` WHERE `ignore` = 0 AND `disabled` = 0 AND `type` != '' GROUP BY `type`") as $groups) {
    echo '++ ' . $groups['type'] . PHP_EOL;
    echo 'menu = ' . $groups['type'] . PHP_EOL;
    echo 'title = ' . $groups['type'] . PHP_EOL . PHP_EOL;
    foreach (dbFetchRows("SELECT `hostname`, `sysName` FROM `devices` WHERE `type` = ? AND `ignore` = 0 AND `disabled` = 0 order by `sysName`", array($groups['type'])) as $devices) {
        //Dot needs to be replaced, since smokeping doesn't accept it at this level
        echo '+++ ' . str_replace('.cloudplus.com', '', $devices['sysName']) . "_" . str_replace(".", "-", $devices['hostname']) . PHP_EOL;
        echo 'menu = ' . $devices['sysName'] . " [" . $devices['hostname'] . "]" . PHP_EOL;
        echo 'title = ' . $devices['sysName'] . " [" . $devices['hostname'] . "]" . PHP_EOL;
        echo 'host = ' . $devices['hostname'] . PHP_EOL . PHP_EOL;
    }
}
