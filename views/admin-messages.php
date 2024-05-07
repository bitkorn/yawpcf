<div>
    <h4>Messages</h4>
    <div style="overflow-x:auto;">
        <form>
            <table class="w3-table w3-table-all w3-hoverable">
                <thead>
                <tr>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="name">Name</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="email">Email</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="subject">Betreff</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="message">Nachricht</th>
                    <th class="bitkorn-yawpcf-column-order w3-pointer" data-order="date_create">Datum Zeit</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $this->messs as $mess ): ?>
                    <tr>
                        <td><?php echo $mess['name'] ?></td>
                        <td><?php echo $mess['email'] ?></td>
                        <td><?php echo $mess['subject'] ?></td>
                        <td><?php echo $mess['message'] ?></td>
                        <td><?php echo $mess['date_create'] ?></td>
                        <td>
                            <button type="button" id="bitkorn_yawpcf_message_delete"
                                    class="w3-button w3-red" data-id="<?= $mess['id'] ?>">X
                            </button>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
