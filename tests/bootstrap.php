<?php

$testserver = realpath(__DIR__ . '/../bin/test-server');
$cmd = "nohup $testserver > /dev/null & echo $!";

echo "Starting HTTP Server...";
$pid = shell_exec($cmd);
echo " PID: $pid\n";

register_shutdown_function(function() use ($pid) {
    echo "Stopping HTTP Server...\n";
    shell_exec("kill $pid");
});
