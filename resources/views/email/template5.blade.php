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
            <img style="width: 100%" src="{{asset('/images/template/5.jpg')}}">
        </div>
        <div style="margin: 20px 0">
            {!! $data['text'] ?? ''!!}
        </div>
        <div style="width: 100%; text-align: center;">
            @if(isset($data['link']))
                <div>

                    <a style="
                    color: #ffffff;
                    border: 0;
                    text-decoration: none;
                    font-size: 16px;
                    padding: 5px 10px;
                    height: 30px;
                    margin: 27% auto;
                    background: #18335E;
                    text-align: center;
                     "
                     href="{{$data['link']}}"
                    >
                        Տեսնել ավելին ->
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
