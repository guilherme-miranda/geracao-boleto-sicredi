<?php
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires'); //sem horário de verão

include_once "vendor/autoload.php";

include_once "model/Arquivo.php";
include_once "model/Data.php";
include_once "model/Erro.php";
include_once "model/PagamentoSicrediBoleto.php";
