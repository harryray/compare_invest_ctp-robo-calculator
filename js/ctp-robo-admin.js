(function ($) {
    'use strict';
    $(document).ready(function (e) {
        window.id = 0;

        $('.repeater').repeater(
            {
                defaultValues: {
                    'id': window.id,

                },
                show: function () {
                    $(this).slideDown();
                    console.log($(this).find('input')[1]);

                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        window.id--;

                        $(this).slideUp(deleteElement);
                        console.log($('.repeater').repeaterVal());
                    }
                },
                ready: function (setIndexes) {


                }
            }
        );
        if ($('#delete-confirm').length) {
            $('#delete-confirm').dialog({
                title: 'Remove charge',
                dialogClass: 'wp-dialog',
                autoOpen: false,
                draggable: false,
                width: 'auto',
                modal: true,
                resizable: false,
                closeOnEscape: true,
                position: {
                    my: "center",
                    at: "center",
                    of: window
                },
                buttons: {
                    "Delete charge": function () {
                        delete_charge();
                        $(this).dialog("close");
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                },
                open: function () {
                    // close dialog by clicking the overlay behind it
                    $('.ui-widget-overlay').bind('click', function () {
                        $('#delete-confirm').dialog('close');
                    })
                },

                create: function () {
                    // style fix for WordPress admin
                    $('.ui-dialog-titlebar-close').addClass('ui-button');
                },
            });
        }
        // bind a button or a link to open the dialog
        $('.ctp-robo-status-remove').click(function (e) {
            e.preventDefault();
            $('#delete-confirm').dialog('open').data($(this));
        });
        $(" .ctp-robo-status-save").on('click', function (e) {
            e.preventDefault();
            let version = $(this).data('version');
            let status = $("select[name='version[" + version + "][status]'").val();
            let robo_id = $(this).data('robo');
            let is_delete = $("input[name='version[" + version + "][remove]'").is(':checked');
            let $this = $(this);
            let url = get_ctp_robo_vars.ajaxurl;
            let nonce = get_ctp_robo_vars.robo_nonce;

            $.ajax({
                beforeSend: function () {
                    $this.addClass('disabled');
                    $this.attr('disabled', 'disabled');
                },
                complete: function () {
                    console.log('saved');
                    $this.removeAttr('disabled');
                    $this.removeClass('disabled');

                },
                type: "POST",
                url: url,
                data: {
                    action: "ctp_save_robo_status",
                    robo_id: robo_id,
                    status: status,
                    version: version,
                    is_delete: is_delete,
                    get_ctp_robo_nonce: nonce
                },
                dataType: 'json',
                success: function (result) {
                    if (result.msg === 'ok') {
                        $("#robo-msg").addClass('alert-success');
                        if (is_delete) {
                            $("tr.tr-" + version).remove();
                        }
                        if (status == 1) {
                            $('input[name="version"]').val(version);
                        }

                        $("#robo-msg").html("<b>Updated successfully</b>")
                    } else {
                        $("#robo-msg").addClass('alert-danger');
                        $("#robo-msg").html("<b>Some error occurred. Please try again </b>")
                    }

                }
            });


        });


        $("#save-robo-charges").on('click', function (e) {
            validate_charges();

            let url = get_ctp_robo_vars.ajaxurl;
            let nonce = get_ctp_robo_vars.robo_nonce;
            e.preventDefault();
            var $this = $(this);

            $.ajax({
                beforeSend: function () {
                    $this.addClass('saving-btn');
                    $this.find('span').text("Saving...");
                    $this.removeClass('save-btn');
                },
                complete: function () {
                    console.log('saved');
                    $this.removeClass('saving-btn');
                    $this.addClass('saved-btn');
                    $this.find('span').text("Saved");
                },
                type: "POST",
                url: url,
                data: {
                    action: "ctp_save_robo_charges",
                    data: $('#robo-data').serialize(),
                    get_ctp_robo_nonce: nonce
                },
                dataType: 'json',
                success: function (result) {
                    if (result.updated === true) {

                        $('input[name="version"]').val(result.version);
                        $('#msg').html('');
                    } else {
                        $('#msg').html('<b>' + result.message + '</b>');
                    }
                    //console.log(result);
                }
            });

        });

        function delete_charge() {
            let $this = $("#delete-confirm").data();
            let version = $this.data('version');
            let status = $("select[name='version[" + version + "][status]'").val();
            let robo_id = $this.data('robo');
            let is_delete = $("input[name='version[" + version + "][remove]'").is(':checked');

            let url = get_ctp_robo_vars.ajaxurl;
            let nonce = get_ctp_robo_vars.robo_nonce;
            $.ajax({
                beforeSend: function () {
                    $this.addClass('disabled');
                    $this.attr('disabled', 'disabled');
                },
                complete: function () {
                    console.log('saved');
                    $this.removeAttr('disabled');
                    $this.removeClass('disabled');

                },
                type: "POST",
                url: url,
                data: {
                    action: "ctp_save_robo_status",
                    robo_id: robo_id,
                    status: status,
                    version: version,
                    is_delete: true,
                    get_ctp_robo_nonce: nonce
                },
                dataType: 'json',
                success: function (result) {
                    if (result.msg === 'ok') {
                        $("#robo-msg").addClass('alert-success');

                        $("tr.tr-" + version).remove();
                        $("#robo-msg").html("<b>Deleted successfully</b>")
                    } else {
                        $("#robo-msg").addClass('alert-danger');
                        $("#robo-msg").html("<b>Some error occurred. Please try again </b>")
                    }

                }
            });
        }

    });


})(jQuery);
