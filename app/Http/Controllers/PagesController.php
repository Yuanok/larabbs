<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root(){
        return view('pages.root');
    }

    public function permissionDenid(){
        if(config('administrator.permission')()){
            return redirect(url(config('administrator.uri')),302);
        }

        return view('pages.permission_denid');
    }
}
