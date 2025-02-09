<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Page/Transactions</title>
    <link rel="stylesheet" href="member_page/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="member_page/css/jquery.jOrgChart.css"/>
    <link rel="stylesheet" href="member_page/css/custom.css"/>
    <link href="member_page/css/prettify.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        label, input { display:block; text-align: left}
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
        .ui-widget-overlay
        {
            opacity: .70 !important; /* Make sure to change both of these, as IE only sees the second one */
            filter: Alpha(Opacity=70) !important;

            background-color: rgb(70, 70, 70) !important; /* This will make it darker */
        }
        .table-grid{
            width:90%; background-color: #ffffff; color: #000000; text-align: left;
            border:1px solid #ffffff; margin-left: 3%; margin-top: 50px; font-size: 12px;
        }
        .rows{
            border-bottom:1px solid lightgray; padding: 7px 5px;
        }
        .th-rows{
            border-bottom:1px solid lightgray; padding: 7px 5px; font-size: 14px;
        }

        .container{
            margin-left: 30px!important;
        }

        .withdraw-btn{
            float: left;
            margin-bottom: 10px;
            margin-left: 47px;
            margin-top: 39px;
            width: 100px;
        }
        .alert-danger {
            background-color: #d9edf7;
            border-color: #d9edf7;
            color: #000000;
            width: 93%;
            font-size: 20px;
        }
        .alert {
            border: 1px solid transparent;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 15px;
        }

        .totals {
            background: #2b542c none repeat scroll 0 0!important;
            border-radius: 5px!important;
            color: #fff!important;
            padding: 3px 7px!important;
        }

        .totals-w {
            background: #761c19 none repeat scroll 0 0!important;
            border-radius: 5px!important;
            color: #fff!important;
            padding: 3px 7px!important;
        }

        .totals-t {
            background: #761c19 none repeat scroll 0 0!important;
            border-radius: 5px!important;
            color: #fff!important;
            padding: 3px 7px!important;
        }

        .totals-b {
            background: #2a6496 none repeat scroll 0 0!important;
            border-radius: 5px!important;
            color: #fff!important;
            padding: 3px 7px!important;
        }

        .withdraw-gc-btn {
            float: left;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-top: 39px;
            width: 100px;
        }

    </style>

    <script type="text/javascript" src="member_page/prettify.js"></script>

    <!-- jQuery includes -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="member_page/jquery.jOrgChart.js"></script>
    <script>
        var dialog2, form2, dialog3, form3, dialog4, form4, form5;
        jQuery(document).ready(function() {
            $( "#my-account" ).on( "click", function(e) {
                e.preventDefault();
                dialog2.dialog( "open" );
            });

            $( "#withdraw-btn" ).on( "click", function(e) {
                e.preventDefault();
                $('#savings_amount').text($('#savings').text());
                @if($member[0]->status == 0)
                var result = confirm("You are a CD account member. Your transaction will be deducted 50% of the amount withdrawn. Continue?");
                if (result) {
                    dialog3.dialog("open");
                }
                @else
                    dialog3.dialog("open");
                @endif
            });

            $( "#unilevel-link" ).on( "click", function(e) {
                e.preventDefault();
                @if(!empty($unilevel_period))
                    dialog4.dialog("open");
                @else
                    alert('You have already maintained your account.');
                @endif
            });

            $( "#withdraw-gc-btn" ).on( "click", function(e) {
                e.preventDefault();
                $('#gc_ohqty').text($('#gc_qty').text());
                dialog5.dialog("open");
            });

            dialog2 = $( "#dialog-form2" ).dialog({
                autoOpen: false,
                height: 508,
                width: 800,
                modal: true,
                buttons: {
                    "Update": function(){
                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form2.prop('action'),

                            data: form2.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    $( location ).prop( 'pathname', '/member_profile');
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("Member not found!");
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog2.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form2[ 0 ].reset();
                },
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                }
            });

            form2 = dialog2.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            dialog3 = $( "#dialog-form3" ).dialog({
                autoOpen: false,
                height: 480,
                width: 800,
                modal: true,
                buttons: {
                    "Send Request": function(){

                        if(!$('#amount').val())
                        {
                            alert('Please enter amount.');
                            $('#amount').focus();
                            return false;
                        }

                        var a=$('#savings_amount').text();
                        a=a.replace(/\,/g,'');
                        a=a.replace(/\₱/g,'');
                        a=parseFloat(a);
                        if($('#amount').val() > a)
                        {
                            alert('Amount must be less than or equal to your savings.');
                            $('#amount').focus();
                            return false;
                        }

                        if(!$('#notes').val())
                        {
                            alert('Please enter notes.');
                            $('#notes').focus();
                            return false;
                        }

                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form3.prop('action'),

                            data: form3.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    $( location ).prop( 'pathname', '/member_withdrawals');
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("Member not found!");
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog3.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form3[ 0 ].reset();
                },
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                }
            });

            form3 = dialog3.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            $('input[name="amount"]').keyup(function(e)
            {
                var txtQty = $(this).val().replace(/[^0-9\.]/g,'');
                $(this).val(txtQty);
            });

            dialog4 = $( "#dialog-form4" ).dialog({
                autoOpen: false,
                height: 250,
                width: 800,
                modal: true,
                buttons: {
                    "Submit": function(){

                        if(!$('#product_code').val())
                        {
                            alert('Please enter product code.');
                            $('#product_code').focus();
                            return false;
                        }

                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form4.prop('action'),

                            data: form4.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    alert("Product code successfully save.");
                                    dialog4.dialog( "close" );
                                    location.href="{{ action('MemberController@member_withdrawals') }}";
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("Product code not found!");
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form1_error').html(err_msg);
                                    $('#form1_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog4.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form4[ 0 ].reset();
                },
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                }
            });

            form4 = dialog4.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            dialog5 = $( "#dialog-form5" ).dialog({
                autoOpen: false,
                height: 480,
                width: 800,
                modal: true,
                buttons: {
                    "Send Claim Request": function(){

                        if(!$('#qty').val())
                        {
                            alert('Please enter no. of GC to claim.');
                            $('#qty').focus();
                            return false;
                        }

                        var a=$('#gc_ohqty').text();
                        a=a.replace(/\,/g,'');
                        a=a.replace(/\₱/g,'');
                        a=parseFloat(a);
                        if($('#qty').val() > a)
                        {
                            alert('Quantity must be less than or equal to your On-Hand quantity.');
                            $('#qty').focus();
                            return false;
                        }

                        if(!$('#gc_notes').val())
                        {
                            alert('Please enter notes.');
                            $('#gc_notes').focus();
                            return false;
                        }

                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form5.prop('action'),

                            data: form5.serialize(),

                            success: function(data, NULL, jqXHR) {
                                $('#ajax-loader').fadeOut();
                                if(jqXHR.status === 200 ) {//redirect if  authenticated user.
                                    $( location ).prop( 'pathname', '/member_withdrawals');
                                }
                            },
                            error: function(data) {
                                $('#ajax-loader').fadeOut();
                                if( data.status === 401 ) {//redirect if not authenticated user
                                    alert("Member not found!");
                                }
                                if( data.status === 422 ) {
                                    //process validation errors here.
                                    var err_msg = '';
                                    var res = JSON.parse(data.responseText);
                                    for (var key in res) {
                                        if (res.hasOwnProperty(key)) {
                                            var obj = res[key];
                                            for (var prop in obj) {
                                                if (obj.hasOwnProperty(prop)) {
                                                    err_msg += '<p>'+obj[prop] + "</p>";
                                                }
                                            }
                                        }
                                    }
                                    $('#first_name').focus();
                                    $('#form5_error').html(err_msg);
                                    $('#form5_error').fadeIn();
                                }
                            }
                        });
                    },
                    Cancel: function() {
                        dialog5.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form5[ 0 ].reset();
                },
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                }
            });

            form5 = dialog5.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            $('input[name="qty"]').keyup(function(e)
            {
                var txtQty = $(this).val().replace(/[^0-9\.]/g,'');
                $(this).val(txtQty);
            });

        });
    </script>
</head>
<body onload="prettyPrint();">
<div class="topbar">
    <div class="topbar-inner">
        <div class="container">
            <a class="brand" href="{{action('MemberController@member_profile')}}">WOH - Geneology</a>
            <ul class="nav_chart">
                <li><a href="{{action('HomepageController@index')}}">Home</a></li>
                <li><a href="" id="my-account">My Account</a></li>
                <li><a href="{{action('MemberController@member_withdrawals')}}">Withdrawals</a></li>
                <li>
                    <a href="{{action('MemberController@member_transactions')}}">Transactions & Ernings</a>
                </li>
                <li><a href="" id="unilevel-link">Unilevel Commision</a></li>
                <li><a href="{{action('MemberController@member_logout')}}" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container" style="text-align: center; width:100%;">
    <a href="" id="withdraw-btn"><img src="member_page/images/withdraw.png" class="withdraw-btn"></a>
    <a href="" id="withdraw-gc-btn"><img src="member_page/images/withdraw-gc.png" class="withdraw-gc-btn"></a>
    <table class="table-grid" cellspacing="0" style="font: 12px">
        <thead>
        <tr>
            <th class='th-rows' style="width: 5%">Req. #</th>
            <th class='th-rows' style="width: 11%">Transaction No.</th>
            <th class='th-rows' style="width: 10%">Check No.</th>
            <th class='th-rows' style="width: 10%">Transaction Date</th>
            <th class='th-rows' style="width: 10%">Issuance Date</th>
            <th class='th-rows' style="width: 5%">Status</th>
            <th class='th-rows' style="width: 10%">Admin Notes</th>
            <th class='th-rows' style="width: 10%" align="right">(&#8369;) Principal Amt.</th>
            <th class='th-rows' style="width: 5%" align="right">(&#8369;) Tax </th>
            <th class='th-rows' style="width: 8%" align="right">(&#8369;) CD Payment </th>
            <th class='th-rows' style="width: 6%" align="right">(&#8369;) Change </th>
            <th class='th-rows' style="width: 10%" align="right">(&#8369;) Check Amt. </th>
        </tr>
        </thead>
        <tbody style="overflow: auto">
        <tr>
            <td colspan="11" style="padding: 0px;">
                <div style="width: 111%; max-height: 500px; overflow: auto;">
                    <table class="table-grid" cellspacing="0" style="width: 100%; margin: 0px">
                        <tbody>
                        @if(isset($member_tran))
                            <?php
                            $earn = 0;
                            $withdrawals = 0;
                            $tax = 0;
                            $cd_deduction = 0;
                            $check_amt = 0;
                            $change = 0;
                            $gc = 0;
                            krsort($member_tran);
                            $unilevels_total = 0;
                            ?>
                            @foreach($member_tran as $key => $mt)
                                <?php
                                if($mt['woh_transaction_type'] == 1 && $mt['status'] != 3)
                                    $withdrawals += $mt['tran_amount'];
                                elseif($mt['woh_transaction_type'] != 1 && $mt['woh_transaction_type'] != 4)
                                    $earn += $mt['tran_amount'];
                                if($mt['woh_transaction_type'] == 4)
                                    $gc += $mt['tran_amount'];
                                if($mt['woh_transaction_type'] == 1 && $mt['status'] != 3)
                                {
                                    $tax += !empty($mt['tax']) ? $mt['tax'] : 0;
                                    $cd_deduction += !empty($mt['cd_payment']) ? $mt['cd_payment'] : 0;
                                }
                                if($mt['woh_transaction_type'] == 1 && isset($mt['cd_payment']) && $mt['cd_payment'] > 0 && $mt['status'] != 3)
                                    $check_amt += ($mt['tran_amount']-$mt['tax'])/2;
                                elseif($mt['woh_transaction_type'] == 1 && !isset($mt['cd_payment']) && $mt['status'] != 3)
                                    $check_amt += $mt['tran_amount']-$mt['tax'];
                                if($mt['woh_transaction_type'] == 1)
                                    $change += $mt['change'];
                                if($mt['woh_transaction_type'] == 8)
                                    $unilevels_total += $mt['tran_amount'];
                                ?>
                                @if($mt['woh_transaction_type'] == 1)
                                <tr style="background-color: @if($mt['woh_member_transaction']%2==0) #efefef @else #ffffff @endif;">
                                    <td class='rows' style="width: 5%; @if($mt['status']==3) text-decoration: line-through; @endif">
                                        {!! $mt['woh_member_transaction'] !!}
                                    </td>
                                    <td class='rows' style="width: 11%">
                                        {!! $mt['transaction_no'] !!}
                                    </td>
                                    <td class='rows' style="width: 10%">{!! $mt['check_number'] !!}</td>
                                    <td class='rows' style="width: 10%; @if($mt['status']==3) text-decoration: line-through; @endif">{!! \Carbon\Carbon::parse($mt['transaction_date'])->format('m/d/Y H:i A') !!}</td>
                                    <td class='rows' style="width: 10%">{!! $mt['issuance_date'] ? \Carbon\Carbon::parse($mt['issuance_date'])->format('m/d/Y H:i A') : '--------------------' !!}</td>
                                    <td class='rows' style="width: 5%">
                                        @if($mt['status'] == 1)
                                            Complete
                                        @elseif($mt['status'] == 2)
                                            Pending
                                        @else
                                            Cancelled
                                        @endif
                                    </td>
                                    <td class='rows' style="width: 10%">{!! $mt['admin_notes'] !!}</td>
                                    <td class='rows' style="width: 10%; @if($mt['status']==3) text-decoration: line-through; @endif" align="right">&#8369; {!! number_format($mt['tran_amount'],2) !!}</td>
                                    <td class='rows' style="width: 5%; @if($mt['status']==3) text-decoration: line-through; @endif" align="right">&#8369; {!! number_format(!empty($mt['tax']) ? $mt['tax'] : 0,2) !!}</td>
                                    <td class='rows' style="width: 8%; @if($mt['status']==3) text-decoration: line-through; @endif" align="right">&#8369; {!! number_format(!empty($mt['cd_payment']) ? $mt['cd_payment'] : 0,2) !!}</td>
                                    <td class='rows' style="width: 6%; @if($mt['status']==3) text-decoration: line-through; @endif" align="right">&#8369; {!! number_format(!empty($mt['change']) ? $mt['change'] : 0,2) !!}</td>
                                    @if(isset($mt['cd_payment']) && $mt['cd_payment'] > 0)
                                        <td class='rows' style="width: 10%; @if($mt['status']==3) text-decoration: line-through; @endif;" align="right">&#8369; {!! number_format((($mt['tran_amount']-$mt['tax'])/2)+$mt['change'],2) !!}</td>
                                    @else
                                        <td class='rows' style="width: 10%; @if($mt['status']==3) text-decoration: line-through; @endif;" align="right">&#8369; {!! number_format(($mt['tran_amount']-$mt['tax']),2) !!}</td>
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="color: #2b542c; background-color: #efefef">
            <td class='rows' colspan="7"  style="color: #2b542c; border-top: 1px solid lightgray">Totals ==></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format($withdrawals,2) }}</b></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format($tax,2) }}</b></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format($cd_deduction,2) }}</b></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format($change,2) }}</b></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><b>&#8369; {{ number_format(($check_amt + $change),2) }}</b></td>
        </tr>
        <tr style="color: #2b542c;">
            <td class='rows' colspan="10"  style="color: #2b542c; border-top: 1px solid lightgray">Current Balance ==></td>
            <td class='rows'  style="color: #2b542c; border-top: 1px solid lightgray"></td>
            <td class='rows' align="right" style="color: #2b542c; border-top: 1px solid lightgray"><span class="totals"><b>&#8369; {{ number_format($earn,2) }}</b></span></td>
        </tr>
        <tr style="color: #2b542c;background-color: #efefef">
            <td class='rows' colspan="10"  style="color: #2b542c;">Total Gc's ==></td>
            <td class='rows' colspan="2" align="right" style="color: #2b542c;"><span class="totals"><b>{{ number_format($gc/300) }} X &#8369; 300 = &#8369; {{ number_format($gc,2) }}</span></td>
        </tr>
        <tr style="color: #2b542c;background-color: #FFFFFF">
            <td class='rows' colspan="10"  style="color: #2b542c;">Total GC Claimed ==></td>
            <td class='rows' colspan="2" align="right" style="color: #2b542c;"><span class="totals-w"><b>{{ number_format($member_gc_claim) }} X &#8369; 300 = &#8369; {{ number_format(($member_gc_claim*300),2) }}</span></td>
        </tr>
        <tr style="color: #2b542c;background-color: #efefef">
            <td class='rows' colspan="10"  style="color: #2b542c;">Total GC On Hand ==></td>
            <td class='rows' colspan="2" align="right" style="color: #2b542c;"><span class="totals-b"><b><span id="gc_qty">{{ number_format(($gc/300)-$member_gc_claim) }} </span> X &#8369; 300 = &#8369; {{ number_format($gc - ($member_gc_claim*300),2) }}</span></td>
        </tr>
        <tr style="color: #2b542c;">
            <td class='rows' colspan="10"  style="color: #2b542c;">Total Earnings ==></td>
            <td class='rows'  style="color: #2b542c;"></td>
            <td class='rows' align="right" style="color: #2b542c;"><span class="totals"><b>&#8369; {{ number_format(($earn+($gc-($member_gc_claim*300))),2) }}</b></span></td>
        </tr>
        <tr style="color: #2b542c;">
            <td class='rows' colspan="10"  style="color: #2b542c;">Total Unilevel Commision ==></td>
            <td class='rows'  style="color: #2b542c;"></td>
            <td class='rows' align="right" style="color: #2b542c;"><span class="totals"><b>&#8369; {{ number_format(($unilevels_total),2) }}</b></span></td>
        </tr>
        <tr style="color: #761c19; background-color: #efefef">
            <td class='rows' colspan="10">Total Withdrawals ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><span class="totals-w"><b>&#8369; {{ number_format(($withdrawals-$tax),2) }}</b></span></td>
        </tr>
        <tr style="color: #761c19;">
            <td class='rows' colspan="10">Total Tax ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><span class="totals-t"><b>&#8369; {{ number_format($tax,2) }}</b></span></td>
        </tr>
        @if(!empty($member_credit))
        <tr style="color: #761c19;">
            <td class='rows' colspan="10">CD Account Credit ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><span class="totals-t"><b>&#8369; {{ number_format($member_credit[0]['credit_amount'],2) }}</b></span></td>
        </tr>
        @endif
        <tr style="color: #2a6496;  background-color: #efefef">
            <td class='rows' colspan="10">Withdrawable Balance ==></td>
            <td class='rows'></td>
            <td class='rows' align="right"><span class="totals-b"><b id="savings">&#8369; {{ number_format(($earn-$withdrawals),2) }}</b></span></td>
        </tr>
        </tbody>
    </table>
</div>
<div id="dialog-form2" title="Account Details">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_update'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
        <label for="first_name" id="lfname">First Name</label>
        <input type="text" name="first_name" id="first_name" placeholder="Jane" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->first_name !!}">
        <label for="middle_name" id="lmname">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name" placeholder="Suarez" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->middle_name !!}">
        <label for="last_name" id="llname">Last Name</label>
        <input type="text" name="last_name" id="last_name" placeholder="Cruz" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->last_name !!}">
        <label for="address" id="laddress">Address</label>
        <input type="text" name="address" id="address" placeholder="Mc Briones St. Tipolo, Mandaue City Cebu" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->address !!}">
        <label for="gender" id="lgender">Gender</label>
        <select name="gender" id="gender" class="text ui-widget-content ui-corner-all"  style="width: 97%">
            <option value="male" <?= $member[0]->gender == "male" ? "selected" : "" ?>>Male</option>
            <option value="female" <?= $member[0]->gender == "female" ? "selected" : "" ?>>Female</option>
        </select>
        <label for="bday" id="lbday">Birthday</label>
        <input type="text" name="bday" id="bday" placeholder="1990-01-25" class="text ui-widget-content ui-corner-all" value="{!! $member[0]->bday !!}">
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
<div id="dialog-form3" title="Withdrawal Request">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_withdrawal'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form1_error" class="alert alert-danger" role="alert">Savings: <span id="savings_amount">0.00</span> </div>
        <label for="amount">Enter Amount</label>
        <input type="text" name="amount" id="amount" placeholder="0.00" class="text ui-widget-content ui-corner-all">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" placeholder="Some text here." class="text ui-widget-content ui-corner-all" style="width:95%; height: 150px;"></textarea>
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
<div id="dialog-form4" title="Unilevel Commision">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_unilevel'), 'id' => 'unilevel_form']) !!}
    <fieldset>
        <label for="amount" style="font-size:14px;">Period Cover</label>
        <select name="period_cover" class="select ui-widget-content ui-corner-all" style="width:95%">
            @if(!empty($unilevel_period))
                @foreach($unilevel_period as $up)
                    <option value="{!! $up['woh_member_unilevel_earning'] !!}">{!! \Carbon\Carbon::parse($up['period_cover_start'])->format('F d, Y') !!} - {!! \Carbon\Carbon::parse($up['period_cover_end'])->format('F d, Y') !!}</option>
                @endforeach
            @endif
        </select> <br>
        <label for="amount" style="font-size:14px;">Enter Product Code</label>
        <input type="text" name="product_code" id="product_code" placeholder="XXXXXXXXXX" class="text ui-widget-content ui-corner-all">
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
<div id="dialog-form5" title="GC Claim Request">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_gc_claim'), 'id' => 'login_form']) !!}
    <fieldset>
        <div id="form5_error" class="alert alert-danger" role="alert">On-Hand GC: <span id="gc_ohqty">0</span> </div>
        <label for="amount">GC Quantity</label>
        <input type="text" name="qty" id="qty" placeholder="0" value="1" readonly class="text ui-widget-content ui-corner-all">
        <label for="notes">Notes</label>
        <textarea name="notes" id="gc_notes" placeholder="Some text here." class="text ui-widget-content ui-corner-all" style="width:95%; height: 150px;"></textarea>
        <input type="hidden" name="woh_member" value="{!! $member[0]->woh_member !!}">
    </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
</div>
</body>
</html>