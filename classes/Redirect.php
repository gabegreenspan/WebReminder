<?php
class Redirect {    
    public static function to ($location = null) {
        if($location) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:   // Can add other errors the same way.
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                    break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}

    //This is a redirect class that redirects the user to an error template page if they incounter errors 
        // that we include in the redirect errors list.