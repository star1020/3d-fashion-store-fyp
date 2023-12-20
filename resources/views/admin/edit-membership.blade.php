@extends('admin/master')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Membership</h4>
        <p class="card-description"> Edit Membership </p>
        <form class="forms-sample" method="POST" action="{{route('memberships.update', $membership->id)}}">
          @csrf
          <input type="hidden" name="_method" value="PATCH">
          <div class="form-group">
            <label for="level">Level</label>
            <input type="text" name="level" class="form-control" id="level" placeholder="Level" value="{{old('level', $membership->level)}}">
            @if ($errors->has('level'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('level') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="totalAmount_spent">Total Amount Spent</label>
            <div class="input-group">
              <input type="text" name="totalAmount_spent" class="form-control" id="totalAmount_spent" placeholder="Total Amount Spent" value="{{old('totalAmount_spent', $membership->totalAmount_spent)}}">
              <span class="field-icon input-group-text">RM</span>
            </div> 
            @if ($errors->has('totalAmount_spent'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('totalAmount_spent') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="discount">Discount</label>
            <div class="input-group">
              <input type="text" name="discount" class="form-control" id="discount" placeholder="Discount" value="{{old('discount', $membership->discount)}}">
              <span class="field-icon input-group-text">RM</span>
            </div> 
            @if ($errors->has('discount'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('discount') }}</span>
            @endif
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('memberships.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>

@endsection