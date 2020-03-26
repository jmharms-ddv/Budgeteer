<?php

namespace App\Http\Controllers\Web;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    /**
      * Gets the view that displays the app.
      */
    public function getApp(Request $request){
        /*
          If the request has a ref variable, redirect to the
          homepage. This is so the SPA doesn't break.
        */
        if($request->has('ref')) {
            return redirect('/');
        }
        /*
          Return the view
        */
        return view('home');
    }

    /**
      * Logs in the user and redirects them home.
      */
    public function postLogin(Request $request){
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            return redirect('/#/home');
        }

        return redirect('/login');
    }

    /**
      * Logs out the user and redirects them home.
      */
    public function getLogout() {
        Auth::logout();
        return redirect('/');
    }
}
