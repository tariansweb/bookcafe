<?php
$pageTitle = 'Events';
require_once 'includes/functions.php';

$events = getUpcomingEvents(10);
?>
<?php include 'includes/header.php'; ?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <span class="page-label">Community</span>
        <h1 class="page-title">Upcoming Events</h1>
        <p class="page-subtitle">Join us for readings, discussions, and celebrations of literature</p>
    </div>
</section>

<!-- Events Content -->
<section class="events-content">
    <div class="container">
        <div class="events-page-list">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                <article class="event-card-full">
                    <div class="event-date-block">
                        <span class="event-day"><?php echo formatDate($event['event_date'], 'd'); ?></span>
                        <span class="event-month"><?php echo formatDate($event['event_date'], 'M'); ?></span>
                        <span class="event-year"><?php echo formatDate($event['event_date'], 'Y'); ?></span>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <h2 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h2>
                            <div class="event-meta">
                                <?php if ($event['event_time']): ?>
                                <span class="meta-item">
                                    <span class="meta-icon">🕐</span>
                                    <?php echo formatTime($event['event_time']); ?>
                                </span>
                                <?php endif; ?>
                                <?php if ($event['location']): ?>
                                <span class="meta-item">
                                    <span class="meta-icon">📍</span>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </span>
                                <?php endif; ?>
                                <?php if ($event['max_attendees']): ?>
                                <span class="meta-item">
                                    <span class="meta-icon">👥</span>
                                    <?php echo $event['max_attendees']; ?> spots
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($event['description']): ?>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <?php endif; ?>
                        <div class="event-actions">
                            <button class="btn btn-primary btn-small">RSVP Now</button>
                            <button class="btn btn-outline btn-small">Add to Calendar</button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback content -->
                <article class="event-card-full">
                    <div class="event-date-block">
                        <span class="event-day">15</span>
                        <span class="event-month">Dec</span>
                        <span class="event-year">2024</span>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <h2 class="event-title">Poetry Night</h2>
                            <div class="event-meta">
                                <span class="meta-item"><span class="meta-icon">🕐</span> 7:00 PM</span>
                                <span class="meta-item"><span class="meta-icon">📍</span> Main Reading Room</span>
                            </div>
                        </div>
                        <p class="event-description">Open mic poetry reading with guest performers. Share your original work or favorite poems in a welcoming, supportive environment.</p>
                        <div class="event-actions">
                            <button class="btn btn-primary btn-small">RSVP Now</button>
                            <button class="btn btn-outline btn-small">Add to Calendar</button>
                        </div>
                    </div>
                </article>
                
                <article class="event-card-full">
                    <div class="event-date-block">
                        <span class="event-day">22</span>
                        <span class="event-month">Dec</span>
                        <span class="event-year">2024</span>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <h2 class="event-title">Book Club: Fiction Favorites</h2>
                            <div class="event-meta">
                                <span class="meta-item"><span class="meta-icon">🕐</span> 6:30 PM</span>
                                <span class="meta-item"><span class="meta-icon">📍</span> Garden Terrace</span>
                            </div>
                        </div>
                        <p class="event-description">Monthly discussion of contemporary fiction. This month we're reading "The Midnight Library" by Matt Haig. All are welcome!</p>
                        <div class="event-actions">
                            <button class="btn btn-primary btn-small">RSVP Now</button>
                            <button class="btn btn-outline btn-small">Add to Calendar</button>
                        </div>
                    </div>
                </article>
                
                <article class="event-card-full">
                    <div class="event-date-block">
                        <span class="event-day">29</span>
                        <span class="event-month">Dec</span>
                        <span class="event-year">2024</span>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <h2 class="event-title">Author Meet & Greet</h2>
                            <div class="event-meta">
                                <span class="meta-item"><span class="meta-icon">🕐</span> 2:00 PM</span>
                                <span class="meta-item"><span class="meta-icon">📍</span> Gallery Space</span>
                                <span class="meta-item"><span class="meta-icon">👥</span> 50 spots</span>
                            </div>
                        </div>
                        <p class="event-description">Meet local authors and get your books signed. Light refreshments will be served. Featured authors will be announced soon!</p>
                        <div class="event-actions">
                            <button class="btn btn-primary btn-small">RSVP Now</button>
                            <button class="btn btn-outline btn-small">Add to Calendar</button>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Host Event CTA -->
<section class="host-event">
    <div class="container">
        <div class="host-content">
            <h3>Want to Host an Event?</h3>
            <p>BookCafe is available for private events, book launches, and literary gatherings.</p>
            <a href="contact.php" class="btn btn-primary">Inquire About Hosting</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

