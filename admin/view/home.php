<?php
$count_categories =count_categories();
$count_coments =count_coments();
$count_order =count_order();
$count_products =count_products();
$count_users =count_users();
?>
<!-- lấy từ biểu đồ -->
<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data', 'Count'],
         <?php
		 $element_text= ['Danh Mục','Bình Luận', 'Đơn Hàng','Sản Phẩm' ,'Tài Khoản'];
		 $element_count =[$count_categories,$count_coments,$count_order,$count_products,$count_users];
			for($i=0;$i< 5;$i++){
				echo "['$element_text[$i]'".",".$element_count[$i] ."],";
			}
		 ?>
        ]);

        var options = {
          chart: {
            title: 'Company Performance',
            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
	 <div class="container_bieudo">
	<div id="columnchart_material" class="columnchart_material"></div>
</div>


<style>
	.container_bieudo {
		width: 1100px;
		margin: 0 auto;
		padding: 20px;
		background: rgb(255, 255, 255);
		border-radius: 15px;
	}

	.columnchart_material {
		width: 100%;
		height: 500px;
	}
</style>