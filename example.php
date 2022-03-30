<?php

require_once 'vendor/autoload.php';

use Gustavo\ConsultaCep\Search;

$search = new Search();

print_r($search->getAddressByZipCode('02473090'));
