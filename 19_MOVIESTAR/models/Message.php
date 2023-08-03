<?php

   class Message {
    private $url;

    public function __construct($url){
        $this->url = $url;
    }

    public function setMessage($msg, $type, $redirect = "auth.php"){
        $_SESSION["msg"] = $msg;
        $_SESSION["type"] = $type;

        // local para onde o usuário será direcionado
        if($redirect != "back") {
            header("Location: $this->url" . $redirect);
        }else{
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }

    }

    public function getMessage(){

        if(!empty($_SESSION["msg"])){
            return [
                "msg" => $_SESSION["msg"], // mensagem
                "type" => $_SESSION["type"] // tipo de mensagem, se é de erro, sucesso, etc.
            ];
        } else {
            return false;
        }

    }

    public function clearMessage(){
        // método para alimpar os campos de textos
        $_SESSION["msg"] = "";
        $_SESSION["type"] = "";
    }

   }