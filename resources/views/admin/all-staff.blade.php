@extends('admin/master')
@section('content')
<style>

.avatar {
  width: 2.75rem;
  height: 2.75rem;
  line-height: 3rem;
  border-radius: 50%;
  display: inline-block;
  background: transparent;
  position: relative;
  text-align: center;
  color: #868e96;
  font-weight: 700;
  vertical-align: bottom;
  font-size: 1rem;
  margin-right: 1rem;
  background-size: cover;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

td.address {
    white-space: pre-wrap;  
}

</style>

<h2>Staff Page</h2><br>
@if(\Session::has('success'))
    <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
    </div><br>
@endif

<table id="example" class="table table-hover" style="width:100%">
    <thead>
        <tr>
            <th>
                <a href="{{route('staffs.create')}}" class="btn btn-primary" title="Add">Add<i class="mdi mdi-plus-circle-outline"></i></a>
            </th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Phone No</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($staffs as $staff)
            <tr>
            <td>
                <div class="d-flex align-items-center">
                  <div class="avatar mr-3" style="background-image: url({{asset('user/images/profile_image/'.$staff->image)}})"></div>

                  <div class="">
                    <p class="font-weight-bold mb-0">{{$staff->name}}</p>
                    <p class="text-muted mb-0">{{$staff->email}}</p>
                  </div>
                </div>
            </td>
            <td>
                @if($staff->gender)
                {{$staff->gender}}
                @else
                -
                @endif
            </td>
            <td class="address">@if($staff->address){{$staff->address}}@else-@endif</td>
            <td>
                @if($staff->phoneNum)
                {{$staff->phoneNum}}
                @else
                -
                @endif
            </td>
            <td>{{$staff->role}}</td>
            <td> 
                <a href="{{route('staffs.edit', $staff->id)}}" class="btn btn-success btn-edit" title="Edit"><i class="mdi mdi-square-edit-outline"></i></a>
                <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" class="d-inline">
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