<?php
class ApiCest
{
    public function tryApi(ApiTester $I)
    {
        $I->sendGET('/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryHello(ApiTester $I)
    {
        $I->sendGET('/hello');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Hello World');
    }
}
