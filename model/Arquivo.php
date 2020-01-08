<?php
namespace model;

/**
 * Description of Arquivo
 * 
 * Classe destinada a operacao de arquivos
 *
 * @author Rafael
 */
class Arquivo
{
    //put your code here
    protected $caminho;

    function __construct($caminho)
    {
        $this->caminho = $caminho;
    }

    function getCaminho()
    {
        return $this->caminho;
    }

    function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }

    function lerArquivo()
    {
        return file_get_contents($this->caminho);
    }

    function gravarArquivo($conteudo)
    {
        $file = fopen($this->caminho, 'w');
        fwrite($file, $conteudo);
        fclose($this->caminho);
    }
}
