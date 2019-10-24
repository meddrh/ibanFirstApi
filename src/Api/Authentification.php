<?php


namespace App\Api;


use Symfony\Component\DependencyInjection\ContainerInterface;

class Authentification
{
     private  $username;
     private  $password;

    /**
     * Authentification constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->username =$container->getParameter('api.username');
        $this->password =$container->getParameter('api.password');

    }

    public function getSecurityHeaderTemplate()
    {

        // Login informations
        $username = $this->username;            // The username used to authenticate
        $password = $this->password;                // The password used to authenticate
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