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
    <div style="width: 100%; text-align: center">
        <div style="margin-bottom: 50px">
            <h1 style="font-size: 32px">Բար գալուստ</h1>
        </div>
        <div>
            {!! $data['text'] ?? ''!!}
        </div>
        @if(isset($data['link']))
            <div style="margin-top: 30px">
                <a style="
                    padding: 8px 30px;
                    background-color: #E2A942;
                    color: #ffffff;
                     border: 1px solid #E2A942;
                     border-radius: 5px;
                    text-decoration: none"
                    href="{{$data['link']}}"
                >
                    Տեսնել ավելին
                </a>
            </div>
        @endif
    </div>
    <br>
    <div>
        @if($files)
            @foreach($files as $file)
                ֆայլ - <a href="{{env('app_url').$file[1]}}" target="_blank" download>{{$file[0]}}</a> <br> <br>
            @endforeach
        @endif
    </div>
</body>
</html>
