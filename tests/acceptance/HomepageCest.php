<?php

namespace App\Test;

class HomepageCest
{
    public function _before(\App\Test\AcceptanceTester $I)
    {
    }

    public function _after(\App\Test\AcceptanceTester $I)
    {
    }

    public function _failed(\App\Test\AcceptanceTester $I)
    {
        $I->displayWarningCodes();
    }

    // tests
    public function loginAsAdmin(\App\Test\AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }
}
