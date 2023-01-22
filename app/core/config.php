<?php

if($_SERVER['SERVER_NAME'] == "localhost") {
  // for local server
  define("ROOT", "http://localhost/melisma/public");
  define("DBDRIVER", "mysql");
  define("DBHOST", "localhost");
  define("DBUSER", "root");
  define("DBPASS", "spongebob");
  define("DBNAME", "melisma_db");
} 
else {
  // for online server
  define("ROOT", "http://www.melisma.com");
  define("DBDRIVER", "mysql");
  define("DBHOST", "localhost");
  define("DBUSER", "root");
  define("DBPASS", "spongebob");
  define("DBNAME", "melisma_db");
}

