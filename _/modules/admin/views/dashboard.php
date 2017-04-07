<?php
redirect(admin_url() . 'sysformat/show_view');
?>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form" id="table_form" method="post" action="">
            <h1>DashBoard</h1>
            <div class="">
                <fieldset class="grey-bg">
                    <legend><a href="#">Options</a></legend>
                    <div class="float-left gutter-right">
                        <span class="label">Display</span>
                        <p class="input-height grey-bg">
                            <input type="checkbox" name="stats-display[]" id="stats-display-0" value="0">&nbsp;<label for="stats-display-0">Views</label>
                            <input type="checkbox" name="stats-display[]" id="stats-display-1" value="1">&nbsp;<label for="stats-display-1">Unique visitors</label>
                        </p>
                    </div>
                    <div class="float-left gutter-right">
                        <span class="label">Sites</span>
                        <p class="input-height grey-bg">
                            <input type="radio" name="stats-sites" id="stats-sites-0" value="0">&nbsp;<label for="stats-sites-0">Group</label>
                            <input type="radio" name="stats-sites" id="stats-sites-1" value="1">&nbsp;<label for="stats-sites-1">Separate</label>
                        </p>
                    </div>
                    <div class="float-left">
                        <span class="label">Mode</span>
                        <select name="stats-sites" id="stats-sites-0">
                            <option value="0">Bars</option>
                            <option value="0">Lines</option>
                        </select>
                    </div>
                </fieldset>
                <div id="chart_div" style="height:330px;"></div>
            </div>
        </form>
    </div>
</section>
<script src="http://www.google.com/jsapi"></script>
<script>

    /*
     * This script is dedicated to building and refreshing the demo chart
     * Remove if not needed
     */

    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});


    // Handle viewport resizing
    var previousWidth = $(window).width();
    $(window).resize(function()
    {
        if (previousWidth != $(window).width())
        {
            drawVisitorsChart();
            previousWidth = $(window).width();
        }
    });

    // Demo chart
    function drawVisitorsChart() {

        // Create our data table.
        var data = new google.visualization.DataTable();
        var raw_data = [['Website', 50, 73, 104, 129, 146, 176, 139, 149, 218, 194, 96, 53],
            ['Shop', 82, 77, 98, 94, 105, 81, 104, 104, 92, 83, 107, 91],
            ['Forum', 50, 39, 39, 41, 47, 49, 59, 59, 52, 64, 59, 51],
            ['Others', 45, 35, 35, 39, 53, 76, 56, 59, 48, 40, 48, 21]];

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        data.addColumn('string', 'Month');
        for (var i = 0; i < raw_data.length; ++i)
        {
            data.addColumn('number', raw_data[i][0]);
        }

        data.addRows(months.length);

        for (var j = 0; j < months.length; ++j)
        {
            data.setValue(j, 0, months[j]);
        }
        for (var i = 0; i < raw_data.length; ++i)
        {
            for (var j = 1; j < raw_data[i].length; ++j)
            {
                data.setValue(j-1, i+1, raw_data[i][j]);
            }
        }

        // Create and draw the visualization.
        var div = $('#chart_div');
        new google.visualization.ColumnChart(div.get(0)).draw(data, {
            title: 'Monthly unique visitors count',
            width: div.width(),
            height: 330,
            legend: 'right',
            yAxis: {title: '(thousands)'}
        });
    };
    $(function(){
        drawVisitorsChart();
    });
</script>