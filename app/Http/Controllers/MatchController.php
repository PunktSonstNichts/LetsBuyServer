<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MatchController extends Controller
{
    public function getMatch (Request $request) {
		$this->validate($request, [
            'list_id' => 'required|max:255',
        ]);

        
    }
}
