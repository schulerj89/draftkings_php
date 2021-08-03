<?php

namespace App\Models;

use App\Models\Player;

class DraftGroup
{
  protected $sport;

  protected $type;

  protected $draftGroupUrl;

  protected $playersUrl;

  protected $draftGroupId;

  protected $gameTypeId;

  protected $rules;

  protected $players;

  public function __construct($sport = '', $type = '')
  {
    $this->sport = $sport;
    $this->type = $type;
    $this->draftGroupId = 0;
    $this->gameTypeId = 0;
    $this->rules = null;
    $this->players = [];
    $this->draftGroupUrl = "https://www.draftkings.com/lobby/getcontests?sport={$this->sport}";
    $this->playersUrl = "https://api.draftkings.com/draftgroups/v1/draftgroups/{draft_group_id}/draftables?format=json";
    $this->gameTypeUrl = "https://api.draftkings.com/draftgroups/v1/{draft_group_id}?format=json";

    $this->setDraftGroupId();
    $this->setGameTypeId();
    $this->setDraftGroupRules();
    $this->setPlayersFromDraftGroup();
  }

  /**
   * Return draft group id
   * 
   * @return int
   */
  public function getDraftGroupId()
  {
    return $this->draftGroupId;
  }

  /**
   * Return players url
   * 
   * @return string
   */
  public function getPlayersUrl()
  {
    return $this->playersUrl;
  }

  /**
   * Set the draft group id based on typexw
   */
  public function setDraftGroupId()
  {
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $this->draftGroupUrl);

    $statusCode = $response->getStatusCode();
    $content = json_decode($response->getBody(), true);

    foreach($content['DraftGroups'] as $_draftGroup) {
        if(strtolower($_draftGroup['DraftGroupTag']) == $this->type) {
            $this->draftGroupId = $_draftGroup['DraftGroupId'];
            break;
        }
    }
  }

  /**
   * Get players from the draft group
   * 
   * @return array
   */
  public function setPlayersFromDraftGroup()
  {
    $draftGroupId = $this->getDraftGroupId();
    $playersUrl = $this->buildDraftGroupUrl($draftGroupId, $this->playersUrl);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $playersUrl);
    $players = json_decode($response->getBody(), true);

    $this->players = $players['draftables'];
  }

  /**
   * Get players from the draft group
   * 
   * @return array
   */
  public function getPlayersFromDraftGroup()
  {
    return $this->players;
  }

  /**
   * Format players url with draft group id
   * 
   * @param int $draftGroupId
   * @param string $url
   * @return string
   */
  public function buildDraftGroupUrl($draftGroupId, $url)
  {
    return str_replace('{draft_group_id}', $draftGroupId, $url);
  }

  /**
   * Set game type id
   */
  public function setGameTypeId()
  {
    $draftGroupId = $this->getDraftGroupId();
    $gameTypeUrl = $this->buildDraftGroupUrl($draftGroupId, $this->gameTypeUrl);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $gameTypeUrl);
    $gameTypeArray = json_decode($response->getBody(), true);

    $this->gameTypeId = $gameTypeArray['draftGroup']['gameTypeId'];
  }

  /**
   * Set draft group rules
   */
  public function setDraftGroupRules()
  {
    $this->rules = new Rules($this->gameTypeId);
  }

  public function getDraftGroupRules()
  {
    return $this->rules;
  }

  /**
   * Generate lineup based on draft group
   * 
   * @return array $lineup
   */
  public function generateLineup()
  {
    $lineupObj = new Lineup($this->players, $this->rules);
    $lineup = $lineupObj->generateLineup();

    return $lineup;
  }
}