<?php

namespace CvoTechnologies\GitHubContributions\Test\TestCase\View\Cell;

use Cake\View\CellTrait;
use Croogo\Core\TestSuite\TestCase;
use CvoTechnologies\GitHubContributions\View\Cell\ContributionsCell;
use CvoTechnologies\StreamEmulation\Emulation\HttpEmulation;
use CvoTechnologies\StreamEmulation\StreamWrapper;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class ContributionsCellTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        StreamWrapper::overrideWrapper('https');

        $request = $this->getMockBuilder('Cake\Network\Request')->getMock();
        $response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $this->View = new \Cake\View\View($request, $response);
    }

    public function tearDown()
    {
        parent::tearDown();

        StreamWrapper::restoreWrapper('https');
    }


    public function testDisplay()
    {
        StreamWrapper::emulate(HttpEmulation::fromCallable(function (RequestInterface $request) {
            return new Response(200, [], file_get_contents(TESTS . 'events.json'));
        }));

        $cell = $this->View->cell('CvoTechnologies/GitHubContributions.Contributions::display', [], [
            'user' => 'Marlinc',
            'limit' => '123',
        ]);
        $render = "{$cell}";

        $this->assertContains('pushed 3 commits to ', $render);
        $this->assertContains('pushed one commit to', $render);
    }
}
