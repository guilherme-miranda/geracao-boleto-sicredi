<?php

namespace model;

/**
 * Classe responsável pro modelar a data e hora
 * Versão 1.4
 * Versão 1.5 Compatível
 * Versão 1.6 Compativel. Adicionado função diferencaHoras
 * Versão 1.7 Compativel. Adicionado função getPontoHHMMSS
 * Versão 1.8 Compartível. Adicionado validação de data com timestamp maior que 1 nos gets
 * Versão 1.9 Compartível. Adicionado getPontoHHMM
 * Versão 1.10 Alterado método init string para instânciar datas padrão BR
 * Versão 1.11 Adicionado método diferenca dias
 * Versão 1.12 Compartível. Adicionado getOracleFormatWithTime e getYYYYMMDDHHIISS
 * Versão 1.13 Compatível. Alterado para metodo getOracleFormat retorbar nulo caso não haja data e hora
 * Versão 1.14 Compartível. Adicionado método getHifenYYYYMM
 * Versão 1.15.Compartível. Adicionado metodos setAno e setMes
 * Versão 1.16 Compativel. Adicionado metodo atualizayyyymmddhhmmss para facilitar depuração
 * Versão 1.17 Compativel. Adicionado criação de data com função data(ano,mes,dia)
 * Versão 1.18 Compativel. Adicionado proteção para não esceder último dia do mês em addMeses
 * @author Odair
 */
class Data
{

    protected $timeStamp;
    protected $yyyymmddhhmmss;

    function __construct($timeStamp)
    {
        $this->timeStamp = $timeStamp;
        $this->atualizayyyymmddhhmmss();
    }

    function getTimeStamp()
    {
        return $this->timeStamp;
    }

    function setTimeStamp($timeStamp)
    {
        $this->timeStamp = $timeStamp;
        $this->atualizayyyymmddhhmmss();
    }

    function getBarraDDMMYYYY()
    {
        return ($this->timeStamp > 1) ? date("d/m/Y", $this->timeStamp) : null;
    }

    function getHifenYYYYMMDD()
    {
        return ($this->timeStamp > 1) ? date("Y-m-d", $this->timeStamp) : null;
    }

    function getHifenYYYYMM()
    {
        return ($this->timeStamp > 1) ? date("Y-m", $this->timeStamp) : null;
    }

    function getYYYYMMDD()
    {
        return ($this->timeStamp > 1) ? date("Ymd", $this->timeStamp) : null;
    }

    function getYYYYMMDDHHIISS()
    {
        return ($this->timeStamp > 1) ? date("YmdHis", $this->timeStamp) : null;
    }

    function getBarraDDMMYY()
    {
        return ($this->timeStamp > 1) ? date("d/m/y", $this->timeStamp) : null;
    }

    function getHHMMSS()
    {
        return ($this->timeStamp > 1) ? date("His", $this->timeStamp) : null;
    }

    function getPontoHHMMSS()
    {
        return ($this->timeStamp > 1) ? date("H:i:s", $this->timeStamp) : null;
    }

    function getPontoHHMM()
    {
        return ($this->timeStamp > 1) ? date("H:i", $this->timeStamp) : null;
    }

    function getBarraDDMMYYYYHHMMSS()
    {
        return ($this->timeStamp > 1) ? date("d/m/Y H:i:s", $this->timeStamp) : null;
    }

    function getHifenYYYYMMDDHHMMSS()
    {
        return ($this->timeStamp > 1) ? date("Y-m-d H:i:s", $this->timeStamp) : null;
    }

    function getAno()
    {
        return ($this->timeStamp > 1) ? date("Y", $this->timeStamp) : null;
    }

    function getMes()
    {
        return ($this->timeStamp > 1) ? date("m", $this->timeStamp) : null;
    }

    function getDia()
    {
        return ($this->timeStamp > 1) ? date("d", $this->timeStamp) : null;
    }

    function addMinutos(int $minutos)
    {
        $this->timeStamp = strtotime("+$minutos minutes", strtotime($this->getHifenYYYYMMDDHHMMSS()));
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function addHoras(int $horas)
    {
        $this->timeStamp = strtotime("+$horas hour", strtotime($this->getHifenYYYYMMDDHHMMSS()));
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function addDias(int $dias)
    {
        $this->timeStamp = strtotime("+$dias days", strtotime($this->getHifenYYYYMMDDHHMMSS()));
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function addMeses(int $meses)
    {
        if ($meses > 0) {
            $date = new \DateTime($this->getHifenYYYYMMDDHHMMSS());
            $diaInicial = $date->format('j');
            $date->modify("+$meses month");
            $diaFinal = $date->format('j');
            if ($diaInicial != $diaFinal) {
                $date->modify('last day of last month');
            }
            $this->timeStamp = $date->getTimestamp();
        } else {
            $this->timeStamp = strtotime("+$meses months", strtotime($this->getHifenYYYYMMDDHHMMSS()));
        }
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function addAnos(int $anos)
    {
        $this->timeStamp = strtotime("+$anos years", strtotime($this->getHifenYYYYMMDDHHMMSS()));
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    public function __toString()
    {
        return $this->getHifenYYYYMMDD();
    }

    function getPrimeiroDiaMes()
    {
        return $this->initAnoMesDia($this->getAno(), $this->getMes(), 1);
    }

    function getUltimoDiaMes()
    {
        return $this->initAnoMesDia($this->getAno(), $this->getMes(), date("t", strtotime($this->getHifenYYYYMMDD())));
    }

    /**
     * Retorna o dia da semana 0 a 6, sendo 0 domingo e 6 sabado
     * @return int
     */
    function getDiaSemanaAtual(): int
    {
        return date("w", $this->timeStamp);
    }

    /**
     * Retorna o primeiro dia da semana, exceto domingo
     * @return type
     */
    function getPrimeiroDiaSemana()
    {
        return $this->addDias(($this->getDiaSemanaAtual() * -1) + 1);
    }

    /**
     * Retorna o ultimo dia da semana, exceto domingo
     * @return type
     */
    function getUltimoDiaSemana()
    {
        return $this->getPrimeiroDiaSemana()->addDias(6);
    }

    function proximoDiaUtil(int $quantidade = 1)
    {
        while ($this->getDiaSemanaAtual() == 0 || $this->getDiaSemanaAtual() == 6) {
            $this->addDias($quantidade);
        }
        return $this;
    }

    /**
     * Este método subtrai duas datas trazendo a quantidade de horas entre elas
     * @param \model\Data $dataInicio
     * @param int $precisao
     * @return float
     */
    function diferencaHoras(Data $dataInicio, int $precisao = 2)
    {
        $diferenca = date_diff(date_create(date('Y-m-d H:i:s', $dataInicio->getTimeStamp())), date_create(date('Y-m-d H:i:s', $this->timeStamp)));
        return round(($diferenca->days * 24) + $diferenca->h + ($diferenca->i / 60), $precisao);
    }

    /**
     * Este método subtrai duas datas trazendo a quantidade de dias entre elas
     * @param \model\Data $dataInicio
     * @param int $precisao
     * @return float
     */
    function diferencaDias(Data $dataInicio)
    {
        $diferenca = date_diff(date_create(date('Y-m-d', $dataInicio->getTimeStamp())), date_create(date('Y-m-d', $this->timeStamp)));
        if ($diferenca->invert) {
            return (-1 * $diferenca->days);
        }
        return ($diferenca->days);
    }

    function setAno(int $ano)
    {
        $this->timeStamp = Data::initAnoMesDia($ano, $this->getMes(), $this->getDia())->getTimeStamp();
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function setMes(int $mes)
    {
        $this->timeStamp = Data::initAnoMesDia($this->getAno(), $mes, $this->getDia())->getTimeStamp();
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function setDia(int $dia)
    {
        $this->timeStamp = Data::initAnoMesDia($this->getAno(), $this->getMes(), $dia)->getTimeStamp();
        $this->atualizayyyymmddhhmmss();
        return $this;
    }

    function atualizayyyymmddhhmmss()
    {
        $this->yyyymmddhhmmss = $this->getHifenYYYYMMDDHHMMSS();
    }

    /**
     * Função que verifica se está data é dia util. Limitado a 2038-01-19 03:14:07
     * devido ao tamanho int32. Considera páscoa e feriados relacionados a ela.
     * @return boolean
     */
    function diaUtil()
    {
        //Elimina a hora para comparação correta
        $this->timeStamp = $this->initString($this->getHifenYYYYMMDD())->getTimeStamp();

        $ano = intval(date('Y', $this->timeStamp));

        $pascoa = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
        $dia_pascoa = date('j', $pascoa);
        $mes_pascoa = date('n', $pascoa);
        $ano_pascoa = date('Y', $pascoa);

        $feriados = array(
            // Datas Fixas dos feriados Nacionail Basileiras
            mktime(0, 0, 0, 1, 1, $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 4, 21, $ano), // Tiradentes - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 5, 1, $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 9, 7, $ano), // Dia da Independência - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 10, 12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
            mktime(0, 0, 0, 11, 2, $ano), // Todos os santos - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 11, 15, $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 12, 25, $ano), // Natal - Lei nº 662, de 06/04/49
            // These days have a date depending on easter
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48, $ano_pascoa), //2ºferia Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47, $ano_pascoa), //3ºferia Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 46, $ano_pascoa), //4ºferia Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2, $ano_pascoa), //6ºfeira Santa  
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa, $ano_pascoa), //Pascoa
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60, $ano_pascoa), //Corpus Cirist
        );

        sort($feriados);

        $fim_semana = ['Sat', 'Sun'];
        if (in_array($this->timeStamp, $feriados) || in_array(date("D", $this->timeStamp), $fim_semana)) {
            return false;
        } else {
            return true;
        }
    }

    static function initAnoMesDia($ano, $mes, $dia)
    {
        return new Data(strtotime($ano . "-" . $mes . "-" . $dia));
    }

    static function initString($string)
    {
        if (empty($string)) {
            return new Data(0);
        } else {
            if (strpos($string, "/") === true) {
                $format = "d/m/Y";
                $dateObj = \DateTime::createFromFormat($format, $string);
                return new Data($dateObj->getTimestamp());
            } else if (strpos($string, "T") !== false) {
                $format = "Y-m-d\TH:i:s.uP";
                $dateObj = \DateTime::createFromFormat($format, $string);
                return new Data($dateObj->getTimestamp);
            } else {
                return new Data(strtotime($string));
            }
        }
    }

    static function initAtual()
    {
        return new Data(strtotime(date("Y-m-d H:i:s")));
    }

    static function getDiaSemana($dia)
    {
        return Data::getDiasSemana()[$dia];
    }

    static function getDiasSemana()
    {
        return [
            0 => 'Domingo',
            1 => 'Segunda',
            2 => 'Terça',
            3 => 'Quarta',
            4 => 'Quinta',
            5 => 'Sexta',
            6 => 'Sábado'
        ];
    }

    static function initData($data): Data
    {
        $d = new Data(strtotime(date("Y-m-d H:i:s")));
        $t = trim($data);
        if (strpos($t, 'data(') !== false && strpos($t, ')') == (strlen($t) - 1)) {
            $parametros = substr($t, strpos($t, '(') + 1, strlen($t) - strpos($t, '(') - 2);
            $parametros = explode(',', $parametros);
            if (!empty(trim($parametros[0]))) {
                if (strpos($parametros[0], '+') !== false || strpos($parametros[0], '-') !== false) {
                    $d->addAnos(trim($parametros[0]));
                } else {
                    $d->setAno(trim($parametros[0]));
                }
            }
            if (!empty(trim($parametros[1]))) {
                if (strpos($parametros[1], '+') !== false || strpos($parametros[1], '-') !== false) {
                    $d->addMeses(trim($parametros[1]));
                } else {
                    $d->setMes(trim($parametros[1]));
                }
            }
            if (!empty(trim($parametros[2]))) {
                if (trim($parametros[2]) == 'u') {
                    $d = $d->getUltimoDiaMes();
                } else if (strpos($parametros[2], '+') !== false || strpos($parametros[2], '-') !== false) {
                    $d->addDias(trim($parametros[2]));
                } else {
                    $d->setDia(trim($parametros[2]));
                }
            }
        }
        return $d;
    }
}
