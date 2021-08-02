<?php

namespace App\Models;

class DraftGroup
{
  protected $sport;

  protected $type;

  protected $draftGroupUrl;

  protected $playersUrl;

  public function __construct($sport = '', $type = '')
  {
    $this->sport = $sport;
    $this->type = $type;
    $this->draftGroupUrl = "https://www.draftkings.com/lobby/getcontests?sport={$this->sport}";
    $this->playersUrl = "https://api.draftkings.com/draftgroups/v1/draftgroups/{draft_group_id}/draftables?format=json";
  }

  /**
   * Get the draft group id based on type
   * 
   * @return int $draftGroupId
   */
  public function getDraftGroupId()
  {
    $draftGroupId = 0;
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $this->draftGroupUrl);

    $statusCode = $response->getStatusCode();
    $content = json_decode($response->getBody(), true);

    foreach($content['DraftGroups'] as $_draftGroup) {
        if(strtolower($_draftGroup['DraftGroupTag']) == $this->type) {
            $draftGroupId = $_draftGroup['DraftGroupId'];
            break;
        }
     }

    return $draftGroupId;
  }

  /**
   * Get players from the draft group
   */
  public function getPlayersFromDraftGroup()
  {
    $draftGroupId = $this->getDraftGroupId();
    $playersUrl = $this->buildPlayersUrl($draftGroupId);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $playersUrl);

    return json_decode($response->getBody(), true);
  }

  protected function buildPlayersUrl($draftGroupId)
  {
    return str_replace('{draft_group_id}', $draftGroupId, $this->playersUrl);
  }
}