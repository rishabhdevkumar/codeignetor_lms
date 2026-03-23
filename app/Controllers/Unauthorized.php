<?php

namespace App\Controllers;

class Unauthorized extends BaseController
{
    public function unauthorized()
    {
        return view('errors/unauthorized');
    }
}
