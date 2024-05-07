<div>
    <h4>YAWPCF Messages</h4>
    <div style="overflow-x:auto;">
        <form>
            <table class="w3-table w3-table-all w3-hoverable">
                <thead>
                <tr>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="name" style="min-width: 100px">
                        <span class="dashicons dashicons-arrow-up-alt2"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
                        Name
                    </th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="email" style="min-width: 100px">
                        <span class="dashicons dashicons-arrow-up-alt2"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
                        Email</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="subject" style="min-width: 120px">
                        <span class="dashicons dashicons-arrow-up-alt2"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
                        Betreff</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="message" style="min-width: 120px">
                        <span class="dashicons dashicons-arrow-up-alt2"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
                        Nachricht</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="date_create" style="min-width: 120px">
                        <span class="dashicons dashicons-arrow-up-alt2"></span><span class="dashicons dashicons-arrow-down-alt2"></span>
                        Datum Zeit</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $this->messs as $mess ): ?>
                    <tr>
                        <td><?php echo $mess['name'] ?></td>
                        <td><?php echo htmlspecialchars($mess['email']) ?></td>
                        <td><?php echo htmlspecialchars($mess['subject']) ?></td>
                        <td><?php echo htmlspecialchars($mess['message']) ?></td>
                        <td><?php echo $mess['date_create'] ?></td>
                        <td>
                            <button type="button" id="bitkorn_yawpcf_message_delete"
                                    class="w3-button w3-red bitkorn-yawpcf-message-delete" data-id="<?= $mess['id'] ?>">X
                            </button>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
