<?php
/**
 * Classe pro modelar o tratamento de erros do sistema
 * Versão 1.0
 * Versão 1.1 Compativel. Adicionado parâmetro para mostrar a excessão na tela 
 * @author Odair
 */
namespace model;
class Erro {
    /**
     * Efetua o tratamento de erro padrão. Codigo 1000 para excessao prevista que deve ser mostrado mensagem para o usuário.
     * @param \Exception $ex Excessão gerada. 
     * @param string $urlRedirecionar
     * @param bool $mostrar
     */
    static function tratamentoPadrão(\Exception $ex, $urlRedirecionar = '', $mostrar = false){
        if($mostrar){
            echo $ex;
            exit;
        }
        else if($ex->getCode() == 1000){
            //Excessão invocada intencionalmente, deve ser mostrada ao usuário
            $_SESSION['notif_tipo'] = 'error';
            $_SESSION['notif_mensagem'] = $ex->getMessage();
            $urlRedirecionar = (empty($urlRedirecionar)) ? "" : "url=".$urlRedirecionar;
            header("Refresh:0;$urlRedirecionar");
            exit;
        }
        else{
            //Excessão gerada pelo código executado. Redirecionar para erro informando o usuario
            Erro::registra($ex);
            $fp = fopen("error.log", "a");
            fwrite($fp, $ex->getMessage());
            fclose($fp);
            $urlRedirecionar = (empty($urlRedirecionar)) ? "url=erro.php" : "url=".$urlRedirecionar;
            header("Refresh:0; $urlRedirecionar");
            exit;
        }
    }
    
    static function registra(\Exception $ex){
        $mensagem = date('Y-m-d H:i:s').'  '.$ex->getFile().':'.$ex->getLine().PHP_EOL;
        $mensagem .= 'Mensagem: '.$ex->getMessage().PHP_EOL;
        $mensagem .= 'Trace: '.$ex->getTraceAsString().PHP_EOL;
        $mensagem .= 'POST: '. print_r($_POST, true).PHP_EOL;
        $mensagem .= 'GET: '. print_r($_GET, true).PHP_EOL;
        $mensagem .= 'SESSION: '. print_r($_SESSION, true).PHP_EOL;
        //$mensagem .= 'SERVER: '. print_r($_SERVER, true).PHP_EOL.PHP_EOL;
        $fp = fopen("error.log", "a");
        fwrite($fp, $mensagem);
        fclose($fp);
    }
}
