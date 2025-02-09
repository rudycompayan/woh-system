@extends('admin_dashboard')
@section('content')
        <!-- /subnavbar -->
<div class="main clearfix">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="widget widget-table action-table"  id="print-element">
                        <div class="widget-header"> <i class="icon-th-list"></i>
                            <h3>KLP Members List Report</h3>
                            <input type="submit" value="Print Report" style="margin-top: 6px;" name="print" id="print">
                        </div>
                        <!-- /widget-header -->
                        <div class="widget-content">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>ACCT. #</th>
                                    <th>Member Name</th>
                                    <th>Username</th>
                                    <th>Registered On</th>
                                    <th>Account Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($klp_member))
                                    @foreach($klp_member as $key => $mt)
                                        <tr>
                                            <td><a href="{{action('AdminController@klp_members_account', ['member'=>$mt['woh_member']])}}">{!! $mt['woh_member'] !!}</a></td>
                                            <td>{!! $mt['first_name'] !!} {!! $mt['last_name'] !!}</td>
                                            <td>{!! $mt['username'] !!}</td>
                                            <td>{!! \Carbon\Carbon::parse($mt['created_at'])->format('m/d/Y H:i A') !!}</td>
                                            <td>{!! isset($mt['cd_code']) ? 'Comission Deduction' : 'Payin' !!}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="5"><b>Totals Member ==> {{ number_format(count($klp_member)) }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /widget-content -->
                    </div>
                    <!-- /widget -->
                </div>
                <!-- /span6 -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /main-inner -->
</div>
<!-- /main -->
<!-- /extra -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="admin_page/js/jquery-1.7.2.min.js"></script>
<script src="admin_page/js/excanvas.min.js"></script>
<script src="admin_page/js/chart.min.js" type="text/javascript"></script>
<script src="admin_page/js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="admin_page/js/full-calendar/fullcalendar.min.js"></script>
<script src="admin_page/js/base.js"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $( "#start_date" ).datepicker();
        $( "#end_date" ).datepicker();
        $("#print").click(function(e){
            e.preventDefault();
            printContent2('print-element');
        });
    });
</script>
@endsection