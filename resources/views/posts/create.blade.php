@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Post</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title<span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="content">Content<span class="text-danger">*</span></label>
                                        <textarea type="text" id="content" name="content" class="form-control" placeholder="Enter post content"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_start">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date_start" name="date_start" class="form-control" placeholder="Enter post start date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_end">End Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date_end" name="date_end" class="form-control" placeholder="Enter post end date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id">User</label>
                                        <select id="user_id" name="user_id" class="form-control" readonly>
                                            @if(auth()->check())
                                                <option value="{{ auth()->user()->id }}">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Featured Image (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Recommended size: 1200 x 630 pixels. Max size: 2MB</small>
                                        
                                        <div class="image-preview mt-3 d-none">
                                            <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="resetImagePreview()">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                        <a href="{{ route('posts.index') }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Update custom file input label with filename
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
    
    // Image preview functionality
    function previewImage(input) {
        var preview = document.getElementById('imagePreview');
        var previewContainer = document.querySelector('.image-preview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function resetImagePreview() {
        var input = document.getElementById('image');
        var preview = document.getElementById('imagePreview');
        var previewContainer = document.querySelector('.image-preview');
        var fileLabel = document.querySelector('.custom-file-label');
        
        input.value = '';
        preview.src = '#';
        previewContainer.classList.add('d-none');
        fileLabel.innerHTML = 'Choose file';
        fileLabel.classList.remove('selected');
    }
</script>
@endsection
