<?php

namespace Gustavo\ConsultaCep;

/**
 * Responsável por realizar a busca por meio do CEP
 * @author Gustavo Clemente da Silva Lopes <gustavo.clementelopes@gmail.com>
 */
class Search{

    /**
     * Url utilizada para consulta de CEP
     * @var string
     */
    private $url = 'https://viacep.com.br/ws/';

    /**
     * Realiza a busca de um endereço por meio do CEP
     * @param string $zipcode CEP utilizado na busca
     * @return array
     */
    public function getAddressByZipCode(string $zipcode) : array{

        if($zipcode = preg_replace('/[^0-9]/','',$zipcode)){

            $response = file_get_contents($this->url . $zipcode . '/json');

            return json_decode($response,true);

        }else{

            return [];
        }
    }
}