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
            <img style="width: 100%" src="{{asset('/images/template/6.jpg')}}">
        </div>
        <div style="margin: 20px 0">
            {!! $data['text'] ?? ''!!}
        </div>
    </div>
</body>
</html>
