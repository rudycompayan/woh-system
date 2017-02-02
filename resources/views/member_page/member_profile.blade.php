<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Member Page</title>
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

        .span {
            background: #fff none repeat scroll 0 0;
            border: 2px solid #000;
            border-radius: 5px;
            display: inline-block;
            padding: 3px 10px;
        }

        .container{
            margin-left: 30px!important;
            width: 100%;
        }

        .alert-danger {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }
        .alert {
            border: 1px solid transparent;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 15px;
        }

    </style>

    <script type="text/javascript" src="member_page/prettify.js"></script>

    <!-- jQuery includes -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="member_page/jquery.jOrgChart.js"></script>

    <script>
        jQuery(document).ready(function() {
            $("#org").jOrgChart({
                chartElement : '#chart',
                dragAndDrop  : true
            });
        });

        $(window).load(function () {
            $('.topbar-inner').css('width',$('#tree').outerWidth());
            $('.nav_chart').css('text-align','left');
        });

        $( function() {
            $('#register').click(function(e){
                e.preventDefault();
                $('#create-user').click();
            });

            var dialog, dialog2, form, form2 ,choose_dialog;

            dialog = $( "#dialog-form" ).dialog({
                autoOpen: false,
                height: 670,
                width: 800,
                modal: true,
                buttons: {
                    "Save": function(){
                        $('#ajax-loader').fadeIn();
                        $.ajax({

                            type: "POST",

                            url: form.prop('action'),

                            data: form.serialize(),

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
                        dialog.dialog( "close" );
                    }
                },
                close: function() {
                    $('#form1_error').html('');
                    $('#form1_error').fadeOut();
                    form[ 0 ].reset();
                    choose_dialog.dialog('close');
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

            form = dialog.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            $( "#create-user" ).button().on( "click", function(e) {
                e.preventDefault();
                dialog.dialog( "open" );
            });

            form = dialog.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
            });

            //---------- form2 ------------------------------------------//
            $('.empty_node').mouseover(function(e){
                $(this).css('cursor','pointer');
            });

            $('.empty_node').click(function(e){
                e.preventDefault();
                if($(this).data('woh_member')) {
                    $('#upline_selected').val($(this).data('woh_member'));
                    $('#tree_position').val($(this).data('position'));
                    $('#downline_of option').remove();
                    $('#downline_of')
                            .append($("<option></option>")
                                    .attr("value",$(this).data('woh_member'))
                                    .text($(this).data('username')));
                }
                else
                    $('#upline_selected').val({!! $member[0]->woh_member !!});
                dialog.dialog( "close" );
                $('#register').hide();
                choose_dialog.dialog( "open" );
            });

            $('#add-account').click(function(e){
                e.preventDefault();
                $('#first_name').val('{!! $member[0]->first_name !!}');
                $('#last_name').val('{!! $member[0]->last_name !!}');
                $('#middle_name').val('{!! $member[0]->middle_name !!}');
                $('#address').val('{!! $member[0]->address !!}');
                $('#gender').val('{!! $member[0]->gender !!}');
                $('#bday').val('{!! $member[0]->bday !!}');
                $('#first_name').hide();
                $('#last_name').hide();
                $('#middle_name').hide();
                $('#address').hide();
                $('#gender').hide();
                $('#bday').hide();
                $('#lfname').hide();
                $('#llname').hide();
                $('#lmname').hide();
                $('#laddress').hide();
                $('#lgender').hide();
                $('#lbday').hide();
                dialog.dialog( "open" );
            });

            $('#new-account').click(function(e){
                e.preventDefault();
                dialog.dialog( "open" );
            });

            choose_dialog = $( "#choose_dialog" ).dialog({
                autoOpen: false,
                height: 100,
                width: 100,
                show: {
                    effect: "blind",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                },
                close: function() {
                    form[ 0 ].reset();
                    location.reload();
                },
            });

            $( "#my-account" ).on( "click", function(e) {
                e.preventDefault();
                dialog2.dialog( "open" );
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
                <li><a href="">Unilevel Commision</a></li>
                <li><a href="" id="register">Add Downline</a></li>
                <li><a href="{{action('MemberController@member_logout')}}" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
            </ul>
            <div class="pull-left">
                <div class="alert-message info" id="show-list">Show underlying list.</div>
                <pre class="prettyprint lang-html" id="list-html" style="display:none"></pre>
            </div>
        </div>
    </div>
</div>

<!-- Chart starts here -->
<ul id="org" style="display:none;">
    <li>
        <a class='with_node'><span class="span">{!! $member[0]->woh_member !!}-{!! $member[0]->username !!}</span><img src="member_page/images/{!! $member[0]->picture !!}" alt="Raspberry"/></a>
        <ul>
            @if($downlines)
                {!! $downlines !!}
            @else
                <li><a class='empty_node' data-woh_member='{!! $member[0]->woh_member !!}' data-position="left" data-level="0-l" data-username='{!! $member[0]->username !!}'><img src="member_page/images/offline.png" alt="Raspberry"/></a></li>
                <li><a class='empty_node' data-woh_member='{!! $member[0]->woh_member !!}' data-position="right" data-level="0-r" data-username='{!! $member[0]->username !!}'><img src="member_page/images/offline.png" alt="Raspberry"/></a></li>
            @endif
        </ul>
    </li>
</ul>
<!-- Chart ends here -->

<div id="chart" class="orgChart" style="text-align: center;"></div>

<script>
    jQuery(document).ready(function() {

        /* Custom jQuery for the example */
        $("#show-list").click(function(e){
            e.preventDefault();

            $('#list-html').toggle('fast', function(){
                if($(this).is(':visible')){
                    $('#show-list').text('Hide underlying list.');
                    $(".topbar").fadeTo('fast',0.9);
                }else{
                    $('#show-list').text('Show underlying list.');
                    $(".topbar").fadeTo('fast',1);
                }
            });
        });

        $('#list-html').text($('#org').html());

        $("#org").bind("DOMSubtreeModified", function() {
            $('#list-html').text('');

            $('#list-html').text($('#org').html());

            prettyPrint();
        });

        $('#tree_position').change(function(e){
            $('#downline_of option').remove();
            $this = $(this);
            var options = [];
            $('.empty_node').each(function(){
                if($(this).data('position') === $this.val() && (options.indexOf($(this).data('woh_member')+'-'+$(this).data('username')) == -1))
                    options.push($(this).data('woh_member')+'-'+$(this).data('username'));
            });
            options.forEach(function(entry) {
                data = entry.split('-');
                $('#downline_of')
                        .append($("<option></option>")
                                .attr("value",data[0])
                                .text(data[1]));
            });
        });

        $('#re-password').blur(function()
        {
            if($(this).val() != $('#password').val())
            {
                $('#form1_error').html('<p>Password does not match.</p>');
                $('#form1_error').fadeIn();
            }
        });

        $('#password').blur(function(){
            if($(this).val() && $(this).val().length < 6)
            {
                $('#form1_error').html('<p>Password must be from 6 to 8 characters.</p>');
                $('#form1_error').fadeIn();
            }
            else if($('#re-password').val() && $(this).val() != $('#re-password').val())
            {
                $('#form1_error').html('<p>Password does not match.</p>');
                $('#form1_error').fadeIn();
            }
            else
            {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
            }
        });

        $('#username').blur(function(){
            if($(this).val() && $(this).val().length < 6)
            {
                $('#form1_error').html('<p>Username must be from 6 to 8 characters.</p>');
                $('#form1_error').fadeIn();
            }
            else
            {
                $('#form1_error').html('');
                $('#form1_error').fadeOut();
            }
        });

        $('#re-password').focus(function(){
            $('#form1_error').html('');
            $('#form1_error').fadeOut();
        });
    });
</script>
<div id="dialog-form" title="Member Registration">
    {!! Form::open(['data-remote','url' => action('MemberController@post_member_add'), 'id' => 'login_form']) !!}
        <fieldset>
            <div id="form1_error" class="alert alert-danger" role="alert" style="display: none"></div>
            <label for="first_name" id="lfname">First Name</label>
            <input type="text" name="first_name" id="first_name" placeholder="Jane" class="text ui-widget-content ui-corner-all">
            <label for="middle_name" id="lmname">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name" placeholder="Suarez" class="text ui-widget-content ui-corner-all">
            <label for="last_name" id="llname">Last Name</label>
            <input type="text" name="last_name" id="last_name" placeholder="Cruz" class="text ui-widget-content ui-corner-all">
            <label for="address" id="laddress">Address</label>
            <input type="text" name="address" id="address" placeholder="Mc Briones St. Tipolo, Mandaue City Cebu" class="text ui-widget-content ui-corner-all">
            <label for="gender" id="lgender">Gender</label>
            <select name="gender" id="gender" class="text ui-widget-content ui-corner-all"  style="width: 97%">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <label for="bday" id="lbday">Birthday</label>
            <input type="text" name="bday" id="bday" placeholder="1990-01-25" class="text ui-widget-content ui-corner-all">
            <label for="position">Position</label>
            <select name="tree_position" id="tree_position" class="text ui-widget-content ui-corner-all" style="width: 97%">
                <option value="">Select</option>
                <option value="left">Left</option>
                <option value="right">Right</option>
            </select>
            <label for="downline_of">Downline</label>
            <select name="downline_of" id="downline_of" class="text ui-widget-content ui-corner-all" style="width: 97%">
            </select>
            <label for="sponsor">Sponsor</label>
            <input type="text" id="sponsor" value="{!! $member[0]->woh_member !!} - {!! $member[0]->username !!}" placeholder="Rudy Compayan" readonly class="text ui-widget-content ui-corner-all">
            <input type="hidden" name="sponsor" id="sponsor" placeholder="Rudy Compayan" value="{!! $member[0]->woh_member !!}" readonly class="text ui-widget-content ui-corner-all">
            <input type="hidden" name="picture" id="picture" placeholder="picture" value="online.png" class="text ui-widget-content ui-corner-all">
            <!--<label for="email">Email</label>
                <input type="text" name="email" id="email" value="jane@smith.com" class="text ui-widget-content ui-corner-all">-->
            <label for="username">Username</label>
            <input type="username" maxlength="8" name="username" id="username" placeholder="username123" class="text ui-widget-content ui-corner-all">
            <label for="password">Password</label>
            <input type="password" maxlength="8" name="password" id="password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
            <label for="re-password">Re-type Password</label>
            <input type="password" maxlength="8" name="re-password" id="re-password" placeholder="xxxxxxx" class="text ui-widget-content ui-corner-all">
            <hr>
            <label for="entry_code">Entry Code</label>
            <input type="text" name="entry_code" id="entry_code" placeholder="xxxxxxxxxxxxxx" class="text ui-widget-content ui-corner-all">
            <label for="pin_code">Pin Code</label>
            <input type="text" name="pin_code" id="pin_code" placeholder="xxxxxxxxxxxxxx" class="text ui-widget-content ui-corner-all">
            <input type="hidden" id="upline_selected" value="{!! $member[0]->woh_member !!}">
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    {!! Form::close() !!}
    <button id="create-user" style="display: none">Create new user</button>
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
<div id="choose_dialog" title="Select Downline" style="text-align: center">
    <a id="add-account"><img src="member_page/images/plus.png" width="50"></a>
    <a id="new-account"><img src="member_page/images/downline.png" width="50"></a>
</div>
</body>
</html>