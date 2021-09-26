<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class KushkiController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout = 'layouts.kushkiTest';
        $this->setupLayout();
        $this->layout->content = View::make('kushki.index', []);
    }

    public function confirm()
    {
        $data = Request::all();
        dd($data);
    }
}