<?php

use PHPUnit\Framework\TestCase;
use Gustavo\ConsultaCep\Search;

/**
 * Responsável por realizar os testes unitários na classe Search
 * @author Gustavo Clemente da Sivla Lopes <gustavo.clementelopes@gmail.com>
 */
class SearchTest extends TestCase
{

    /**
     * Realiza o teste na consulta de CEP
     * @param string $zipcode CEP utilizado no teste
     * @param string $api API utilizada na consulta
     * @param array $expected Resposta esperada para esse teste
     * @dataProvider validZipCodeProvider
     * @dataProvider invalidZipCodeProvider
     * @test
     */
    public function testGetAddressByZipCode($zipcode, $api, array $expected)
    {
        $search = new Search();
        $result = $search->getAddressByZipCode($zipcode, $api);

        $this->assertEquals($expected, $result);
    }

    /**
     * Realiza o teste na consulta de CEP por uma API inválida
     * @test
     */
    public function testGetAddressByInvalidAPI(){

        $search = new Search();
        $result = $search->getAddressByZipCode('02473090', 'cepi');

        $this->expectException(InvalidArgumentException::class);

    }

    /**
     * Retorna CEP's válidos para testes em todas as API´s
     * @return array
     */
    public function validZipCodeProvider(): array
    {

        return [

            'encontrado - viacep' => [

                '02473090',
                'viacep',
                [
                    "cep" => "02473-090",
                    "logradouro" => "Rua Luzim",
                    "complemento" => "",
                    "bairro" => "Vila Roque",
                    "localidade" => "São Paulo",
                    "uf" => "SP",
                    "ibge" => "3550308",
                    "gia" => "1004",
                    "ddd" => "11",
                    "siafi" => "7107"
                ]
            ],

            'encontrado - cepla' => [

                '02473090',
                'cepla',
                [
                    "cep" => "02473090",
                    "uf" => "SP",
                    "cidade" => "São Paulo",
                    "bairro" => "Vila Roque",
                    "logradouro" => "Rua Luzim"
                ]
            ],

            'encontrado - apicep' => [

                '02473090',
                'apicep',
                [
                    "status" => 200,
                    "ok" => true,
                    "code" => "02473-090",
                    "state" => "SP",
                    "city" => "São Paulo",
                    "district" => "Vila Roque",
                    "address" => "Rua Luzim",
                    "statusText" => "ok"
                ]
            ]

        ];
    }
    /**
     * Retorna CEP's válidos para testes em todas as API´s
     * @return array
     */
    public function invalidZipCodeProvider(): array
    {

        return [

            'não encontrado - viacep' => [

                '01234567',
                'viacep',
                [
                    "erro" => true
                ]
            ],

            'não encontrado - cepla' => [

                '01234567',
                'cepla',
                []
            ],

            'não encontrado - apicep' => [

                '01234567',
                'apicep',
                [
                    "status" => 404,
                    "ok" => false,
                    "message" => "CEP não encontrado",
                    "statusText" => "not_found"
                ]
            ],

            'cep inválido - cepla' => [

                'abcdefghik',
                'cepla',
                [
                    "msg" => 'O CEP informado é invalido'
                ]
            ],
            'cep inválido - viacep' => [

                'abcdefghik',
                'viacep',
                [
                    "msg" => 'O CEP informado é invalido'
                ]
            ],
            'cep inválido - apicep' => [

                'abcdefghik',
                'apicep',
                [
                    "msg" => 'O CEP informado é invalido'
                ]
            ]


        ];
    }
}
