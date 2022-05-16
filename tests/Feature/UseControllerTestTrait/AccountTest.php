<?php

namespace Tests\Feature\UserControllerTestTrait;

use App\Models\Account;
use Tests\TestCase;
use Tests\Traits\ControllerTestTrait;

class AccountTest extends TestCase
{
    use ControllerTestTrait;

    // User Dev
    private $email = 'dev@dev.com';
    private $password = '123456789';

    // Table and Model
    private $table = 'accounts';
    private $model = Account::class;

    // Base route
    private $baseRoute = 'account';

    // View
    private $viewList = 'crud::list';
    private $viewShow = 'crud::show';

    // Expect fields
    private $not_null_fields = ['name','email','phone','address'];
    private $is_email_fields = ['email'];
    private $only_number_fields = ['number'];
    private $specific_length_fields = [['name',2,50]];
}
