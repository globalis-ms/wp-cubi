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
        $I->amOnPage('/wp/wp-admin/');
        $I->dontSeeInCurrentUrl('/wp/wp-admin/');
        $I->seeInCurrentUrl('/wp/wp-login.php');
        $I->submitForm('#loginform', [
            'log' => $I->getConfigParameter('admin_login'),
            'pwd' => $I->getConfigParameter('admin_password')
        ]);
        $I->seeInCurrentUrl('/wp/wp-admin/');
        $I->dontSee('Warning');
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
