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
});
