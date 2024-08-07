<?php

namespace CyberDuck\Pardot\OAuth;

class PardotOAuth
{
    private string $domain = 'https://login.salesforce.com';
    private string $authorize_uri = '/services/oauth2/authorize';
    private string $token_uri = '/services/oauth2/token';

    private string $client_id;
    private string $client_secret;
    private string $redirect_uri;

    public function __construct(
        string $client_id,
        string $client_secret,
        string $redirect_uri,
        string $domain
    )
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->domain = $domain;
    }

    public function getAuthorizeUri(): string
    {
        return $this->getDomain() . $this->authorize_uri;
    }

    public function getTokenUri(): string
    {
        return $this->getDomain() . $this->token_uri;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getAuthorizationUri(): string
    {
        $data = [
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'scope' => ''
        ];
        $query = http_build_query($data);
        return sprintf('%s?%s', $this->getAuthorizeUri(), $query);
    }

    public function getAccessToken($authorizationCode): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->getTokenUri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query([
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => $authorizationCode,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirect_uri
            ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;
    }
}
