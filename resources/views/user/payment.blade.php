@extends('user/master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>@import url("https://fonts.googleapis.com/css?family=Source+Code+Pro:400,500,600,700|Source+Sans+Pro:400,600,700&display=swap");
		.wrapper {
		  min-height: 100vh;
		  display: flex;
		  padding: 50px 15px;
		}
		@media screen and (max-width: 700px), (max-height: 500px) {
		  .wrapper {
			flex-wrap: wrap;
			flex-direction: column;
		  }
		}
		
		.card-form {
		  max-width: 570px;
		  margin: auto;
		  width: 100%;
		}
		@media screen and (max-width: 576px) {
		  .card-form {
			margin: 0 auto;
		  }
		}
		.card-form__inner {
		  background: #fff;
		  box-shadow: 0 30px 60px 0 rgba(90, 116, 148, 0.4);
		  border-radius: 10px;
		  padding: 35px;
		  padding-top: 180px;
		}
		@media screen and (max-width: 480px) {
		  .card-form__inner {
			padding: 25px;
			padding-top: 165px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-form__inner {
			padding: 15px;
			padding-top: 165px;
		  }
		}
		.card-form__row {
		  display: flex;
		  align-items: flex-start;
		}
		@media screen and (max-width: 480px) {
		  .card-form__row {
			flex-wrap: wrap;
		  }
		}
		.card-form__col {
		  flex: auto;
		  margin-right: 35px;
		}
		.card-form__col:last-child {
		  margin-right: 0;
		}
		@media screen and (max-width: 480px) {
		  .card-form__col {
			margin-right: 0;
			flex: unset;
			width: 100%;
			margin-bottom: 20px;
		  }
		  .card-form__col:last-child {
			margin-bottom: 0;
		  }
		}
		.card-form__col.-cvv {
		  max-width: 150px;
		}
		@media screen and (max-width: 480px) {
		  .card-form__col.-cvv {
			max-width: initial;
		  }
		}
		.card-form__group {
		  display: flex;
		  align-items: flex-start;
		  flex-wrap: wrap;
		}
		.card-form__group .card-input__input {
		  flex: 1;
		  margin-right: 15px;
		}
		.card-form__group .card-input__input:last-child {
		  margin-right: 0;
		}
		.card-form__button {
		  width: 100%;
		  height: 55px;
		  background: #2364d2;
		  border: none;
		  border-radius: 5px;
		  font-size: 22px;
		  font-weight: 500;
		  font-family: "Source Sans Pro", sans-serif;
		  color: #fff;
		  margin-top: 20px;
		  cursor: pointer;
		}
		@media screen and (max-width: 480px) {
		  .card-form__button {
			margin-top: 10px;
		  }
		}
		
		.card-item {
		  max-width: 430px;
		  height: 270px;
		  margin-left: auto;
		  margin-right: auto;
		  position: relative;
		  z-index: 2;
		  width: 100%;
		}
		@media screen and (max-width: 480px) {
		  .card-item {
			max-width: 310px;
			height: 220px;
			width: 90%;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item {
			height: 180px;
		  }
		}
		.card-item.-active .card-item__side.-front {
		  transform: perspective(1000px) rotateY(180deg) rotateX(0deg) rotateZ(0deg);
		}
		.card-item.-active .card-item__side.-back {
		  transform: perspective(1000px) rotateY(0) rotateX(0deg) rotateZ(0deg);
		}
		.card-item__focus {
		  position: absolute;
		  z-index: 3;
		  border-radius: 5px;
		  left: 0;
		  top: 0;
		  width: 100%;
		  height: 100%;
		  transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);
		  opacity: 0;
		  pointer-events: none;
		  overflow: hidden;
		  border: 2px solid rgba(255, 255, 255, 0.65);
		}
		.card-item__focus:after {
		  content: "";
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  background: #08142f;
		  height: 100%;
		  border-radius: 5px;
		  filter: blur(25px);
		  opacity: 0.5;
		}
		.card-item__focus.-active {
		  opacity: 1;
		}
		.card-item__side {
		  border-radius: 15px;
		  overflow: hidden;
		  box-shadow: 0 20px 60px 0 rgba(14, 42, 90, 0.55);
		  transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
		  transform-style: preserve-3d;
		  transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
		  backface-visibility: hidden;
		  height: 100%;
		}
		.card-item__side.-back {
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  transform: perspective(2000px) rotateY(-180deg) rotateX(0deg) rotate(0deg);
		  z-index: 2;
		  padding: 0;
		  height: 100%;
		}
		.card-item__side.-back .card-item__cover {
		  transform: rotateY(-180deg);
		}
		.card-item__bg {
		  max-width: 100%;
		  display: block;
		  max-height: 100%;
		  height: 100%;
		  width: 100%;
		  object-fit: cover;
		}
		.card-item__cover {
		  height: 100%;
		  background-color: #1c1d27;
		  position: absolute;
		  height: 100%;
		  background-color: #1c1d27;
		  left: 0;
		  top: 0;
		  width: 100%;
		  border-radius: 15px;
		  overflow: hidden;
		}
		.card-item__cover:after {
		  content: "";
		  position: absolute;
		  left: 0;
		  top: 0;
		  width: 100%;
		  height: 100%;
		  background: rgba(6, 2, 29, 0.45);
		}
		.card-item__top {
		  display: flex;
		  align-items: flex-start;
		  justify-content: space-between;
		  margin-bottom: 40px;
		  padding: 0 10px;
		}
		@media screen and (max-width: 480px) {
		  .card-item__top {
			margin-bottom: 25px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__top {
			margin-bottom: 15px;
		  }
		}
		.card-item__chip {
		  width: 60px;
		}
		@media screen and (max-width: 480px) {
		  .card-item__chip {
			width: 50px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__chip {
			width: 40px;
		  }
		}
		.card-item__type {
		  height: 45px;
		  position: relative;
		  display: flex;
		  justify-content: flex-end;
		  max-width: 100px;
		  margin-left: auto;
		  width: 100%;
		}
		@media screen and (max-width: 480px) {
		  .card-item__type {
			height: 40px;
			max-width: 90px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__type {
			height: 30px;
		  }
		}
		.card-item__typeImg {
		  max-width: 100%;
		  object-fit: contain;
		  max-height: 100%;
		  object-position: top right;
		}
		.card-item__info {
		  color: #fff;
		  width: 100%;
		  max-width: calc(100% - 85px);
		  padding: 10px 15px;
		  font-weight: 500;
		  display: block;
		  cursor: pointer;
		}
		@media screen and (max-width: 480px) {
		  .card-item__info {
			padding: 10px;
		  }
		}
		.card-item__holder {
		  opacity: 0.7;
		  font-size: 13px;
		  margin-bottom: 6px;
		}
		@media screen and (max-width: 480px) {
		  .card-item__holder {
			font-size: 12px;
			margin-bottom: 5px;
		  }
		}
		.card-item__wrapper {
		  font-family: "Source Code Pro", monospace;
		  padding: 25px 15px;
		  position: relative;
		  z-index: 4;
		  height: 100%;
		  text-shadow: 7px 6px 10px rgba(14, 42, 90, 0.8);
		  user-select: none;
		}
		@media screen and (max-width: 480px) {
		  .card-item__wrapper {
			padding: 20px 10px;
		  }
		}
		.card-item__name {
		  font-size: 18px;
		  line-height: 1;
		  white-space: nowrap;
		  max-width: 100%;
		  overflow: hidden;
		  text-overflow: ellipsis;
		  text-transform: uppercase;
		}
		@media screen and (max-width: 480px) {
		  .card-item__name {
			font-size: 16px;
		  }
		}
		.card-item__nameItem {
		  display: inline-block;
		  min-width: 8px;
		  position: relative;
		}
		.card-item__number {
		  font-weight: 500;
		  line-height: 1;
		  color: #fff;
		  font-size: 27px;
		  margin-bottom: 35px;
		  display: inline-block;
		  padding: 10px 15px;
		  cursor: pointer;
		}
		@media screen and (max-width: 480px) {
		  .card-item__number {
			font-size: 21px;
			margin-bottom: 15px;
			padding: 10px 10px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__number {
			font-size: 19px;
			margin-bottom: 10px;
			padding: 10px 10px;
		  }
		}
		.card-item__numberItem {
		  width: 16px;
		  display: inline-block;
		}
		.card-item__numberItem.-active {
		  width: 30px;
		}
		@media screen and (max-width: 480px) {
		  .card-item__numberItem {
			width: 13px;
		  }
		  .card-item__numberItem.-active {
			width: 16px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__numberItem {
			width: 12px;
		  }
		  .card-item__numberItem.-active {
			width: 8px;
		  }
		}
		.card-item__content {
		  color: #fff;
		  display: flex;
		  align-items: flex-start;
		}
		.card-item__date {
		  flex-wrap: wrap;
		  font-size: 18px;
		  margin-left: auto;
		  padding: 10px;
		  display: inline-flex;
		  width: 80px;
		  white-space: nowrap;
		  flex-shrink: 0;
		  cursor: pointer;
		}
		@media screen and (max-width: 480px) {
		  .card-item__date {
			font-size: 16px;
		  }
		}
		.card-item__dateItem {
		  position: relative;
		}
		.card-item__dateItem span {
		  width: 22px;
		  display: inline-block;
		}
		.card-item__dateTitle {
		  opacity: 0.7;
		  font-size: 13px;
		  padding-bottom: 6px;
		  width: 100%;
		}
		@media screen and (max-width: 480px) {
		  .card-item__dateTitle {
			font-size: 12px;
			padding-bottom: 5px;
		  }
		}
		.card-item__band {
		  background: rgba(0, 0, 19, 0.8);
		  width: 100%;
		  height: 50px;
		  margin-top: 30px;
		  position: relative;
		  z-index: 2;
		}
		@media screen and (max-width: 480px) {
		  .card-item__band {
			margin-top: 20px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__band {
			height: 40px;
			margin-top: 10px;
		  }
		}
		.card-item__cvv {
		  text-align: right;
		  position: relative;
		  z-index: 2;
		  padding: 15px;
		}
		.card-item__cvv .card-item__type {
		  opacity: 0.7;
		}
		@media screen and (max-width: 360px) {
		  .card-item__cvv {
			padding: 10px 15px;
		  }
		}
		.card-item__cvvTitle {
		  padding-right: 10px;
		  font-size: 15px;
		  font-weight: 500;
		  color: #fff;
		  margin-bottom: 5px;
		}
		.card-item__cvvBand {
		  height: 45px;
		  background: #fff;
		  margin-bottom: 30px;
		  text-align: right;
		  display: flex;
		  align-items: center;
		  justify-content: flex-end;
		  padding-right: 10px;
		  color: #1a3b5d;
		  font-size: 18px;
		  border-radius: 4px;
		  box-shadow: 0px 10px 20px -7px rgba(32, 56, 117, 0.35);
		}
		@media screen and (max-width: 480px) {
		  .card-item__cvvBand {
			height: 40px;
			margin-bottom: 20px;
		  }
		}
		@media screen and (max-width: 360px) {
		  .card-item__cvvBand {
			margin-bottom: 15px;
		  }
		}
		
		.card-list {
		  margin-bottom: -130px;
		}
		@media screen and (max-width: 480px) {
		  .card-list {
			margin-bottom: -120px;
		  }
		}
		
		.card-input {
		  margin-bottom: 20px;
		}
		.card-input__label {
		  font-size: 14px;
		  margin-bottom: 5px;
		  font-weight: 500;
		  color: #1a3b5d;
		  width: 100%;
		  display: block;
		  user-select: none;
		}
		.card-input__input {
		  width: 100%;
		  height: 50px;
		  border-radius: 5px;
		  box-shadow: none;
		  border: 1px solid #ced6e0;
		  transition: all 0.3s ease-in-out;
		  font-size: 18px;
		  padding: 5px 15px;
		  background: none;
		  color: #1a3b5d;
		  font-family: "Source Sans Pro", sans-serif;
		}
		.card-input__input:hover, .card-input__input:focus {
		  border-color: #3d9cff;
		}
		.card-input__input:focus {
		  box-shadow: 0px 10px 20px -13px rgba(32, 56, 117, 0.35);
		}
		.card-input__input.-select {
		  -webkit-appearance: none;
		  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUxJREFUeNrM1sEJwkAQBdCsngXPHsQO9O5FS7AAMVYgdqAd2IGCDWgFnryLFQiCZ8EGnJUNimiyM/tnk4HNEAg/8y6ZmMRVqz9eUJvRaSbvutCZ347bXVJy/ZnvTmdJ862Me+hAbZCTs6GHpyUi1tTSvPnqTpoWZPUa7W7ncT3vK4h4zVejy8QzM3WhVUO8ykI6jOxoGA4ig3BLHcNFSCGqGAkig2yqgpEiMsjSfY9LxYQg7L6r0X6wS29YJiYQYecemY+wHrXD1+bklGhpAhBDeu/JfIVGxaAQ9sb8CI+CQSJ+QmJg0Ii/EE2MBiIXooHRQhRCkBhNhBcEhLkwf05ZCG8ICCOpk0MULmvDSY2M8UawIRExLIQIEgHDRoghihgRIgiigBEjgiFATBACAgFgghEwSAAGgoBCBBgYAg5hYKAIFYgHBo6w9RRgAFfy160QuV8NAAAAAElFTkSuQmCC");
		  background-size: 12px;
		  background-position: 90% center;
		  background-repeat: no-repeat;
		  padding-right: 30px;
		}
		
		.slide-fade-up-enter-active {
		  transition: all 0.25s ease-in-out;
		  transition-delay: 0.1s;
		  position: relative;
		}
		
		.slide-fade-up-leave-active {
		  transition: all 0.25s ease-in-out;
		  position: absolute;
		}
		
		.slide-fade-up-enter {
		  opacity: 0;
		  transform: translateY(15px);
		  pointer-events: none;
		}
		
		.slide-fade-up-leave-to {
		  opacity: 0;
		  transform: translateY(-15px);
		  pointer-events: none;
		}
		
		.slide-fade-right-enter-active {
		  transition: all 0.25s ease-in-out;
		  transition-delay: 0.1s;
		  position: relative;
		}
		
		.slide-fade-right-leave-active {
		  transition: all 0.25s ease-in-out;
		  position: absolute;
		}
		
		.slide-fade-right-enter {
		  opacity: 0;
		  transform: translateX(10px) rotate(45deg);
		  pointer-events: none;
		}
		
		.slide-fade-right-leave-to {
		  opacity: 0;
		  transform: translateX(-10px) rotate(45deg);
		  pointer-events: none;
		}
		.or-separator {
			display: flex;
			align-items: center;
			text-align: center;
			margin: 20px 0;
		}

		.or-separator hr {
			width: 100%;
			margin: 0 10px;
		}

		.or-text {
			white-space: nowrap;
		}

		.membership-badge {
			display: inline-block;
			padding: 5px 10px;
			border-radius: 20px;
			font-weight: bold;
			font-size: 14px;
			text-transform: uppercase;
		}

		.gold {
			background-color: #ffc107;
			color: #fff;
		}

		.silver {
			background-color: #c4c4c4;
			color: #fff;
		}

		.bronze {
			background-color: #cd7f32;
			color: #fff;
		}

		.platinum {
			background-color: #e5e4e2;
			color: #0c0c0c;
			border: 2px solid #c0c0c0;
		}
	</style>
<div class="container" style="margin-top:50px">
		<div class="row">
		  <div class="col-md-4 order-md-2 mb-4" style="position: sticky; top: 100px; overflow: auto; height: 400px;">
			<h4 class="d-flex justify-content-between align-items-center mb-3">
			  <span class="text-muted">Your order</span>
			</h4>
			<ul class="list-group mb-3">
                @foreach ($groupedItems as $productId => $item)
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">{{ $item['name'] }}</h6>
                            @foreach ($item['details'] as $detail)
                                <small class="text-muted">{{ $detail }}</small><br>
                            @endforeach
                        </div>
                        <span class="text-muted">RM{{ number_format($item['subtotal'], 2) }}</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                    <h6 class="my-0">Shipping Price<span id="updateDesc"></span></h6>
                    </div>
                    <span class="text-muted" id="showShippingPrice">RM{{$shippingCost}}</span>
                </li>
  
				<li class="list-group-item d-flex justify-content-between bg-light">
				  <div class="text-success">
					<h6 class="my-0">Member
					  <span class="membership-badge {{auth()->user()->membership_level}}">{{auth()->user()->membership_level}}</span>
					</h6>
					<small>Discount {{$discountRate}}%</small><br>
				  </div>
				  <span class="text-success">-RM {{$discount}}</span>
				</li>
			  
			  <li class="list-group-item d-flex justify-content-between">
				<span>Total</span>
				<strong id="showTotalPrice">RM {{$finalTotalPrice}}</strong>
			  </li>
			</ul>
		  </div>
		  
		  <div class="col-md-8 order-md-1">
			<form class="needs-validation" novalidate method="post" action="/user/process-payment">
			@csrf
			<input type="hidden" id="payment_method_nonce" name="payment_method_nonce">
			<input type="hidden" id="totalPrice" name="totalPrice" value="{{$finalTotalPrice}}">
			<input type="hidden" id="cartItemIds" name="cartItemIds" value="{{ $cartItemIds }}">
    		<input type="hidden" id="deliveryAddress" name="deliveryAddress" value="{{ $fullAddress }}">
			<input type="hidden" id="deliveryPrice" name="deliveryPrice" value="{{ $shippingCost }}">
			<input type="hidden" id="state" name="state" value="{{ $state }}">
			  <h4 class="mb-3">Payment</h4>
			  <div class="wrapper" id="app">
				<div class="card-form">
				  <div class="card-list">
					<div class="card-item" v-bind:class="{ '-active' : isCardFlipped }">
					  <div class="card-item__side -front">
						<div class="card-item__focus" v-bind:class="{'-active' : focusElementStyle }" v-bind:style="focusElementStyle" ref="focusElement"></div>
						<div class="card-item__cover"><img src="https://assets.bwbx.io/images/users/iqjWHBFdfxIU/i98YsJ0Nxn1U/v0/-1x-1.jpg" class="card-item__bg"></div>
			  
						<div class="card-item__wrapper">
						  <div class="card-item__top">
			  
							<div class="card-item__type">
							  <transition name="slide-fade-up">
								<img v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + getCardType + '.png'" v-if="getCardType" v-bind:key="getCardType" alt="" class="card-item__typeImg">
							  </transition>
							</div>
						  </div>
						  <label for="cardNumber" class="card-item__number" ref="cardNumber">
							<template v-if="getCardType === 'amex'">
							  <span v-for="(n, $index) in amexCardMask" :key="$index">
								<transition name="slide-fade-up">
								  <div class="card-item__numberItem" v-if="$index > 4 && $index < 14 && cardNumber.length > $index && n.trim() !== ''">*</div>
								  <div class="card-item__numberItem" :class="{ '-active' : n.trim() === '' }" :key="$index" v-else-if="cardNumber.length > $index">
									${cardNumber[$index]}
								  </div>
								  <div class="card-item__numberItem" :class="{ '-active' : n.trim() === '' }" v-else :key="$index + 1">${n}</div>
								</transition>
							  </span>
							</template>
			  
							<template v-else>
							  <span v-for="(n, $index) in otherCardMask" :key="$index">
								<transition name="slide-fade-up">
								  <div class="card-item__numberItem" v-if="$index > 4 && $index < 15 && cardNumber.length > $index && n.trim() !== ''">*</div>
								  <div class="card-item__numberItem" :class="{ '-active' : n.trim() === '' }" :key="$index" v-else-if="cardNumber.length > $index">
									${cardNumber[$index]}
								  </div>
								  <div class="card-item__numberItem" :class="{ '-active' : n.trim() === '' }" v-else :key="$index + 1">${n}</div>
								</transition>
							  </span>
							</template>
						  </label>
						  <div class="card-item__content">
							<label for="cardName" class="card-item__info" ref="cardName">
							  <div class="card-item__holder">Card Holder</div>
							  <transition name="slide-fade-up">
								<div class="card-item__name" v-if="cardName.length" key="1">
								  <transition-group name="slide-fade-right">
									<span class="card-item__nameItem" v-for="(n, $index) in cardName.replace(/\s\s+/g, ' ')" v-if="$index === $index" v-bind:key="$index + 1">${n}</span>
								  </transition-group>
								</div>
								<div class="card-item__name" v-else key="2">Full Name</div>
							  </transition>
							</label>
							<div class="card-item__date" ref="cardDate">
							  <label for="cardMonth" class="card-item__dateTitle">Expires</label>
							  <label for="cardMonth" class="card-item__dateItem">
								<transition name="slide-fade-up">
								  <span v-if="cardMonth" v-bind:key="cardMonth">${cardMonth}</span>
								  <span v-else key="2">MM</span>
								</transition>
							  </label>
							  /
							  <label for="cardYear" class="card-item__dateItem">
								<transition name="slide-fade-up">
								  <span v-if="cardYear" v-bind:key="cardYear">${String(cardYear).slice(2,4)}</span>
								  <span v-else key="2">YY</span>
								</transition>
							  </label>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="card-item__side -back">
						<div class="card-item__cover">
						  <img v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
						</div>
						<div class="card-item__band"></div>
						<div class="card-item__cvv">
						  <div class="card-item__cvvTitle">CVV</div>
						  <div class="card-item__cvvBand">
							<span v-for="(n, $index) in cardCvv" :key="$index">
							  *
							</span>
			  
						  </div>
						  <div class="card-item__type">
							<img v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + getCardType + '.png'" v-if="getCardType" class="card-item__typeImg">
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="card-form__inner">
					<div class="card-input">
					  <label for="cardNumber" class="card-input__label">Card Number</label>
					  <input type="text" id="cardNumber" class="card-input__input" v-mask="generateCardNumberMask" v-model="cardNumber" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardNumber" autocomplete="off">
					</div>
					<div class="card-input">
					  <label for="cardName" class="card-input__label">Card Holders</label>
					  <input type="text" id="cardName" class="card-input__input" v-model="cardName" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardName" autocomplete="off">
					</div>
					<div class="card-form__row">
					  <div class="card-form__col">
						<div class="card-form__group">
						  <label for="cardMonth" class="card-input__label">Expiration Date</label>
						  <select class="card-input__input -select" id="cardMonth" v-model="cardMonth" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardDate">
							<option value="" disabled selected>Month</option>
							<option v-bind:value="n < 10 ? '0' + n : n" v-for="n in 12" v-bind:disabled="n < minCardMonth" v-bind:key="n">
                            ${n < 10 ? '0' + n : n}
							</option>
						  </select>
						  <select class="card-input__input -select" id="cardYear" v-model="cardYear" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardDate">
							<option value="" disabled selected>Year</option>
							<option v-bind:value="$index + minCardYear" v-for="(n, $index) in 12" v-bind:key="n">
                            ${$index + minCardYear}
							</option>
						  </select>
						</div>
					  </div>
					  <div class="card-form__col -cvv">
						<div class="card-input">
						  <label for="cardCvv" class="card-input__label">CVV</label>
						  <input type="text" class="card-input__input" id="cardCvv" v-mask="'####'" maxlength="4" v-model="cardCvv" v-on:focus="flipCard(true)" v-on:blur="flipCard(false)" autocomplete="off">
						</div>
					  </div>
					</div>
					<button class="card-form__button" id="customCardButton" style="margin-bottom:15px">
					<img  height="50%" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjRweCIgaGVpZ2h0PSIxOHB4IiB2aWV3Qm94PSIwIDAgMjQgMTgiIHhtbG5zPSJodHRwOiYjeDJGOyYjeDJGO3d3dy53My5vcmcmI3gyRjsyMDAwJiN4MkY7c3ZnIj48ZyBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMy4wMDAwMDAsIC02LjAwMDAwMCkiIGZpbGw9IiNmZmZmZmYiIGZpbGwtcnVsZT0ibm9uemVybyI+PHBhdGggZD0iTTguMjc1MjEzMzgsMTIuNTEyMjY1MyBDNy45MzAwMzU0MiwxMi41MTIyNjUzIDcuNjUwMjEzMzgsMTIuMjMyNDQzMiA3LjY1MDIxMzM4LDExLjg4NzI2NTMgQzcuNjUwMjEzMzgsMTEuNTQyMDg3MyA3LjkzMDAzNTQyLDExLjI2MjI2NTMgOC4yNzUyMTMzOCwxMS4yNjIyNjUzIEwyNC43ODc5MDQyLDExLjI2MjI2NTMgQzI1LjU5NTU5MzksMTEuMjYyMjY1MyAyNi4yNSwxMS45MTc1OTA1IDI2LjI1LDEyLjcyNTUzNjggTDI2LjI1LDIyLjI4NjcyODQgQzI2LjI1LDIzLjA5NDY3NDggMjUuNTk1NTkzOSwyMy43NSAyNC43ODc5MDQyLDIzLjc1IEw1LjIxMjMxMzAyLDIzLjc1IEM0LjQwNDYyMzI1LDIzLjc1IDMuNzUsMjMuMDk0Njc0OCAzLjc1LDIyLjI4NjczOTcgTDMuNzUsNy43MTMyNzE1MiBDMy43NSw2LjkwNTMyNTE4IDQuNDA0NDA2MDgsNi4yNSA1LjIxMjI3MjEyLDYuMjUgTDI0Ljc4ODA2NjQsNi4yNTU1MjE2MyBDMjUuNTk1NjA3OSw2LjI1NTczMTQ3IDI2LjI1LDYuOTEwOTk1MDcgMjYuMjUsNy43MTg3MDM2MiBMMjYuMjUsOS4yMzU3NzE2MSBDMjYuMjUsOS41ODA5NDk1OCAyNS45NzAyNjc1LDkuODYwODExNjggMjUuNjI1MDg5NSw5Ljg2MDg2MTEyIEMyNS4yNzk5MTE1LDkuODYwOTEwNTUgMjUuMDAwMDQ5NCw5LjU4MTEyODYgMjUsOS4yMzU5NTA2MyBMMjQuOTk5NzgyNyw3LjcxODc5MzEzIEMyNC45OTk3ODI3LDcuNjAwODMxODkgMjQuOTA0NjYxMSw3LjUwNTU1MTk3IDI0Ljc4NzcyNzgsNy41MDU1MjE1OCBMNS4yMTIwOTU4Myw3LjQ5OTk5OTk4IEM1LjA5NTE1NTA2LDcuNDk5OTk5OTggNSw3LjU5NTI4ODY4IDUsNy43MTMyNjAyOCBMNS4wMDAyMTcxOCwyMi4yODY3Mjg0IEM1LjAwMDIxNzE4LDIyLjQwNDcxMTMgNS4wOTUzNzIyMywyMi41IDUuMjEyMzEzMDIsMjIuNSBMMjQuNzg3OTA0MiwyMi41IEMyNC45MDQ4NDUsMjIuNSAyNSwyMi40MDQ3MTEzIDI1LDIyLjI4NjcyODQgTDI1LDEyLjcyNTUzNjggQzI1LDEyLjYwNzU1NCAyNC45MDQ4NDQ5LDEyLjUxMjI2NTMgMjQuNzg3OTA0MiwxMi41MTIyNjUzIEw4LjI3NTIxMzM4LDEyLjUxMjI2NTMgWiIgaWQ9IlN0cm9rZS0xIj48L3BhdGg+PC9nPjwvZz48L3N2Zz4" alt="" class="paypal-logo-card paypal-logo-card-">
					<span style="margin-left:5px">Pay With Credit/Debit Card<span>
					</button>
					<div class="or-separator">
						<hr>
						<span class="or-text">OR</span>
						<hr>
					</div>
					<div id="paypal-button-container"></div>
				  </div>
				</div>
			  
			  </div>
			</form>
		  </div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
		<script src="https://unpkg.com/vue-the-mask@0.11.1/dist/vue-the-mask.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=ATN-pNSz-OnvA2bPB-QpOx2KWXLxw05w5KTskH_QZEWxH5X8g62D0araB_uQ2GqqQQ-pSTdKul2XJtB4&currency=MYR"></script>
<script src="https://js.braintreegateway.com/web/3.76.0/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.76.0/js/hosted-fields.min.js"></script>
		<script>
			var totalPrice = parseFloat("{{ $finalTotalPrice }}".replace(/,/g, ''));
			var totalPrice = parseFloat("{{ $finalTotalPrice }}".replace(/,/g, ''));
			var cartItemIds = document.getElementById('cartItemIds').value;
		new Vue({
		  el: "#app",
          delimiters: ['${', '}'],
		  data() {
			return {
			  currentCardBackground: Math.floor(Math.random() * 25 + 1),
			  cardName: "",
			  cardNumber: "",
			  cardMonth: "",
			  cardYear: "",
			  cardCvv: "",
			  minCardYear: new Date().getFullYear(),
			  amexCardMask: "#### ###### #####",
			  otherCardMask: "#### #### #### ####",
			  cardNumberTemp: "",
			  isCardFlipped: false,
			  focusElementStyle: null,
			  isInputFocused: false
			};
		  },
		  mounted() {
			this.cardNumberTemp = this.otherCardMask;
			document.getElementById("cardNumber").focus();
		  },
		  computed: {
			getCardType() {
			  let number = this.cardNumber;
			  let re = new RegExp("^4");
			  if (number.match(re) != null) return "visa";
		
			  re = new RegExp("^(34|37)");
			  if (number.match(re) != null) return "amex";
		
			  re = new RegExp("^5[1-5]");
			  if (number.match(re) != null) return "mastercard";
		
			  re = new RegExp("^6011");
			  if (number.match(re) != null) return "discover";
		
			  re = new RegExp("^9792");
			  if (number.match(re) != null) return "troy";
		
			  return "visa"; // default type
			},
			generateCardNumberMask() {
			  return this.getCardType === "amex"
				? this.amexCardMask
				: this.otherCardMask;
			},
			minCardMonth() {
			  if (this.cardYear === this.minCardYear) return new Date().getMonth() + 1;
			  return 1;
			}
		  },
		  watch: {
			cardYear() {
			  if (this.cardMonth < this.minCardMonth) {
				this.cardMonth = "";
			  }
			}
		  },
		  methods: {
			flipCard(status) {
			  this.isCardFlipped = status;
			},
			focusInput(e) {
			  this.isInputFocused = true;
			  let targetRef = e.target.dataset.ref;
			  let target = this.$refs[targetRef];
			  this.focusElementStyle = {
				width: `${target.offsetWidth}px`,
				height: `${target.offsetHeight}px`,
				transform: `translateX(${target.offsetLeft}px) translateY(${target.offsetTop}px)`
			  };
			},
			blurInput() {
			  let vm = this;
			  setTimeout(() => {
				if (!vm.isInputFocused) {
				  vm.focusElementStyle = null;
				}
			  }, 300);
			  vm.isInputFocused = false;
			}
		  }
		});
		
		paypal.Buttons({
    fundingSource: paypal.FUNDING.PAYPAL,
    createOrder: function(data, actions) {
        return checkStock().then(stockData => {
            if (stockData.success && stockData.inStock) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: totalPrice
                        }
                    }]
                });
            } else {
                Swal.fire({
                    title: 'Out of Stock',
                    html: 'Some items in your cart are out of stock.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                throw new Error('Out of stock');
            }
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            submitOrderDetails(data.orderID, 'paypal');
        });
    },
    style: {
        layout: 'vertical'
    }
}).render('#paypal-button-container');

function checkStock() {
	console.log("Checking stock for cart items:", cartItemIds);
    return fetch('/user/check-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cartItemIds: cartItemIds
        })
    })
    .then(response => response.json());
}

document.addEventListener('DOMContentLoaded', function() {
    fetch('/user/payment/token')
		.then(response => {
			if (!response.ok) {
				throw new Error('Server error');
			}
			return response.json();
		})
        .then(data => {
            braintree.client.create({
                authorization: data.token
            }, function (clientErr, clientInstance) {
                if (clientErr) {
                    console.error('Error creating Braintree instance:', clientErr);
                    return;
                }
                document.getElementById('customCardButton').addEventListener('click', function(event) {
                    event.preventDefault();
                    checkStock().then(stockData => {
                        if (stockData.success && stockData.inStock) {
                            var errors = [];
                            var cardNumber = document.getElementById('cardNumber').value.replace(/\s+/g, '');
                            var cardName = document.getElementById('cardName').value;
                            var cardCvv = document.getElementById('cardCvv').value;
                            var cardMonth = document.getElementById('cardMonth').value;
                            var cardYear = document.getElementById('cardYear').value;

                            // Validation
							if (cardName === '') errors.push("Name is required.");
                            if (!cardName.match(/^[a-zA-Z\s]*$/)) errors.push("Name must be alphabetic.");
                            if (!cardNumber.match(/^\d{15,16}$/)) errors.push("Card number must be 15 or 16 digits.");
                            if (!cardCvv.match(/^\d{3,4}$/)) errors.push("CVV must be 3 or 4 digits.");
                            if (cardMonth === '' || cardYear === '') errors.push("Expiration date is required.");

                            if (errors.length > 0) {
                                Swal.fire({
                                    title: 'Error!',
                                    html: errors.join('<br>'),
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                tokenizeCard(clientInstance, {
                                    number: cardNumber,
                                    cvv: cardCvv,
                                    expirationMonth: cardMonth,
                                    expirationYear: cardYear
                                });
                            }
                        } else {
							Swal.close();
                            Swal.fire({
                                title: 'Out of Stock',
                                text: 'Some items in your cart are no longer available.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error during stock check:', error);
                    });
                });
            });
        })
        .catch(error => {
			console.error('Error fetching client token:', error);
			if (error.message === 'Server error') {
				Swal.close();
				Swal.fire({
					title: 'Error',
					text: 'An error occurred. The page will now reload.',
					icon: 'error',
					confirmButtonText: 'Reload'
				}).then((result) => {
					if (result.isConfirmed) {
						window.location.reload();
					}
				});
			}
		});
});
function tokenizeCard(clientInstance, cardData) {
    clientInstance.request({
        endpoint: 'payment_methods/credit_cards',
        method: 'post',
        data: {
            creditCard: {
                number: cardData.number,
                cvv: cardData.cvv,
                expirationMonth: cardData.expirationMonth,
                expirationYear: cardData.expirationYear,
                options: {
                    validate: false
                }
            }
        }
    }, function (err, response) {
        if (err) {
            console.error('Error tokenizing card:', err);
			Swal.close();
			Swal.fire({
				title: 'Payment Error',
				text: 'Failed to process your card details. Please check your information and try again.',
				icon: 'error',
				confirmButtonText: 'OK'
			});
			return;
        }

        if (response.creditCards && response.creditCards[0]) {
            var nonce = response.creditCards[0].nonce;
			
            submitOrderDetails(nonce, 'creditCard'); // Nonce used as transactionId for Braintree
        } else {
            console.error('No credit card tokenization response');
        }
    });
}
function submitOrderDetails(transactionId, paymentType) {
	if (!transactionId) {
		Swal.close();
		Swal.fire({
			title: 'Error',
			text: 'Request timed out. Please reload the page.',
			icon: 'error',
			confirmButtonText: 'OK'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.reload();
			}
		}).catch(error => {
			console.error('Error:', error);
			Swal.close();
			Swal.fire({
				title: 'Error',
				text: 'There was a problem processing your transaction. Please try again.',
				icon: 'error',
				confirmButtonText: 'OK'
			});
		});
		return;
	}
	showWaitingPopup();

    fetch('/user/store-transaction', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            transactionId: transactionId,
			paymentType: paymentType,
            cartItemIds: document.getElementById('cartItemIds').value,
            deliveryAddress: document.getElementById('deliveryAddress').value,
			deliveryPrice: document.getElementById('deliveryPrice').value,
            totalPrice: totalPrice,
            state: document.getElementById('state').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/user/tracking/' + data.orderId; 
        } else {
            console.error('Error storing transaction:', data.message);
			Swal.close();
			Swal.fire({
				title: 'Error',
				text: data.message,
				icon: 'error',
				confirmButtonText: 'OK'
        	});
        }
    })
    .catch(error => console.error('Error:', error));
}
function showWaitingPopup() {
    Swal.fire({
        title: 'Processing your payment...',
        html: 'Please wait.',
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
		willOpen: () => {
            Swal.showLoading();
        }
    });
}

</script>
        @endsection