<?php

namespace App\Controller;

use App\Api\Authentification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Routing\Annotation\Route;

class IbanFirstAPIController extends AbstractController
{
    /**
     * IbanFirstAPIController constructor.
     * @param Authentification $authentification
     */

    public $authentification;

    public function __construct(Authentification $authentification)
    {
        $this->authentification = $authentification;
    }


    /**
     * @Route("/", name="iban_first_a_p_i")
     */
    public function index()
    {
        return $this->render('iban_first_api/index.html.twig', [
            'wallets' => $this->getWallestList(),
        ]);
    }
    public function getWallestList()
    {
        $client = new CurlHttpClient();
        $response = $client->request('GET', 'https://uat1.ibanfirst.com/api/wallets/', [
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->authentification->getSecurityHeaderTemplate(),
                'CONTENT_TYPE' => 'application/json'],
        ]);

        $content = $response->toArray();

        return $content;
    }


    /**
     * @Route("/ibanFirst/getDetailsOperation/{id}", name="get_details_operation")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getDetailsFinancialMouvements($id)
    {
        $client = new CurlHttpClient();
        $url = 'https://uat1.ibanfirst.com/api/financialMovements/';

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->authentification->getSecurityHeaderTemplate(),
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */

    public function getDetailsWallet($id)
    {
        $client = new CurlHttpClient();
        $response = $client->request('GET', 'https://uat1.ibanfirst.com/api/wallets/-'.$id, [
            'headers' => [
                'Authorization' => 'WSSE profile="UsernameToken"',
                'X-WSSE' => $this->authentification->getSecurityHeaderTemplate(),
                'CONTENT_TYPE' => 'application/json'],
        ]);

        $content = $response->toArray();

        return $this->render('iban_first_api/show.html.twig', [
            'wallet' => $content,
        ]);
    }

}
