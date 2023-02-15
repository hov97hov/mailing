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
            <a href="{{$data['imgLink']}}">
                <img style="width: 100%" src="https://ymail.yerevak.am{{$data['image'][0][1]}}" alt="">
            </a>
        </div>
    </div>
    <br>
    <div>
        @if($files)
            @foreach($files as $file)
                ֆայլ - <a target="_blank" href="https://ymail.yerevak.am{{$file[1]}}" download>{{$file[0]}}</a> <br> <br>
            @endforeach
        @endif
    </div>
    <br>
    <br>
    <div>
        @if($data['mailingImg'] != 'null')
            <a href="{{$data['bottom_img_link']}}" target="_blank">
                <img src="{{$data['mailingImg']}}">
            </a>
        @endif
    </div>
</body>
</html>
