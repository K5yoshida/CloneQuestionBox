@extends('layout.Main')

@section('title',  '退会完了画面')

@section('css')
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/error.css')}}">
@endsection

@section('content')
    <div class="message">
        退会が完了しました
    </div>
    <div class="message-detail">
        ご利用ありがとうございました
    </div>
    <div class="button"><a href="/">ホームに戻る</a></div>
    <div class="space"></div>
@endsection