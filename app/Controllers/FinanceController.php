<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\FinancialYear;
use App\Models\FinanceEntry;

class FinanceController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $organizationId = (int) ($user['organization_id'] ?? 1);
        $yearModel = new FinancialYear();
        $years = $yearModel->allForOrganization($organizationId);

        $currentYear = $years[0] ?? null;
        $entries = [];
        if ($currentYear) {
            $entryModel = new FinanceEntry();
            $entries = $entryModel->allForYear((int) $currentYear['id']);
        }

        $this->view('dashboard/finance', [
            'user' => $user,
            'years' => $years,
            'entries' => $entries,
            'csrf' => Csrf::token(),
        ]);
    }
}
