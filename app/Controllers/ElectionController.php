<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Auth;
use App\Models\Election;

class ElectionController extends Controller
{
    public function index(): void
    {
        $electionModel = new Election();
        $elections = $electionModel->allPublished();
        $this->view('public/elections', [
            'elections' => $elections,
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $data = [
            'organization_id' => (int) ($_POST['organization_id'] ?? 0),
            'title' => Validator::sanitize($_POST['title'] ?? ''),
            'held_on' => Validator::sanitize($_POST['held_on'] ?? ''),
            'description' => Validator::sanitize($_POST['description'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];

        $electionModel = new Election();
        $electionModel->create($data);
        header('Location: ' . \url_to('dashboard'));
        exit;
    }
}
