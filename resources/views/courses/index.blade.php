@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Course</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Course Table</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Category</th>

                            <th>Course name</th>

                            <th>
                                &nbsp;Action
                            </th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Category</th>

                            <th>Course name</th>


                            <th>
                                &nbsp;Action
                            </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @forelse($courses as $course)
                            <tr data-entry-id="{{ $course->id }}">
                                <td>{{ $course->id }}</td>
                                <td>{{ $course->category }}</td>

                                <td>{{ $course->fullname }}</td>

                                <td style="text-align: center">

                                        <a class="btn btn-xs btn-primary" href="{{ route('courses.show', $course->id) }}">
                                           Show
                                        </a>


                                </td>
                            </tr>
                        @empty
                            <tr data-entry-id="{{ $course->id }}">
                               <td>NO DATA</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>



                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
@section('custom_scripts')
    @parent
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable();
    } );
</script>
@endsection
