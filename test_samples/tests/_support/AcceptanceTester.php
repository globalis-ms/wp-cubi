<?php

namespace WpCubiTest;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    public function loginAsAdmin()
    {
        $I = $this;
        $I->amOnPage(Page\Login::$URL);
        $I->dontSeeInCurrentUrl(Page\BackOffice::$URL);
        $I->seeInCurrentUrl(Page\Login::$URL);
        $I->submitForm(Page\Login::$LoginForm, [
            'log' => $I->getConfigParameter('admin_login'),
            'pwd' => $I->getConfigParameter('admin_password')
        ]);
        $I->seeInCurrentUrl(Page\BackOffice::$URL);
        $I->dontSeeElement('//b[text()="Warning"]');
    }


    public function displayWarningCodes()
    {
        $I = $this;
        $warnings = $I->grabMultiple('//b[text()="Warning"]/parent::*');
        foreach ($warnings as $warning) {
            $I->variableDump($warnings);
        }
    }
}
