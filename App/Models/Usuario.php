<?php

    namespace App\Models; 
    
    use MF\Models\Model;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($name)
        {
           return $this->$name;
        }


        public function __set($name, $value)
        {
           $this->$name = $value; 
        }

        //salvar

        public function salvar(){
            $query = "insert into usuarios(nome, email, senha)values(:nome, :email, :senha)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            return $this;

        }

        public function validarCadastro(){
            $valido = true;

            if(strlen($this->__get('nome')) < 3){
                $valido = false;
            }

            if(strlen($this->__get('email')) < 3){
                $valido = false;
            }

            if(strlen($this->__get('senha')) < 8){
                $valido = false;
            }

            return $valido;
        }
        //recuperar um usuario po email
        public function getUsuarioEmail(){
            $query = "select nome, email from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autenticar(){
            $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue('email', $this->__get('email'));
            $stmt->bindValue('senha', $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($usuario['id'] != '' && $usuario['nome'] != ''){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);

            }

            return $this;

        }
        //metodo para pesquisa no banco de dados
        public function getAll(){
            $query = "
            select 
                id, nome, email 
             from 
                usuarios 
             where 
                nome like :nome and id != :id_usuario
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id')); //usado para nao pesquisar o id da sessao logado

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        




        
    }


?>