<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Di Setujui',     11],
            ['Di Survey',      2],
            ['Di Tolak',  2],
        ]);

        var options = {
            title: 'PERMOHONANYANG MASUK',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }
</script>

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $page_title?></h2>
		</div>
		<div class="row clearfix">
        </div>
		<div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card animated zoomIn">
                    <div id="piechart_3d" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		        <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="info-box-3 bg-orange">
                            <div class="icon">
                                <div class="chart chart-bar"><canvas width="44" height="34" style="display: inline-block; width: 44px; height: 34px; vertical-align: top;"></canvas></div>
                            </div>
                            <div class="content">
                                <div class="text">JUMLAH PERMOHONAN</div>
                                <div class="number"><?php echo $permohonan ?></div>
                            </div>
                        </div>
                    </div>
                </div>

		        <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="info-box-3 bg-deep-orange">
                            <div class="icon">
                                <div class="chart chart-bar"><canvas width="44" height="34" style="display: inline-block; width: 44px; height: 34px; vertical-align: top;"></canvas></div>
                            </div>
                            <div class="content">
                                <div class="text">JUMLAH KELOMPOK</div>
                                <div class="number"><?php echo $kelompok ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>