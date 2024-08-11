@extends('layouts.master')

@section('head')
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Log Activity</h6>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Datetime</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center">
                                <th>No</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Datetime</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($logs as $key => $log)
                            <tr class="text-center">
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $log->User->name }}</td>
                                <td class="align-middle">{{ $log->activity }}</td>
                                <td class="align-middle">{{ date('d M Y - H:i', strtotime($log->created_at )) }}</td>
                            </tr>
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
