<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div style="width: 100%">
        <div>
            @if($files)
                @foreach($files as $file)
                    ներբեռնել ֆայլ - <a href="{{asset($file[1])}}" target="_blank" download>{{$file[0]}}</a> <br> <br>
                @endforeach
            @endif
        </div>
        <div style="width: 100%">
            <img style="width: 100%" src="{{asset('/images/template/7.jpg')}}">
        </div>
        <div style="margin: 20px 0">
            {!! $data['text'] ?? ''!!}
        </div>
        <div style="width: 100%; position: relative">
            @if(isset($data['link']))
                <div style="
                background-image: url({{asset('/images/template/8.jpg')}});
                background-size: 100%;
                background-repeat: no-repeat;
                height: 293px;
                width: 100%;
                display: flex;
                ">

                    <a style="
                    color: #ffffff;
                    border-bottom: 1px solid #ffffff;
                    text-decoration: none;
                    font-size: 16px;
                    text-align: center;
                    height: 30px;
                    margin: 18% auto;
                    padding-top: 5px;
                     "
                     href="{{$data['link']}}"
                    >
                        Անցնել հղմամբ ->
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
