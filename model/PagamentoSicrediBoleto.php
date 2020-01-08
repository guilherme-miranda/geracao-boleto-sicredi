<?php

namespace model;

use Curl\Curl;

include '../inc.php';

/**
 * Classe responsável por realizar a comunicação com a api do Sicredi
 *
 * @author Odair
 * @author Rafael
 */
class PagamentoSicrediBoleto
{

    const tokenMaster = "***************"; //chave Mestre
    const agencia = "****"; //agência do beneficiário
    const posto = "**";     //posto do beneficiário
    const cedente = "*****"; //código do cedente

    protected $tokenTransacional;

    function __construct()
    {
        $this->tokenMaster = PagamentoSicrediBoleto::tokenMaster;
        $this->agencia = PagamentoSicrediBoleto::agencia;
        $this->posto = PagamentoSicrediBoleto::posto;
        $this->cedente = PagamentoSicrediBoleto::cedente;
    }

    /**
     * Faz login na api e salva o token
     */
    function getTokenTransacional()
    {
        $arquivo = new Arquivo("tokenTransacional.json");

        $json = $arquivo->lerArquivo();
        $data = json_decode($json, true);

        $resposta = null;

        if (!empty($data['dataExpiracao'])) {
            $dataExpiracao = \DateTime::createFromFormat("Y-m-d\TH:i:s.uP", $data['dataExpiracao']);
            $atual = date("Y-m-d\TH:i:s.uP");

            if ($dataExpiracao >= $atual) {
                $this->tokenTransacional = $data['chaveTransacao'];
            } else {
                $curl = new \Curl\Curl();
                $curl->setHeader('token', $this->tokenMaster);
                $curl->setHeader('Content-Type', 'application/json');
                $resposta = $curl->post("https://cobrancaonline.sicredi.com.br/sicredi-cobranca-ws-ecomm-api/ecomm/v1/boleto/autenticacao");
                $this->tokenTransacional = $resposta->chaveTransacao;
                $curl->close();
                if (!empty($resposta->chaveTransacao)) {
                    $arquivo->gravarArquivo(json_encode($resposta));
                }
            }
        } else {
            $curl = new \Curl\Curl();
            $curl->setHeader('token', $this->tokenMaster);
            $curl->setHeader('Content-Type', 'application/json');
            $resposta = $curl->post("https://cobrancaonline.sicredi.com.br/sicredi-cobranca-ws-ecomm-api/ecomm/v1/boleto/autenticacao");
            $this->tokenTransacional = $resposta->chaveTransacao;
            if (!empty($resposta->chaveTransacao)) {
                $arquivo->gravarArquivo(json_encode($resposta));
            }
            $curl->close();
        }
        if (!empty($this->tokenTransacional)) {
            return $this->tokenTransacional;
        } else {
            throw new \Exception("Falha ao gerar token transacional '$resposta->codigo' - '$resposta->mensagem' - $resposta->parametro'", 1000);
        }
    }

    /**
     * 
     * @param int $tipoPessoa
     * @param string $cpfCnpj
     * @param string $nome
     * @param string $cep
     * @param string $especieDocumento
     * @param string $seuNumero
     * @param \model\Data $dataVencimento
     * @param float $valor
     * @param string $tipoDesconto
     * @param string $tipoJuros
     * @param float $juros
     * @param string $mensagem
     * @param string $endereco
     * @param string $cidade
     * @param string $uf
     * @param string $telefone
     * @param string $informativo
     */
    function emissao(int $tipoPessoa, string $cpfCnpj, string $nome, string $cep, string $especieDocumento, string $seuNumero, Data $dataVencimento, float $valor, string $tipoDesconto, string $tipoJuros, float $juros, string $mensagem, string $endereco, string $cidade, string $uf, string $multas, string $telefone, string $informativo)
    {
        $curl = new \Curl\Curl();
        $curl->setHeader('token', $this->tokenTransacional);
        $curl->setHeader('Content-Type', 'application/json');

        /**
         * parâmetros necessários para a geração do boleto
         * todos parãmetros podem ser consultados no manual de cobranca online do Sicredi
         * obtido junto ao Sicredi
         */
        $array = [
            'agencia' => $this->agencia,
            'posto' => $this->posto,
            'cedente' => $this->cedente,
            'tipoPessoa' => $tipoPessoa,
            'cpfCnpj' => $cpfCnpj,
            'nome' => $nome,
            'cep' => $cep,
            'especieDocumento' => $especieDocumento,
            'seuNumero' => $seuNumero,
            'dataVencimento' => $dataVencimento->getBarraDDMMYYYY(),
            'valor' => $valor,
            'tipoDesconto' => $tipoDesconto,
            'tipoJuros' => $tipoJuros,
            'juros' => $juros,
            'multas' => $multas,
            'mensagem' => $mensagem,
            'endereco' => $endereco,
            'cidade' => $cidade,
            'uf' => $uf,
            'telefone' => $telefone,
            'informativo' => $informativo
        ];
        $json = json_encode($array);

        $retorno = $curl->post('https://cobrancaonline.sicredi.com.br/sicredi-cobranca-ws-ecomm-api/ecomm/v1/boleto/emissao', $json);
        if (!empty($retorno->nossoNumero)) {
            return $this->impressao($retorno->nossoNumero); //caso retorne nossoNumero, vai para impressao
        } else {
            throw new \Exception("Falha ao registrar boleto - '$retorno->codigo' - '$retorno->mensagem' - $retorno->parametro'", 1000);
        }
        $curl->close();
    }

    function impressao(string $nossoNumero)
    {
        $curl = new \Curl\Curl();
        $curl->setHeader('token', $this->tokenTransacional);

        $array = [
            'agencia' => $this->agencia,
            'cedente' => $this->cedente,
            'nossoNumero' => $nossoNumero,
            'posto' => $this->posto,
        ];

        $retorno = $curl->get('https://cobrancaonline.sicredi.com.br/sicredi-cobranca-ws-ecomm-api/ecomm/v1/boleto/impressao', $array);

        $curl->close();

        if (!empty($retorno->arquivo)) {
            $pdf = base64_decode($retorno->arquivo);

            return $pdf; //retorna pdf já convertido de base64
        } else {
            throw new \Exception("Falha ao exibir PDF - '$retorno->codigo' - '$retorno->mensagem' - $retorno->parametro'", 1000);
        }
    }
}
