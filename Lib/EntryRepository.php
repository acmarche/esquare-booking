<?php

namespace AcMarche\Booking\Lib;

use AcMarche\Booking\BookingJf;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class EntryRepository
{
    use ConnectionTrait;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connect();
    }

    public function getEntries(int $room): array
    {
        try {
            $json = $this->getRemoteEntries($room);

            return json_decode($json);

        } catch (\Exception $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur agenda', $e->getMessage());

            return [];
        }
    }

    public function getEntriesByDay(string $date, int $room): array
    {
        try {
            if ($json = $this->getRemoteEntriesByDate($date, $room)) {
                return json_decode($json);
            }

        } catch (\Exception $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur agenda', $e->getMessage());

        }

        return [];
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    public function getRemoteEntries(int $room): ?string
    {
        $params = ['room' => $room];
        try {
            $request = $this->httpClient->request(
                'GET',
                $this->url.'/entries/'.$room,
                [
                    'query' => $params,
                ]
            );

            return $this->getContent($request);
        } catch (TransportExceptionInterface $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    public function getRemoteEntriesByDate(string $date, int $room)
    {
        $params = ['date' => $date, 'room' => $room];
        try {
            $request = $this->httpClient->request(
                'GET',
                $this->url.'/entries/'.$date.'/'.$room,
                [
                    'query' => $params,
                ]
            );

            return $this->getContent($request);
        } catch (TransportExceptionInterface $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function requestGet(string $url, array $options = []): ?string
    {
        try {
            $request = $this->httpClient->request(
                'GET',
                $this->url.$url,
                [
                    'query' => $options,
                ]
            );

            return $this->getContent($request);

        } catch (TransportExceptionInterface $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function requestPost(string $url, array $parameters = []): ?string
    {
        try {
            $request = $this->httpClient->request(
                'POST',
                $this->url.$url,
                [
                    'json' => $parameters,
                ]
            );

            return $this->getContent($request);
        } catch (TransportExceptionInterface $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getContent(ResponseInterface $request): ?string
    {
        $statusCode = $request->getStatusCode();
        if ($statusCode === 200) {
            try {
                return $request->getContent();
            } catch (ClientExceptionInterface | TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
                throw  new \Exception($e->getMessage());
            }
        }

        return null;
    }
}
