<?php


class HomepageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function _failed(\AcceptanceTester $I)
    {
        $I->displayWarningCodes();
    }

    // tests
    public function loginAsAdmin(AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }
}
