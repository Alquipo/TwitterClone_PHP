<?php
    namespace App\Controllers;


    use MF\Controller\Action;
    use MF\Models\Container;

    class AppController extends Action{
        
        public function timeline(){
            session_start();

            if($_SESSION['id'] != '' && $_SESSION['nome'] != ''){
                $this->render('timeline');

            }else{
                header('Location: /?login=erro');
            }

            

        }
    }

?>