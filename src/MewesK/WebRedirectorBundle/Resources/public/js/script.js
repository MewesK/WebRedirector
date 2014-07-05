$('.confirm').on('click', function (e) {
    e.preventDefault();
    var href = e.currentTarget.href;
    $('#confirm').modal({ backdrop: 'static' }).one('click', '.delete', function (e) {
        location.href = href;
    });
});