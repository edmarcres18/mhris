<!-- resources/views/leaves/print.blade.php -->

@extends('layouts.app')

@section('content_header')
    <h1>Print Leaves</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->name }}</td>
                                    <td>{{ $leave->date_from }}</td>
                                    <td>{{ $leave->date_to }}</td>
                                    <td>{{ $leave->type->name }}</td>
                                    <td>{{ $leave->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
