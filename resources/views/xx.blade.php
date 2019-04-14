<!DOCTYPE html>
<html>
<head>
	<title>
		xx
	</title>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
	     $(document).ready(function(){
      var i=1;
     $("#add_row").click(function(){
      $('#addr'+i).html("<td>"+ (i+1) +"</td><td><input name='name"+i+"' type='text' placeholder='Name' class='form-control input-md'  /> </td><td><input  name='mail"+i+"' type='text' placeholder='Mail'  class='form-control input-md'></td><td><input  name='mobile"+i+"' type='text' placeholder='Mobile'  class='form-control input-md'></td>");

      $('#dev-table').append('<tr id="addr'+(i+1)+'"></tr>');
      i++; 
  });
     $("#delete_row").click(function(){
       if(i>1){
     $("#addr"+(i-1)).html('');
     i--;
     }
   });

});
</script>
</head>
<style type="text/css">
	    	.row{
		    margin-top:40px;
		    padding: 0 10px;
		}
		.clickable{
		    cursor: pointer;   
		}

		.panel-heading div {
			margin-top: -18px;
			font-size: 15px;
		}
		.panel-heading div span{
			margin-left:5px;
		}
		.panel-body{
			display: none;
		}
</style>
<body>
<div class="container">
   
    	<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary" style="width: 1000px;">
					<div class="panel-heading" style="width: 1000px;">
						<h3 class="panel-title">Developers</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
								<i class="glyphicon glyphicon-filter"></i>
							</span>
							<span class="clickable filter1" data-toggle="tooltip" title="Toggle table filter" data-container="body">
								<i class="glyphicon glyphicon-plus" id="add_row" ></i>
							</span>
						</div>
					</div>
					<div class="panel-body">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
					</div>
					<table class="table table-hover" id="dev-table" style="width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Username</th>
								<th>#</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Username</th>
							</tr>
						</thead>
						<tbody>
							<tr id='addr0'>
								<td>
								1
								</td>
								<td>
								<input type="text" name='name0'  placeholder='Name' class="form-control"/>
								</td>
								<td>
								<input type="text" name='mail0' placeholder='Mail' class="form-control"/>
								</td>
								<td>
								<input type="text" name='mobile0' placeholder='Mobile' class="form-control"/>
								<td>
								1
								</td>
								<td>
								<input type="text" name='name0'  placeholder='Name' class="form-control"/>
								</td>
								<td>
								<input type="text" name='mail0' placeholder='Mail' class="form-control"/>
								</td>
								<td>
								<input type="text" name='mobile0' placeholder='Mobile' class="form-control"/>
						</td>
					        </tr>
                    
							<tr>
								<td>2</td>
								<td>Bob</td>
								<td>Loblaw</td>
								<td>boblahblah</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Holden</td>
								<td>Caulfield</td>
								<td>penceyreject</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<!--
		<a id="add_row" class="btn btn-default pull-left">Add Row</a><a id='delete_row' class="pull-right btn btn-default">Delete 
		Row</a>
    -->		
	</div>
<script src="/js/filter.js"></script>


</body>
</html>
