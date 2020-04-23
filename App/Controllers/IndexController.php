<?php
    namespace App\Controllers;


use MF\Controller\Action;
use MF\Models\Container;
    

    class IndexController extends Action{

        public function index(){
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            $this->render('index');

        }

        public function inscreverse(){
            //corrigir o erro da view inscreverse
            $this->view->usuario = array(
                'nome'=> '',
                'email'=> '',
                'senha'=> ''
            );
            $this->view->erroCadastro = false;

            $this->render('inscreverse');

        }

        public function registrar(){

          
           //recever os dados do usuario

           $usuario = Container::getModel('Usuario');

           $usuario->__set('nome', $_POST['nome']);
           $usuario->__set('email', $_POST['email']);
           $usuario->__set('senha', md5($_POST['senha']));

           //controle para saber se o usuario ja existe

           if($usuario->validarCadastro() == true && count($usuario->getUsuarioEmail()) == 0){
           
            
                $usuario->salvar();
                $this->render('cadastro');
           
           
           }else{
               //   QUANDO DER ERRO APARECER OS CAMPOS PREENCHIDOS NO FORM
                $this->view->usuario = array(
                    'nome'=> $_POST['nome'],
                    'email'=> $_POST['email'],
                    'senha'=> $_POST['senha']
                );
                $this->view->erroCadastro = true;

                $this->render('inscreverse');  
           }
           

        }

       

    }
