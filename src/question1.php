<?php

namespace App;

require 'vendor/autoload.php';
use GuzzleHttp\Client;


class Accounts {
   public $token;
   public $client;
   public $header;

   public function __construct(Client $client)
   {
      $this->client = $client;
   }

   //Method to make auth calls
   public function makeAuthCalls(){

      $request = $this->client->post('https://sandbox.monnify.com/api/v1/auth/login' ,[
         'auth' => [
            'MK_TEST_WD7TZCMQV7',
            'H5EQMQSHSURJNQ7UH2R78YAH6UN54ZP7'
         ]
      ]);

      $result = json_decode($request->getBody()->getContents(), true);
      
      // $this->token =  $result['responseBody']['accessToken'];
      return $result;

   }


   //Method to Reserve account
   public function ReserveAccount($accountReferece = '', $accountName = '', $currencyCode = '', $customerEmail = '') {
      $request = $this->client->request(
         'POST',
         'https://sandbox.monnify.com/api/v1/bank-transfer/reserved-accounts' ,
         [
            'headers' => [
               'Authorization' => "Bearer {$this->token}"
            ],
            'json' => [
               'accountReference' => $accountReferece,
               'accountName' => $accountName,
               'currencyCode' => $currencyCode,
               'contractCode' => '2957982769',
               'customerEmail' => $customerEmail
            ],
         ]
      );
      return json_decode($request->getBody()->getContents(), true);
   }

   //Method to deactivate account
   public function deactivateAccount($account) {
      $request = $this->client->request(
         'DELETE',
         "https://sandbox.monnify.com/api/v1/bank-transfer/reserved-accounts/{$account}",
         [
            'headers' => [
               'Authorization' => "Bearer {$this->token}"
            ]
         ]
      );
      return json_decode($request->getBody()->getContents(), true);
   }

   //Method to get transaction status
   public function getTranscationStatus($paymentReference = '', $transactionReference = ''){
      $request = $this->client->request(
         'GET',
         "https://sandbox.monnify.com/api/v1/merchant/transactions/query",
         [
            'headers' => [
               'Authorization' => "Basic {$this->token}"
            ]
            ], [
               'query' => [
                  'paymentReference' => $paymentReference,
                  'transactionReference' => $transactionReference
               ]
            ]
      );
      return json_decode($request->getBody()->getContents(), true);
   }
}
?>