<?php namespace tests\app\controllers\grading;

use app\models\User;
use app\libraries\Core;
use tests\BaseUnitTest;
use app\libraries\response\WebResponse;
use app\controllers\grading\ElectronicGraderController;

class ElectronicGraderControllerTester extends BaseUnitTest
{
   /** @var ElectronicGraderController */
    protected $controller;

    /** @var Core */
    protected $core;

    /** @var User */
    protected $user;

    /** @var bool[] */
    protected $methodsCalled;

    public function setUp(): void
    {
        parent::setUp();

        $this->core = $this->createMockCore([], [], [ 
            'getAllTeamViewedTimesForGradeable'  => [],
            'getAllUsers'                        => [],
            'getPostsForThread'                  => []
        ]);

        $this->controller = new ElectronicGraderController($this->core);
    }

    /** @test */
    public function testQueriesMadeInElectronicGraderView()
    {
        $response1 = $this->controller->showDetails();
        $response2 = $this->controller->showGrading();

        $this->assertMethodCalled('getAllTeamViewedTimesForGradeable');
        $this->assertMethodCalled('getAllUsers');
        $this->assertMethodCalled('getPostsForThread'); 
        $this->assertInstanceOf(WebResponse::class, $response1->web_response);
        $this->assertInstanceOf(WebResponse::class, $response2->web_response);
    }

}
