<?php

class HomeController {

    public function index() { 
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/header.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/home/homePage.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/views/footer.php';
    }

}