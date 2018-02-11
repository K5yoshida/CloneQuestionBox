@extends('layout.Main')

@section('title',  '設定画面')

@section('css')
    <link rel="stylesheet" href="{{assets('css/home.css')}}">
    <link rel="stylesheet" href="{{assets('css/option.css')}}">
    <link rel="stylesheet" href="{{assets('css/nvibar.css')}}">
    <link rel="stylesheet" href="{{assets('css/switch-button.css')}}">
@endsection

@section('content')
    <div class="user-name">{{'@' . $userInfo->screen_name}}さんの設定画面</div>
    <div class="logout-button"><a href="/auth/twitter/logout">ログアウト</a></div>
    <div class="setting-area">
        <form action="/user/option/post" method="post">
            <input type="hidden" name="{{ $nameKey }}" value="{{ $name }}">
            <input type="hidden" name="{{ $valueKey }}" value="{{ $value }}">
            <div class="setting-label">表示名</div>
            <input class="setting-name-text" type="text" name="user_name" value="{{$userInfo->username}}"
                   maxlength="45">
            <div class="setting-label">メールアドレスの設定</div>
            <input class="setting-name-text" type="email" name="email" maxlength="45" placeholder=" 例）abc@docomo.ne.jp">
            <div class="setting-switch-area">
                <div class="setting-switch-label">メール通知の送信</div>
                <div class="switchArea">
                    @if($userInfo->notification_flog == 1)
                        <input type="checkbox" id="switch1" name="notification" value="on" checked>
                    @else
                        <input type="checkbox" id="switch1" name="notification" value="on">
                    @endif
                    <label for="switch1"><span></span></label>
                    <div id="swImg"></div>
                </div>
            </div>
            <button name="type" value="image" type="submit" class="setting-button" data-disable-with="送信中">決定</button>
        </form>
    </div>
    <div class="space"></div>
@endsection

@section('nvi')
    <div class="nvi-bar-fixed">
        <div class="nvi-bar-icon"><a href="/"><img src="{{assets('img/home_off.png')}}" class="nvi-bar-icon-img"></a>
        </div>
        <div class="nvi-bar-icon"><a href="/user/message"><img src="{{assets('img/message_off.png')}}"
                                                               class="nvi-bar-icon-img"></a></div>
        <div class="nvi-bar-icon"><a href="/user/option"><img src="{{assets('img/setting_on.png')}}"
                                                              class="nvi-bar-icon-img"></a></div>
    </div>
@endsection