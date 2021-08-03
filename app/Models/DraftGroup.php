<?php

namespace App\Models;

class DraftGroup
{
  protected $sport;

  protected $type;

  protected $draftGroupUrl;

  protected $playersUrl;

  protected $draftGroupId;

  protected $gameTypeId;

  public function __construct($sport = '', $type = '')
  {
    $this->sport = $sport;
    $this->type = $type;
    $this->draftGroupId = 0;
    $this->gameTypeId = 0;
    $this->draftGroupUrl = "https://www.draftkings.com/lobby/getcontests?sport={$this->sport}";
    $this->playersUrl = "https://api.draftkings.com/draftgroups/v1/draftgroups/{draft_group_id}/draftables?format=json";
    $this->gameTypeUrl = "https://api.draftkings.com/draftgroups/v1/{draft_group_id}?format=json";

    $this->setDraftGroupId();
    $this->setGameTypeId();
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
  public function getPlayersFromDraftGroup()
  {
    $draftGroupId = $this->getDraftGroupId();
    $playersUrl = $this->buildDraftGroupUrl($draftGroupId, $this->playersUrl);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $playersUrl);
    $players = json_decode($response->getBody(), true);

    return $players['draftables'];
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
    $this->gameTypeId = $gameTypeArray['draftGroup']['contestType']['contestTypeId'];
  }

  /**
   * Get draft group rules
   * 
   * @return object App\Model\Rules
   */
  public function getDraftGroupRules()
  {
    $rules = new Rules($this->gameTypeId);

    return $rules;
  }
}