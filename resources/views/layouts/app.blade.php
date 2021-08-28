<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{ Config::get('app.name') }}</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
  <!-- MDB data tables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-components-web/4.0.0/material-components-web.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.material.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
.mdc-data-table {
    border-width: 1px!important;
    width: 100%!important;
}
div.mdc-layout-grid {
  min-width: 800px;
}
    .map-container{
overflow:hidden;
padding-bottom:56.25%;
position:relative;
height:0;
}
.map-container iframe{
left:0;
top:0;
height:100%;
width:100%;
position:absolute;
}
.form-control,.btn {
  border-radius: 1.25rem!important;
}
.badge-pill {
  padding-right: 0.9em!important;
  padding-left: .9em!important;
  font-size:16px!important;
}
 .text-lef{
   float:left!important;
 }
  table.table td{
    padding-top: .2rem!important;
    padding-bottom: .2rem!important;
  }
  .table td{
    vertical-align: middle!important;
  }
  </style>
 @yield('css')
</head>

<body class="grey lighten-3">
<!-- PAYFAST -->
<script src="https://www.payfast.co.za/onsite/engine.js"></script>
  <!--Main Navigation-->
  <header>

    <!-- Navbar -->
    @yield('nav')
    <!-- Navbar -->

    <!-- Sidebar -->
    @yield('side')
    <!-- Sidebar -->

  </header>
  <!--Main Navigation-->

  <!--Main layout-->
  <main class="pt-5 mx-lg-5">
    @yield('content')
  </main>
  <!--Main layout-->

  <!--Footer-->
  @yield('foot')
  <!--/.Footer-->

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="{{ asset('js/mdb.min.js') }}"></script>
  <!-- loading -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

  <!-- MDB data tables -->
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.material.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



  <!-- Initializations -->

  <script>
  $(document).ready(function() {
    $('.reportables').DataTable( {
        autoWidth: false,
        columnDefs: [
            {
                targets: ['_all'],
                className: 'mdc-data-table__cell'
            }
        ]
    } );
  });
  </script>

  <script type="text/javascript">
    // Animations initialization
    new WOW().init();

  </script>

  <!-- Charts -->
  <script>
    // Line
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });

    //pie
    var ctxP = document.getElementById("pieChart").getContext('2d');
    var myPieChart = new Chart(ctxP, {
      type: 'pie',
      data: {
        labels: ["Red", "Green", "Yellow", "Grey", "Dark Grey"],
        datasets: [{
          data: [300, 50, 100, 40, 120],
          backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
          hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
        }]
      },
      options: {
        responsive: true,
        legend: false
      }
    });


    //line
    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
      type: 'line',
      data: {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
            label: "My First dataset",
            backgroundColor: [
              'rgba(105, 0, 132, .2)',
            ],
            borderColor: [
              'rgba(200, 99, 132, .7)',
            ],
            borderWidth: 2,
            data: [65, 59, 80, 81, 56, 55, 40]
          },
          {
            label: "My Second dataset",
            backgroundColor: [
              'rgba(0, 137, 132, .2)',
            ],
            borderColor: [
              'rgba(0, 10, 130, .7)',
            ],
            data: [28, 48, 40, 19, 86, 27, 90]
          }
        ]
      },
      options: {
        responsive: true
      }
    });


    //radar
    var ctxR = document.getElementById("radarChart").getContext('2d');
    var myRadarChart = new Chart(ctxR, {
      type: 'radar',
      data: {
        labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
        datasets: [{
          label: "My First dataset",
          data: [65, 59, 90, 81, 56, 55, 40],
          backgroundColor: [
            'rgba(105, 0, 132, .2)',
          ],
          borderColor: [
            'rgba(200, 99, 132, .7)',
          ],
          borderWidth: 2
        }, {
          label: "My Second dataset",
          data: [28, 48, 40, 19, 96, 27, 100],
          backgroundColor: [
            'rgba(0, 250, 220, .2)',
          ],
          borderColor: [
            'rgba(0, 213, 132, .7)',
          ],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true
      }
    });

    //doughnut
    var ctxD = document.getElementById("doughnutChart").getContext('2d');
    var myLineChart = new Chart(ctxD, {
      type: 'doughnut',
      data: {
        labels: ["Red", "Green", "Yellow", "Grey", "Dark Grey"],
        datasets: [{
          data: [300, 50, 100, 40, 120],
          backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
          hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
        }]
      },
      options: {
        responsive: true
      }
    });

  </script>

  <!--Google Maps-->
  <script src="https://maps.google.com/maps/api/js"></script>
  <script>
    // Regular map
    function regular_map() {
      var var_location = new google.maps.LatLng(40.725118, -73.997699);

      var var_mapoptions = {
        center: var_location,
        zoom: 14
      };

      var var_map = new google.maps.Map(document.getElementById("map-container"),
        var_mapoptions);

      var var_marker = new google.maps.Marker({
        position: var_location,
        map: var_map,
        title: "New York"
      });
    }

    new Chart(document.getElementById("horizontalBar"), {
      "type": "horizontalBar",
      "data": {
        "labels": ["Red", "Orange", "Yellow", "Green", "Blue", "Purple", "Grey"],
        "datasets": [{
          "label": "My First Dataset",
          "data": [22, 33, 55, 12, 86, 23, 14],
          "fill": false,
          "backgroundColor": ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"
          ],
          "borderColor": ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)",
            "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)",
            "rgb(201, 203, 207)"
          ],
          "borderWidth": 1
        }]
      },
      "options": {
        "scales": {
          "xAxes": [{
            "ticks": {
              "beginAtZero": true
            }
          }]
        }
      }
    });
  </script>

  <script>
  $(document).ready(function() {
	var max_fields = 6; 
	var wrapper = $("#input_fields_wrap"); 
	var add_button = $("#add_field_button");
	
	var x = 1;
	$(add_button).click(function(e){ 
		e.preventDefault();
		if(x < max_fields){ 
			x++; 
			$(wrapper).append('<div class="row mb-1"><div class="col-md-7"> <input type="text" placeholder="label" class="form-control ml-1" name="option[]" value=""></div><div class="col-md-4"> <input type="number" placeholder="value" class="form-control ml-1" name="value[]" value=""></div><div class="col-md-1"> <a href="#" class="remove_field"><i class="fas fa-times"></i></a></div></div>'); //add input box
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).closest('.row').remove(); x--;
	})
});
  </script>

<script>
  $(document).ready(function() {
    var $table = $('.tbodyscroll'),
    $bodyCells = $table.find('tbody tr:first').children(),colWidth;
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();

    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
      $(v).width(colWidth[i]);
    });
    
    $table.find('tfoot tr').children().each(function(i, v) {
      $(v).width(colWidth[i]);
    });
  });
  </script>

<script>
	$(document).ready(function() {
		initCustomer = function(formId){
			$.LoadingOverlay('show');
			var postFormData = $("form[name=" + formId + "]").serialize();
			$.ajax({
				type: "POST",
				url: "{{ route('init_customer') }}",
				data: postFormData,
				success: function( response ){
					$.LoadingOverlay('hide');
          if ( response.hasOwnProperty('error') ){
            $('#frm_err').text(response.message);
            $('#frm_err').show();
          }else{
            $('#code_suc').text(response.message);
            $('#sectionUserForm').hide();
            $('#code_suc').show();
            $('#codeSectionForm').show();
          }
				}
			});
		}
    verifyEmail = function(formId){
      $('#code_suc').hide();
			$.LoadingOverlay('show');
			var postFormData = $("form[name=" + formId + "]").serialize();
			$.ajax({
				type: "POST",
				url: "{{ route('verifyEmail') }}",
				data: postFormData,
				success: function( response ){
					$.LoadingOverlay('hide');
          if ( response.hasOwnProperty('error') ){
            $('#code_err').text(response.message);
            $('#code_err').show();
          }else{
            $('#successVerifyModal').modal('show');
          }
				}
			});
		}
	});

</script>

 <!-- success modal  -->
 <div class="modal fade" id="successVerifyModal" tabindex="-1" role="dialog" aria-labelledby="successVerifyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successVerifyModalLabel">Email verification successfull</h5>
        <button type="button" class="close" data-dismiss="mokkdal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Email verified successfully. Click on button below to proceed.</p>
      </div>
      <div class="modal-footer">
			<div id="formButtons">
			<form action="{{ route('create_customer') }}" method="post">
        <button type="submit" class="btn btn-primary">Proceed</button>
      </form>
			</div>
      </div>
    </div>
  </div>
</div>
<!-- end modal -->
</body>

</html>
