<?php

use GuzzleHttp\Client;

if (!function_exists('installer_trans')) {
    function installer_trans($key)
    {
        return $key;
    }
}

if (!function_exists('purchaseCodeValidation')) {
    function purchaseCodeValidation($purchaseCode, $alias)
    {
        try {
            $client = new Client();
            $url = config('system.license.api');
            $queryParams = http_build_query([
                'purchase_code' => $purchaseCode,
                'alias' => strtolower($alias),
                'website' => url('/'),
            ]);
            $res = $client->get($url . '?' . $queryParams);
            if ($res->getStatusCode() == 200) {
                return json_decode($res->getBody());
            }
            return false;
        } catch (RequestException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('isInLiveServer')) {
    function isInLiveServer()
    {
        $locals = ['localhost', '127.0.0.1'];
        $host = parse_url(url('/'))['host'] ?? '';
        if (in_array($host, $locals)) {
            return false;
        }
        if (str_ends_with($host, '.test') || str_ends_with($host, 'viro.com')) {
            return false;
        }
        return true;
    }
}

if (!function_exists('licenseType')) {
    function licenseType($type = null)
    {
        $licenseType = config('system.license.type');
        if ($type) {
            return ($type == $licenseType) ? true : false;
        } else {
            return $licenseType;
        }
    }
}

if (!function_exists('extensionAvailability')) {
    function extensionAvailability($name)
    {
        if (!extension_loaded($name)) {
            $response = false;
        } else {
            $response = true;
        }
        return $response;
    }
}

if (!function_exists('phpExtensions')) {
    function phpExtensions()
    {
        $extensions = [
            'BCMath',
            'Ctype',
            'Fileinfo',
            'JSON',
            'Mbstring',
            'OpenSSL',
            'PDO',
            'pdo_mysql',
            'Tokenizer',
            'XML',
            'cURL',
            'zip',
            'GD',
        ];
        return $extensions;
    }
}

if (!function_exists('filePermissionValidation')) {
    function filePermissionValidation($name)
    {
        $perm = substr(sprintf('%o', fileperms($name)), -4);
        if ($perm >= '0775') {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }
}

if (!function_exists('filePermissions')) {
    function filePermissions()
    {
        $filePermissions = [
            base_path('addons/'),
            base_path('bootstrap/'),
            base_path('bootstrap/cache/'),
            base_path('lang/'),
            base_path('public/'),
            base_path('public/images/'),
            base_path('public/themes/'),
            base_path('public/themes/basic/'),
            base_path('public/themes/basic/images'),
            base_path('resources/'),
            base_path('resources/views/'),
            base_path('resources/views/themes/'),
            base_path('storage/'),
            base_path('storage/app/'),
            base_path('storage/framework/'),
            base_path('storage/logs/'),
        ];
        return $filePermissions;
    }
}

if (!function_exists('currentStep')) {
    function currentStep($stepNumber)
    {
        $steps = [
            'requirements' => 1,
            'permissions' => 2,
            'license' => 3,
            'database' => 4,
            'import' => 5,
            'complete' => 6,
        ];

        $step = $steps[request()->segment(2)] ?? null;
        if ($step == $stepNumber) {
            return 'current';
        } elseif ($step > $stepNumber) {
            return 'active';
        }
    }
}