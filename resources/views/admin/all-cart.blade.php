@extends('admin/master')
@section('content')

<h2>Cart Page</h2><br>

@if(session('success'))
<div class="alert alert-success">
    <p>{{ session('success') }}</p>
</div><br>
@endif

<div class="container-fluid">
    <div class="table-responsive">
        <table id="example" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Cart Item ID</th>
                    <th>Product ID</th>
                    <th>User ID</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $cartItem)
                <tr>
                    <td>{{ $cartItem->id }}</td>
                    <td>{{ $cartItem->productId }}</td>
                    <td>{{ $cartItem->userId }}</td>
                    <td>{{ $cartItem->color }}</td>
                    <td>{{ $cartItem->size }}</td>
                    <td>{{ $cartItem->quantity }}</td>
                    <td>{{ $cartItem->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "lengthMenu": [5, 10, 20, 50],
            "dom": 'ZBfrltip',
            "buttons": [
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ]
                    }
                },
            ],
            "drawCallback": function() {
                $('.image-link').magnificPopup({
                    type: 'image'
                });
            }
        });
    });
</script>

<script>
    $(".delete_button").click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
            title: "Confirmation",
            text: "Are you sure to delete this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location.href = url;
            }
        });
    });
</script>

@endsection