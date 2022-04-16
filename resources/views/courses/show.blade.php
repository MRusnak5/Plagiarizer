@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{$course->fullname}}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Table</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-bordered table-striped">
                        <tbody>

                        <tr>
                            <th>
                                Id
                            </th>
                            <td>
                                {{ $course->id }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Category
                            </th>
                            <td>
                                {{ $course->category }}
                            </td>
                        </tr>




                        <tr>
                            <th>
                                Course name
                            </th>
                            <td>
                                {{ $course->fullname }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Start date
                            </th>
                            <td>
                                {{ $course->startdate }}
                            </td>
                        </tr>  <tr>
                            <th>
                                End date
                            </th>
                            <td>
                                {{ $course->enddate }}
                            </td>
                        </tr>

                        </tbody>
                    </table>



                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="quizTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Test name</th>

                    <th style="text-align: center;">
                        &nbsp;&nbsp;Action
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($course->quizes as $quiz)
                    <tr data-entry-id="{{ $quiz->id }}">
                        <td>{{ $quiz->id }}</td>
                        <td>{{ $quiz->name }}</td>

                        <td style="text-align: center">

                            <a class="btn btn-xs btn-primary" href="{{ route('course.analyze', $quiz->id) }}">
                                Analyze
                            </a>


                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>



        </div>

    <!-- /.container-fluid -->
@endsection
@section('custom_scripts')
    @parent
<script type="text/javascript">
    $(document).ready( function () {
        $('#quizTable').DataTable();

    } );
</script>
@endsection
