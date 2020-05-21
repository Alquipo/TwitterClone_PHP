<?php
    namespace App\Controllers;


    use MF\Controller\Action;
    use MF\Models\Container;

    class AppController extends Action{
        
        public function timeline(){
            
            $this->validaAutenticacao();

            //recuperação dos tweets
            $tweet = Container::getModel('Tweet');
               
            $tweet->__set('id_usuario', $_SESSION['id']);
     
                             
            $this->view->tweet = $tweet->getAll();

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);



            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotaTweets();
            $this->view->total_seguindo = $usuario->getTotaSeguindo();
            $this->view->total_seguidores = $usuario->getTotaSeguidores();

            $this->render('timeline');


        }

        public function tweet(){
            
            $this->validaAutenticacao();
            
            
            $tweet =  Container::getModel('Tweet');
            
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweet->salvar();

            header('Location: /timeline');
                   
        }

        public function deletarTweet(){
            
            $this->validaAutenticacao();
            
            
            $tweet =  Container::getModel('Tweet');

            // print_r($_POST);

            $tweet->__set('id', $_POST['id_tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);

           // print_r($tweet);
            $tweet->deletar();

            header('Location: /timeline');
                   
        }

        public function validaAutenticacao(){
            session_start();
           
            if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
                header('Location: /?login=erro');

            }
        }


        public function quemSeguir(){
            $this->validaAutenticacao();

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

            $usuarios = array();
            
            if($pesquisarPor != ''){
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);//usado para nao mostrar o usuario da sessao
                $usuarios = $usuario->getAll();

               
            }
            $this->view->usuarios = $usuarios;

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);



            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotaTweets();
            $this->view->total_seguindo = $usuario->getTotaSeguindo();
            $this->view->total_seguidores = $usuario->getTotaSeguidores();
            
            $this->render('quemSeguir');

        }

        public function acao(){
            $this->validaAutenticacao();

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            if($acao == 'seguir'){
                $usuario->seguirUsuario($id_usuario_seguindo);
            }else if($acao == 'deixar_de_seguir'){
                $usuario->deixarSeguirUsuario($id_usuario_seguindo);
                
            }

            header('Location: /quem_seguir');

        }

    }

?>