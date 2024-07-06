@php
use App\Helpers\GooglePlacesHelper;
@endphp

@extends('layout')

@section('head')
<link rel="stylesheet" href="{{ asset('css/style_index.css') }}">
@endsection

@section('title', 'ホーム')

@section('main')
<div id="page_container">
    <div class="via_system">
        <form method="POST" action="/via">
            @csrf
            <div class="search">
                <div class="search_criteria">
                    所要時間
                    <input name="limit" placeholder="分" required class="input_search_criteria" type="number" maxlength="3" min="0" max="999">
                </div>
                @if($use_gps != 1)
                <div class="search_criteria">
                    出発地点
                    <input name="origin" required class="input_search_criteria" maxlength="30">
                </div>
                @endif
                <div class="search_criteria">
                    目的地
                    <input name="destination" required class="input_search_criteria" maxlength="30">
                </div>
                <div class="search_criteria">
                    移動手段
                    @if($use_gps == 1)
                    <select aria-label="Default select example" name="means" style="display:inline" required class="select_search_criteria">
                        <option value="driving | {{ $user_lat }},{{ $user_long }}">車</option>
                        <option value="bicycling | {{ $user_lat }},{{ $user_long }}">自転車</option>
                        <option value="walking | {{ $user_lat }},{{ $user_long }}">歩き</option>
                    </select>
                    @else
                    <select aria-label="Default select example" name="means" style="display:inline" required class="select_search_criteria">
                        <option value="driving">車</option>
                        <option value="bicycling">自転車</option>
                        <option value="walking">歩き</option>
                    </select>
                    @endif
                </div>
            </div>

            <div class="checkbox">
                <div class="checkbox_items">
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(1)" id="btn" name="via_btn" value="restaurant">レストラン</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(2)" id="btn" name="via_btn" value="pharmacy">薬局</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(3)" id="btn" name="via_btn" value="hotel">バー</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(4)" id="btn" name="via_btn" value="station">駅</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(5)" id="btn" name="via_btn" value="amusement park">遊園地</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(6)" id="btn" name="via_btn" value="Tourist attractions">観光スポット</label>
                </div>
                <div class="checkbox_items">
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(7)" id="btn" name="via_btn" value="museum">美術館、博物館</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(8)" id="btn" name="via_btn" value="Temple">お寺</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(9)" id="btn" name="via_btn" value="convenience store">コンビニ</label>
                    <label><input type="checkbox" class="btn btn-outline-danger rounded-pill check_btn btn-danger" onclick="buttonClick(10)" id="btn" name="via_btn" value="cafe">カフェ</label>
                </div>
            </div>

            <div class="button">
                <div class="gps_start_point">
                    @if($use_gps == 0)
                    <button type="button" id="btn_target" class="btn submit_btn btn-outline-danger">
                        現在地から出発する
                    </button>
                    @else
                    <button type="button" id="btn_" class="btn submit_btn btn-outline-danger" onclick="location.href='/'">
                        出発地点を設定する
                    </button>
                    @endif
                </div>
                <button type="button" class="btn submit_btn btn-outline-danger btn_clear" id="target" name="place" onclick="clearFormElements()">クリア</button>
                <button type="submit" class="btn submit_btn btn-outline-danger search-btn" id="target" name="search_mode" value="normal" onclick="validateFormAndRedirect()">検索</button>
            </div>
        </form>
    </div>

    <div class="decoration">
        <div class="suggest_place_box">
            <h5 class="red_word">周辺の経由地スポット</h5>
            <div class="suggest_place">
                @foreach($places as $place)
                <div class="suggest_box">
                    <div class="detail1">
                        <span class="star5_rating" data-rate="{{ $place['rating'] }}"></span>
                        <span>{{ $place['rating'] }}</span><br>
                        <span class="name">{{ $place['name'] }}</span><br>
                    </div>
                    <span><img src="{{ GooglePlacesHelper::generatePhotoUrl($place['photo_reference']) }}" style="width:200px"></span>
                    <div class="detail2">
                        <span class="vicinity">{{ $place['vicinity'] }}</span><br>
                        <button onclick="window.open('https://www.google.com/search?q={{ urlencode($place['name']) }}', '_blank')" class="btn btn-outline-danger">WEBで開く</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="map">
            <h5 class="red_word">MAP</h5>
            <iframe src="https://maps.google.co.jp/maps?output=embed&t=m&hl=ja&z=18&ll={{ $user_lat }},{{ $user_long }}" frameborder="0" scrolling="no" style="filter: hue-rotate(200deg); -webkit-filter: hue-rotate(200deg)"></iframe>
        </div>
    </div>
</div>
<div id="loading" class="loading-overlay">
    <div class="loader"></div>
</div>
<script src="{{ asset('js/geo.js') }}"></script>
<script src="{{ asset('js/clear_form.js') }}"></script>
<script>
    function hideLoadingScreen() {
        document.getElementById('loading').style.display = 'none';
    }
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    };
    window.onload = hideLoadingScreen;
    document.querySelector('.search-btn').addEventListener('click', function() {
        document.getElementById('loading').style.display = 'flex';
    });
</script>
@endsection