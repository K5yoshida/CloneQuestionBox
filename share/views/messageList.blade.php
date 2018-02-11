@extends('layout.Main')

@section('title',  'メッセージリスト')

@section('css')
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/nvibar.css')}}">
    <link rel="stylesheet" href="{{assets('css/messageList.css')}}">
@endsection

@section('content')
    <div class="title">あなたに届いたメッセージ<br/></div>
    <div class="message-type-area">
        <div class="message-margin"></div>
        @if($flog === 0)
            <div class="message-type-no-answer message-type"><a href="/user/message">未解答</a></div>
            <div class="message-type-answer message-type"><a href="/user/message/answer">解答済み</a></div>
        @elseif($flog === 1)
            <div class="message-type-answer message-type"><a href="/user/message">未解答</a></div>
            <div class="message-type-no-answer message-type"><a href="/user/message/answer">解答済み</a></div>
        @endif

    </div>
    @foreach($messageList as $message)
        <div class="message-area">
            @if(timeExist($message->created) === true)
                <div class="message-time">{{convertToFuzzyTime($message->created)}}</div>
            @elseif(timeExist($message->created) === false)
                <div class="message-time-over">{{convertToFuzzyTime($message->created)}}</div>
            @endif
            <a class="message-text" href="{{"/post/$message->hash"}}">{{$message->message_text}}</a>
            <div class="message-util"></div>
        </div>
    @endforeach
@endsection

@section('nvi')
    <div class="nvi-bar-fixed">
        <div class="nvi-bar-icon"><a href="/"><img src="{{assets('img/home_off.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/message"><img src="{{assets('img/message_on.png')}}" class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/option"><img src="{{assets('img/setting_off.png')}}" class="nvi-bar-icon-img"></a></div>
    </div>
@endsection