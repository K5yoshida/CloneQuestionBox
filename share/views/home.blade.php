@extends('layout.Main')

@section('title',  'トップページ')

@section('css')
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
@endsection

@section('content')
    <div class="top-logo-area">
        <div style="padding: 60px"></div>
        <div class="top-logo"></div>
        <div class="top-logo-title">{{getenv('APP_NAME')}}を作ってみよう</div>

        <div class="decision">
            <div class="decision-text">
                <a class="decision-text-link" href="/auth/twitter" target="_self">Twitterでログインする</a>
            </div>
        </div>
    </div>
@endsection