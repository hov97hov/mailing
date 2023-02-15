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
    <div style="width: 300px; text-align: center">
        <div>
            <img style="width: 100%" src="{{env('app_url').$data['image'][0][1]}}" alt="">
        </div>
        <br>
        <br>
        <a style="padding: 10px 30px; border-radius: 6px; border: 0; background: #253266; color: #ffffff; text-decoration: none" href="{{$data['btnLink']}}">
            {{$data['btnName']}}
        </a>
    </div>
    <br>
    <br>
    <div>
        @if($files)
            @foreach($files as $file)
                ֆայլ - <a href="{{env('app_url').$file[1]}}" download>{{$file[0]}}</a> <br> <br>
            @endforeach
        @endif
    </div>
    <br>
    <br>
    <div>
        @if($data['mailingImg'] != 'null')
            <img  src="{{env('app_url').$data['mailingImg']}}">
        @endif
    </div>
</body>
</html>
