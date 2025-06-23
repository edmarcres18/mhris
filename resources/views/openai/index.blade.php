@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">OpenAI Integration</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Generate Response</div>
                <div class="card-body">
                    <form id="openai-form">
                        @csrf
                        <div class="form-group">
                            <label for="prompt">Enter your prompt:</label>
                            <textarea class="form-control" id="prompt" name="prompt" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Generate Response</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Response</div>
                <div class="card-body">
                    <div id="response-container">
                        <p id="response-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#openai-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("openai.generate") }}',
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#response-text').text('Generating response...');
                },
                success: function(data) {
                    $('#response-text').text(data.response);
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $('#response-text').text('Error: ' + errorMessage);
                }
            });
        });
    });
</script>
@endpush
