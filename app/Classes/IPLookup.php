<?php

namespace App\Classes;

use Illuminate\Support\Facades\Cache;

class IPLookup
{
    public function lookup($ip)
    {
        if (Cache::has($ip)) {
            $data = Cache::get($ip);
        } else {
            $ipInfo = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

            $country = (string) @$ipInfo->geoplugin_countryName;
            $country_code = (string) @$ipInfo->geoplugin_countryCode;
            $timezone = (string) @$ipInfo->geoplugin_timezone;
            $city = (string) @$ipInfo->geoplugin_city;
            $latitude = (string) @$ipInfo->geoplugin_latitude;
            $longitude = (string) @$ipInfo->geoplugin_longitude;
            $currency = (string) @$ipInfo->geoplugin_currencyCode;

            $data['ip'] = $ip;
            $data['country'] = !empty($country) ? $country : 'Unknown';
            $data['country_code'] = !empty($country_code) ? $country_code : 'Unknown';
            $data['timezone'] = !empty($timezone) ? $timezone : 'Unknown';
            $data['city'] = !empty($city) ? $city : 'Unknown';
            $data['latitude'] = !empty($latitude) ? $latitude : 'Unknown';
            $data['longitude'] = !empty($longitude) ? $longitude : 'Unknown';
            $data['location'] = $data['city'] . ', ' . $data['country_code'];
            $data['currency'] = !empty($currency) ? $currency : 'Unknown';

            Cache::forever($ip, $data);
        }

        return (object) $data;
    }
}