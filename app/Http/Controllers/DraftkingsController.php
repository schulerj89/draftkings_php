<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftGroup;

class DraftkingsController extends Controller
{
    public function getPlayers($sport, $type)
    {
        $draftGroup = new DraftGroup($sport, $type);
        $players = $draftGroup->getPlayersFromDraftGroup();

        return response()->json($players);
    }

    public function generateLineup($sport, $type)
    {
        $draftGroup = new DraftGroup($sport, $type);
        $lineup = $draftGroup->generateLineup();

        return response()->json($lineup);
    }
}
