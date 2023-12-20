@extends('admin/master')
@section('content')

<h2>Membership Page</h2><br>
@if(\Session::has('success'))
    <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
    </div><br>
@endif

<table id="example" class="table table-hover" style="width:100%">
    <thead>
        <tr>
            <th>
                <a href="{{route('memberships.create')}}" class="btn btn-primary" title="Add">Add<i class="mdi mdi-plus-circle-outline"></i></a>
            </th>
        </tr>
        <tr>
            <th>Level</th>
            <th>Total Amount Spent</th>
            <th>Discount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($memberships as $membership)
            <tr>
            <td>{{$membership->level}}</td>
            <td>{{$membership->totalAmount_spent}}</td>
            <td>{{$membership->discount}}%</td>
            <td> 
                <a href="{{route('memberships.edit', $membership->id)}}" class="btn btn-success btn-edit" title="Edit"><i class="mdi mdi-square-edit-outline"></i></a>
                <form action="{{ route('memberships.destroy', $membership->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete_button btn-delete" title="Delete">
                        <i class="mdi mdi-delete-outline"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "lengthMenu": [5, 10, 20, 50],
                "dom": 'ZBfrltip',
                "buttons": [
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                ],
            });
        });
    </script>

    <script>
        $('.delete_button').closest('form').submit(function(e){
            e.preventDefault();

            var form = this;

            swal({
                title: "Confirmation",
                text: "Are you sure to delete this record?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                // Check if the user confirmed the deletion
                if (willDelete) {
                    // Submit the form programmatically
                    form.submit();
                }
            });
        });
    </script>

@endsection