
<!DOCTYPE html>

<html>
    <head>
        <title>Hardwires</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
   .bar-area{
        height: 388px;
        background: transparent!important;
        width: 31px;
        position: relative!important;
        vertical-align:bottom!important;
        /* margin-bottom:54px!important; */
   }
   .bar{
        background:#558ed5!important;
        width:8px!important;
        max-width:8px!important;
   }
   .centers{
       /* text-align:center!important; */
    }
    .maintr{
        position:relative!important;
    }
   .main{
       position:relative!important;
       margin-left:94px;
    }
    .holder{
        /* background:purple; */
    }
    .inner-holder{
        position:absolute!important;
        bottom:0!important;
        margin-bottom: -52px!important;
    }
    .bar span{
        position: absolute;color: black;border-radius: 50%;font-size: 14px;padding: 2px 4px;right:-4px;top: -25px;font-weight: bold;text-shadow: 2px 0px #c5c3c3;
    }
</style>
</head>
<body style=" padding-bottom: 74px;">
    <div style="width:100%">
        <div style="margin: 30px auto; width: 608px;">
            <div>
                <table class="inner-holder" style="">
                    <tr>
                        <td width="200px"></td>
                        <td style="text-align:center!important;"><img width="200px" src="{{ public_path('g_logo.png') }}"></td>
                        <td></td>
                    </tr>
                </table>
                <br><br>
                <hr style="border-top: 2px solid rgb(195 189 189 / 10%);">
            </div>
            <div style="text-align: center; font-size: 15px;color: #777;">#{{ $ref }}</div>
            <br><br>
        <div>
        <div class="vl_hr_rel" style="width:100%; height: 487px; background-size: cover; margin-bottom: 30px; background-image: url({{ public_path('g_chart.png') }}); padding-left:7px;">
            <div class="holder centers">
                <table class="main centers">
                    <tr class="maintr centers">
                        <td class="bar-area">
                            <table class="inner-holder" style="">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[0] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[0] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area">
                            <table class="inner-holder" style="">
                               <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[1] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[1] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[2] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[2] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[3] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[3] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[4] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[4] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[5] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[5] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[6] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[6] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[7] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[7] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[8] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[8] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[9] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[9] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[10] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[10] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[11] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[11] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[12] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[12] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[13] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[13] }}px;"></td></tr>
                            </table>
                        </td>
                        <td class="bar-area" style="">
                            <table class="inner-holder">
                                <tr><td style="margin:0px;padding:0px;font-size:7px;"><span>{{ $scores[14] }}</span></td></tr>
                                <tr><td class="bar" style="height:{{ $heights[14] }}px;"></td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="text-align: center;font-size: 13px;color: #969494;">
            Hardwires block you from optimum human!Rewire asap!
        </div>
    </div>
    <div style="margin: 30px auto; width: 608px;">
        <p style="font-weight: bold;margin-top: 30px;margin-bottom: 50px;">Please see table below for HARDWIRES icons, abbreviations and definitions. Familiarise yourself with these and have the POSTER ready and open for the coaching session. This is a prerequisite to the coaching session. Ebooks are in the process of being made available online for downloading and as soon as this process is up and running you will receive an introductory offer to order.</p>
    </div>
</body>
</html>