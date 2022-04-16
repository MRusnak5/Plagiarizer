@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Users</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Users Table</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="userDatatable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email Address</th>
                            <th>Last login</th>
                            <th>Last IP</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Username</th>
                            <th>Email Address</th>
                            <th>Last login</th>
                            <th>Last IP</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{\Carbon\Carbon::parse($user->lastlogin)->format('d-m-Y H:i:s')}}

                                </td>
                                <td>{{$user->lastip}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}

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
            $('#userDatatable').DataTable();
        } );
    </script>
@endsection

