<?php

namespace Gustavo\ConsultaCep;

use InvalidArgumentException;

/**
 * Responsável por realizar a busca por meio do CEP
 * @author Gustavo Clemente da Silva Lopes <gustavo.clementelopes@gmail.com>
 */
class Search
{

    /**
     * URLs utilizadas para consulta nas APIS
     * @var array
     */
    private $url_map = [

        'viacep' => [

            'url' => 'https://viacep.com.br/ws/:zipcode/json'
        ],

        'cepla' => [

            'url' => 'http://cep.la/:zipcode',
            'context' => [
                'http' => [

                    'method' => 'GET',
                    'header' => 'Accept: application/json'
                ]
            ]
        ],

        'apicep' => [

            'url' => 'https://ws.apicep.com/cep/:zipcode.json'
        ],

    ];

    /**
     * Realiza a busca de um endereço por meio do CEP
     * @param string $zipcode CEP utilizado na busca
     * @param string $api (Opcional) API utilizada para consultar o CEP. Por padrão é utilizado viacep
     * @return array
     */
    public function getAddressByZipCode(string $zipcode, string $api = 'viacep'): array
    {

        if ($this->isAPIValid($api)) {

            if ($zipcode = preg_replace('/[^0-9]/', '', $zipcode)) {

                $url = $this->url_map[$api]['url'];
                $url_formated = str_replace(':zipcode',$zipcode,$url);
                $context = $this->getAPIContext($api);
                $response = file_get_contents($url_formated,false,$context);

                return json_decode($response, true);

            } else {

                return [];
            }
        } else {

            $accepted_apis = implode(',',$this->getAvaliableAPIS());
            throw new InvalidArgumentException("O parâmetro 'api' é invalido. Valores aceitos: " . $accepted_apis);
        }
    }

    /**
     * Retorna todas as API's para consultas de CEP que podem ser utilizadas
     * @return array
     */
    public function getAvaliableAPIS()
    {

        return array_keys($this->url_map);
    }

    /**
     * Indica se uma determinada API é válida
     * @param string $api API que será verificada
     * @return bool
     */
    private function isAPIValid(string $api)
    {

        return isset($this->url_map[$api]);
    }

    /**
     * Retorna um contexto para execução da API caso exista
     * @param string $api API que será verificada
     * @return resource|null
     */
    private function getAPIContext($api){

        $api_parameters = $this->url_map[$api];
        if(isset($api_parameters['context'])){

            $context = stream_context_create($api_parameters['context']);

            return $context;

        }else{

            return null;
        }
    }
}
