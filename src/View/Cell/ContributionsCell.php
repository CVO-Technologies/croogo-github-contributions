<?php

namespace CvoTechnologies\GitHubContributions\View\Cell;

use Cake\View\Cell;

class ContributionsCell extends Cell
{
    public $helpers = ['CvoTechnologies/GitHubContributions.Event'];

    protected $_validCellOptions = ['user', 'limit'];

    /**
     * Display the latest contributions of a GitHub user or organisation.
     *
     * @return void
     */
    public function display()
    {
        $this->modelFactory('Endpoint', ['Muffin\Webservice\Model\EndpointRegistry', 'get']);
        $this->loadModel('CvoTechnologies/GitHub.Events', 'Endpoint');

        $events = $this->Events
            ->find()
            ->where([
                'user' => $this->user
            ])
            ->limit($this->limit);
//            ->cache('test-' . $this->user . '-' . $this->limit);

        $this->set('events', $events);
    }
}
