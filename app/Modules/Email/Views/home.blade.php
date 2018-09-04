@include('admin.head')
<div class="container">
    @include('admin.menu')
    <div id="wrap">
	<div class="row center">
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar','corechart'], 'callback': drawCharts});
      function drawCharts(){
        drawChart();
        ban_do_nguon();
        email_theo_ngay();
      }

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Số', 'Email'],
          ['{{config("filesystems.status")[1]}}', {{ $type[1] }}],
          ['{{config("filesystems.status")[2]}}', {{ $type[2] }}],
          ['{{config("filesystems.status")[3]}}', {{ $type[3] }}],
          ['{{config("filesystems.status")[4]}}', {{ $type[4] }}],
        ]);

        var options = {
          title: 'Phân loại email'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }


      function ban_do_nguon() {
        var data = google.visualization.arrayToDataTable([
          ['Số', 'Email'],
          ['{{config("filesystems.collect")[1]}}', {{ $source[1] }}],
          ['{{config("filesystems.collect")[2]}}', {{ $source[2] }}],
          ['{{config("filesystems.collect")[3]}}', {{ $source[3] }}],
        ]);

        var options = {
          title: 'Phân loại email'
        };

        var chart = new google.visualization.PieChart(document.getElementById('nguon'));

        chart.draw(data, options);
      }


      function email_theo_ngay() {
        var data = google.visualization.arrayToDataTable([
        ['Ngày', 'Spamtrap','Report', 'Forward'],
        <?php ksort($days)?>
        @foreach($days as $key => $day)
          ['{{ $key }}',  {{ empty($day[1])? 0 : $day[1]}}, {{ empty($day[2])? 0 : $day[2]}}, {{ empty($day[3])? 0 : $day[3] }}],
        @endforeach
        ]);

        var options = {
        title: 'Báo cáo hằng ngày',
        curveType: 'function',
        height : 400,
        width: 800,
        legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('email_by_days'));

        chart.draw(data, options);
      }
    </script>
    <div class="row">
      <div class="col-md-12">
        <div id="email_by_days" style="width: 900px; margin: 10px auto"></div>
      </div>

      <div class="col-md-6">
        <div id="piechart" style="height: 500px;"></div>
      </div>
      <div class="col-md-6">
        <div id="nguon" style="height: 500px;"></div>
      </div>
    </div>

	</div>
     <footer>
            <p class="text-center">Copyright by VNCERT</p>
        </footer>
    </div>
</div>
@include('admin.footer')