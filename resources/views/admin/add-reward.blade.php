@extends('admin/master')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
<link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Reward</h4>
        <p class="card-description"> Add Reward </p>
        <form class="forms-sample" method="POST" action="{{route('rewards.store')}}">
          @csrf
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="level" placeholder="Name" value="{{old('name')}}">
            @if ($errors->has('name'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('name') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description" cols="30" rows="10" maxlength="254" value="{{old('description')}}"></textarea>
            @if ($errors->has('description'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('description') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="filepond" name="filepond" id="image" data-max-file-size="3MB" data-max-files="1" />
            @if ($errors->has('filepond'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('filepond') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="points_required">Points Required</label>
            <input type="text" name="points_required" class="form-control" id="points_required" placeholder="Points Required" value="{{old('points_required')}}">
            @if ($errors->has('points_required'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('points_required') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="quantity_available">Quantity Available</label>
            <input type="text" name="quantity_available" class="form-control" id="quantity_available" placeholder="Quantity Available" value="{{old('quantity_available')}}">
            @if ($errors->has('quantity_available'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('quantity_available') }}</span>
            @endif
          </div>

          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('rewards.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>
  
  <script>
    /*
    We want to preview images, so we need to register the Image Preview plugin
    */
    FilePond.registerPlugin(
        
        // encodes the file as base64 data
        FilePondPluginFileEncode,
        
        // validates the size of the file
        FilePondPluginFileValidateSize,

        // validates the type of the file
        FilePondPluginFileValidateType, 
        
        // corrects mobile image orientation
        FilePondPluginImageExifOrientation,
        
        // previews dropped images
        FilePondPluginImagePreview
    
    );

    // Select the file input and use create() to turn it into a pond
    const inputElement = document.querySelector('input[type="file"]');
    const pond = FilePond.create(inputElement, {
        acceptedFileTypes: ['image/*'],
        imagePreviewHeight: 200,
        imagePreviewWidth: 200,
        allowImagePreview: true,
        allowMultiple: true
    });
  </script>

@endsection