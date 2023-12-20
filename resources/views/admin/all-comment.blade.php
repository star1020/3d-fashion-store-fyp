@extends('admin/master')
@section('content')
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
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

.swiper {
    width: 180px;
    height: 40%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    /* background: #fff; */
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: 100px !important;
    height: 100px !important;
    object-fit: cover;
    border-radius: unset !important;
}

.swiper {
    margin-left: auto;
    margin-right: auto;
}

td.comment {
    white-space: pre-wrap;  
}
</style>

<h2>Review and Rating Page</h2><br>

@if(\Session::has('success'))
    <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
    </div><br>
@endif

<div class="container-fluid">
    <div class="table-responsive">
        <table id="example" class="table table-hover" style="width:100%">
            <thead>
                {{-- <tr>
                    <th>
                        <a href="{{route('comments.create')}}" class="btn btn-primary" title="Add">Add<i class="mdi mdi-plus-circle-outline"></i></a>
                    </th>
                </tr> --}}
                <tr>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Image</th>
                    <th>Date Time</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                        <div class="avatar mr-3" style="background-image: url({{asset('user/images/profile_image/'.$comment->user->image)}})"></div>

                        <div class="">
                            <p class="font-weight-bold mb-0">{{$comment->user->name}}</p>
                            <p class="text-muted mb-0">{{$comment->user->email}}</p>
                        </div>
                        </div>
                    </td>
                    <td>{{$comment->rating}} <i class="fa fa-star text-warning"></i></td>
                    <td>{{$comment->review}}</td>
                    <td>
                        @if($comment->image != NULL)
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach (explode('|', $comment->image) as $image)
                                    <div class="swiper-slide">
                                        <a href="{{asset('user/images/review/'.$image)}}" class="image-link">
                                            <img src="{{asset('user/images/review/'.$image)}}" class="img-fluid">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        @else
                            <p style="text-align: center">No Image</p>   
                        @endif
                    </td>
                    <td>{{$comment->created_at}}</td>
                    <td class="comment">
                        @if($comment->admin_reply)
                            {{ $comment->admin_reply }}
                        @else
                            -
                        @endif
                    </td>
                    <td> 
                        <a href="{{route('comments.edit', $comment->id)}}" class="btn btn-success btn-edit" title="Edit"><i class="mdi mdi-square-edit-outline"></i></a>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
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
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
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

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 0,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>


@endsection