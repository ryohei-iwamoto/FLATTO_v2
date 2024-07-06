@extends('layout')

@section('title', 'エラー')

@section('main')
<div class="apology">
    エラー：<font color="crimson"> {{ $error_code }}</font><br>
    {{ $error_message }}
</div>
@endsection