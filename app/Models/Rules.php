<?php

namespace App\Models;

class Rules
{
    protected $rulesUrl;

    protected $rules;

    public function __construct($gameTypeId)
    {
        $this->rulesUrl = "https://api.draftkings.com/lineups/v1/gametypes/$gameTypeId/rules?format=json";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->rulesUrl);
        $this->rules = json_decode($response->getBody(), true);
    }

    /**
     * Return the lineup construction template
     * 
     * @return array
     */
    public function getLineupTemplate()
    {
        return $this->rules['lineupTemplate'];
    }

    /**
     * Return the min/max salary cap
     * 
     * @return array
     */
    public function getSalaryCapRules()
    {
        return $this->rules['salaryCap'];
    }
}