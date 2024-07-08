@php
use App\Helpers\GooglePlacesHelper;
@endphp

@extends('layout')

@section('title', '経由地')

@section('main')
<div id="page_container">
    <div class="via_spot">
        <div class="via_name">
            <div class="via_spot_rate">
                <span class="star5_rating" data-rate="{{ $via_place['rating'] }}"></span>
                <span>{{ $via_place['rating'] }}</span>
            </div>
            <h3 class="red_word">{{ $via_place['name'] }}</h3>
            @if ($session_id == 0)
            <form id="addFavoriteForm" method="POST" action="/add_favorite" style="{{ $favorite ? 'display: none;' : 'display: flex;' }}">
                <input type="hidden" name="place" value="{{ 'add _=_ ' . $via_place['name'] . ' _=_ ' . json_encode($via_place) . ' _=_ ' . $url . ' _=_ ' . $means . ' _=_ ' . $favorite . ' _=_ 0 _=_ ' . json_encode($reformated_via_candidates_places_api_data) . ' _=_ ' . $original_lat . ' _=_ ' . $original_long . ' _=_ ' . $destination_lat . ' _=_ ' . $destination_long . ' _=_ ' . $origin . ' _=_ ' . $destination }}">
                <button type="submit" class="btn addtofavorite btn-outline-danger">お気に入りに登録する</button>
            </form>

            <form id="removeFavoriteForm" method="POST" action="/add_favorite" style="{{ $favorite ? 'display: flex;' : 'display: none;' }}">
                <input type="hidden" name="place" value="{{ 'remove _=_ ' . $via_place['name'] . ' _=_ ' . json_encode($via_place) . ' _=_ ' . $url . ' _=_ ' . $means . ' _=_ ' . $favorite . ' _=_ 0 _=_ ' . json_encode($reformated_via_candidates_places_api_data) . ' _=_ ' . $original_lat . ' _=_ ' . $original_long . ' _=_ ' . $destination_lat . ' _=_ ' . $destination_long . ' _=_ ' . $origin . ' _=_ ' . $destination }}">
                <button type="submit" class="btn removefavorite btn-danger">お気に入りを解除する</button>
            </form>
            @endif
        </div>
        <div class="via_img">
            @if (empty($via_place['photo_reference']))
            <img src="{{ asset('static/img/map_logo.jpg') }}">
            @else
            <img src="{{ GooglePlacesHelper::generatePhotoUrl($via_place['photo_reference']) }}" style="max-height:230px">
            @endif
        </div>
        <div class="via_detail_status">
            <p>所要時間　<span class="red_word">約{{ $via_place['add_duration']['text'] }}</span></p>
            <p>移動距離　<span class="red_word">約{{ $via_place['add_distance']['text'] }}</span></p>
            @if ($session_id == 0)
            <div class="via_btns">
                <form method="POST" action="/add_history" target="_blank">
                    <button type="submit" class="btn btn-outline-danger map_btn" name="place" value="url:{{ urlencode($url) }} name:{{ urlencode($via_place['name']) }} distance:{{ $via_place['add_distance']['value'] }} means:{{ $means }} duration:{{ $via_place['add_duration']['value'] }} destination:{{ urlencode($destination) }}">
                        ここに行く
                    </button>
                </form>
                <button onclick="window.open('https://www.google.com/search?q={{ urlencode($via_place['name']) }}', '_blank')" class="btn btn-outline-danger">WEBで開く</button>
            </div>
            @else
            <div class="via_btns">
                <button type="button" class="btn btn-outline-danger map_btn" onclick="window.open('{{ $url }}', '_blank')">ここに行く</button>
                <button onclick="window.open('https://www.google.com/search?q={{ urlencode($via_place['name']) }}', '_blank')" class="btn btn-outline-danger">WEBで開く</button>
            </div>
            @endif
        </div>
    </div>
    <div class="review_box">
        <h5 class="red_word" style="font-weight:600">レビュー</h5>
        <div class="rates">
            @if ($rate == 'no_review')
            <p>まだレビューがありません。</p>
            @else
            @foreach ($rate as $rate_item)
            <div class="rate_box">
                <div class="user">
                    <img src="{{ $rate_item['profile_photo_url'] }}" style="width:4vw; margin:2vw 2.5vw 0.5vw 1.5vw;">
                    <p style="text-align:center; margin-right:1.5vw;">{{ $rate_item['author_name'] }}</p>
                </div>
                <div class="review">
                    <span class="star5_rating" data-rate="{{ $rate_item['rating'] }}"></span>
                    <span>{{ $rate_item['rating'] }}</span>
                    <div class="review-text collapsed">
                        <p style="font-size:18px;">{!! nl2br(e($rate_item['text'])) !!}</p>
                    </div>
                    <span class="more-text" onclick="toggleMore(this)">もっと見る</span>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <div class="decoration">
        <!-- 
    <div class="daimei">
        <span>
            <h5 class="red_word flex-item" style="font-weight:600; margin-right:40vw;">周辺の経由地スポット</h5>
        </span>
        <span>
            <h5 class="red_word flex-item" style="font-weight:600">MAP</h5>
        </span>
    </div> 
    -->
        <div class="suggest_place_box">
            <h5 class="red_word">周辺の経由地スポット</h5>
            <div class="suggest_place">
                @foreach ($reformated_via_candidates_places_api_data as $via)
                <div class="suggest_box">
                    <div class="detail1">
                        <span class="star5_rating" data-rate="{{ $via_place['rating'] }}"></span><span>{{ $via_place['rating'] }}</span><br>
                        <span class="name">{{ $via['name'] }}</span><br>
                    </div>
                    @if (empty($via_place['photo_reference']))
                    <span><img src="{{ asset('img/map_logo.jpg') }}" style="width:200px"></span>
                    @else
                    <span><img src="{{ GooglePlacesHelper::generatePhotoUrl($via['photo_reference']) }}" style="width:200px"></span>
                    @endif
                    <div class="detail2 over_1900">
                        <span class="vicinity">{{ $via['vicinity'] }}</span><br>
                        <div class="d-flex justify-content-start centered-buttons">
                            <form method="POST" action="/next_via">
                                @csrf
                                <button type="submit" name="next_via" value="{{ json_encode($via_place) }} _=_ ({{ $original_lat }},{{ $original_long }})_=_({{ $destination_lat }},{{ $destination_long }})_=_{{ $means }}_=_{{ $origin }}_=_{{ $destination }}" class="btn btn-outline-danger change_other_via_btn">経由地を変更する</button>
                            </form>
                            <button onclick="window.open('https://www.google.com/search?q={{ urlencode($via['name']) }}', '_blank')" class="btn btn-outline-danger other_via_web_btn">WEBで開く</button>
                        </div>
                    </div>
                    <div class="detail2 above_1900">
                        <span class="vicinity">{{ $via['vicinity'] }}</span><br>
                        <div class="d-flex justify-content-start centered-buttons">
                            <form method="POST" action="/next_via">
                                @csrf
                                <button type="submit" name="next_via" value="{{ @json_encode($via_place) }}_=_({{ $original_lat }},{{ $original_long }})_=_({{ $destination_lat }},{{ $destination_long }})_=_{{ $means }}_=_{{ $origin }}_=_{{ $destination }}" class="btn btn-outline-danger change_other_via_btn">変更</button>
                            </form>
                            <button onclick="window.open('https://www.google.com/search?q={{ urlencode($via['name']) }}', '_blank')" class="btn btn-outline-danger other_via_web_btn">WEB</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="map">
            <h5 class="red_word">MAP</h5>
            <iframe width="700" height="420" style="border:0" loading="lazy" allowfullscreen style="filter:hue-rotate(200deg);-webkit-filter:hue-rotate(200deg)" src="{{ $mapURL }}">
            </iframe>
        </div>
    </div>
</div>
@endsection


<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById("footer").style.display = "none";
    });
</script>
<script src="{{ asset('static/js/geo.js') }}"></script>
<script src="{{ asset('static/js/manage_favorite.js') }}"></script>
<script>
    function toggleMore(element) {
        var reviewText = element.previousElementSibling;
        reviewText.classList.toggle('collapsed');
        element.textContent = reviewText.classList.contains('collapsed') ? 'もっと見る' : '隠す';
    }

    window.addEventListener('load', function() {
        var collapsedElement = document.querySelector('.collapsed');
        var moreTextElement = document.querySelector('.more-text');

        if (collapsedElement.offsetHeight < 10 * window.innerHeight / 100) {
            moreTextElement.style.display = 'none';
        }
    });
</script>
