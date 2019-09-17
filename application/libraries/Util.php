<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Util
{

    const MONTH = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );

    const YEAR = array(
        2019 => '2019',
        2018 => '2018',
        2017 => '2017',
        2016 => '2016', 
    );

    function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }  


}
?>
