function requestPost(coupon) {
    fetch('./compra.php', {
        method: 'POST',
        body: JSON.stringify(coupon)
    });
}