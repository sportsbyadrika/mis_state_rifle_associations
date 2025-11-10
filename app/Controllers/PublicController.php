<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Election;
use App\Models\News;
use App\Models\Bylaw;

class PublicController extends Controller
{
    public function home(): void
    {
        $electionModel = new Election();
        $newsModel = new News();
        $bylawModel = new Bylaw();

        $this->view('public/home', [
            'elections' => $electionModel->allPublished(),
            'newsItems' => $newsModel->latestPublic(),
            'bylaws' => $bylawModel->allPublished(),
        ]);
    }
}
