<?php

namespace NavJobs\GoogleGeocoder;

use GuzzleHttp\Client;
use NavJobs\GoogleGeocoder\Exceptions\NoResult;
use NavJobs\GoogleGeocoder\Exceptions\InvalidKey;
use NavJobs\GoogleGeocoder\Exceptions\AccessDenied;
use NavJobs\GoogleGeocoder\Exceptions\QuotaExceeded;

class GoogleGeocoder
{
    protected $endpoint = 'https://maps.googleapis.com/maps/api/geocode/json?';
    protected $client;

    /**
     * GoogleGeocoder constructor.
     *
     * @param Client $client
     * @param $apiKey
     * @param $language
     * @param $region
     */
    public function __construct(Client $client, $apiKey = null,  $language = null, $region = null)
    {
        $this->client = $client;
        $this->addOptionalQueryParameters($apiKey, $language, $region);
    }

    /**
     * Geocodes the provided address
     *
     * @param $address
     * @return array
     */
    public function geocode($address)
    {
        $query = $this->buildQuery('address', $address);

        $response = $this->validateResponse(
            $this->getResponse($query)
        );

        return $this->buildResults($response['results']);
    }

    /**
     * Reverse geocodes by the provided place id.
     *
     * @param $placeId
     * @return array
     */
    public function reverseByPlaceId($placeId)
    {
        $query = $this->buildQuery('place_id', $placeId);

        $response = $this->validateResponse(
            $this->getResponse($query)
        );

        return $this->buildResults($response['results']);
    }

    /**
     * Reverse geocodes by the provided coordinates.
     *
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function reverseByCoordinates($latitude, $longitude)
    {
        $query = $this->buildQuery('address', "{$latitude},{$longitude}");

        $response = $this->validateResponse(
            $this->getResponse($query)
        );

        return $this->buildResults($response['results']);
    }

    /**
     * Adds any of the optional query parameters to the query.
     *
     * @param $apiKey
     * @param $language
     * @param $region
     */
    private function addOptionalQueryParameters($apiKey, $language, $region)
    {
        if ($apiKey) {
            $this->endpoint = $this->buildQuery('key', $apiKey);
        }

        if ($language) {
            $this->endpoint = $this->buildQuery('language', $language);
        }

        if ($region) {
            $this->endpoint = $this->buildQuery('region', $region);
        }
    }

    /**
     * Helper that adds an additional parameter to the query.
     *
     * @param $key
     * @param $value
     * @return string
     */
    private function buildQuery($key, $value)
    {
        return sprintf('%s&%s=%s', $this->endpoint, $key, rawurlencode($value));
    }

    /**
     * Get the response from Google and decode it.
     *
     * @param $endpoint
     * @return mixed
     */
    private function getResponse($endpoint)
    {
        return json_decode($this->client->get($endpoint)->getBody(), true);
    }

    /**
     * Makes sure the response does not contain any errors.
     *
     * @param $response
     * @return mixed
     * @throws AccessDenied
     * @throws InvalidKey
     * @throws NoResult
     * @throws QuotaExceeded
     */
    private function validateResponse($response)
    {
        if (!isset($response)) {
            throw new NoResult(sprintf('Could not execute query'));
        }

        if ('REQUEST_DENIED' === $response['status'] && 'The provided API key is invalid.' === $response['error_message']) {
            throw new InvalidKey(sprintf('API key is invalid'));
        }

        if ('REQUEST_DENIED' === $response['status']) {
            throw new AccessDenied(sprintf('API access denied. Message: %s', $response['error_message']));
        }

        // you are over your quota
        if ('OVER_QUERY_LIMIT' === $response['status']) {
            throw new QuotaExceeded('Daily quota exceeded');
        }

        // no result
        if (!isset($response['results']) || !count($response['results']) || 'OK' !== $response['status']) {
            throw new NoResult('No results for query');
        }

        return $response;
    }

    /**
     * Transform the response from Google into a result set.
     *
     * @param $results
     * @return array
     */
    private function buildResults($results)
    {
        return array_map(function ($result) {
            $coordinates = $result['geometry']['location'];
            $data = [
                'address' => $result['formatted_address'],
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lng'],
                'place_id' => $result['place_id'],
                'types' => $result['types']
            ];

            if (isset($result['geometry']['bounds'])) {
                $bounds = $result['geometry']['bounds'];
                $data['bounds'] = [
                    'northeast' => [
                        'latitude' => $bounds['northeast']['lat'],
                        'longitude' => $bounds['northeast']['lng'],
                    ],
                    'southwest' => [
                        'latitude' => $bounds['southwest']['lat'],
                        'longitude' => $bounds['southwest']['lng'],
                    ]
                ];
            }

            return $data;
        }, $results);
    }

}
