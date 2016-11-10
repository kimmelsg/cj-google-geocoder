<?php

namespace NavJobs\GoogleGeocoder\Test;

class TestResponses
{
    /**
     * @return array
     */
    public static function getCityResponse()
    {
        return json_encode([
            'results' => [
                [
                    'address_components' => [
                        [
                            'long_name' => 'Asheville',
                            'short_name' => 'Asheville',
                            'types' => [
                                'locality',
                                'political',
                            ],
                        ],
                        [
                            'long_name' => 'Buncombe County',
                            'short_name' => 'Buncombe County',
                            'types' => [
                                'administrative_area_level_2',
                                'political',
                            ]
                        ],
                        [
                            'long_name' => 'North Carolina',
                            'short_name' => 'NC',
                            'types' => [
                                'administrative_area_level_1',
                                'political',
                            ],
                        ],
                        [
                            'long_name' => 'United States',
                            'short_name' => 'US',
                            'types' => [
                                'country',
                                'political',
                            ],
                        ],
                    ],
                    'formatted_address' => 'Asheville, NC, USA',
                    'geometry' => [
                        'bounds' => [
                            'northeast' => [
                                'lat' => 35.656299,
                                'lng' => -82.4599379,
                            ],
                            'southwest' => [
                                'lat' => 35.421565,
                                'lng' => -82.6708731,
                            ],
                        ],
                        'location' => [
                            'lat' => 35.5950581,
                            'lng' => -82.5514869,
                        ],
                        'location_type' => 'APPROXIMATE',
                        'viewport' => [
                            'northeast' => [
                                'lat' => 35.656299,
                                'lng' => -82.4599379,
                            ],
                            'southwest' => [
                                'lat' => 35.421565,
                                'lng' => -82.6708731,
                            ],
                        ],
                    ],
                    'place_id' => 'ChIJCW8PPKmMWYgRXTo0BsEx75Q',
                    'types' => [
                        'locality',
                        'political',
                    ],
                ]
            ],
            'status' => 'OK',
        ]);
    }

    /**
     * @return array
     */
    public static function getInvalidKeyResponse()
    {
        return json_encode([
            'error_message' => 'The provided API key is invalid.',
            'results' => [],
            'status' => 'REQUEST_DENIED'
        ]);
    }

    /**
     * @return array
     */
    public static function getAccessDeniedResponse()
    {
        return json_encode([
            'error_message' => 'Access Denied',
            'results' => [],
            'status' => 'REQUEST_DENIED'
        ]);
    }

    /**
     * @return array
     */
    public static function getQuotedExceededResponse()
    {
        return json_encode([
            'error_message' => 'Over Query Limit',
            'results' => [],
            'status' => 'OVER_QUERY_LIMIT'
        ]);
    }

    /**
     * @return array
     */
    public static function getNoResultsResponse()
    {
        return json_encode([
            'results' => null,
            'status' => 'NO'
        ]);
    }
}
