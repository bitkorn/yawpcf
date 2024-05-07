<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post" class="w3-container" id="bitkorn-yawpcf-form">

    <label for="yawpcf-name">Name</label>
    <input type="text" id="yawpcf-name" name="yawpcf-name" value="<?php echo( isset( $_POST["yawpcf-name"] ) ? esc_attr( $_POST["yawpcf-name"] ) : '' ) ?>"
           placeholder="Name" required="required" class="w3-input">

    <label for="yawpcf-email">Email</label>
    <input type="email" id="yawpcf-email" name="yawpcf-email" value="<?php echo( isset( $_POST["yawpcf-email"] ) ? esc_attr( $_POST["yawpcf-email"] ) : '' ) ?>"
           placeholder="Email" required="required" class="w3-input">

    <label for="yawpcf-subject">Betreff</label>
    <input type="text" id="yawpcf-subject" name="yawpcf-subject" value="<?php echo( isset( $_POST["yawpcf-subject"] ) ? esc_attr( $_POST["yawpcf-subject"] ) : '' ) ?>"
           placeholder="Subject" required="required" class="w3-input">

    <label for="yawpcf-message">Nachricht</label>
    <textarea rows="10" id="yawpcf-message" name="yawpcf-message" required="required" class="w3-input"
              placeholder="Message (required)"><?php echo( isset( $_POST["yawpcf-message"] ) ? esc_attr( $_POST["yawpcf-message"] ) : '' ) ?></textarea>
    <p>
        <input type="submit" name="bitkorn-yawpcf-submitted" value="senden" class="w3-button w3-pale-blue">
    </p>
</form>
<?= $this->messageHtml ?>
