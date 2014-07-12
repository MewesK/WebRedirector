$(function() {
    "use strict";

    // Add click handlers for 'delete' modal calls
    $('.delete').on('click', function (e) {
        e.preventDefault();

        // Get modal href
        var href = e.currentTarget.href;

        // Call modal using the id
        $.get(href, function(data) {
            $('body').append($(data).modal({ backdrop: 'static' }));
        });
    });

    // Add click handlers for 'test' modal calls
    $('.test').on('click', function (e1) {
        e1.preventDefault();

        // Get modal href
        var href = e1.currentTarget.href;

        // Call modal using the id
        $.get(
            href,
            function(modalData) {
                // Append modal and add submit listener
                $('body').append($(modalData).modal({ backdrop: 'static' }).on(
                    'click',
                    'button[type="submit"]',
                    // On submit
                    function(e2) {
                        e2.preventDefault();

                        // submit data and replace modal body with result
                        $.post(
                            href,
                            $('form[name="mewesk_webredirectorbundle_test"]').serialize(),
                            function(submitData) {
                                $('#test').find('div.modal-body').replaceWith($($.parseHTML(submitData)).find('div.modal-body'));
                            }
                        );
                    }
                ));
            }
        );
    });

    // Add click handlers for 'test-unsaved' modal calls
    $('.test-unsaved').on('click', function (e1) {
        e1.preventDefault();

        // Get modal href
        var href = e1.currentTarget.href;

        // Call modal using the redirect form data
        $.post(
            href,
            $('form[name="mewesk_webredirectorbundle_redirect"]').serialize(),
            function(modalData) {
                // Append modal and add submit listener
                $('body').append($(modalData).modal({ backdrop: 'static' }).on(
                    'click',
                    'button[type="submit"]',
                    // On submit
                    function(e2) {
                        e2.preventDefault();

                        // Submit data and replace modal body with result
                        $.post(
                            href,
                            $('form[name="mewesk_webredirectorbundle_test"]').serialize(),
                            function(submitData) {
                                $('#test').find('div.modal-body').replaceWith($($.parseHTML(submitData)).find('div.modal-body'));
                            }
                        );
                    }
                ));
            }
        );
    });

    // Make redirect tables sortable
    $('.sortable').sortable({
        placeholder: "active",
        stop: function(event, ui) {
            // Check if the twig defined variable "positionUrl" exists
            if (typeof positionUrl === 'undefined' || positionUrl === null || positionUrl === '') {
                return;
            }

            // Disable sorting
            $('.sortable').sortable("disable");

            // Save sorting
            $.post(
                positionUrl.replace('%25ID%25', ui.item.data('id')),
                { position: ui.item.index() },
                function() {
                    // Enable sorting
                    $('.sortable').sortable("enable");
                }
            );
        }
    }).disableSelection();
});