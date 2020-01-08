# Geração de boletos com API Sicredi

Esse projeto consiste na geração de cobranças baseada na API disponibilizada pelo banco Sicredi, de maneira simples e prática.

O projeto é baseado em parte no modelo MVC (Model-View-Control) e utiliza a lib Simple CURL.

Todos os pârametros necessários podem ser configurados de acordo com o necessário. (tipo de juros, multa, etc).

Ao requisitar a geração do boleto, o código retornado é diretamente convertido em PDF e exibido na tela do usuário.

O sistema de geração de boletos do Sicredi utiliza uma chave Mestre e uma chave Transacional. Esta última só pode ser gerada uma vez a cada 24 horas, o que faz com que seja necessário armazená-la.
Neste projeto a chave e data de expiração são armazenados em arquivo .JSON e a data atual do sistema é comparada com a data atual do sistema.

## Começando

Para iniciar o processo de integração sistêmica para o registro online de boletos, o associado deve ser previamente cadastrado na Cooperativa de Crédito/Agência como beneficiário de Cobrança e optante pela Cobrança Online.
Ao contratar o produto é gerado o código do beneficiário (código do convênio) e o seu uso será obrigatório na integração troca de informações entre a empresa beneficiária e o Sicredi.
A Cobrança Online contém as seguintes operações
- Autenticação: Operação responsável pela autenticação da Chave Master e criação da Chave de Transação dos beneficiários que aderirem o produto Cobrança Online do Sicredi.
- Emissão: Operação responsável pelo cadastro do pagador, do boleto, do informativo e da mensagem relacionadas ao boleto a ser criado.
- Impressão: Operação responsável pela impressão e reimpressão dos dados de boletos. 

### Pré-requisitos

- Possuir Contrato de Cobrança Sicredi.
- Estar habilitado para o Cobrança Online Sicredi com termo de adesão assinado.
- `Código de Acesso` gerado pelo Internet Banking (chave Master)

### Instalando

Devem ser modificados algumas variáveis no PagamentoSicrediBoleto.php

```
    const tokenMaster 	= 		"***********************************************";	//chave Mestre
    const agencia 		=		"****"; 		//agência do beneficiário
    const posto 		=		"**";			//posto do beneficiário
    const cedente 		=		"*****";		//código do cedente
```

## Preview

 ![alt text](https://github.com/spotecnologia/geracao-boleto-sicredi/blob/master/preview.png "Preview")

## Construído com

* [Bootstrap](https://getbootstrap.com/)

## Authors

* **Odair Pianta**			[Odair Pianta] (https://github.com/odairpianta)
* **Rafael André Hoffmann**	[Rafael Hoffmann] (https://github.com/rafa-hoffmann)

Veja também a lista de [contribuidores](https://github.com/spotecnologia/geracao-boleto-sicredi/graphs/contributors) que participaram desse projeto.

## Licença

Licenciado em GNU General Public License v3.0 [LICENSE.md](LICENSE.md) para detalhes

