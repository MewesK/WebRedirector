$(function() {
    "use strict";

    $('.delete').on('click', function (e) {
        e.preventDefault();
        var href = e.currentTarget.href;
        $.get(href, function(data) {
            $('body').append($(data).modal({ backdrop: 'static' }));
        });
    });

    $('.test').on('click', function (e) {
        e.preventDefault();
        var href = e.currentTarget.href;
        $.get(href, function(data) {
            $('body').append($(data).modal({ backdrop: 'static' }));
        });
    });

    $('.sortable').sortable({
        placeholder: "active",
        stop: function(event, ui) {
            // check if the twig defined variable "positionUrl" exists
            if (typeof positionUrl === 'undefined' || positionUrl === null || positionUrl === '') {
                return;
            }

            // disable sorting
            $('.sortable').sortable("disable");

            // save sorting
            $.post(
                positionUrl.replace('%25ID%25', ui.item.data('id')),
                { position: ui.item.index() },
                function() {
                    // enable sorting
                    $('.sortable').sortable("enable");
                }
            );
        }
    }).disableSelection();
});