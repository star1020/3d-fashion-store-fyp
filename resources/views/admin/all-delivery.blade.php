@extends('admin/master')
@section('content')

<h2>Delivery Page</h2><br>

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
                    <th>Order ID</th>
                    <th>Cart Items</th>
                    <th>User ID</th>
                    <th>Delivery Address</th>
                    <th>Order Status</th>
                    <th>Order Date</th>
                    <th>Delivery Man Name</th>
                    <th>Delivery Man Phone</th>
                    <th>Delivery Company</th>
                    <th>Estimated Delivery Date</th>
                    <th>Actual Delivery Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersWithDeliveries as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->cartItemIds }}</td>
                    <td>{{ $order->userId }}</td>
                    <td>{{ $order->deliveryAddress }}</td>
                    <td>{{ $order->orderStatus }}</td>
                    <td>{{ $order->orderDate }}</td>
                    <td>{{ $order->delivery->deliveryManName ?? 'N/A' }}</td>
                    <td>{{ $order->delivery->deliveryManPhone ?? 'N/A' }}</td>
                    <td>{{ $order->delivery->deliveryCompany ?? 'N/A' }}</td>
                    <td>{{ $order->delivery->estimatedDeliveryDate ?? 'N/A' }}</td>
                    <td>{{ $order->delivery->actualDeliveryDate ?? 'N/A' }}</td>
                    <td>
                        <a href="{{route('delivery.edit', $order->delivery->id)}}" class="btn btn-success btn-edit" title="Edit"><i class="mdi mdi-square-edit-outline"></i></a>
                    </td>
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

@endsection

