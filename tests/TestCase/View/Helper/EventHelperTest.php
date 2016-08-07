<?php

namespace CvoTechnologies\GitHubContributions\Test\TestCase\View\Helper;

use Cake\View\View;
use Croogo\Core\TestSuite\TestCase;
use CvoTechnologies\GitHubContributions\View\Helper\EventHelper;
use CvoTechnologies\GitHub\Model\Resource\Event;
use CvoTechnologies\GitHub\Model\Resource\Event\CreateEvent;
use CvoTechnologies\GitHub\Model\Resource\Repository;
use CvoTechnologies\GitHub\Model\Resource\User;

class EventHelperTest extends TestCase
{
    /**
     * @var \CvoTechnologies\GitHubContributions\View\Helper\EventHelper
     */
    public $helper;

    public function setUp()
    {
        parent::setUp();

        $this->helper = new EventHelper(new View());
    }

    /**
     * @dataProvider eventsDataProvider
     */
    public function testDescribe(Event $event, $expected)
    {
        $this->assertEquals($expected, $this->helper->describe($event));
    }

    public function testActorLink()
    {
        $this->assertEquals(
            '<a href="https://github.com/marlinc">Marlinc</a>',
            $this->helper->actorLink(
                new User([
                    'login' => 'marlinc',
                    'display_login' => 'Marlinc'
                ])
            )
        );
    }

    public function testRepoLink()
    {
        $this->assertEquals(
            '<a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>',
            $this->helper->repoLink(
                new Repository([
                    'name' => 'cvo-technologies/croogo-github-contributions',
                ])
            )
        );
    }

    public function eventsDataProvider()
    {
        $actor = new User([
            'login' => 'Marlinc',
            'display_login' => 'Marlinc'
        ]);
        $repo = new Repository([
            'name' => 'cvo-technologies/croogo-github-contributions'
        ]);
        $pullRequest = [
            'title' => 'Test pull request'
        ];
        $issue = [
            'title' => 'Test issue'
        ];
        $comment = [
            'body' => 'Test comment'
        ];

        return [
            [
                new CreateEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> created <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>'
            ],
            [
                new Event\PushEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'size' => 1
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> pushed one commit to <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>'
            ],
            [
                new Event\PushEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'size' => 5
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> pushed 5 commits to <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>'
            ],
            [
                new Event\DeleteEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'ref_type' => 'branch',
                        'ref' => 'test-branch'
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> deleted branch <strong>test-branch</strong> from <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>'
            ],
            [
                new Event\PullRequest\OpenedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'pull_request' => $pullRequest
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> just opened a pull request on <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>: Test pull request'
            ],
            [
                new Event\PullRequest\ClosedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'pull_request' => $pullRequest
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> just closed a pull request on <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>: Test pull request'
            ],
            [
                new Event\Issues\OpenedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'issue' => $issue
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> just opened an issue on <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>: Test issue'
            ],
            [
                new Event\Issues\ClosedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'issue' => $issue
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> just closed an issue on <a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>: Test issue'
            ],
            [
                new Event\IssueComment\CreatedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                    'payload' => [
                        'issue' => $issue,
                        'comment' => $comment
                    ]
                ]),
                '<a href="https://github.com/Marlinc">Marlinc</a> just commented on <strong>Test issue (<a href="https://github.com/cvo-technologies/croogo-github-contributions">cvo-technologies/croogo-github-contributions</a>)</strong>: Test comment'
            ],
            [
                new Event\Watch\StartedEvent([
                    'actor' => $actor,
                    'repo' => $repo,
                ]),
                'Marlinc just started watching cvo-technologies/croogo-github-contributions'
            ]
        ];
    }
}
