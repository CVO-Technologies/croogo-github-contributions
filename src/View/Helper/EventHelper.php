<?php

namespace CvoTechnologies\GitHubContributions\View\Helper;

use Cake\View\Helper;
use CvoTechnologies\GitHub\Model\Resource\Event;
use CvoTechnologies\GitHub\Model\Resource\Repository;
use CvoTechnologies\GitHub\Model\Resource\User;

class EventHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * Return a HTML formatted description of a GitHub event.
     *
     * @param Event $event The GitHub event to describe.
     * @return string
     */
    public function describe(Event $event)
    {
        if ($event instanceof Event\CreateEvent) {
            return $this->actorLink($event->actor) . ' created ' . $this->repoLink($event->repo);
        }
        if ($event instanceof Event\PushEvent) {
            $count = $event->payload['size'];

            return $this->actorLink($event->actor) . ' pushed ' . __n('one commit', '{0} commits', $count, $count) . ' to ' . $this->repoLink($event->repo);
        }
        if ($event instanceof Event\DeleteEvent) {
            return $this->actorLink($event->actor) . ' deleted ' . h($event->payload['ref_type']) . ' <strong>' . h($event->payload['ref']) . '</strong> from ' . $this->repoLink($event->repo);
        }
        if ($event instanceof Event\PullRequest\OpenedEvent) {
            return $this->actorLink($event->actor) . ' just opened a pull request on ' . $this->repoLink($event->repo) . ': ' . h($event->payload['pull_request']['title']);
        }
        if ($event instanceof Event\PullRequest\ClosedEvent) {
            return $this->actorLink($event->actor) . ' just closed a pull request on ' . $this->repoLink($event->repo) . ': ' . h($event->payload['pull_request']['title']);
        }
        if ($event instanceof Event\Issues\OpenedEvent) {
            return $this->actorLink($event->actor) . ' just opened an issue on ' . $this->repoLink($event->repo) . ': ' . h($event->payload['issue']['title']);
        }
        if ($event instanceof Event\Issues\ClosedEvent) {
            return $this->actorLink($event->actor) . ' just closed an issue on ' . $this->repoLink($event->repo) . ': ' . h($event->payload['issue']['title']);
        }
        if ($event instanceof Event\IssueComment\CreatedEvent) {
            return $this->actorLink($event->actor) . ' just commented on <strong>' . h($event->payload['issue']['title']) . ' (' . $this->repoLink($event->repo) . ')</strong>: ' . $event->payload['comment']['body'];
        }

        return h($event->describe());
    }

    /**
     * Return a HTML formatted GitHub actor.
     *
     * @param User $actor The actor to format.
     * @return mixed
     */
    public function actorLink(User $actor)
    {
        return $this->Html->link($actor->display_login, 'https://github.com/' . $actor->login);
    }

    /**
     * Return a HTML formatted GitHub repo.
     *
     * @param Repository|object $repo The repo to format.
     * @return mixed
     */
    public function repoLink(Repository $repo)
    {
        return $this->Html->link($repo->name, 'https://github.com/' . $repo->name);
    }
}
