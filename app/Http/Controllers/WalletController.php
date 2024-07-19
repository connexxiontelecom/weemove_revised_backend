<?php

namespace App\Http\Controllers;
use App\Models\Account;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WalletController extends Controller
{
    private $client;
    private $apiKey = "MK_TEST_2UM78S43SG";
    private $clientSecret = "HJGKH9F7A2VKX5XG7HANTKFNPGGUMVSS";
    private $baseUrl = "https://sandbox.monnify.com";

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'verify' => false // Disable SSL verification for sandbox (not recommended for production)
        ]);
    }

    public function createAuthToken()
    {
        $keys = "{$this->apiKey}:{$this->clientSecret}";
        $key = base64_encode($keys);

        $response = $this->client->request('POST', '/api/v1/auth/login', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $key,
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents());

        return $responseBody->responseBody->accessToken;
    }

    private function createAccount($fieldsArray)
    {
        $fields = json_encode($fieldsArray);
        $authToken = $this->createAuthToken();

        $response = $this->client->request('POST', '/api/v2/bank-transfer/reserved-accounts', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $authToken,
            ],
            'body' => $fields
        ]);
        return $response->getBody()->getContents();
    }


    public function createVirtualAccount(Request $request) {

        $this->validate($request, [
            'fullname' => 'required',
            'email' => 'required',
        ]);

        $parameters =  $request->all();

        $accountReference = substr(sha1(time()), 20, 40);
        $accountName = $parameters['fullname'];
        $currencyCode = "NGN";
        $contractCode = "5321458792";
        $customerEmail = $parameters['email'];
        $customerName = $parameters['fullname'];
        $getAllAvailableBanks = false;
        $preferredBanks = "035";
        $fields = [
            "accountReference" => $accountReference,
            "accountName" => $accountName,
            "currencyCode" => $currencyCode,
            "contractCode" => $contractCode,
            "customerEmail" => $customerEmail,
            "customerName" => $customerName,
            "getAllAvailableBanks" => $getAllAvailableBanks,
            "preferredBanks" => [$preferredBanks]
        ];
        $response = $this->createAccount($fields);
        $response = json_decode($response);
        if ($response->requestSuccessful) {
            return response()->json(['accountInformation' => $response->responseBody, 'message' => "Success"], 200);
        } else {
            return response()->json([ 'message' => "Could not create account"], 400);
        }
    }

}

