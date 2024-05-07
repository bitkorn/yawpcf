

function decodeHTMLEntities(text) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = text;
    return textArea.value;
}

jQuery(document).ready(function () {
    let orderDirec = 'asc';
    let baseUrl = location.protocol + '//' + location.host + location.pathname;
    const searchParams = new URLSearchParams(location.search);

    jQuery('.bitkorn-yawpcf-column-order').click(function () {
        let order = jQuery(this).data('order');
        orderDirec = searchParams.get('order_direc') === 'asc' ? 'desc' : 'asc';
        searchParams.set('order', order);
        searchParams.set('order_direc', orderDirec);
        window.location.href = baseUrl + '?' + searchParams.toString();

    });

    jQuery('.bitkorn-yawpcf-message-delete').click(function () {
        if(!confirm('Are you sure you want to delete?')) {

        }
        let id = jQuery(this).data('id');

        jQuery.ajax({
            url: decodeHTMLEntities(BITKORN_YAWPCF_AJAXURL), // wp-admin/admin-ajax.php
            data: {id: id},
            success: function (data) {
                window.location.reload();
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });
});
