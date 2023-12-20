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
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Create New Product</h4>
            <form id="productForm" action="{{ route('product.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="sizeCountData" name="sizeCountData" value="">
                <div class="form-group">
                    <label for="productName">Product Name*</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="" maxlength="255">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productType">Product Type*</label>
                            <select class="form-select" name="productType" id="productType">
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Product Categories*</label>
                            <select class="form-select" name="category" id="category">
                                @foreach($categories as $category)
                                    <option value="{{ $category->value }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="productDesc">Description*</label>
                    <textarea class="form-control" id="productDesc" name="productDesc" maxlength="255">1</textarea>
                </div>

                <div class="form-group">
                    <label for="order_id">Product Price*</label>
                    <input type="text" class="form-control" name="productPrice" id="productPrice" placeholder="RM12" maxlength="255" value="">
                    
                </div>
                <div class="form-group">
                    <label>Product Detail*</label>
                    <table class="table" id="dynamic_field_color">
                        <tr class="product-detail" data-color-id="1">
                            <td style="padding-left:0px;">
                                <select class="form-select" name="color[]">
                                    @foreach($colors as $color)
                                        <option value="{{ $color->value }}">{{ $color->label() }}</option>
                                    @endforeach
                                </select>
                                <table class="table sizeTable">
                                    <tbody class="sizeWrapper">
                                        <tr>
                                            <td style="padding-left:0px;">
                                                <select class="form-select" name="size[]">
                                                    @foreach($sizes as $size)
                                                        <option value="{{ $size }}">{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="stock[]" placeholder="Enter Stock" class="form-control name_email"/>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary add-newSize">Add Size</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="vertical-align: unset;">
                                <button type="button" class="btn btn-primary add-newColor">Add Color</button>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <label for="productImages">Product Image*</label> <small class="form-text text-muted">The first image on preview will be thumbnail</small>
                    <div class="form-card">
                        <input type="file" class="filepond" id="productImages" name="filepond[]" multiple data-max-file-size="30MB" data-max-files="5" />
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
        const productImagesPond = FilePond.create(document.querySelector('#productImages'), {
            imagePreviewHeight: 200,
            imagePreviewWidth: 200,
            allowImagePreview: true,
            allowMultiple: true,
            maxFilesize: '3MB', // Adjust as needed
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
                if (!model) return;

                const predictions = await model.detect(imageElement);
                console.log(predictions);
                const fashionObjects = ['person', 'hat', 'suitcase', 'handbag', 'tie', 'backpack', 'umbrella', 'shoe', 'eyeglasses', 'bag' , 'scissors'];
                const containsFashion = predictions.some(prediction => fashionObjects.includes(prediction.class));

                if (!containsFashion) {
                    productImagesPond.removeFile(file.id);
                    Swal.fire({
                        title: "Some Images Rejected",
                        text: "Some images do not contain fashion-related objects and will be removed.",
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
            maxFilesize: '3MB', // Adjust as needed
            maxFiles: 1
        });
        const productModelPond = FilePond.create(document.querySelector('#productModel'), {
            allowImagePreview: false,
            allowMultiple: false,
            maxFilesize: '50MB', // Adjust as needed
            maxFiles: 1
        });

        const initialPanelHeight = '350px'; // Adjust as needed
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

                if (stockValue === '' || parseInt(stockValue, 10) <= 0) {
                    errors.push(`Row ${index + 1}, Color '${color}', Size '${size}': Stock quantity must be greater than 0 and cannot be empty.`);
                }else if (!stockValue.match(digitPattern)) {
                    errors.push(`Row ${index + 1}, Color '${color}', Size '${size}': Stock quantity must be a positive integer and cannot be empty.`);
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
            var pricePattern = /^(RM\s?|\$)?\d+(\.\d{1,2})?$/; 
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
            var allowedModelExtensions = [".gltf",".glb"];
            var modelFileCount = 0;
            var imageBaseNames = new Set();

            var productImages = productImagesPond.getFiles();
            if (productImages.length === 0) {
                errors.push("At least one product image is required.");
            } else {
                productImages.forEach(function(fileItem) {
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
                        modelBaseName = fileItem.file.name.split('.').slice(0, -1).join('.');
                        if (!(imageBaseNames.has(modelBaseName))) {
                            errors.push("A product image with the same base name as the model file is required.");
                        }
                    } else if (fullExtension !== '') {
                        errors.push("Invalid product model file type. Allowed type is: " + allowedModelExtensions.join(", ") + ".");
                    }
                });
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
    
    function updateSizeCountInput() {
        $('#sizeCountData').val(JSON.stringify(sizeCount));
        console.log('sizeCount:', sizeCount);
    }
    

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
