@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Update PAGIBIG Loan Status</h2>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('loan_pagibig.update_status', $loan->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="complete" {{ $loan->status == 'complete' ? 'selected' : '' }}>Complete</option>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                                <a href="{{ route('loan_pagibig.index') }}" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Confirm before submitting the form
            $('form').on('submit', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to update the loan status?')) {
                    this.submit();
                }
            });
        });
    </script>
@stop
