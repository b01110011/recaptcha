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

        if (empty($token)) $errorObject;

        $data = $this->submit($token);
        if (!$data) $errorObject;

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

        /**
         * PHP 5.6.0 changed the way you specify the peer name for SSL context options.
         * Using "CN_name" will still work, but it will raise deprecated errors.
         */
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $options =
        [
            'http' =>
            [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $params,
                // Force the peer to validate (not needed in 5.6.0+, but still works)
                'verify_peer' => true,
                // Force the peer validation to use www.google.com
                $peer_key => 'www.google.com',
            ]
        ];

        $context = stream_context_create($options);

        return json_decode(file_get_contents(self::SITE_VERIFY_URL, false, $context), true);
    }
}