<?php

namespace B01110011ReCaptcha;

class ReCaptcha
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public $secret;
    public $permissibleScore;
    public $token;

    public function __construct($secret, $permissibleScore = 0)
    {
        if (empty($secret))
            throw new \RuntimeException('No secret provided');

        if (!is_string($secret))
            throw new \RuntimeException('The provided secret must be a string');

        $this->secret = $secret;
        $this->permissibleScore = $permissibleScore;
    }

    public function verify($token)
    {
        $errorObject =
        [
            'success' => false,
            'score' => false,
            'isSuccess' => false,
            'hostname' => null,
            'action' => null,
            'challenge_ts' => null,
            'error_codes' => ['Empty token.']
        ];

        if (empty($token)) return $errorObject;

        $data = $this->submit($token);
        if (!$data) return $errorObject;

        $success = isset($data['success']) ? $data['success'] : false;
        $score = isset($data['score']) ? $data['score'] : false;
        $isSuccess = $success && $score !== false ? $this->permissibleScore <= $score : false;
        $hostname = isset($data['hostname']) ? $data['hostname'] : null;
        $action = isset($data['action']) ? $data['action'] : null;
        $challenge_ts = isset($data['challenge_ts']) ? $data['challenge_ts'] : null;
        $error_codes = $data['error-codes'];
        
        return
        [
            'success' => $success,
            'score' => $score,
            'isSuccess' => $isSuccess,
            'hostname' => $hostname,
            'action' => $action,
            'challenge_ts' => $challenge_ts,
            'error_codes' => $error_codes
        ];
    }

    public function submit($token)
    {
        $params = ['secret' => $this->secret, 'response' => $token];
        $params = http_build_query($params, '', '&');

        $curl = curl_init(self::SITE_VERIFY_URL);

        curl_setopt_array($curl,
        [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);
    }
}