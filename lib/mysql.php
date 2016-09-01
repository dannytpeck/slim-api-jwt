<?php

function connect_db() {
  $server = 'localhost';
  $user = 'dtpeck';
  $pass = '';
  $database = 'c9';
  $connection = new mysqli($server, $user, $pass, $database);
  
  return $connection;
}

?>