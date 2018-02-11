@extends('layout.Main')

@section('title',  'エラーページ')

@section('css')
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/error.css')}}">
@endsection

@section('content')
    <div class="message">
        エラーが発生しました
    </div>
    <div class="message-detail">
        もう一度ホームからアクセスしてください
    </div>
    <div class="button"><a href="/">ホームに戻る</a></div>
    <div class="space"></div>
@endsection