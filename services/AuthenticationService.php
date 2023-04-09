<?php

namespace f7k\Service;

use f7k\Sources\ServiceBase;
use Hybridauth\Provider\Google;
use Hybridauth\User\Profile;

class AuthenticationService extends ServiceBase {

    private Google $GoogleOAuthAdapter;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();

        $config = [
            'callback' => $_ENV['OAUTH_GOOGLE_REDIRECT_URI'],
            'keys' => [
                'id' => $_ENV['OAUTH_GOOGLE_CLIENT_ID'],
                'secret' => $_ENV['OAUTH_GOOGLE_CLIENT_SECRET']
            ]
        ];

        $this->GoogleOAuthAdapter = new Google($config);
    }

    public function login(): void
    {
        $this->GoogleOAuthAdapter->authenticate();

        $profile = $this->getUserProfile();

        $_SESSION['user'] = [
            "name" => $profile->displayName,
            "email" => $profile->email,
        ];
    }

    public function logout(): void
    {
        $this->GoogleOAuthAdapter->disconnect();
        unset($_SESSION['user']);
    }

    public function isLoggedIn(): bool
    {
        return $this->GoogleOAuthAdapter->isConnected() ? true : false;
    }

    public function getUserProfile(): Profile
    {
        return $this->GoogleOAuthAdapter->getUserProfile(); 

    }
}