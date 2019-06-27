<?php
class View
{
    function __construct()
    {
        //Vista base
    }
    function render($nombre)
    {
        require 'views/'.$nombre.'.php';
    }
}