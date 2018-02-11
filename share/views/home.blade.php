@extends('layout.Main')

@section('title',  'トップページ')

@section('ogp')
    <meta property="og:title" content="{{getenv('APP_NAME')}}" />
    <meta property="og:url" content="{{getenv('APP_URL')}}" />
    <meta property="og:image" content="{{getenv('APP_URL') . '/img/main_image.png'}}" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{getenv('APP_NAME')}}" />
    <meta name="twitter:description" content="このサイトは{{getenv('APP_NAME')}}です" />
    <meta name="twitter:image" content="{{getenv('APP_URL') . '/img/main_image.png'}}" />
@endsection

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