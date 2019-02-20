[![Circle CI](https://circleci.com/gh/ConstructionJobs/google-geocoder.svg?style=shield)](https://circleci.com/gh/ConstructionJobs/google-geocoder)
[![Code Climate](https://codeclimate.com/github/ConstructionJobs/google-geocoder/badges/gpa.svg)](https://codeclimate.com/github/ConstructionJobs/google-geocoder)

###### Google Geocoding
Provides an abstraction for requests to Google Maps geocoding service.

## Installation
You can install this package via Composer using this command:

```bash
composer require ConstructionJobs/google-geocoder
```

## Laravel Installation
This package comes with a service provider for use with Laravel.
You will not need to do anything if you're using laravel version 5.5 and up.

If you are using laravel 5.4 or below, to install the service provider:

```php
// config/app.php
'providers' => [
    // other providers
    'ConstructionJobs\GoogleGeocoder\GoogleGeocoderServiceProvider'
];
```

Also you must publish the config file:

```php
php artisan vendor:publish --provider="ConstructionJobs\GoogleGeocoder\GoogleGeocoderServiceProvider"
```

The config file allows you to set your `api key`, `language` and `region`.

## Usage

There are three ways that you may use this package.

```php
// Geocode an address
$geocoder = new Geocoder;
$geocoder->geocode('New York, NY');

// Reverse geocode from coordinates
$geocoder = new Geocoder;
$geocoder->reverseByCoordinates(40.7127837, -74.0059413);

// Reverse geocode from a Google place id.
$geocoder = new Geocoder;
$geocoder->reverseByPlaceId('ChIJOwg_06VPwokRYv534QaPC8g');
```

All of these methods return a standard response format as follows:

```php
[
    'address' => 'New York, NY, USA',
    'latitude' => 40.7127837,
    'longitude' => -74.0059413,
    'place_id' => ChIJOwg_06VPwokRYv534QaPC8g,
    'types' => [
        'locality',
        'political'
    ]
    $bounds = [
        'northeast' => [
            'latitude' => 40.9152555,
            'longitude' => -73.7002721,
        ],
        'southwest' => [
            'latitude' => 40.496044,
            'longitude' => -74.255735,
        ]
    ];
}
```
