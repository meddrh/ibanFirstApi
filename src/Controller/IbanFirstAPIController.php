<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Routing\Annotation\Route;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
class IbanFirstAPIController extends AbstractController
{
    /**
     * @Route("/", name="iban_first_a_p_i")
     */


    public function index(){
      return $this->render('iban_first_api/index.html.twig', [
            'wallets' => $this->getWallestList(),
        ]);
    }
    public function getWallestList()
    {

        $client = new CurlHttpClient();
        $response = $client->request('GET', 'https://uat1.ibanfirst.com/api/wallets/',[
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->getSecurityHeaderTemplate(),
                'CONTENT_TYPE' => 'application/json'],
        ]);

        $content = $response->toArray();

        return $content;
    }

    /**
     * @Route("/ibanFirst/getDetailsOperation/{id}", name="get_details_operation")
     * @param $id
     * @return array
     */
    public function getDetailsFinancialMouvements($id)
    {

        $client = new CurlHttpClient();
        $content = [] ;
        $url = 'https://uat1.ibanfirst.com/api/financialMovements/';

        $response = $client->request('GET', $url,[
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->getSecurityHeaderTemplate(),
                'CONTENT_TYPE' => 'application/json'],
            'query' => [
                'walletId' => $id,
            ],
        ]);

        $content = $response->toArray();

        return $this->render('iban_first_api/details.html.twig', [
            'detailsWallet' => $content,
        ]);
    }

    /**
     * @Route("/ibanFirst/getDetailsWallet/{id}", name="get_details_wallet")
     * @param $id
     * @return array
     */


    public function getDetailsWallet($id)
    {

        $client = new CurlHttpClient();
        $response = $client->request('GET', 'https://uat1.ibanfirst.com/api/wallets/-'.$id,[
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->getSecurityHeaderTemplate(),
                'CONTENT_TYPE' => 'application/json'],
        ]);

        $content = $response->toArray();

        return $this->render('iban_first_api/show.html.twig', [
            'wallet' => $content,
        ]);

    }
    public function getSecurityHeaderTemplate()
    {

        // Login informations
        $username = "cx97129";            // The username used to authenticate
        $password = "yVeYj+s/BMbUHajko2eVTVlKdLPNXbXO3Ywga6pKUFFaGV0gNnwj4Bt7RsxpNBD2GTwpRNenVF1hf23CiKf7Ag==";                // The password used to authenticate
        $nonce = "";                    // The nonce
        $nonce64 = "";                    // The nonce with a Base64 encoding
        $date = "";                    // The date of the request, in  ISO 8601 format
        $digest = "";                    // The password digest needed to authenticate you
        $header = "";                    // The final header to put in your request

// Making the nonce and the encoded nonce
        $chars = "0123456789abcdef";
        for ($i = 0; $i < 32; $i++) {
            $nonce .= $chars[rand(0, 15)];
        }
        $nonce64 = base64_encode($nonce);

// Getting the date at the right format (e.g. YYYY-MM-DDTHH:MM:SSZ)
        $date = gmdate('c');
        $date = substr($date, 0, 19) . "Z";

// Getting the password digest
        $digest = base64_encode(sha1($nonce . $date . $password, true));

// Getting the X-WSSE header to put in your request
        $header = sprintf('UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"', $username, $digest, $nonce64, $date);

        return $header;
    }
}
