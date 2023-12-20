@extends('admin/master')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">FAQ</h4>
        <p class="card-description"> Add FAQ </p>
        <form class="forms-sample" method="POST" action="{{route('faqs.store')}}">
          @csrf
          <div class="form-group">
            <label for="question">Question</label>
            <input type="text" name="question" class="form-control" id="question" placeholder="Question" value="{{old('question')}}">
            @if ($errors->has('question'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('question') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="answer">Answer</label>
            <input type="text" name="answer" class="form-control" id="answer" placeholder="Answer" value="{{old('answer')}}">
            @if ($errors->has('answer'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('answer') }}</span>
            @endif
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('faqs.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>

@endsection