<?php

use App\Core\Database;
use App\Models\User;
use App\Models\Organization;
use App\Models\FinancialYear;
use App\Models\IncomeExpenseHead;
use App\Models\MembershipType;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../bootstrap.php';

$db = Database::getInstance();
$db->beginTransaction();

try {
    $userModel = new User();
    $organizationModel = new Organization();
    $financialYearModel = new FinancialYear();
    $incomeHeadModel = new IncomeExpenseHead();
    $membershipTypeModel = new MembershipType();

    $ksraId = $organizationModel->create([
        'name' => 'Kerala State Rifle Association',
        'type' => Organization::TYPE_STATE,
        'address' => 'Thiruvananthapuram, Kerala',
        'email' => 'info@ksra.org',
        'phone' => '+91-471-0000000',
    ]);

    $superAdminId = $userModel->create([
        'name' => 'KSRA Super Admin',
        'email' => 'superadmin@ksra.org',
        'phone' => '+91-9000000000',
        'password' => password_hash('ChangeMe123!', PASSWORD_DEFAULT),
        'role' => User::ROLE_SUPER_ADMIN,
        'status' => 'active',
    ]);

    $draId = $organizationModel->create([
        'name' => 'Thiruvananthapuram District Rifle Association',
        'type' => Organization::TYPE_DISTRICT,
        'parent_id' => $ksraId,
        'address' => 'Thiruvananthapuram, Kerala',
        'email' => 'tvm@dra.org',
        'phone' => '+91-471-1111111',
    ]);

    $financialYearModel->create([
        'organization_id' => $ksraId,
        'label' => '2024-2025',
        'date_from' => '2024-04-01',
        'date_to' => '2025-03-31',
        'is_current' => 1,
    ]);

    $incomeHeadModel->create([
        'organization_id' => $ksraId,
        'type' => 'income',
        'name' => 'Membership Fees',
        'description' => 'Annual membership fee collections',
    ]);

    $membershipTypeModel->create([
        'organization_id' => $draId,
        'name' => 'Annual DRA Membership',
        'description' => 'One-year membership for district shooters',
        'amount' => 1500,
        'duration_months' => 12,
    ]);

    $db->prepare('INSERT INTO news_items (title, body, published_at, is_public) VALUES (:title, :body, :published_at, 1)')->execute([
        'title' => 'KSRA Announces New Training Programs',
        'body' => 'KSRA launches new safety and marksmanship training sessions across districts.',
        'published_at' => date('Y-m-d H:i:s'),
    ]);

    $db->prepare('INSERT INTO bylaws (title, document_path, published_at) VALUES (:title, :document_path, :published_at)')->execute([
        'title' => 'KSRA Constitution 2024',
        'document_path' => '/storage/documents/ksra-constitution.pdf',
        'published_at' => date('Y-m-d H:i:s'),
    ]);

    $db->commit();
    echo "Database seeded successfully." . PHP_EOL;
} catch (Throwable $exception) {
    $db->rollBack();
    echo 'Seeding failed: ' . $exception->getMessage() . PHP_EOL;
}
