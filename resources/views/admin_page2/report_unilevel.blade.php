@extends('admin_dashboard')
@section('content')
    <!-- /subnavbar -->
    <div class="main clearfix">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <form action="{{action('AdminController@post_filter_unilevel')}}" method="post">
                                <div class="widget-header"> <i class="icon-th-list"></i>
                                    <h3>Member Unilevel Report</h3>
                                    <input type="text" style="margin-top: 6px;" name="name" placeholder="Search here..." value="{{ $name }}">
                                    <input type="submit" value="Search" style="margin-top: 6px;" name="submit">
                                </div>
                            </form>
                            <!-- /widget-header -->
                            <div class="widget-content"   style="overflow: scroll; max-height: 500px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Member Details</th>
                                        <th>Level 1</th>
                                        <th>Level 2</th>
                                        <th>Level 3</th>
                                        <th>Level 4</th>
                                        <th>Level 5</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($all_report))
                                        @foreach($all_report as $key => $member_report)
                                                <?php
                                                $earn = 0;
                                                $withdrawals = 0;
                                                $gc = 0;
                                                $unilevels_total = 0;
                                                ?>
                                                <tr>
                                                    <td>{!! $key !!}</td>
                                                @for($x=0; $x<5; $x++)
                                                    <?php
                                                    if(isset($member_report[0][$x]) && $member_report[0][$x]['woh_transaction_type'] == 8)
                                                        $unilevels_total += $member_report[0][$x]['tran_amount'];
                                                    ?>
                                                    @if(isset($member_report[0][$x]))
                                                        <td>&#8369; {!! number_format($member_report[0][$x]['tran_amount'],2) !!}</td>
                                                    @else
                                                        <td>&#8369; 0.00</td>
                                                    @endif
                                                @endfor
                                                    <td>&#8369; {!! number_format($unilevels_total,2) !!}</td>
                                                </tr>
                                        @endforeach
                                    @endif
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
        });
    </script>
@endsection