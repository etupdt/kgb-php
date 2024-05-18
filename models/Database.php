<?php

class Database {

    // public static $host = "mysql:host=192.168.43.145;dbname=kgb";
    // public static $username = "kgb";
    // public static $password = "password";
    public static $host = $env["DATABASE_HOST"];
    public static $username = $env["DATABASE_USER"];
    public static $password = $env["DATABASE_PASSWORD"];
    // public static $host = "mysql:host=mysql-denist.alwaysdata.net;dbname=denist_kgb";
    // public static $username = "denist_kgb";
    // public static $password = "dsjfdERRfhks54-";

}
