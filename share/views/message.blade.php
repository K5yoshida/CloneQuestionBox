@extends('layout.Main')

@section('title',  'トップページ')

@section('ogp')
    <meta property="og:title" content="{{getenv('APP_NAME')}}" />
    <meta property="og:url" content="{{getenv('APP_URL') . '/post' . $message->hash}}" />
    <meta property="og:image" content="{{$message->image_path}}" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{getenv('APP_NAME')}}" />
    <meta name="twitter:description" content="このサイトは{{getenv('APP_NAME')}}です。" />
    <meta name="twitter:image" content="{{$message->image_path}}" />
@endsection

@section('css')
    <link rel="stylesheet" href="{{assets('css/message.css')}}">
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/nvibar.css')}}">
    <link rel="stylesheet" href="{{assets('css/userHome.css')}}">
    <script src="{{assets('js/main.vue.js')}}"></script>
    <script src="{{assets('js/vue.min.js')}}"></script>
@endsection

@section('content')
    <div class="name">{{'@' . $message->screen_name}}さんの{{getenv('APP_NAME')}}</div>
    <div class="message-image">
        @if($message->send_flog == 1)
            <div>{{$message->answer_text}}</div>
        @endif
        <img src="{{$message->image_path}}">
    </div>
    @if($loginUserExist && $message->send_flog == 0)
    <div class="answer">質問に回答しよう</div>
    <div id="answer-message">
        <form action="/post/{{$message->hash}}/answer" accept-charset="UTF-8" method="post">
            <input type="hidden" name="{{ $nameKey }}" value="{{ $name }}">
            <input type="hidden" name="{{ $valueKey }}" value="{{ $value }}">
            <textarea maxlength="100" required name="message" class="answer-message" v-model='b'></textarea>
            <div class="answer-count">@{{ 100 - b.length }}/100</div>
            <button name="type" value="image" type="submit" class="answer-button" data-disable-with="送信中">回答を送る (画像)</button>
            <button name="type" value="link" type="submit" class="answer-button" data-disable-with="送信中">回答を送る (リンク)</button>
        </form>
    </div>
    @endif

    <div class="mail-icon">
        <img width="50%" src="{{assets('img/mail_icon.png')}}">
    </div>

    <div class="question-title">匿名で質問ができます</div>
    <div class="question-title-sub">※この質問は匿名で送信されます</div>
    <form action="/{{$message->screen_name}}/message" accept-charset="UTF-8" method="post">
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
        <div class="nvi-bar-icon"><a href="/"><img src="{{assets('img/home_off.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/message"><img src="{{assets('img/message_off.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/option"><img src="{{assets('img/setting_off.png')}}" class="nvi-bar-icon-img"></a></div>
    </div>
@endsection