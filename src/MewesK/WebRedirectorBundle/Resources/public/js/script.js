$(function() {
    "use strict";

    // Add click handlers for 'delete' modal calls
    $('.delete').on('click', function (e) {
        e.preventDefault();

        // Get modal href
        var href = e.currentTarget.href;

        // Call modal using the id
        $.get(href, function(data) {
            $('body').append($(data).modal({ backdrop: 'static' }).on('hidden.bs.modal', function(){
                $(this).data('modal', null).remove();
            }));
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
                    'submit',
                    'form',
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
                ).on('shown.bs.modal', function(e2){
                    // focus input field
                    $(this).find('#mewesk_webredirectorbundle_test_url').focus();
                }).on('hidden.bs.modal', function(){
                    $(this).remove();
                }));
            }
        );
    });

    // Add click handlers for 'test-unsaved' modal calls
    $('.test-unsaved').on('click', function (e1) {
        e1.preventDefault();

        // Get modal href
        var href = e1.currentTarget.href;

        // Transform redirect form data to test form data
        var formData = $('form[name="mewesk_webredirectorbundle_redirect"]').serializeArray();
        for(var i = 0; i < formData.length; i++) {
            var object = formData[i];
            object.name = object.name.replace('mewesk_webredirectorbundle_redirect', 'mewesk_webredirectorbundle_test');
            formData[i] = object;
        }
        delete formData[formData.length - 1];

        // Call modal using the test form data
        $.post(
            href,
            formData,
            function(modalData) {
                // Append modal and add submit listener
                $('body').append($(modalData).modal({ backdrop: 'static' }).on(
                    'submit',
                    'form',
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
                ).on('shown.bs.modal', function(e2){
                    // focus input field
                    $(this).find('#mewesk_webredirectorbundle_test_url').focus();
                }).on('hidden.bs.modal', function(e2){
                    // remove modal
                    $(this).remove();
                }));
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