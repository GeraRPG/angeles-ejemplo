<?php
include_once ("libs/mail.php");
if (isset($_POST['user_name']))
{
    $mail = new mails();
    $mail->datosMail("gera@hubs.mx","CCK - Contacto - ".$_POST['user_asunto']);                        
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            //exit script outputting json data
            $output = json_encode(
            array(
                    'type'=>'error', 
                    'text' => 'Request must come from Ajax'
            ));
            
            die($output);
    }

	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["user_name"])  || !isset($_POST["user_mail"]) || !isset($_POST["user_phone"]) || !isset($_POST["user_asunto"]) || !isset($_POST["user_comentarios"]) )
	{
		$output = json_encode(array('type'=>'error', 'text' => '¡Un campo esta vacio!'));
		die($output);
        }
        
        //Sanitize input data using PHP filter_var().
        $user_Name = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
        $user_mail = filter_var($_POST["user_mail"], FILTER_SANITIZE_EMAIL);
        $user_phone  = filter_var($_POST["user_phone"], FILTER_SANITIZE_STRING);
        $user_asunto = filter_var($_POST['user_asunto'], FILTER_SANITIZE_STRING);
        $user_comentarios = filter_var($_POST["user_comentarios"], FILTER_SANITIZE_STRING);
	//additional php validation
	if(strlen($user_Name)<2) // If length is less than 3 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => '¡El nombre es demaciado corto!'));
		die($output);
    }
    if (strlen($user_phone)<10 || strlen($user_phone)>10)
    {
            $output = json_encode(array('type'=>'error', 'text'=>'¡El numero telefonico es invalido!'));
	        die($output);
	}
	if(!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => '¡Ingresa un correo valido!'));
		die($output);
    }
    if(strlen($user_asunto)<3)
    {  
        $output = json_encode(array('type'=>'error','text'=>'¡El asunto es demaciado corto!'));
        die($output);
    }
    if (strlen($user_comentarios)<3) {
        # code...   
        $output = json_encode(array('type'=>'error','text'=>'¡El comentario es demaciado corto!'));
    }
    
        $data  = array ("<br>",
                        "<strong>Nombre: </strong>". $user_Name ."<br>",
                        "<strong>Asunto: </strong>". $user_asunto."<br>",
                        "<strong>Telefono: </strong>". $user_phone ."<br>",
                        "<strong>Correo: </strong>". $user_mail ."<br>",
                        "<strong>Mensaje: </strong>". $user_comentarios ."<br>");
        $mail->contenido($data);
        $mail->user_mail($user_mail,"html");
        $res = $mail->enviarMAil();
        if(!$res)
	{
		$output = json_encode(array('type'=>'error', 'text' => '¡No se pudo enviar el correo!'));
		die($output);
	}else
	{
        $output = json_encode(array('type'=>'message', 'text' => '!Hola '.$user_Name.', Gracias por cominicarte con nosotros !'));
        die($output);
	}
}else{
        $output = json_encode(array('type'=>'message', 'text' => 'Error: 500'));
        die($output);
}