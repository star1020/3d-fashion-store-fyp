@extends('admin/master')
@section('content')

<h2>Payment Page</h2><br>

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
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Transaction ID</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                    <th>Payment Fee</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->orderId }}</td>
                    <td>{{ $payment->userId }}</td>
                    <td>{{ $payment->transactionId }}</td>
                    <td>{{ $payment->paymentMethod }}</td>
                    <td>{{ $payment->paymentDate }}</td>
                    <td>{{ $payment->totalPaymentFee }}</td>
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