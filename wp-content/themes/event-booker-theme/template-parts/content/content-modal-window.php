<?php 
                $id_start_date =  'yvg_event_start';
                $id_end_date =  'yvg_event_end';

                $title = get_the_title();
                $start = get_post_meta(get_the_ID(), 'event-booker_'. $id_start_date, true);
                $end = get_post_meta(get_the_ID(), 'event-booker_'. $id_end_date, true);
?>

<div>
  <p>
    <b>Event Title: </b> <?php echo esc_html($title); ?>
  </p>
  <p>
    <b>Start Date: </b> <?php echo esc_html($start); ?>
  </p>
  <p>
    <b>End Date: </b> <?php echo esc_html($end); ?>
  </p>
  <?php if (has_post_thumbnail()) { ?>
        <div class="event-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div>
    <?php } ?>
    <div class="container-lead-form">
        <h2>Contact Form</h2>
        <form id="lead_booker_form" method="post" enctype="multipart/form-data">
            <div class="contact-form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="contact-form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="contact-form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <?php submit_button( __( 'Book', 'eventbookertheme' ), 'primary', 'lead_form_submit' );?>
        </form>
    </div>
</div>