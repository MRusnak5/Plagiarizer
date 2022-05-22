@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Analyze quiz</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if (empty($quiz_constants)&&(empty($merge)))
                        <h1>Data not found!</h1>
                    @endif
                    <table class="table table-bordered" id="analyzeDatatable" width="100%" cellspacing="0">
                        @if (!empty($quiz_constants)&&!empty($merge))
                            <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Quiz Name</th>
                                <th>Avg Time Spend on Question</th>
                                <th>Grade</th>
                                <th>Quiz start date</th>
                                <th>Quiz start end date</th>

                                @for ($i = 1; $i <= $quiz_constants[0]->slot; $i++)
                                    <th> Q.{{$i}} Student Mark /Max Mark</th>
                                    <th> Q.{{$i}}  submitted at </th>
                                @endfor

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Student Name</th>
                                <th>Quiz Name</th>
                                <th>Avg Time Spend on Question</th>
                                <th>Grade</th>
                                <th>Quiz start date</th>
                                <th>Quiz start end date</th>

                                @for ($i = 1; $i <= $quiz_constants[0]->slot; $i++)
                                    <th>Q.{{$i}} Student Mark /Max Mark</th>
                                    <th>Q.{{$i}}  submitted at </th>
                                @endfor

                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach($merge as $key =>$constants)
                                <tr>
                                    <td>{{ $constants['student_name']}}</td>
                                    <td>{{ $constants['quiz_name']}}</td>
                                    <td>{{ number_format($constants['Average_time_taken'],2)}} seconds</td>
                                    <td>{{ number_format($constants['fraction'],3).'/'.number_format($constants['maxmark']),2}}</td>
                                    <td>{{\Carbon\Carbon::parse($constants['quiz_started_at'])->format('d-m-Y H:i:s')}}</td>
                                    <td>{{\Carbon\Carbon::parse($constants['quiz_finished_at'])->format('d-m-Y H:i:s')}}</td>

                                    @for ($i = 0; $i < $constants['slot']; $i++)
                                        <td>{{ number_format($constants[$i.'.Fraction'],2)}}
                                            /{{ number_format($constants[$i.'.Maxmark'],2)}}</td>
                                        <td>{{\Carbon\Carbon::parse($constants[$i.'.Answered_at'])->format('d-m-Y H:i:s')}}</td>
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                    </table>


                </div>
                <div class="table-responsive">

                    <table class="table table-bordered" id="globalAwgTable" width="100%" cellspacing="0">
                        <h1>Quiz averages</h1>
                        <thead>
                        <tr>
                            <th>Numbers of students</th>
                            <th>Average Time spend on Question in whole Quiz</th>
                            <th>Average Grade</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{count($merge)}} </td>
                            <td>{{number_format($avgTimePerQuestion,4)}} seconds</td>
                            <td>{{number_format($avgMarkQuiz,4)}} </td>
                        </tr>
                        </tbody>
                    </table>


                </div>

                <div class="table-responsive">

                    <table class="table table-bordered" id="gradesTimeDatatable" width="100%" cellspacing="0">
                        <h1>Grades similarities</h1>
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($json_output_marks as $key=>$marks)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($json_output_marks as $key=>$marks)
                            <tr>
                                <td>{{$key}}</td>
                                @foreach($marks as $v)
                                    <td>{{ $v}}</td>
                                @endforeach
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    @endif

                </div>
                <div class="table-responsive">

                    <table class="table table-bordered" id="answeredTimeDatatable" width="100%" cellspacing="0">
                        <h1>Answered at time similarities</h1>
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($json_output_answered_at_similarity as $key=>$answered_at)
                                <th>{{$key}}</th>
                            @endforeach

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($json_output_answered_at_similarity as $key=>$answered_at)
                            <tr>
                                <td>{{$key}}</td>
                                @foreach($answered_at as $v)
                                    <td>{{ $v}}</td>
                                @endforeach


                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>

        <!-- /.container-fluid -->
        @endsection
        @section('custom_scripts')
            @parent
            <script>
                $(document).ready(function () {
                    // $('#answeredTimeDatatable').DataTable({
                    //     "order": [[0, "desc"]],
                    //     sDom: 'lrtip',
                    //     select: true,
                    //     scrollY: true,
                    //     scrollX: true,
                    //     columnDefs: [
                    //         {
                    //             target: 1,
                    //             className: 'cell-border'
                    //         }
                    //     ],
                    //     "sRowSelect": "single",
                    //     "processing": true,
                    //     "bPaginate": true,
                    //     "bSort": true,
                    //     "autoWidth": true,
                    //
                    //
                    //
                    //
                    //      });
                    $('#analyzeDatatable').DataTable({

                    });
                    $('#answeredTimeDatatable').DataTable();
                    $('#gradesTimeDatatable').DataTable();
                });
            </script>
@endsection

