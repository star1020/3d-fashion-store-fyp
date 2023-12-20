@extends('admin/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
<link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<link rel="stylesheet" href="{{asset('user/image-uploader/image-uploader.css')}}">
<script src="{{asset('user/image-uploader/image-uploader.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<style>
    
    .image-uploader .upload-text {
        cursor: pointer;
    }

    .submit-btn {
        float: unset !important;
    }

    .filepond--credits{
        display: none;
    }
        /**
        * FilePond Custom Styles
        */
    .filepond--drop-label {
        color: #4c4e53;
    }
    
    .filepond--label-action {
        text-decoration-color: #babdc0;
    }
    
    .filepond--panel-root {
        border-radius: 2em;
        background-color: #edf0f4;
        height: 1em;
    }
    
    .filepond--item-panel {
        background-color: #595e68;
    }
    
    .filepond--drip-blob {
        background-color: #7f8a9a;
    }
    
    .filepond--item {
        width: calc(50% - 0.5em);
    }
    .col-md-6 .filepond--item {
        width: 100% !important;
    }

    @media (min-width: 30em) {
        .filepond--item {
            width: calc(50% - 0.5em);
        }
        .col-md-6 .filepond--item {
        width: 100% !important;
    }
    }
    
    @media (min-width: 50em) {
        .filepond--item {
            width: calc(33.33% - 0.5em);
        }
        .col-md-6 .filepond--item {
        width: 100% !important;
    }
    }

    .filepond--root {
        max-height: 100em;
    }

    .filepond--root .filepond--drop-label {
        cursor: pointer;
    }

    .filepond--drop-label.filepond--drop-label label {
        cursor: pointer;
    }
    .image-container {
        display: flex;
        width:100%;
        justify-content: center; 
        align-items: center; 
        flex-wrap: wrap; 
    }
    .image-wrapper {
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center; 
        margin: 5px; 
        overflow: hidden; 
    }
    .product-image {
        max-width: 100%;
        max-height: 100%; 
        object-fit: contain;
    }
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Product</h4>
            <form id="productForm" action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="sizeCountData" name="sizeCountData" value="">
                <div class="form-group">
                    <label for="productName">Product Name*</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="{{ $product->productName }}" maxlength="255">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productType">Product Type*</label>
                            <select class="form-select" name="productType" id="productType">
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->value }}" {{ $product->productType->value == $type->value ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Product Categories*</label>
                            <select class="form-select" name="category" id="category">
                                @foreach($categories as $category)
                                    <option value="{{ $category->value }}" {{ $product->category->value == $category->value ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="productDesc">Description*</label>
                    <textarea class="form-control" id="productDesc" name="productDesc" maxlength="255">{{ $product->productDesc }}</textarea>
                </div>

                <div class="form-group">
                    <label for="order_id">Product Price*</label>
                    <input type="text" class="form-control" name="productPrice" id="productPrice" placeholder="RM12" maxlength="255" value="{{ $product->price }}">
                    
                </div>
                <div class="form-group">
                    <label>Product Detail*</label>
                    <table class="table" id="dynamic_field_color">
                        @foreach($allColors as $index => $colorValue)
                            <tr class="product-detail" data-color-id="{{ $index + 1 }}">
                                <td style="padding-left:0px;">
                                    <select class="form-select" name="color[]">
                                        @foreach($colors as $colorOption)
                                            <option value="{{ $colorOption->value }}" {{ $colorValue == $colorOption->label() ? 'selected' : '' }}>
                                                {{ $colorOption->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <table class="table sizeTable">
                                        <tbody class="sizeWrapper">
                                            @foreach($allSizes[$index] as $sizeIndex => $sizeValue)
                                                <tr>
                                                    <td style="padding-left:0px;">
                                                        <select class="form-select" name="size[]">
                                                            @foreach($sizes as $sizeOption)
                                                            <option value="{{ $sizeOption->value }}" {{ $sizeValue == $sizeOption->value ? 'selected' : '' }}>
                                                                {{ $sizeOption }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="stock[]" placeholder="Enter Stock" class="form-control name_email" value="{{ $allStocks[$index][$sizeIndex] }}"/>
                                                    </td>
                                                    <td>
                                                        @if($sizeIndex == 0 && $index == 0)
                                                            <button type="button" class="btn btn-primary add-newSize">Add Size</button>
                                                        @elseif($sizeIndex == 0)
                                                            <button type="button" class="btn btn-primary add-newSize">Add Size</button>
                                                        @else
                                                            <button type="button" class="btn btn-danger remove-size">Remove</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td style="vertical-align: unset;">
                                    @if($loop->first)
                                        <button type="button" class="btn btn-primary add-newColor">Add Color</button>
                                    @else
                                        <button type="button" class="btn btn-danger remove-color">Remove</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @php
                    $filePaths = explode('|', $product->productImgObj);
                    $modelFile = null;
                    if (str_ends_with(end($filePaths), '.gltf')||str_ends_with(end($filePaths), '.glb')) {
                        $modelFile = array_pop($filePaths);
                    }
                    $imageFiles = $filePaths;
                    $qrFile = $product->productTryOnQR;
                @endphp
                <div class="form-group">
                    <label>Current Images</label>
                    <div class="form-card">
                        <div class="image-container">
                            @foreach($imageFiles as $imageFile)
                                <div class="image-wrapper">
                                    <img src="{{ asset('user/images/product/' . $imageFile) }}" alt="Product Image" class="product-image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <small class="form-text text-muted">Uploading a new image will replace all existing image.<br>The first image on preview will be thumbnail</small>
                </div>
                <div class="form-group">
                    <label for="productImages">Product Image</label>
                    <div class="form-card">
                        <input type="file" class="filepond" id="productImages" name="filepond[]" multiple data-max-file-size="30MB" data-max-files="5" />
                    </div>
                </div>
                       
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current 3D Model</label>
                            <div>
                                @if($modelFile)
                                    <model-viewer src="{{ asset('user/images/product/' . $modelFile) }}" alt="3D Model" auto-rotate camera-controls style="width: 100%; height: 200px;"></model-viewer>
                                @else
                                    <p>No 3D model available.</p>
                                @endif
                            </div>
                        </div>
                        <small class="form-text text-muted">Uploading a new model will replace the existing one.</small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current Try On QR Code</label>
                            <div class="image-container">
                                @if($qrFile)
                                    <div class="image-wrapper">
                                        <img src="{{ asset('user/images/product/' . $qrFile) }}" alt="Product Image" class="product-image" width=>
                                    </div>
                                @else
                                    <p style="width:100%">No QR code available.</p>
                                @endif
                            </div>
                            <small class="form-text text-muted">Uploading a new QR code will replace the existing one.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productModel">Product Model</label>
                            <div class="form-card">
                                <input type="file" class="filepond" id="productModel" name="productModel" multiple data-max-file-size="30MB" data-max-files="1" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="virtualTryOnQR">Virtual Try On QR</label>
                            <div class="form-card">
                                <input type="file" class="filepond" id="virtualTryOnQR" name="virtualTryOnQR" multiple data-max-file-size="3MB" data-max-files="1" />
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                <a href="/admin/all-product" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.1.1/model-viewer.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var colors = {!! json_encode($colors) !!};
var sizes = {!! json_encode($sizes) !!};
var maxSizes = parseInt({{ count($sizes) }});
var maxColors = parseInt({{ count($colors) }})-1;
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        FilePond.registerPlugin(
            FilePondPluginFileEncode,
            FilePondPluginFileValidateSize,
            FilePondPluginImageExifOrientation,
            FilePondPluginImagePreview
        );
        // Create FilePond instances
        const productImagesPond = FilePond.create(document.querySelector('#productImages'), {
            imagePreviewHeight: 200,
            imagePreviewWidth: 200,
            allowImagePreview: true,
            allowMultiple: true,
            maxFilesize: '3MB', 
            maxFiles: 5
        });

        let model;
        async function loadModel() {
            if (!model) {
                try {
                    model = await cocoSsd.load();
                } catch (error) {
                    console.error("Error loading COCO-SSD model:", error);
                }
            }
            return model;
        }
        async function performObjectDetection(imageElement, file) {
            try {
                const model = await loadModel();
                if (!model) return; // Exit if model is not loaded

                const predictions = await model.detect(imageElement);
                console.log(predictions);
                const fashionObjects = ['person', 'hat', 'suitcase', 'handbag', 'tie', 'backpack', 'umbrella', 'shoe', 'eyeglasses', 'bag' , 'scissors'];
                const containsFashion = predictions.some(prediction => fashionObjects.includes(prediction.class));

                if (!containsFashion) {
                    productImagesPond.removeFile(file.id);
                    Swal.fire({
                        title: "Some Images Rejected",
                        text: "Some images do not contain fashion-related objects and will be removed. Try to use image with person.",
                        icon: "error",
                        button: "OK",
                    });
                }
            } catch (error) {
                console.error("Error in object detection:", error);
            }
        }

        document.querySelector('#productImages').addEventListener('FilePond:addfile', (e) => {
            const file = e.detail.file.file;
            const imageElement = new Image();
            imageElement.crossOrigin = "anonymous"; // Handle potential CORS issues
            imageElement.src = URL.createObjectURL(file);
            imageElement.onload = () => {
                performObjectDetection(imageElement, e.detail.file);
            };
        });

        const virtualTryOnQRPond = FilePond.create(document.querySelector('#virtualTryOnQR'), {
            imagePreviewHeight: 200,
            imagePreviewWidth: 200,
            allowImagePreview: true,
            allowMultiple: false,
            maxFilesize: '3MB', 
            maxFiles: 1
        });
        const productModelPond = FilePond.create(document.querySelector('#productModel'), {
            allowImagePreview: false,
            allowMultiple: false,
            maxFilesize: '50MB', 
            maxFiles: 1
        });

        const initialPanelHeight = '350px'; 
        document.querySelector('.filepond--root').style.height = initialPanelHeight;

    $(document).ready(function() {
        function validateUniqueColors() {
            var colors = [];
            var errors = [];
            $('[name="color[]"]').each(function(index) {
                var colorValue = $(this).val();
                if (colors.includes(colorValue)) {
                    errors.push(`Row ${index + 1}: Color '${colorValue}' is selected more than once.`);
                }
                colors.push(colorValue);
            });
            return errors;
        }

        // Function to validate unique sizes within the same color
        function validateUniqueSizes() {
            var errors = [];
            $('[name="color[]"]').each(function(index) {
                var colorValue = $(this).val();
                var sizes = new Set();
                $(this).closest('tr').find('[name="size[]"]').each(function(sizeIndex) {
                    var sizeValue = $(this).val();
                    if (sizes.has(sizeValue)) {
                        errors.push(`Row ${index + 1}: Size '${sizeValue}' within color '${colorValue}' is selected more than once.`);
                    }
                    sizes.add(sizeValue);
                });
            });
            return errors;
        }

        function validateStock() {
            var errors = [];
            var digitPattern = /^[1-9]\d*$/; 
            $('[name="stock[]"]').each(function(index) {

                var stockValue = $(this).val();
                var parentTd = $(this).closest('td').parent().closest('td');
                var color = parentTd.find('[name="color[]"]').val();
                var container = $(this).closest('tr'); 
                var size = container.find('[name="size[]"]').val();
                
                color = (color !== undefined && color !== '') ? color : 'N/A';
                size = (size !== undefined && size !== '') ? size : 'N/A';

                if (stockValue === null || stockValue === ''){
                    errors.push(`Row ${index + 1}, Color '${color}', Size '${size}': If no stock added please set it to 0`);
                }else if(parseInt(stockValue, 10) !== 0 && stockValue !== null && stockValue !== ''){
                    if (!stockValue.match(digitPattern)) {
                        errors.push(`Row ${index + 1}, Color '${color}', Size '${size}': Stock quantity must be a positive integer`);
                    }
                }
            });
            return errors;
        }


        function validateProduct() {
            var errors = [];
            var productName = $('#productName').val().trim();
            var productType = $('#productType').val();
            var category = $('#category').val();
            var description = $('#productDesc').val().trim();
            var price = $('#productPrice').val().trim();
            var pricePattern = /^(RM\s?|\$)?\d*(\.\d{1,2})?$/;
            if (!productName) {
                errors.push("Product Name is required.");
            }
            if (!productType) {
                errors.push("Product Type is required.");
            }
            if (!category) {
                errors.push("Category is required.");
            }
            if (!description) {
                errors.push("Description is required.");
            }
            if (!price) {
                errors.push("Valid Product Price is required.");
            }else{
                if (!pricePattern.test(price)) {
                errors.push("Valid Product Price is required (e.g., RM10, RM 10, 10, 10.00).");
                }
            }

            return errors;
        }

        function validateProductImages() {
            var errors = [];
            var allowedImageExtensions = [".jpg", ".jpeg", ".png"];
            var allowedModelExtensions = [".gltf", ".glb"];
            var modelFileCount = 0;
            var imageBaseNames = new Set();
            var newProductImage = false;
            var existingModelFullName = "{{ $modelFile }}";
            var existingModelBaseName = existingModelFullName ? existingModelFullName.split('_').slice(1).join('_').split('.').slice(0, -1).join('.') : '';
            var productImages = productImagesPond.getFiles();
            if (productImages)
            {
                productImages.forEach(function(fileItem) {
                    newProductImage = true;
                    var fileExtension = fileItem.file.name.split('.').pop().toLowerCase();
                    var fullExtension = "." + fileExtension;
                    var baseName = fileItem.file.name.split('.').slice(0, -1).join('.');

                    if (!allowedImageExtensions.includes(fullExtension)) {
                        errors.push("Invalid product image file type. Allowed types are: " + allowedImageExtensions.join(", ") + ".");
                    } else if (imageBaseNames.has(baseName)) {
                        errors.push("Duplicate product image name detected: " + baseName + ". Each image must have a unique name.");
                    } else {
                        imageBaseNames.add(baseName);
                    }
                });
            }

            if (productModelPond) {
                var productModelFiles = productModelPond.getFiles();
                productModelFiles.forEach(function(fileItem) {
                    var fileExtension = fileItem.file.name.split('.').pop().toLowerCase();
                    var fullExtension = "." + fileExtension;
                    if (allowedModelExtensions.includes(fullExtension)) {
                        modelFileCount++;
                        modelBaseName = fileItem.file.name.split('.').slice(0, -1).join('.');
                        if (!(imageBaseNames.has(modelBaseName))) {
                            errors.push("A product image with the same base name as the model file is required.");
                        }
                    } else if (fullExtension !== '') {
                        errors.push("Invalid product model file type. Allowed type is: " + allowedModelExtensions.join(", ") + ".");
                    }
                });
            }

            if(newProductImage){
                if (modelFileCount === 0 && existingModelBaseName && !imageBaseNames.has(existingModelBaseName)) {
                    errors.push("A product image with the same base name as the existing model file (" + existingModelBaseName + ") is required.");
                }
            }
            


            if (virtualTryOnQRPond) {
                var virtualTryOnQRFiles = virtualTryOnQRPond.getFiles();
                virtualTryOnQRFiles.forEach(function(fileItem) {
                    var fileExtension = fileItem.file.name.split('.').pop().toLowerCase();
                    if (!allowedImageExtensions.includes("." + fileExtension)) {
                        errors.push("Invalid virtual try-on QR file type. Allowed types are: " + allowedImageExtensions.join(", ") + ".");
                    }
                });
            }

            return errors;
        }

        function displayErrors(errors) {
            if (errors.length > 0) {
                var errorHtml = "<ul style='text-align: left;'>";
                errors.forEach(function(error) {
                    errorHtml += "<li>" + error + "</li>";
                });
                errorHtml += "</ul>";

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorHtml,
                    confirmButtonText: 'Ok'
                });
                return false;
            }
            return true;
        }

        $('#productForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var colorErrors = validateUniqueColors();
                var sizeErrors = validateUniqueSizes();
                var stockErrors = validateStock();
                var productErrors = validateProduct();
                var imageErrors = validateProductImages();

                var allErrors = [].concat(colorErrors, sizeErrors, stockErrors, productErrors, imageErrors);

                if (allErrors.length > 0) {
                    displayErrors(allErrors);
                } else {
                    this.submit();
                }
            });
        });
    });

$(document).ready(function(){
    var colorCount = 1;
    var colorCountId = 1;
    var sizeCount = {1: 1};
    var allSizes = @json($allSizes);
    // Resetting sizeCount based on allSizes data
    sizeCount = {};
    allSizes.forEach((sizesForColor, index) => {
        var colorId = index + 1; 
        sizeCount[colorId] = sizesForColor.length;
        colorCountId = colorId;
    });
    function updateSizeCountInput() {
        $('#sizeCountData').val(JSON.stringify(sizeCount));
        console.log('sizeCount:', sizeCount);
    }
    updateSizeCountInput();

    $(document).on('click', '.add-newSize', function() {
        var colorId = $(this).closest('tr[data-color-id]').data('color-id');
        sizeCount[colorId] = (sizeCount[colorId] || 0) + 1;
        if (sizeCount[colorId] > maxSizes) {
            alert(`You can only add up to ${maxSizes} sizes for each color.`);
            sizeCount[colorId] --;
            return;
        }
        var sizeOptions = sizes.map(size => `<option value="${size}">${size}</option>`).join('');
        var newRow = `<tr>
            <td style="padding-left:0px;"><select name="size[]" class="form-select">${sizeOptions}</select></td>
            <td><input type="text" name="stock[]" placeholder="Enter Stock" class="form-control name_email"/></td>
            <td><button type="button" class="btn btn-danger remove-size">Remove</button></td>
        </tr>`;

        $(this).closest('.sizeWrapper').append(newRow);
        updateSizeCountInput();
    });

    $(document).on('click', '.add-newColor', function() {
        if (colorCount > maxColors) {
            alert(`You can only add up to ${maxColors} colors.`);
            return;
        }
        colorCountId++;
        sizeCount[colorCountId] = 1;
        colorCount++;

        var colorOptions = colors.map(color => `<option value="${color}">${ucwords(color)}</option>`).join('');
        var sizeOptions = sizes.map(size => `<option value="${size}">${size}</option>`).join('');
        var newColorRow = `<tr>
            <td style="padding-left:0px;">
                <select class="form-select" name="color[]">${colorOptions}</select>
                <table class="table sizeTable">
                    <tbody class="sizeWrapper">
                        <tr>
                            <td style="padding-left:0px;"><select class="form-select" name="size[]">${sizeOptions}</select></td>
                            <td><input type="text" name="stock[]" placeholder="Enter Stock" class="form-control name_email"/></td>
                            <td><button type="button" class="btn btn-primary add-newSize">Add Size</button></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="vertical-align: unset;">
                <button type="button" class="btn btn-danger remove-color">Remove</button>
            </td>
        </tr>`;

       newColorRow = $(newColorRow).attr('data-color-id', colorCountId); // Assign unique ID
        $('#dynamic_field_color').append(newColorRow);
        updateSizeCountInput();
    });

    $(document).on('click', '.remove-color', function(){  
        var colorId = $(this).closest('tr[data-color-id]').data('color-id');
        delete sizeCount[colorId];
        colorCount--;
        $(this).closest('tr').remove();
        updateSizeCountInput();
    });

    $(document).on('click', '.remove-size', function(){
        var colorId = $(this).closest('tr[data-color-id]').data('color-id');
        if (sizeCount[colorId] > 0) {
            sizeCount[colorId]--;
        }
        $(this).closest('tr').remove();
        updateSizeCountInput();
    });

    function ucwords(str) {
    return (str + '').replace(/^(.)|\s+(.)/g, function($1) {
        return $1.toUpperCase();
    });
}
});

</script>
@endsection
