<div>
    <h4>Messages</h4>
    <div style="overflow-x:auto;">
        <form>
            <table class="w3-table w3-table-all w3-hoverable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Betreff</th>
                    <th>Nachricht</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $this->messs as $mess ): ?>
                    <tr>
                        <td><?php echo $mess['id'] ?></td>
                        <td><?php echo $mess['name'] ?></td>
                        <td><?php echo $mess['email'] ?></td>
                        <td><?php echo $mess['subject'] ?></td>
                        <td><?php echo $mess['message'] ?></td>
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
