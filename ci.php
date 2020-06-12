<?php

if ($_POST['payload']) {
    shell_exec('cd /home/crch1223/dev.christophecraig.com/bigred/ && git pull');
}
