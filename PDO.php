<?php

$DBConnection= parse_ini_file("lab5.ini");
        extract($DBConnection);
        $pdo=new PDO($dsn, $user,$passwd);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        ?>

