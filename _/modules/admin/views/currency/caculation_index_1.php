<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>Caculation</h1>
            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <div id="content">
                <a href="#"><div title="Go to top" id="jump"></div></a>


                <div class="title"><span>CALCULATION &gt; EXECUTION</span></div>
                <div id="add-page">
                    <div id="container">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="right"><input type="hidden" id="step" value="0" />
                                    Times loops : </td>
                                <td><?php if ($setting['conts'] == 1) echo $setting['frequencys'] . ' seconds'; else echo 'No loop'; ?> </td>
                            </tr>
                        </table>

                        <label id="tientrinh"></label>

                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script language="javascript">
    var solan = 0;
    var seconds = <?php echo $setting['frequencys'] * 1000 ?>;
<?php
if ($setting['conts'] == 1)
    $lap = $setting['frequencys'];
else
    $lap = 1;
?>
    var lap = <?php echo $lap ?>;
    var txt = "";
    var record = 0;
    var m =1;
    var ms = 1;

    var cur = new Date();
    var seconds_cur = cur.valueOf();

    var temp = Math.floor(seconds_cur / seconds);
    var i = 0;

    while(i < lap)
    {

        var cur2 = new Date();
        var seconds_cur2 = cur2.valueOf();

        var temp2 = Math.floor(seconds_cur2 / seconds);
        if(temp2 >= temp+1)
        {
            i++;

            var h2 = cur2.getHours();
            var m2 = cur2.getMinutes();
            var s2 = cur2.getSeconds();
            var ms2 = cur2.getMilliseconds();
            TinhIndex(h2,m2,s2);
            temp = temp2;
        }
    }


    function TinhIndex(h2,m2,s2)
    {
        var Date1 		= new Date();
        var d1 			= Date1.valueOf();
        var year 		= Date1.getFullYear();
        var month 		= Date1.getMonth()+1;
        var day 		= Date1.getDate();
        var hour 		= Date1.getHours();
        var minute 		= Date1.getMinutes();
        var second 		= Date1.getSeconds();
        var milisecond  = Date1.getMilliseconds();

        solan++;
        txt = txt + 'Loops : '+solan+' |  '+day+'/'+month+'/'+year+' Start '+hour+':'+minute+':'+second+':'+milisecond;

        Calcul_index("calculs",h2,m2,s2);


        var Date2 		= new Date();
        var hour2 		= Date2.getHours();
        var minute2 	= Date2.getMinutes();
        var second2 	= Date2.getSeconds();
        var milisecond2 = Date2.getMilliseconds();
        var d2 = Date2.valueOf();
        var d3 = parseInt(d2) - parseInt(d1);
        txt = txt+' - End '+hour2+':'+minute2+':'+second2+':'+milisecond2+' -  '+ d3 +'ms '+'<br> ';
        document.getElementById("tientrinh").innerHTML = txt;

    }

    function Calcul_index(act,h,m,s)
    {
        $.ajax({
            url: $admin_url+'composition/'+act+'?h='+h+"&m="+m+'&s='+s,
            success:function(data){
                record = data;
                //window.history.go(-1);
            }
        });
    }


</script>