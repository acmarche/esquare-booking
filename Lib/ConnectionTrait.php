<?php


namespace AcMarche\Booking\Lib;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

trait ConnectionTrait
{
    private HttpClientInterface $httpClient;
    private string $code;
    private string $url;
    private string $clef;
    private string $user;
    private string $password;
    private ?string $token;

    public function connect()
    {
        Env::loadEnv();

        $this->url = $_ENV['GRR_URL'];
        $this->user = $_ENV['GRR_USER'];
        $this->password = $_ENV['GRR_PASSWORD'];

        $options = [

        ];

        $this->httpClient = HttpClient::create($options);
    }
}
