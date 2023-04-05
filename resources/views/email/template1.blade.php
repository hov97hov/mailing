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
        <div style="margin-bottom: 40px">
            <a href="{{$data['imgLink']}}">
                <img style="max-width: 100%" src="https://ymail.yerevak.am{{$data['image'][1]}}" alt="">
            </a>
        </div>
    </div>
    <div style="margin-bottom: 40px; text-align: center">
        @if($files)
            @foreach($files as $file)
                <a
                    style="
                    font-family: 'Arial AMU';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 22px;
                    line-height: 30px;
                    text-align: center;
                    color: #392E2C;
                    padding: 5px;
                    border: 1px solid #6E6E6E;
                    border-radius: 5px;
                    display: inline-flex;
                    align-items: center;
                    text-decoration: none;
                   "
                    target="_blank" href="https://ymail.yerevak.am{{$file[1]}}" download
                >
                    <img style="margin-right: 10px" src="https://ymail.yerevak.am/images/download.png">
                    <span>Ներբեռնել ֆայլը</span>
                </a>
            @endforeach
        @endif
    </div>
    @if($data['mailingImg'] != 'null')
        <div style="width: 80%; height: 2px; float: right; background: #D9D9D9; margin-bottom: 10px"></div>
    @endif
    <div style="float: right">
        @if($data['mailingImg'] != 'null')
            <a href="{{$data['bottom_img_link']}}" style="
                padding: 5px 10px;
                background: #DDE8F8;
                border-radius: 3px;
                font-family: 'Arial AMU';
                text-decoration: none;
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 16px;
                text-align: center;
                color: rgba(37, 50, 102, 0.75);
                display: flex;
                align-items: center;
              "
               target="_blank">
                <img style="margin-right: 10px" src="https://ymail.yerevak.am/images/ok.png" alt="">
                <span style="margin-top: 10px">Ուղարկեք հաղորդագրություն 30․000 էլ․ հասցեների</span>
            </a>
        @endif
    </div>
</div>
</body>
</html>
