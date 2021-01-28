<?php

namespace App\Adapters;

use App\Adapters\Interfaces\GeocodeInterface;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class GeocodeAdapter implements GeocodeInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLatLong($data)
    {
        $url = 'https://nominatim.openstreetmap.org/search/';
        $format = 'json';
        $addressdetails = 1;
        $method = 'GET';
        
        $searchAdress = rawurlencode($data['alamat'] . ' ' . $data['daerah'] . ' ' . $data['kotkab']);

        $response = $this->client->request($method, $url.$searchAdress, [
            'query' => [
                'format'            => $format,
                'addressdetails'    => $addressdetails
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if($response==NULL){
            $returnData = [
                'lat'   => "0",
                'lon'   => "0"
            ];
        }
        else{
            $returnData = [
                'lat'   => $response[0]['lat'],
                'lon'   => $response[0]['lon']
            ];
        }
        

        return $returnData;
    }
}


?>