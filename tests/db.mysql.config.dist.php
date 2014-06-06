<?php
return new \Phalcon\Config(array
(
    "database" => array
    (
        "adapter"  => "Mysql",
        "host"     => "{{DB_HOST}}",
        "username" => "{{DB_USERNAME}}",
        "password" => "{{DB_PASSWORD}}",
        "dbname"     => "{{DB_NAME}}",
    ),
));
