<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">	
			<div class="card">
				<div class="card-body">	
					<div class="row">
						<div class="col-md-12">		
							<button class="col-sm-3 float-right btn btn-primary btn-sm" type="button" id="new_laundry"><i class="fa fa-plus"></i> New Laundry</button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">		
							<table class="table table-bordered" id="laundry-list">
								<thead>
									<tr>
										<th class="text-center">Date</th>
										<th class="text-center">Queue</th>
										<th class="text-center">Customer Name</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$list = $conn->query("SELECT * FROM laundry_list order by status asc, id asc ");
									while($row=$list->fetch_assoc()):
									?>
									<tr>
										<td class=""><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
										<td class="text-right"><?php echo $row['queue'] ?></td>
										<td class=""><?php echo ucwords($row['customer_name']) ?></td>
										<?php if($row['status'] == 0): ?>
											<td class="text-center"><span class="badge badge-secondary">Pending</span></td>
										<?php elseif($row['status'] == 1): ?>
											<td class="text-center"><span class="badge badge-primary">Processing</span></td>
										<?php elseif($row['status'] == 2): ?>
											<td class="text-center"><span class="badge badge-info">Ready to be Claim</span></td>
										<?php elseif($row['status'] == 3): ?>
											<td class="text-center"><span class="badge badge-success">Claimed</span></td>
										<?php endif; ?>
										<td class="text-center">
											<button type="button" class="btn btn-outline-primary btn-sm edit_laundry" data-id="<?php echo $row['id'] ?>">Edit</button>
											<button type="button" class="btn btn-outline-danger btn-sm delete_laundry" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>	
	</div>	
</div>
<script>
	$('#new_laundry').click(function(){
		uni_modal('New Laundry','manage_laundry.php','mid-large')
	})
	$('.edit_laundry').click(function(){
		uni_modal('Edit Laundry','manage_laundry.php?id='+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_laundry').click(function(){
		_conf("Are you sre to remove this data from list?","delete_laundry",[$(this).attr('data-id')])
	})
	$('#laundry-list').dataTable()
	function delete_laundry($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_laundry',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}

</script>