<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftGroup;

class DraftkingsController extends Controller
{
    public function getPlayers(Request $request, $sport, $type)
    {
        $draftGroup = new DraftGroup($sport, $type);
        $players = $draftGroup->getPlayersFromDraftGroup();

        return response()->json($players);
    }
}
