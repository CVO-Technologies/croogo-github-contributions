<ul>
    <?php foreach ($events as $event): ?>
        <li><?= h($event->created_at->timeAgoInWords()); ?> - <?= $this->Event->describe($event); ?></li>
    <?php endforeach; ?>
</ul>
