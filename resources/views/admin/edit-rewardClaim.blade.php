@extends('admin/master')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @if(\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div><br>
        @endif
        <h4 class="card-title">User Redeemed</h4>
        <p class="card-description"> Edit User Redeemed </p>
        <form class="forms-sample" method="POST" action="{{ route('rewardClaims.update', ['id' => $rewardClaim->id]) }}" >
          @csrf
          <div class="form-group">
            <label for="username">UserName</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{old('username', $rewardClaim->user->name)}}" readonly>
            @if ($errors->has('username'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('username') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="answer">User Email</label>
            <input type="text" name="email" class="form-control" id="email" placeholder="Email" value="{{old('email', $rewardClaim->user->email)}}" readonly>
            @if ($errors->has('email'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('email') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="reward">Reward</label>
            <input type="text" name="reward" class="form-control" id="reward" placeholder="Reward" value="{{old('reward', $rewardClaim->reward->name)}}" readonly>
            @if ($errors->has('reward'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('reward') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="current_address">Current Address</label>
            <input type="text" name="current_address" class="form-control" id="current_address" placeholder="Current Address" value="{{old('current_address', $rewardClaim->current_address)}}">
            @if ($errors->has('current_address'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('current_address') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="delivery_address">Delivery Address</label>
            <input type="text" name="delivery_address" class="form-control" id="reward" placeholder="Delivery Address" value="{{old('delivery_address', $rewardClaim->delivery_address)}}">
            @if ($errors->has('delivery_address'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('delivery_address') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-select" {{ $rewardClaim->status === 'completed' ? 'disabled' : '' }}>
                @if ($rewardClaim->status === 'confirmed')
                    <option value="confirmed" selected>Confirmed</option>
                    <option value="courier_picked">Courier Picked</option>
                @elseif ($rewardClaim->status === 'courier_picked')
                    <option value="courier_picked" selected>Courier Picked</option>
                    <option value="on_the_way">On The Way</option>
                @elseif ($rewardClaim->status === 'on_the_way')
                    <option value="on_the_way" selected>On The Way</option>
                    <option value="ready_for_pickup">Ready For Pickup</option>
                @elseif ($rewardClaim->status === 'ready_for_pickup')
                    <option value="ready_for_pickup" selected>Ready For Pickup</option>
                    <option value="completed">Completed</option>
                @elseif ($rewardClaim->status === 'completed')
                    <option value="completed" selected>Completed</option>
                @endif
            </select>
          </div>

          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('rewardClaims.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>
@endsection