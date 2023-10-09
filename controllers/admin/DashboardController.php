<?php

class DashboardController {

    public function index() { 
        require_once 'views/header.php';
        require_once 'views/admin/dashboard.php';
        require_once 'views/footer.php';
    }

}