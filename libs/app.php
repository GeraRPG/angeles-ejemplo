<?php
require_once 'controllers/errores.php';
class App
{
    function __construct() 
    {
        //App Router
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        //LLamar al home
        if (empty($url[0])) {
            $archivoController = 'controllers/index.php';
            require $archivoController;
            $controller = new Index();
            $controller->loadModel('index');
            $controller->render();
            return false;    
        }else{
            $archivoController = 'controllers/' . $url[0] . '.php';
        }
        //Llamar a otros controladores
        if (file_exists($archivoController)){
            require $archivoController;
            $controller = new $url[0];
            $controller->loadModel($url[0]);
            // Se obtiene el número de param
            $nparam = sizeof($url);
            // Si se llama a un metodos
            if ($nparam > 1) {
                // Hay parametros
                if ($nparam > 2){
                    $param = [];
                    for ($i=2; $i < $nparam ; $i++) { 
                        array_push($param, $url[$i]);
                    }
                    $controller->{$url[1]}($param);
                }else{
                    // Solo se llama al método
                    $controller->{$url[1]}();
                }
            }else{
                // Si se llama a un controllador
                $controller->render();
            }
        }else{
            $controller = new Errores();
        }
    }
}