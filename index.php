<?php include_once 'inc.php'; ?>
<?php
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['acao'] == 'inserir') {
        $dados = $_POST;    //converte os dados recebidos do formulário em array

        $pagamento  = new model\PagamentoSicrediBoleto();  //cria objeto PagamentoSicrediBoleto
        $tokenTransacional = $pagamento->getTokenTransacional(); //gera o tokenTransacional

        $dataVencimento = model\Data::initString($dados['dataVencimento']); //converte data do form

        //realiza a emissao do boleto e armazena o pdf resultado do processo
        $pdf = $pagamento->emissao(
            $dados['tipoPessoa'],
            $dados['cpfCnpj'],
            $dados['nome'],
            $dados['cep'],
            $dados['especieDocumento'],
            "123", //seuNumero
            $dataVencimento,
            /* @var $dados type */
            (float) $dados['valor'],
            "A", //tipoDesconto
            "B", //tipoJuros
            $dados['juros'],
            $dados['mensagem'],
            $dados['endereco'],
            $dados['cidade'],
            $dados['uf'],
            $dados['multas'],
            $dados['telefone'],
            $dados['informativo']
        );

        header('Content-Type: application/pdf'); 
        echo $pdf;                                  //exibe o pdf no navegador
    }
} catch (Exception $ex) {
    model\Erro::tratamentoPadrão($ex);
}
?>
<!doctype html>
<html>
<head>
    <?php include_once 'css/inc.php'; ?>
    <link href="css/select2.min.css" rel="stylesheet" />
    <link href="css/select2-bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <?php include_once 'js/inc.php'; ?>
    <script type="text/javascript" src="js/select2.min.js"></script>
    <title>Gerar Boleto Sicredi</title>

</head>

<body class="grey lighten-5">
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="text-center">Gerar Boleto Sicredi - Teste</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type='hidden' name='acao' value='inserir' required />
                        <h5 class="card-title">Dados do pagador</h5>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipoPessoa" id="inlineRadio1" value="1" checked>
                                        <label class="form-check-label" for="inlineRadio1">Pessoa Física</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipoPessoa" id="inlineRadio2" value="2">
                                        <label class="form-check-label" for="inlineRadio2">Pessoa Jurídica</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpfCnpj">CPF/CNPJ</label>
                                    <input type="number" class="form-control" id="cpfCnpj" name="cpfCnpj" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nome">Telefone</label>
                                    <input type="number" class="form-control" id="telefone" name="telefone" required>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title">Endereço de cobrança</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep">CEP</label>
                                    <input type="number" class="form-control" id="cep" name="cep" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="endereco">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cidade">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="uf">Estado</label>
                                    <select name="uf" class='form-control' required>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title">Dados do boleto</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select">Espécie do documento</label>
                                    <select class="form-control" name="especieDocumento" required style="width: 100%" required>
                                        <option value="A">A - DUPLICATA MERCANTIL (DM)</option>
                                        <option value="B">B - DUPLICATA RURAL (DR)</option>
                                        <option value="C">C - NOTA PROMISSORIA (NP)</option>
                                        <option value="D">D - NOTA PROMISSORIA RURAL (NR)</option>
                                        <option value="E">E - NOTA DE SEGURO (NS)</option>
                                        <option value="G">G - RECIBO (RC)</option>
                                        <option value="H">H - LETRA DE CAMBIO (LC)</option>
                                        <option value="I">I - NOTA DE DEBITO (ND)</option>
                                        <option value="J">J - DUPLICATA DE SERVICO (DS)</option>
                                        <option value="K">K - OUTROS (OS)</option>
                                        <option value="O">O - BOLETO OFERTA (OFE)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dataVencimento">Data de Vencimento</label>
                                    <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input type="number" class="form-control" id="valor" name="valor" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="juros">Juros (%) (opcional)</label>
                                    <input type="number" class="form-control" id="juros" name="juros" step="0.01" value="0.33" min="0.01" max="100.00" placeholder="0.33">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="multas">Multas (opcional)</label>
                                    <input type="number" class="form-control" id="multas" name="multas" value="0.00" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valor">Informativo (opcional)</label>
                                    <input type="text" class="form-control" id="informativo" name="informativo">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="textarea">Mensagem (opcional)</label>
                                    <textarea class="form-control" name="mensagem" maxlength="500" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right" name="submit">Gerar boleto</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('footer.php'); ?>
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <script type="text/javascript">
        new WOW().init();
    </script>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#especieDocumento').select2({
            width: 'resolve',
            allowClear: true,
            theme: 'bootstrap4',
            placeholder: "Escolha a espécie do documento"
        });
        $('#estado').select2({
            width: 'resolve',
            allowClear: true,
            theme: 'bootstrap4',
            placeholder: "Escolha o Estado"
        });
    });
</script>
<?php include_once 'notif.php'; //inclusao da classe para notificacao de erros?>