<?php

class HomeController {

    public function index() { 
        require_once 'views/header.php';
        echo '<main class="container d-flex justify-content-center align-items-center h-100">
            <img class="w-75" src="assets/images.jfif"/>
        </main>';
        require_once 'views/footer.php';
    }

}