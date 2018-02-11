@extends('layout.Main')

@section('title',  'メインページ')


@section('ogp')
    <meta property="og:title" content="{{getenv('APP_NAME')}}" />
    <meta property="og:url" content="{{getenv('APP_URL') . '/' . $userInfo->screen_name}}" />
    <meta property="og:image" content="{{getenv('APP_URL') . '/img/main_image.png'}}" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{getenv('APP_NAME')}}" />
    <meta name="twitter:description" content="{{'@' . $userInfo->screen_name}}さんの{{getenv('APP_NAME')}}です" />
    <meta name="twitter:image" content="{{getenv('APP_URL') . '/img/main_image.png'}}" />
@endsection

@section('css')
    <link rel="stylesheet" href="{{assets('css/userHome.css')}}">
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/nvibar.css')}}">
@endsection

@section('content')
    @if($flog)
        <div class="box-message">
            これはあなたの{{getenv('APP_NAME')}}です。<br/>
            質問を確認しよう！！！
        </div>
    @else
        <div style="padding: 10px"></div>
    @endif
    <div class="profile">
        <div class="profile-tittle">{{'@' . $userInfo->screen_name}}さんの{{getenv('APP_NAME')}}</div>
        <div class="icon">
            <img class="icon-image" src="{{$userInfo->user_image}}">
        </div>
    </div>
    <div class="user-area">
        <div class="user-name">{{$userInfo->username}}</div>
    </div>
    <div class="question-title">匿名で質問ができます</div>
    <div class="question-title-sub">※この質問は匿名で送信されます</div>
    <form action="/{{$userInfo->screen_name}}/message" accept-charset="UTF-8" method="post">
        <input type="hidden" name="{{ $nameKey }}" value="{{ $name }}">
        <input type="hidden" name="{{ $valueKey }}" value="{{ $value }}">
        <textarea name="message" class="question-message" required></textarea>
        <div class="question-caution">
            <a href="/">利用規約</a> <a href="/">プライバシーポリシー</a> に同意の上で利用してください
        </div>
        <button name="button" type="submit" class="question-button" data-disable-with="送信中">質問を送る</button>
    </form>
@endsection

@section('nvi')
    <div class="nvi-bar-fixed">
        <div class="nvi-bar-icon"><a href="/"><img src="{{assets('img/home_on.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/message"><img src="{{assets('img/message_off.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/option"><img src="{{assets('img/setting_off.png')}}" class="nvi-bar-icon-img"></a></div>
    </div>
@endsection