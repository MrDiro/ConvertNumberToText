<?php

class ConvertNumberToText {

    private $len = null;
    private $cifraCardinal = null;
    private $cifraOrdinal = null;
    private $number = null;

    public function __construct($number)
    {
        $this->number = $number;
        $this->len = strlen((string)$number);;
        $this->cifraCardinal = array(
            1 => array(0 => "cero", 1 => "uno", 2 => "dos", 3 => "tres", 4 => "cuatro", 5 => "cinco", 6 => "seis", 7 => "siete", 8 => "ocho", 9 => "nueve"),
            2 => array(10 => "diez", 11 => "once", 12 => "doce", 13 => "trece", 14 => "catorce", 15 => "quince", 16 => "dieciséis", 17 => "diecisiete", 18 => "dieciocho", 19 => "diecinueve", 20 => "veinte", 30 => "treinta", 40 => "cuarenta", 50 => "cincuenta", 60 => "sesenta", 70 => "setenta", 80 => "ochenta", 90 => "noventa"),
            3 => array(100 => "cien", 200 => "doscientos", 300 => "trescientos", 400 => "cuatrocientos", 500 => "quinientos", 600 => "seiscientos", 700 => "setecientos", 800 => "ochocientos", 900 => "novecientos"),
            4 => "mil",
            7 => "millon"
        );
        $this->cifraOrdinal = array(
            1 => array(1 => "Primero", 2 => "Segundo", 3 => "Tercero", 4 => "Cuarto", 5 => "Quinto", 6 => "Sexto", 7 => "Séptimo", 8 => "Octavo", 9 => "Noveno"),
            2 => array(10 => "Décimo", 20 => "Vigésimo", 30 => "Trigésimo", 40 => "Cuadragésimo", 50 => "Quincuagésimo", 60 => "Sexagésimo", 70 => "Septuagésimo", 80 => "Octogésimo", 90 => "Nonagésimo"),
            3 => array(100 => "Centésimo", 200 => "Ducentésimo", 300 => "Tricentésimo", 400 => "Cuadringentésimo", 500 => "Quingentésimo", 600 => "Sexcentésimo", 700 => "Septingentésimo", 800 => "Octingentésimo", 900 => "Noningentésimo")
        );
    }

    public function cardinal() {

        $len = $this->len;
        $num = $this->number;
        $str = "";

        switch ($len) {
            case 1:
                $str = $this->unidades($len, $num);
                break;
            case 2:
                $str = $this->decenas($len, $num);
                break;
            case 3:
                $str = $this->centenas($len, $num);
                break;
            case 4:
                $str = $this->milesimas($len, $num);
                break;
        }

        return $str;
    }

    public function ordinal() {
        
        $len = $this->len;
        $num = $this->number;
        $str = "";

        switch ($len) {
            case 1:
                $str = $this->unidadOrdinal($len, $num);
                break;
            case 2:
                $str = $this->decenaOrdinal($len, $num);
                break;
            case 3:
                $str = $this->centenaOrdinal($len, $num);
                break;
        }

        return $str;
    }

    private function unidades($len, $num) {

        $cifra = $this->cifraCardinal;

        return $cifra[$len][$num];
    }

    private function decenas($len, $num) {

        $cifra = $this->cifraCardinal;
        $str = "";
        $unidad = $num % 10;
        $decena = (int) ($num / 10) % 10;

        if ($num >= 20 && $num % 10 == 0) {
            $str = $cifra[$len][$decena * 10];
        } else if ($num >= 20) {
            $str = $cifra[$len][$decena * 10] . " y " . $cifra[$len - 1][$unidad];
        } else {
            $str = $cifra[$len][$num];    
        }

        return $str;
    }

    private function centenas($len, $num) {

        $cifra = $this->cifraCardinal;
        $str = "";
        $unidad = $num % 10;
        $decena = (int) ($num / 10) % 10;
        $centena = (int) ($num / 10 ** 2) % 10;

        if ($centena == 1 && $decena == 0 && $unidad == 0) {
            $str = $cifra[$len][$centena * 100];
        } else if ($centena == 1 && $decena == 0 && $unidad > 0) {
            $str = $cifra[$len][$centena * 100] . "to " . $this->unidades($len - 2, $unidad);
        } else if ($centena == 1 && $decena > 0 && $unidad >= 0) {
            $str = $cifra[$len][$centena * 100] . "to " . $this->decenas($len -1, ((int) $decena . $unidad));
        }if ($centena > 1 && $decena == 0 && $unidad == 0) {
            $str = $cifra[$len][$centena * 100];
        } else if ($centena > 1 && $decena == 0 && $unidad > 0) {
            $str = $cifra[$len][$centena * 100] . " " . $this->unidades($len - 2, $unidad);
        } else if ($centena > 1 && $decena > 0 && $unidad >= 0) {
            $str = $cifra[$len][$centena * 100] . " " . $this->decenas($len -1, ((int) $decena . $unidad));
        }

        return $str;
    }

    private function milesimas($len, $num) {

        $cifra = $this->cifraCardinal;
        $str = "";
        $unidad = $num % 10;
        $decena = (int)($num / 10) % 10;
        $centena = (int)($num / 10 ** 2) % 10;
        $mil = (int)($num / 10 ** 3) % 10;

        if ($mil == 1 && $centena == 0 && $decena == 0 && $unidad == 0) {
            $str = $cifra[$len];
        } else if ($mil == 1 && $centena == 0 && $decena == 0 && $unidad >= 0) {
            $str = $cifra[$len] . " " . $this->unidades($len - 3, $unidad);
        } else if ($mil == 1 && $centena == 0 && $decena >= 0 && $unidad >= 0) {
            $str = $cifra[$len] . " " . $this->decenas($len - 2, (int)($decena . $unidad));
        } else if ($mil == 1 && $centena >= 0 && $decena >= 0 && $unidad >= 0) {
            $str = $cifra[$len] . " " . $this->centenas($len - 1, ((int) $centena . $decena . $unidad));
        }if ($mil > 1 && $centena == 0 && $decena == 0 && $unidad == 0) {
            $str = $cifra[$len - 3][$mil] . " " . $cifra[$len];
        } else if ($mil > 1 && $centena == 0 && $decena == 0 && $unidad >= 0) {
            $str = $cifra[$len - 3][$mil] . " " . $cifra[$len] . " " . $this->unidades($len - 3, $unidad);
        } else if ($mil > 1 && $centena == 0 && $decena >= 0 && $unidad >= 0) {
            $str = $cifra[$len - 3][$mil] . " " . $cifra[$len] . " " . $this->decenas($len - 2, (int)($decena . $unidad));
        } else if ($mil > 1 && $centena >= 0 && $decena >= 0 && $unidad >= 0) {
            $str = $cifra[$len - 3][$mil] . " " . $cifra[$len] . " " . $this->centenas($len - 1, ((int) $centena . $decena . $unidad));
        }

        return $str;
    }

    private function unidadOrdinal($len, $num) {

        $str = "";
        $cifra = $this->cifraOrdinal;

        if ($num > 0) {
            $str = $cifra[$len][$num];
        }

        return $str;
    }

    private function decenaOrdinal($len, $num) {

        $str = "";
        $cifra = $this->cifraOrdinal;
        $unidad = $num % 10;
        $decena = (int)($num / 10) % 10;

        if ($decena > 0) {
            $str = $cifra[$len][$decena * 10] . " " . $this->unidadOrdinal($len - 1, $unidad);
        } else {
            $str = $cifra[$len][$decena * 10];
        }

        return $str;
    }

    private function centenaOrdinal($len, $num) {

        $str = "";
        $cifra = $this->cifraOrdinal;
        $unidad = $num % 10;
        $decena = (int)($num / 10) % 10;
        $centena = (int)($num / 10 ** 2) % 10;

        if ($decena == 0 && $unidad == 0) {
            $str = $cifra[$len][$centena * 100];
        } else if ($decena == 0 && $unidad > 0) {
            $str = $cifra[$len][$centena * 100] . " " . $this->unidadOrdinal($len - 2, $unidad);
        } else {
            $str = $cifra[$len][$centena * 100] . " " . $this->decenaOrdinal($len - 1, (int)($decena . $unidad));
        }

        return $str;
    }
}
