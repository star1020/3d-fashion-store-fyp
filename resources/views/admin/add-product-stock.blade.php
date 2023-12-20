@extends('admin/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .submit-btn {
        float: unset !important;
    }
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Product</h4>
            <form id="productForm" action="{{ route('product.increaseStock', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="sizeCountData" name="sizeCountData" value="">
                <div class="form-group">
                    <label for="productName" disabled>Product Name:</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="{{ $product->productName }}" maxlength="255" disabled>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productType">Product Type:</label>
                            <select class="form-select" name="productType" id="productType" disabled>
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
                            <label for="category">Product Categories:</label>
                            <select class="form-select" name="category" id="category" disabled>
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
                    <label for="productDesc">Description:</label>
                    <textarea class="form-control" id="productDesc" name="productDesc" maxlength="255" disabled>{{ $product->productDesc }}</textarea>
                </div>

                <div class="form-group">
                    <label for="order_id">Product Price</label>
                    <input type="text" class="form-control" name="productPrice" id="productPrice" placeholder="RM12" maxlength="255" value="{{ $product->price }}" disabled>
                    
                </div>
                <div class="form-group">
                    <label>Product Detail</label>
                    <table class="table" id="dynamic_field_color">
                        @foreach($allColors as $index => $colorValue)
                            <tr class="product-detail" data-color-id="{{ $index + 1 }}">
                                <td style="padding-left:0px;">
                                    <select class="form-select" name="color[]" disabled>
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
                                                        <select class="form-select" name="size[]" disabled>
                                                            @foreach($sizes as $sizeOption)
                                                            <option value="{{ $sizeOption->value }}" {{ $sizeValue == $sizeOption->value ? 'selected' : '' }}>
                                                                {{ $sizeOption }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="stock[]" placeholder="Enter Stock" class="form-control name_email" value="0"/>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                <a href="/admin/all-product" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    $(document).ready(function() {
        var colorCountId = 1;
        var sizeCount = {1: 1};
        var allSizes = @json($allSizes);
        sizeCount = {};
        allSizes.forEach((sizesForColor, index) => {
            var colorId = index + 1; 
            sizeCount[colorId] = sizesForColor.length;
            colorCountId = colorId;
        });
        $('#sizeCountData').val(JSON.stringify(sizeCount));

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
                var stockErrors = validateStock();

                var allErrors = [].concat(stockErrors);

                if (allErrors.length > 0) {
                    displayErrors(allErrors);
                } else {
                    this.submit();
                }
            });
        });
    });

</script>
@endsection
