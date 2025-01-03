<div class="container-xxl py-5" id="curricular-activities">
    <div class="container py-5 px-lg-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="text-primary-gradient fw-medium">Curricular Activities</h5>
            <h1 class="mb-5">Upcoming Events and Activities</h1>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
            <?php
            // Fetch curricular activities from the database
            $activities = getCurricularActivities($conn, $userId, $date);
            foreach ($activities as $activity):
            ?>
                <div class="testimonial-item rounded p-4">
                    <div class="d-flex align-items-center mb-4">
                        <img class="img-fluid bg-white rounded flex-shrink-0 p-1" src="img/event-placeholder.jpg" style="width: 85px; height: 85px;">
                        <div class="ms-4">
                            <h5 class="mb-1"><?= htmlspecialchars($activity['title']) ?></h5>
                            <p class="mb-1"><?= htmlspecialchars($activity['description']) ?></p>
                            <div>
                                <small class="fa fa-calendar text-primary"></small>
                                <small class="ms-1"><?= htmlspecialchars($activity['event_date']) ?></small>
                            </div>
                        </div>
                    </div>
                    <a href="<?= htmlspecialchars($activity['event_link']) ?>" target="_blank" class="btn btn-primary-gradient">View Event</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
