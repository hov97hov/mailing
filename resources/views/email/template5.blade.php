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
    <div style="min-width: 320px">
        
         <div style="text-align: center">
        <div style="margin: 30px 0">
            {!! $data['text'] !!}
        </div>
        <br>
        <br>
        <a target="_blank" style="padding: 10px 30px; border-radius: 6px; border: 0; background: #253266; color: #ffffff; text-decoration: none" href="{{$data['btnLink']}}">
            {{$data['btnName']}}
        </a>
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
            <a target="_blank" href="{{$data['bottom_img_link']}}">
                <img src="https://ymail.yerevak.am/images/mailing.png">
            </a>
        @endif
    </div>
        
    </div>
   
</body>
</html>
