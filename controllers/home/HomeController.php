<?php

class HomeController {

    public function index() { 
        require_once 'views/header.php';
        require_once 'views/home/homePage.php';
        require_once 'views/footer.php';
    }

}