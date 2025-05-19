(function ($) {
        'use strict';
        $(document).ready(function (e) {
            var url = window.location.href;
            if ($("#robo-version").length && $("#robo-version").val() !== '' && url.indexOf('?version=') === -1) {
                var pageUrl = '?version=' + $("#robo-version").val();
                // history.pushState(null, null, pageUrl);
            }

            //RSPL Task#71
            $('.robo-comapare-checkbox').removeAttr('disabled' );

            /**
             *  RSPL TASK #61
             */
            $('.modal.printable').on('shown.bs.modal', function () {
                $('.modal-dialog', this).addClass('focused');
                $('body').addClass('modalprinter');

                if ($(this).hasClass('autoprint')) {
                    // window.print();
                    printDiv('custom-print-results-id');
                }
            }).on('hidden.bs.modal', function () {
                $('.modal-dialog', this).removeClass('focused');
                $('body').removeClass('modalprinter');
            });

            /** ---------------------- **/
            check_for_vals(e);
            if ($("#active-from").length) {
                //$("#active-from").val(new Date($("#active-from").val()));
                //$("#active-to").val(new Date($("#active-to").val()));
                $("#active-from").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: 'd M yy',
                    onClose: function (selectedDate) {
                        if ($.type(selectedDate) !== "Date") {

                            selectedDate = $.datepicker.formatDate('d M yy', selectedDate);
                        }
                        $("#active-to").datepicker("option", "minDate", selectedDate);
                    }
                });
                $("#active-to").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: 'd M yy',
                    onClose: function (selectedDate) {
                        if ($.type(selectedDate) !== "Date") {
                            selectedDate = $.datepicker.formatDate('d M yy', selectedDate)
                        }
                        if ($("#active-from").val() !== selectedDate) {
                            $("#active-from").datepicker("option", "maxDate", selectedDate);
                        }
                    }
                });

            }
            if ($("#robo-form select").length) {
                $("#robo-form select").selectric();
            }
            $('.robo-data input').on('focus', function () {
                jQuery(this).parent().addClass('active');
            });
            $('.robo-data input').on('focusout', function () {
                jQuery(this).parent().removeClass('active');
            });
            $("#robo-form input").on('change', function () {
                $("input[name='update']").val(1);
            });
            $("#robo-form select").on('change', function () {
                $("input[name='update']").val(1);
            });

            $("#robo-data select").on('change', robo_form_changed);
            $('#robo-data input').on('change', robo_form_changed);
            $('.cplat-submit-form-container form input').on('change', function () {
                $("input[name='update']").val(1);
            });

            function robo_form_changed() {
                $('.save-robo-charges').addClass('save-btn');
                $('.save-robo-charges').removeClass('saved-btn');
                $('.save-robo-charges span').text("Save");
            }

            //RSPL Task#48
            $(document).on('click','a.robo-results-details', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('.cplat-robo-submit-form-container div.results-table-summary-' + id).slideToggle();
            });
            //RSPL Task#48
            $(document).on('click','a.close-result-details', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('.cplat-robo-submit-form-container div.results-table-summary-' + id).slideUp();
            });


            $('input#robo-result-save').on('click', function (e) {
                e.preventDefault();
                //Current Save Results button should be disabled
                $(this).attr('disabled', 'disabled');
                let version_name = $('#robo-version-name').val().trim();
                if (version_name === undefined || version_name === "") {
                    alert("Please enter a unique version name");
                    return false;
                }
                let version = $('#robo-version').val();
                let nonce = get_ctp_robo_vars.robo_nonce;
                $.ajax({
                    type: "POST",
                    url: get_ctp_robo_vars.ajaxurl,
                    data: {
                        action: "ctp_robo_save_results_name",
                        version_name: version_name,
                        version: version,
                        get_ctp_robo_nonce: nonce
                    },
                    dataType: 'json',
                    success:
                        function (result) {
                            if (result !== null && result.msg == 'ok') {
                                // $('#msg').html('Your results are saved')
                                $('.robo-save-result-msg').html('<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"> <span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span> Your results have been saved!</div>');
                            } else{
                                $('.robo-save-result-msg').html('<div class="fusion-alert alert danger alert-dismissable alert-danger alert-shadow"> <span class="alert-icon"><i class="fa-lg  fa fa-exclamation-triangle"></i></span> Error occured while storing your results!</div>');
                            }
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        },
                    complete:
                        function () {
                            //Current Save Results button should be enable again
                            $('input#robo-result-save').removeAttr('disabled', 'disabled');
                        }
                })
                ;
            });


            //RSPL TaskID #60
            $('#robo-result-email').on('click', function (e) {
                e.preventDefault();

                let version = $('#robo-version').val();
                let nonce = get_ctp_robo_vars.robo_nonce;
                $.ajax({
                    type: "POST",
                    url: get_ctp_robo_vars.ajaxurl,
                    data: {
                        action: "ctp_robo_email_result",
                        //version_name: version_name,
                        version: version,
                        get_ctp_robo_nonce: nonce
                    },
                    dataType: 'json',
                    success:
                        function (result) {
                            if (result !== null && result.msg == 'ok') {
                                // $('#msg').html('Your results are saved')
                                $('.robo-save-result-msg').html('<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span> '+result.email_msg+'</div>');
                            } else{
                                $('.robo-save-result-msg').html('<div class="fusion-alert alert danger alert-dismissable alert-danger alert-shadow"><span class="alert-icon"><i class="fa-lg  fa fa-exclamation-triangle"></i></span> '+result.email_msg+'</div>');
                            }
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        }
                })
                ;
            });


            //RSPL Task#48
            $('button.update-results').on('click', function (e) {
                e.preventDefault();
                //Current Update Results button should be disabled
                $(this).attr('disabled', 'disabled');
                //Compare button should be disabled again when someone clicks on Update Results button
                $('.robo-compare-btn-go').addClass('disabled');
                $('.robo-compare-btn-go').attr('disabled', 'disabled');
                let version = $('#robo-version').val();
                let order_by = $('#order_resluts_by').val();
                let robo_count = $('#robo_count').val();
                let nonce = get_ctp_robo_vars.robo_nonce;
                $('.ind-robo-result-item').remove();
                $('.robo-loading-update').show();
                $.ajax({
                    type: "POST",
                    url: get_ctp_robo_vars.ajaxurl,
                    data: {
                        action: "ctp_robo_sort_results",
                        version: version,
                        order_by: order_by,
                        robo_count: robo_count,
                        get_ctp_robo_nonce: nonce
                    },
                    dataType: 'json',
                    success:
                        function (result) {
                            if (result !== null && result.length !== 0 && result.msg !== 'undefined') {
                                $('.robo-result-item').append(result.version);
                            }
                        },
                    complete:
                        function () {
                            $('.robo-loading-update').hide();
                            //Current Update Results button should be enable again
                            $(this).attr('disabled', 'disabled');
                            $('button.update-results').removeAttr('disabled', 'disabled');
                        }
                });
            });

            if ($("select[name='inv_child_val']").length) {
                $("select[name='inv_child_val']").selectric();
                $("select[name='inv_child_val']").on('change', function () {
                    if (($(this).val() == 2 || $(this).val() == 3) && $("select[name='pension']").val() == 1) {
                        // RSPL Task#41
                        // $(".jsipp").show();
                        // $('#supp_lump_sum_yes').trigger('click');
                        $('.pensions.jsipp').hide();
                    } else {
                        $(".jsipp").hide();
                        // $('#supp_lump_sum_no').trigger('click');
                        $('.pensions.jsipp').show();
                    }
                    if ($(this).val() == 1 || $(this).val() == 3) {
                        $(".junior").show();
                    } else {
                        $(".junior").hide();
                    }


                });
            }
            if ($("select[name='pension']").length) {
                $("select[name='pension']").selectric();
                $("select[name='pension']").on('change', function () {
                    if ($(this).val() == 1) {
                        $(".pensions").show();
                    } else {
                        $(".pensions").hide();
                    }

                });
            }
            $(".calc-total-lump-sum").on("input", function () {
                var sum = 0;
                $(".calc-total-lump-sum").each(function () {
                    var n = $(this).val();
                    n = parseFloat(n.replace(/\D/g, '').replace(' ', ''));
                    if (isNaN(parseFloat(n))) {
                        n = 0;
                    }
                    sum += +n;
                });
                $("#lump_sum_min_total").val("£ " + commaSeparateNumber(sum));
                $("#lump_sum_min").val("£ " + commaSeparateNumber(sum));
            });
            $(".calc-total-monthly-saving").on("input", function () {
                let sum_monthly_saving = 0;
                $(".calc-total-monthly-saving").each(function () {
                    var n = $(this).val();
                    n = parseFloat(n.replace(/\D/g, '').replace(' ', ''));
                    if (isNaN(parseFloat(n))) {
                        n = 0;
                    }
                    sum_monthly_saving += +n;
                });
                $("#monthly_saving_val").val("£ " + commaSeparateNumber(sum_monthly_saving));
                $("#monthly_saving_val_total").val("£ " + commaSeparateNumber(sum_monthly_saving));
            });

            $(".calc-total-avg-saving").on("input", function () {
                let sum_min_inv = 0;
                $(".calc-total-avg-saving").each(function () {
                    var n = jQuery(this).val();
                    //RSPL TASK#40
                    var inv_type = jQuery(this).closest('.row').find('select').val();
                    n = parseFloat(n.replace(/\D/g, '').replace(' ', ''));
                    if ( inv_type >=1 ) {
                        n = n*inv_type;
                    }
                    if (isNaN(parseFloat(n))) {
                        n = 0;
                    }
                    sum_min_inv += +n;
                });

                $("#min_inv").val("£ " + commaSeparateNumber(sum_min_inv));
                $("#min_inv_total").val("£ " + commaSeparateNumber(sum_min_inv));
            });

            //RSPL TASK#40 & RSPL TASK#42
            $('.min_inv_val select').on("change",function() {
                if (jQuery(this).attr('name') == 'min_inv_freq') {
                    let n = jQuery('#min_inv_total').val();
                    n = parseFloat(n.replace(/\D/g, '').replace(' ', ''));
                    if (isNaN(parseFloat(n))) {
                        n = 0;
                    }
                    $("#min_inv").val("£ " + commaSeparateNumber(n));
                    $("#min_inv_total").val("£ " + commaSeparateNumber(n));
                } else {
                    $(".calc-total-avg-saving").trigger("input");
                }
            });

// Add commas to numbers

            $(".robo-data input[type=text].numeric-input").on('focusout', function (e) {

                // if(e.which != 8 && isNaN(String.fromCharCode(e.which))){
                //     e.preventDefault();
                // };
                var n = $(this).val().replace(/\D\./g, '');
                n = parseFloat(n);
                if (isNaN(n)) {
                    n = '';
                }
                $(this).val(n.toLocaleString());
            });
            $(".robo-data .status-save").on('click', function (e) {

                e.preventDefault();
                let version = $(this).data('version');
                let status = $("input[name='version[" + version + "][status]").val();
                let robo_id = $(this).data('robo');
                let $this = $(this);
                let nonce = get_ctp_robo_vars.robo_nonce;
                $.ajax({
                    beforeSend: function () {
                        $this.addClass('disbled');
                    },
                    complete: function () {
                        $this.removeClass('disabled');

                    },
                    type: "POST",
                    url: url,
                    data: {
                        action: "ctp_save_robo_status",
                        robo_id: robo_id,
                        status: status,
                        version: version,
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

                    }
                });


            });

            $("#is_monthly_saving_min_inv").on('change', function () {
                if ($('#is_monthly_saving_min_inv').is(':checked')) {

                }
            });
            $("input[name='monthly_saving']").on('change', function () {
                if ($('#monthly_saving_yes').is(':checked')) {
                    $(".monthly_saving_val").show();
                    $(".min_inv_val").find('input').val('0');
                    $(".min_inv_val").hide();
                    $('.q3 .robo-individual-field-hidden-cls15').removeClass('robo-individual-field-hidden-cls15');
                }
                if ($('#monthly_saving_no').is(':checked')) {
                    $(".monthly_saving_val").find('input').val('0');
                    $(".monthly_saving_val").hide();
                    $(".min_inv_val").show();
                    $('.q3 .robo-individual-field-hidden-cls15').removeClass('robo-individual-field-hidden-cls15');
                }
                wrappers_display();
            });

            $("input[name='robo_investment_products']").on('change', function () {
                    if ($('#robo_investment_products_yes').is(':checked')) {
                        $('.lump_sum_val .robo-total').find('input').val('0');
                        $('.min_inv_val .robo-total').find('input').val('0');
                        $('.q3 .robo-individual-field-hidden-cls7').removeClass('robo-individual-field-hidden-cls7');
                        show_wrappers();
                    }
                    if ($('#robo_investment_products_no').is(':checked')) {
                        $('.lump_sum_val .robo-wrapper').find('input').val('0');
                        $('.min_inv_val .robo-wrapper').find('input').val('0');
                        $('.monthly_saving_val').find('input').val('0');
                        $('.q3 .robo-individual-field-hidden-cls7').removeClass('robo-individual-field-hidden-cls7');
                        hide_wrappers();
                    }
                }
            );
            $("input[name='inv_child']").on('change', function () {
                if ($('#inv_child_yes').is(':checked')) {
                    // RSPL Task#41
                    // $(".inv_child_type").show();
                    // $(".jsipp").show();
                    // $('#supp_lump_sum_yes').trigger('click');
                    $(".junior").show();
                    $('.pensions.jsipp').show();
                    $('input#inv_child_val').val('3');
                    $('.q3 .robo-individual-field-hidden-cls6').removeClass('robo-individual-field-hidden-cls6');
                }
                if ($('#inv_child_no').is(':checked')) {
                    $(".inv_child_type").hide();
                    $(".junior").hide();
                    $(".jsipp").hide();
                    // RSPL Task#41
                    // $('#supp_lump_sum_no').trigger('click');
                    $('.pensions.jsipp').hide();
                    $('input#inv_child_val').val('0');
                    $('.q3 .robo-individual-field-hidden-cls6').removeClass('robo-individual-field-hidden-cls6');
                }
            });
            $("input[name='inv_advisor']").on('change', function () {
                    if ($("#inv_advisor_yes").is(':checked')) {
                        show_hidden_q3();
                        $('.row.monthly_saving').hide();
                        $('.q3 .robo-individual-field-hidden-cls1').removeClass('robo-individual-field-hidden-cls1');
                    }
                    if ($("#inv_advisor_no").is(':checked')) {
                        {
                            hide_hidden_q3();
                        }
                    }
                    $('.min_inv_val').hide();
                }
            );
            $("input[name='investment_objective']").on('change', function () {
                if ($("#investment_objective_yes").is(':checked')) {
                    $(".robo_with_pension").show();
                    $('.q3 .robo-individual-field-hidden-cls3').removeClass('robo-individual-field-hidden-cls3');
                    $('.q3 .robo-individual-field-hidden-cls4').removeClass('robo-individual-field-hidden-cls4');
                }
                if ($("#investment_objective_no").is(':checked')) {
                    $("#pension_yes").prop("checked", false);//RSPL Task #68 
                    $(".robo_with_pension").hide();
                    $('.q3 .robo-individual-field-hidden-cls3').removeClass('robo-individual-field-hidden-cls3');
                    $('.q3 .robo-individual-field-hidden-cls4').removeClass('robo-individual-field-hidden-cls4');

                }
            });
            //RSPL Task#43
            $('.calculate-now').on('click',function () {
                $(".robo-calc-hidden-cls").removeClass('robo-calc-hidden-cls');
            });
            $("input[name='inv_manual']").on('change', function () {
                if ($("#inv_manual_yes").is(':checked')) {
                    $(".calculator").show();
                    // $(".q2").show();
                    $("div.q2").addClass('robo-single-field-hidden-cls');
                    $("div.q3").addClass('robo-single-field-hidden-cls');
                    if (!$("#inv_advisor_yes").is(':checked')) {
                        hide_hidden_q3();
                    }
                    if ($("#calculator_no").is(':checked')) {
                        $('.redirect_to_platform_calculator').show();
                    }
                    if ($("#calculator_yes").is(':checked')) {
                        $("div.q2").removeClass('robo-single-field-hidden-cls');
                        $(".q2").show();
                    }
                    if ($("#inv_advisor_yes").is(':checked')) {
                        show_hidden_q3();
                    }
                }
                if ($("#inv_manual_no").is(':checked')) {
                    $(".calculator").hide();
                    $(".q2").hide();
                    show_hidden_q3();
                    $('.redirect_to_platform_calculator').hide();
                }
                $('.final-row-continue').hide();
                $('.q3 .robo-individual-field-hidden-cls1').removeClass('robo-individual-field-hidden-cls1');
                $('.min_inv_val').hide();
            });
            $("input[name='calculator']").on('change', function () {
                if ($("#calculator_yes").is(':checked')) {
                    show_hidden_q1();
                    //RSPL Task#43
                    $('.redirect_to_platform_calculator').hide();
                    $('.q2').show();
                    $("div.q2").removeClass('robo-single-field-hidden-cls');
                    if ($("#inv_advisor_yes").is(':checked')) {
                        $("div.q3").removeClass('robo-single-field-hidden-cls');
                        $('.q3').show();
                    }

                }
                if ($("#calculator_no").is(':checked')) {
                    //RSPL Task#43
                    // if (!$("#inv_manual_yes").hasClass('calculator-open')) {
                    //     window.open('platform-calculator', '_blank');
                    //     $("#inv_manual_yes").addClass('calculator-open');
                    // }
                    $('.redirect_to_platform_calculator').show();
                    $('.q2').hide();
                    $("div.q2").addClass('robo-single-field-hidden-cls');
                    $('.q3').hide();
                    $("div.q3").addClass('robo-single-field-hidden-cls');
                    hide_hidden_q1();
                }
            });
            $("input[name='advisory']").on('change', function () {
                if ($("#advisory_yes").is(':checked')) {
                    $(".with_me_help").show();
                    $('.q3 .robo-individual-field-hidden-cls2').removeClass('robo-individual-field-hidden-cls2');
                }
                //RSPL Task#86
                if ($("#advisory_no").is(':checked') || $("#advisory_either").is(':checked')) {
                    $(".with_me_help").hide();
                    $('.q3 .robo-individual-field-hidden-cls2').removeClass('robo-individual-field-hidden-cls2');
                }
            });

            $("input[name='supp_lump_sum']").on('change', function () {
                if ($("#supp_lump_sum_yes").is(':checked')) {
                    $(".lump_sum_val").show();
                    // $(".min_inv_val").show();

                    // $(".monthly_saving_val").show();
                    $(".monthly_saving").show();
                    //RSPL Task#43
                    // $('#monthly_saving_val_total').val('0');
                    // $('.q3 .robo-individual-field-hidden-cls15').removeClass('robo-individual-field-hidden-cls15');
                } else {
                    //RSPL Task#43
                    // $(".monthly_saving").show();
                    $(".monthly_saving").hide();
                    $(".monthly_saving_val").show();
                    $('#monthly_saving_yes').trigger('click');
                    $(".lump_sum_val").find('input').val('0');
                    $(".lump_sum_val").hide();
                    $(".min_inv_val").hide();
                    // $('.q3 .robo-individual-field-hidden-cls15').removeClass('robo-individual-field-hidden-cls15');
                }
                wrappers_display();
            });

            $("input[name='mobile_app']").on('change', function () {
                $('.q3 .robo-individual-field-hidden-cls16').removeClass('robo-individual-field-hidden-cls16');
            });

            $("input[name='ethical_investment']").on('change', function () {
                $('.q3 .robo-individual-field-hidden-cls17').removeClass('robo-individual-field-hidden-cls17');
            });

            $("input[name='telephone_advice']").on('change', function () {
                $('.final-row-continue').show();
                $('.final-row-continue').removeClass('final-row-continue');
            });


            $("#save-robo-charges").on('click', function (e) {
                let is_valid = validate_charges();
                if (!is_valid) {
                    return false
                        ;
                }
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
                        if (result.updated == 1) {
                            $('input[name="version"]').val(result.version);
                            $('#msg').html('Your charges are saved');
                        } else {
                            $('#msg').html('<b>' + result.message + '</b>');
                        }

                    }
                });

            });


        });
        $("#robo-form").on('submit', function () {
            let valid = true;
            let lump_sum_check = false;
            let monthly_saving_check = false;
            let min_inv_check = false;
            if ($("#robo_investment_products_yes").is(":checked")) {
                if ($("#supp_lump_sum_yes").is(":checked")) {
                    lump_sum_check = valid_currency($("input[name='lump_sum_min']").val());
                    min_inv_check = valid_currency($("input[name='min_inv']").val());
                }
                if ($("#monthly_saving_yes").is(":checked")) {
                    monthly_saving_check = valid_currency($("input[name='monthly_saving_val_total']").val());
                }
            } else {
                if ($("#supp_lump_sum_yes").is(":checked")) {
                    // lump_sum_check = valid_currency($("input[name='lump_sum_min']").val());
                    lump_sum_check = valid_currency($("input[name='lump_sum_min_total']").val());
                    // min_inv_check = valid_currency($("input[name='min_inv']").val());
                    min_inv_check = valid_currency($("input[name='min_inv_total']").val());
                }
                if ($("#monthly_saving_yes").is(":checked")) {
                    monthly_saving_check = valid_currency($("input[name='monthly_saving_val_total']").val());
                }
            }

            if (!lump_sum_check && !monthly_saving_check && !min_inv_check) {
                valid = false;
            }
            if (!valid) {
                $(".money-error").show().delay(5000).fadeOut();
                alert(get_ctp_robo_vars.form_error);
            }
            return valid;
        });

        function validate_charges() {
            if ($('#active-from').val() == '' || $('#active-from').val() == undefined) {
                alert("From date must not be left Blank");
                return false;
            }
            if ($('#active-to').val() == '' || $('#active-to').val() == undefined) {
                alert("To date must not be left Blank");
                return false;
            }
            if ($('#is_monthly_saving_min_inv').is(':checked') && ($("#monthly_saving_min_inv").val() === undefined || $("#monthly_saving_min_inv").val() === '')) {
                alert("Please enter Minimum Monthly Savings");
                return false;
            }
            if ($('#is_lump_sum_min').is(':checked') && ($("#lump_sum_min").val() === undefined || $("#lump_sum_min").val() === '')) {
                alert("Please enter minimum Lump Sum value");
                return false;
            }

            return true;
        }

        function check_for_vals() {
            $("div.hidden-opaque input").attr('disabled');
            if ($("input[name='monthly_saving']").length) {
                if ($('#monthly_saving_yes').is(':checked')) {
                    $(".monthly_saving_val").show();


                } else if ($('#monthly_saving_no').is(':checked')) {
                    $(".monthly_saving_val").hide();

                }
                wrappers_display();
            }
            if ($("input[name='inv_child']").length) {
                if ($('#inv_child_yes').is(':checked')) {
                    // RSPL Task#41
                    // $(".inv_child_type").show();
                    $(".junior").show();
                    $('.pensions.jsipp').show();
                    $('input#inv_child_val').val('3');
                    $('.q3 .robo-individual-field-hidden-cls6').removeClass('robo-individual-field-hidden-cls6');
                }
                if ($('#inv_child_no').is(':checked')) {
                    $(".inv_child_type").hid
                    $(".junior").hide();
                    $('.pensions.jsipp').hide();
                    $('input#inv_child_val').val('0');
                    $('.q3 .robo-individual-field-hidden-cls6').removeClass('robo-individual-field-hidden-cls6');
                }
            }
            if ($("input[name='inv_advisor']").length) {
                if ($("#inv_advisor_yes").is(':checked')) {
                    show_hidden_q3();
                }
                if ($("#inv_advisor_no").is(':checked')) {
                    hide_hidden_q3();

                }
            }

            if ($("input[name='supp_lump_sum']").length) {
                if ($("#supp_lump_sum_yes").is(':checked')) {
                    $(".lump_sum_val").show();
                    // $(".min_inv_val").show();

                    // $(".monthly_saving_val").hide();
                    // $(".monthly_saving").hide();
                    $(".monthly_saving_val").show();
                    $(".monthly_saving").show();
                    $("input[name='supp_lump_sum']").trigger('change');
                    wrappers_display();
                } else {
                    // $(".monthly_saving").show();
                    $(".monthly_saving").hide();
                    $(".monthly_saving_val").show();

                    $(".lump_sum_val").hide();
                    // $(".min_inv_val").hide();
                    $("input[name='supp_lump_sum']").trigger('change');
                }

            }
            if ($("input[name='robo_with_obj']").length) {
                if ($("#robo_with_obj_yes").is(':checked')) {
                    $(".lump_sum_val").show();
                }
                if ($("#robo_with_obj_no").is(':checked')) {
                    {
                        $(".lump_sum_val").hide();
                    }
                }
            }
            if ($("input[name='inv_manual']").length) {
                if ($("#inv_manual_yes").is(':checked')) {
                    $(".calculator").show();
                    $(".q2").show();
                    if (!$("#inv_advisor_yes").is(':checked')) {
                        hide_hidden_q3();
                    }
                    if ($("#calculator_no").is(':checked')) {
                        $('.redirect_to_platform_calculator').show();
                    }
                }
                if ($("#inv_manual_no").is(':checked')) {
                    $(".calculator").hide();
                    $(".q2").hide();
                    show_hidden_q3();
                    $('.redirect_to_platform_calculator').hide();
                }
                if ($("#monthly_saving_yes").is(':checked')) {
                    $('.monthly_saving_val').show();
                    $('.monthly_saving').show();
                    if ($('#supp_lump_sum_no').is(':checked')) {
                        $('.monthly_saving').hide();
                    }
                } else {
                    // $('.monthly_saving').hide();
                    $('.monthly_saving_val').hide();
                    // console.log($("#robo_investment_products_yes").is(':checked'));
                    // console.log($("#robo_investment_products_no").is(':checked'));
                    if ($("#robo_investment_products_yes").is(':checked')) {
                        $('.min_inv_val').show();
                        $('.min_inv_val .robo-total').hide();
                    } else {
                        $('.min_inv_val .robo-wrapper').hide();
                        $('.min_inv_val').show();
                    }
                    // $("input[name='robo_investment_products']").trigger('change');
                }
                $('.final-row-continue').hide();
            }
            if ($("input[name='calculator']").length) {

                if ($("#calculator_yes").is(':checked')) {
                    show_hidden_q1();
                    //RSPL Task#43
                    $('.redirect_to_platform_calculator').hide();
                    $("div.q2").removeClass('robo-single-field-hidden-cls');
                }
                if ($("#calculator_no").is(':checked')) {
                    //RSPL Task#43
                    $('.redirect_to_platform_calculator').show();
                    $("div.q2").addClass('robo-single-field-hidden-cls');
                    $("div.q3").addClass('robo-single-field-hidden-cls');

                    // if (!$("#inv_manual_yes").hasClass('calculator-open')) {
                    //     window.open('platform-calculator', '_blank');
                    //     $("#inv_manual_yes").addClass('calculator-open');
                    // }
                    hide_hidden_q1();

                }

            }

            if ($("input[name='investment_objective']").length) {
                if ($("#investment_objective_yes").is(':checked')) {
                    $(".robo_with_pension").show();
                    $('.q3 .robo-individual-field-hidden-cls3').removeClass('robo-individual-field-hidden-cls3');
                    $('.q3 .robo-individual-field-hidden-cls4').removeClass('robo-individual-field-hidden-cls4');
                }
                if ($("#investment_objective_no").is(':checked')) {
                    $("#pension_yes").prop("checked", false);//RSPL Task #68 
                    $(".robo_with_pension").hide();
                    $('.q3 .robo-individual-field-hidden-cls3').removeClass('robo-individual-field-hidden-cls3');
                    $('.q3 .robo-individual-field-hidden-cls4').removeClass('robo-individual-field-hidden-cls4');
                }
            }
        }

        //RSPL Task#71
        $(document).on('click','.robo-comapare-checkbox',function(e){
            var i_checked_robo = $('.robo-comapare-checkbox:checked').length;
            if( i_checked_robo == 0 || i_checked_robo == 1 ) {
                $('.robo-compare-btn-go').addClass('disabled');
                $('.robo-comapare-checkbox').removeAttr('disabled' );
            } else if( i_checked_robo == 2 ) {
                $('.robo-compare-btn-go').removeClass('disabled');
                $('.robo-comapare-checkbox').removeAttr('disabled' );
            } else if ( i_checked_robo == 3 ) {
                $('.robo-compare-btn-go').removeClass('disabled');
                $('.robo-comapare-checkbox:not(:checked)').attr('disabled', 'disabled');
            } else {
                $(this).attr('disabled', 'disabled');
                $('.robo-comapare-checkbox:not(:checked)').attr('disabled', 'disabled');
                var checkbox = $(this);
                if (checkbox.is(":checked")) {
                    // do the confirmation thing here
                    e.preventDefault();
                    return false;
                }
            }
        });

        //RSPL Task#71
        $('.robo-compare-btn-go').click(function() {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin-animate"></i>');
            let s_selected_robos = jQuery(".robo-comapare-checkbox:checkbox:checked").map(function() {return this.value;}).get().join(',');
            let post_data_arr = [];
            $( ".robo-comapare-checkbox:checkbox:checked" ).each(function( index ) {
                let robo_comapare_custody_charge = $(this).closest('.robo-comapare-main-col').find('.robo-comapare-custody-charge').val();
                let robo_comapare_product_charge = $(this).closest('.robo-comapare-main-col').find('.robo-comapare-product-charge').val();
                let robo_comapare_product_charge_total = $(this).closest('.robo-comapare-main-col').find('.robo-comapare-product-charge-total').val()
                let robo_i_id = $(this).val();
                post_data_arr.push({
                    id: robo_i_id,
                    robo_comapare_custody_charge: robo_comapare_custody_charge,
                    robo_comapare_product_charge: robo_comapare_product_charge,
                    robo_comapare_product_charge_total: robo_comapare_product_charge_total
                });
            });
            let s_redirection_url = $(this).attr('data-val');
            let s_robo_version = $(this).attr('data-version');
            let nonce = get_ctp_robo_vars.robo_nonce;
            $.ajax({
                type: "POST",
                url: get_ctp_robo_vars.ajaxurl,
                data: {
                    action: "ctp_robo_comparisons_data",
                    version: s_robo_version,
                    s_selected_robos: s_selected_robos,
                    post_data_arr: post_data_arr,
                    get_ctp_robo_nonce: nonce
                },
                dataType: 'json',
                success:
                    function (result) {
                    },
                complete:
                    function (result) {
                        window.location.href = s_redirection_url+'?version='+s_robo_version;
                    }
            });
        });

        function hide_hidden_q1() {
            $("div.q2").addClass('hidden-opaque');
            $("div.q2 input").attr('disabled', 'disabled');
        }

        function show_hidden_q1() {
            $("div.q2").removeClass('hidden-opaque');
            $("div.q2 input").removeAttr('disabled');

            $(".advisor_calc").hide();

        }

        function hide_hidden_q3() {
            $("div.q3").addClass('hidden-opaque');
            $("div.q3").addClass('robo-single-field-hidden-cls');
            $("div.q3 input").attr('disabled', 'disabled');
            $(".advisor_calc").show();
            $('.q3').hide();
        }

        function show_hidden_q3() {
            //$("div.q2").removeClass('hidden-opaque');
            //$("div.q2 input").removeAttr('disabled');
            $("div.q3").removeClass('hidden-opaque');
            $("div.q3 input").removeAttr('disabled');
            $("div.q3").removeClass('robo-single-field-hidden-cls');
            $(".advisor_calc").hide();
            $('.q3').show();
        }

        function hide_wrappers() {
            if ($(".lump_sum_val").is(':visible')) {
                $(".lump_sum_val .robo-total").show();
                $(".lump_sum_val  .robo-wrapper").hide();
            }
            if ($(".min_inv_val").is(':visible')) {
                $(".min_inv_val .robo-total").show();
                $(".min_inv_val  .robo-wrapper").hide();
            }
            if ($(".monthly_saving_val").is(':visible')) {
                $(".monthly_saving_val .robo-total").show();
                $(".monthly_saving_val  .robo-wrapper").hide();
            }
        }

        function show_wrappers() {
            if ($(".lump_sum_val").is(':visible')) {
                $(".lump_sum_val .robo-total").hide();
                $(".lump_sum_val .robo-wrapper").show();
            }
            if ($(".min_inv_val").is(':visible')) {
                $(".min_inv_val .robo-total").hide();
                $(".min_inv_val .robo-wrapper").show();
            }
            if ($(".monthly_saving_val").is(':visible')) {
                $(".monthly_saving_val .robo-total").hide();
                $(".monthly_saving_val .robo-wrapper").show();
            }
        }

        function wrappers_display() {
            if ($('#robo_investment_products_yes').is(':checked')) {
                show_wrappers();
            } else if ($('#robo_investment_products_no').is(':checked')) {
                hide_wrappers();
            } else {
                show_wrappers();
            }

        }

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }

        function valid_currency(val) {
            let new_val = val.replace(/[^0-9.]/g, "");
            return new_val !== undefined && new_val !== "" && new_val > 0
        }

        /*RSPL Task#61*/
        $(document).on('click','.results-print-btn', function (e) {
            printDiv('custom-print-results-id');
        });
        /*RSPL Task#61*/
        function printDiv(divName) {
            var content = document.getElementById(divName).innerHTML;
            var mywindow = window.open('', 'Print', 'height=' + screen.height + ',width=' + screen.width);

            mywindow.document.write('<html><head><title>Robo Calculator - Results</title>');
            //RSPL Task#85 - Do not remove this code/style as it is added to adjust the content in print and print-preview
            mywindow.document.write('<style>html, body { page-break-after: avoid; page-break-before: avoid; }table.invoice-items { page-break-inside:auto } .invoice-items tr { page-break-inside:avoid; page-break-after:auto } .invoice-items thead { display:table-header-group } .invoice-items tfoot { display:table-footer-group }</style></head><body>');
            mywindow.document.write(content);
            mywindow.document.write('</body></html>');

            mywindow.document.close();
            mywindow.focus();
            mywindow.print();
            mywindow.close();
            return true;
        }
    }


)(jQuery);
