@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Post</h3>
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

                        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title<span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title" value="{{ $post->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="content">Content<span class="text-danger">*</span></label>
                                        <textarea id="content" name="content" class="form-control" placeholder="Enter post content">{{ $post->content }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_start">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date_start" name="date_start" class="form-control" placeholder="Enter post start date" value="{{ $post->date_start }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_end">End Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date_end" name="date_end" class="form-control" placeholder="Enter post end date" value="{{ $post->date_end }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id">User</label>
                                        <select id="user_id" name="user_id" class="form-control" readonly>
                                            @if(auth()->check())
                                                <option value="{{ auth()->user()->id }}">{{ auth()->user()->first_name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Featured Image (Optional)</label>
                                        
                                        @if($post->image_path)
                                        <div class="current-image mb-3">
                                            <img src="{{ asset($post->image_path) }}" alt="Current featured image" class="img-thumbnail" style="max-height: 200px;">
                                            <p class="text-muted mt-1 mb-0">Current image</p>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" id="removeImage" name="remove_image" value="1">
                                                <label class="form-check-label" for="removeImage">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                            <label class="custom-file-label" for="image">Choose new file</label>
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
                                        <button type="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
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
        
        // Toggle current image visibility when remove checkbox is clicked
        $("#removeImage").on("change", function() {
            if($(this).is(":checked")) {
                $(".current-image img").css("opacity", "0.3");
            } else {
                $(".current-image img").css("opacity", "1");
            }
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
                
                // If there's a current image and a remove checkbox, check it
                if(document.getElementById('removeImage')) {
                    document.getElementById('removeImage').checked = true;
                    $(".current-image img").css("opacity", "0.3");
                }
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
        fileLabel.innerHTML = 'Choose new file';
        fileLabel.classList.remove('selected');
        
        // Uncheck remove image if it exists
        if(document.getElementById('removeImage')) {
            document.getElementById('removeImage').checked = false;
            $(".current-image img").css("opacity", "1");
        }
    }
</script>
@endsection
