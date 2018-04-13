<?php

namespace Someline\Services;


use GuzzleHttp\Client;

class ZhangYueService
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUri = 'http://api.res.ireader.com';

    /**
     * @var string
     */
    protected $clientId = '4';

    /**
     * @var string
     */
    protected $clientSecretKey = '90c9561f21f25584531cfb744cbd6919';

    /**
     * ZhangYueService constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
//            'exceptions' => false,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function getSign($id)
    {
        return strtolower(md5($this->clientId . $this->clientSecretKey . $id));
    }

    /**
     * @param $bookId
     * @return mixed
     */
    public function fetchBookInfo($bookId)
    {
        $response = $this->client->get('/api/v2/book/bookInfo', [
            'query' => [
                'clientId' => $this->clientId,
                'bookId' => $bookId,
                'resType' => 'json',
                'sign' => $this->getSign($bookId),
            ],
        ]);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode((string)$response->getBody(), true);
        } else {
            return false;
        }
    }

    public function fetchBookList()
    {
        $response = $this->client->get('/api/v2/book/bookList',[
            'query' => [
              'clientId' => $this->clientId,
              'resType' => 'json',
              'sign' => $this->getSign(null),
            ],
        ]);

        if($response->getStatusCode() >=200 && $response->getStatusCode() < 300) {
            return json_decode((string)$response->getBody(), true);
        } else {
            return false;
        }
    }


}