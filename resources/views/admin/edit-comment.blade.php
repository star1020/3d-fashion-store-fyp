@extends('admin/master')
@section('content')

<style>
  .input-group-text{
    border: none !important;
  }
</style>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Review</h4>
        <p class="card-description"> Add/Edit Comment </p>
        <form class="forms-sample" method="POST" action="{{ route('comments.update', $comment->id) }}" >
          @csrf
          <input type="hidden" name="_method" value="PATCH"> 
          <input type="hidden" name="userId" value="{{$comment->user_id}}"> 
          <input type="hidden" name="productId" value="{{$comment->product_id}}"> 
          <div class="form-group">
            <label for="exampleInputName1">Name</label>
            <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Name" value="{{$comment->user->name}}" readonly>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail3">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail3" placeholder="Email" value="{{$comment->user->email}}" readonly>
          </div>
          <div class="form-group">
            <label for="exampleInputRating">Rating</label>
            <div class="input-group">
              <input type="text" name="rating" class="form-control" id="exampleInputRating" placeholder="Rating" value="{{$comment->rating}}" readonly>
              <span class="input-group-text"><i class="fas fa-star text-warning"></i></span>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputReview">Review</label>
            <textarea readonly name="review" class="form-control" id="exampleInputReview" cols="30" rows="10">{{$comment->review}}</textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputComment">Comment</label>
            <textarea name="admin_reply" class="form-control" id="exampleInputComment" cols="30" rows="10" maxlength="254">{{$comment->admin_reply}}</textarea>
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('comments.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>

  
@endsection