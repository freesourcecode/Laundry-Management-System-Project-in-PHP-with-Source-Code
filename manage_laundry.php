<?php
include "db_connect.php";

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM laundry_list where id =".$_GET['id']);
	foreach($qry->fetch_array() as $k => $v){
		$$k = $v;
	}

}
?>

<div class="container-fluid">
	<form action="" id="manage-laundry">
		<div class="col-lg-12">	
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">	
			<div class="row">
				<div class="col-md-6">	
					<div class="form-group">	
						<label for="" class="control-label">Customer Name</label>
						<input type="text" class="form-control" name="customer_name" value="<?php echo isset($customer_name) ? $customer_name : '' ?>">
					</div>
				</div>
				<?php if(isset($_GET['id'])): ?>
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Status</label>
						<select name="status" id="" class="custom-select browser-default">
							<option value="0" <?php echo $status == 0 ? "selected" : '' ?>>Pending</option>
							<option value="1" <?php echo $status == 1 ? "selected" : '' ?>>Processing</option>
							<option value="2" <?php echo $status == 2 ? "selected" : '' ?>>Ready to be Claim</option>
							<option value="3" <?php echo $status == 3 ? "selected" : '' ?>>Claimed</option>
						</select>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label class="control-label">Remarks</label>
					<textarea name="remarks" id="" cols="30" rows="2" class="form-control"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
				</div>
			</div>
			<hr>	
			<div class="row">	
				<div class="col-md-4">	
					<div class="form-group">	
						<label for="" class="control-label">Laundry Category</label>
						<select class="custom-select browser-default" id="laundry_category_id">
							<?php 
								$cat = $conn->query("SELECT * FROM laundry_categories order by name asc");
								while($row= $cat->fetch_assoc()):
									$cname_arr[$row['id']] = $row['name'];
							?>
							<option value="<?php echo $row['id'] ?>" data-price="<?php echo $row['price'] ?>"><?php echo $row['name'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>
				</div>
				<div class="col-md-4">	
					<div class="form-group">	
						<label for="" class="control-label">Weight</label>
						<input type="number" step="any" min="1" value="1" class="form-control text-right" id="weight">
					</div>
				</div>
				<div class="col-md-4">	
					<div class="form-group">	
						<label for="" class="control-label">&nbsp;</label>
						<button class="btn btn-info btn-sm btn-block" type="button" id="add_to_list"><i class="fa fa-plus"></i> Add to List</button>
					</div>
				</div>
			</div>
			<div class="row">	
				<table class="table table-bordered" id="list">
					<colgroup>	
						<col width="30%">
						<col width="15%">
						<col width="25%">
						<col width="25%">
						<col width="5%">
					</colgroup>	
					<thead>	
						<tr>
							<th class="text-center">Category</th>
							<th class="text-center">Weight(kg)</th>
							<th class="text-center">Unit Price</th>
							<th class="text-center">Amount</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($_GET['id'])): ?>
						<?php 
							$list = $conn->query("SELECT * from laundry_items where laundry_id = ".$id);
							while($row=$list->fetch_assoc()):
						?>
							<tr data-id="<?php echo $row['id'] ?>">
								<td class="">
									<input type="hidden" name="item_id[]" id="" value="<?php echo $row['id'] ?>">
									<input type="hidden" name="laundry_category_id[]" id="" value="<?php echo $row['laundry_category_id'] ?>"><?php echo isset($cname_arr[$row['laundry_category_id']]) ? ucwords($cname_arr[$row['laundry_category_id']]) : '' ?></td>
								<td><input type="number" class="text-center" name="weight[]" id="" value="<?php echo $row['weight'] ?>"></td>
								<td class="text-right"><input type="hidden" name="unit_price[]" id="" value="<?php echo $row['unit_price'] ?>"><?php echo number_format($row['unit_price'],2) ?></td>
								<td class="text-right"><input type="hidden" name="amount[]" id="" value="<?php echo $row['amount'] ?>"><p><?php echo number_format($row['amount'],2) ?></p></td>
								<td><button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>
							</tr>
						<?php endwhile; ?>
						<?php endif; ?>

					</tbody>	
					<tfoot>
						<tr>
							<th class="text-right" colspan="3"></th>
							<th class="text-right" id="tamount"></th>
							<th class="text-right"></th>
						</tr>
					</tfoot>
				</table>
			</div>	
			<hr>
			<div class="row">
				<div class="form-group">
					<div class="custom-control custom-switch" id="pay-switch">
					  <input type="checkbox" class="custom-control-input" value="1" name="pay" id="paid" <?php echo isset($pay_status) && $pay_status == 1 ? 'checked' :'' ?>>
					  <label class="custom-control-label" for="paid">Pay</label>
					</div>
				</div>
			</div>
			<div class="row" id="payment">
				<div class="col-md-6">
					<div class="form-group">	
						<label for="" class="control-label">Amount Tendered</label>
						<input type="number" step="any" min="0" value="<?php echo isset($amount_tendered) ? $amount_tendered : 0 ?>" class="form-control text-right" name="tendered">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">	
						<label for="" class="control-label">Total Amount</label>
						<input type="number" step="any" min="1" value="<?php echo isset($total_amount) ? $total_amount : 0 ?>" class="form-control text-right" name="tamount" readonly="">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">	
						<label for="" class="control-label">Change</label>
						<input type="number" step="any" min="1" value="<?php echo isset($amount_change) ? $amount_change : 0 ?>" class="form-control text-right" name="change" readonly="">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	if('<?php echo isset($_GET['id']) ?>' == 1){
			calc()
		}
	if($('[name="pay"]').prop('checked') == true){
			$('[name="tendered"]').attr('required',true)
			$('#payment').show();
		}else{
			$('#payment').hide();
			$('[name="tendered"]').attr('required',false)
		}	
	$('#pay-switch').click(function(){
		if($('[name="pay"]').prop('checked') == true){
			$('[name="tendered"]').attr('required',true)
			$('#payment').show('slideDown');
		}else{
			$('#payment').hide('SlideUp');
			$('[name="tendered"]').attr('required',false)
		}	
	})
	$('[name="tendered"],[name="tamount"]').on('keypup keydown keypress change input',function(){
		var tend = $('[name="tendered"]').val();
		var amount = $('[name="tamount"]').val();
		var change = parseFloat(tend) - parseFloat(amount)
		change = parseFloat(change).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2})
		$('[name="change"]').val(change)
	})
	$('#add_to_list').click(function(){
		var cat = $('#laundry_category_id').val(),
			_weight = $('#weight').val();
		if(cat == '' || _weight ==''){
			alert_toast('Fill the category and weight fields first.','warning')
			return false;
		}
		if($('#list tr[data-id="'+cat+'"]').length > 0){
			alert_toast('Category already exist.','warning')
			return false;
		}
		var price = $('#laundry_category_id option[value="'+cat+'"]').attr('data-price');
		var cname = $('#laundry_category_id option[value="'+cat+'"]').html();
		var amount = parseFloat(price) * parseFloat(_weight);
		var tr = $('<tr></tr>');
		tr.attr('data-id',cat)
		tr.append('<input type="hidden" name="item_id[]" id="" value=""><td class=""><input type="hidden" name="laundry_category_id[]" id="" value="'+cat+'">'+cname+'</td>')
		tr.append('<td><input type="number" class="text-center" name="weight[]" id="" value="'+_weight+'"></td>')
		tr.append('<td class="text-right"><input type="hidden" name="unit_price[]" id="" value="'+price+'">'+(parseFloat(price).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2}))+'</td>')
		tr.append('<td class="text-right"><input type="hidden" name="amount[]" id="" value="'+amount+'"><p>'+(parseFloat(amount).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2}))+'</p></td>')
		tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>')
		$('#list tbody').append(tr)
		calc()
		$('[name="weight[]"]').on('keyup keydown keypress change',function(){
			calc();
		})
			$('[name="tendered"]').trigger('keypress')
		
		$('#laundry_category_id').val('')
		$('#weight').val('')
	})
	function rem_list(_this){
		_this.closest('tr').remove()
		calc()
			$('[name="tendered"]').trigger('keypress')


	}
	function calc(){
		var total = 0;
		$('#list tbody tr').each(function(){
			var _this = $(this)
			var weight = _this.find('[name="weight[]"]').val()
			var unit_price = _this.find('[name="unit_price[]"]').val()
			var amount = parseFloat(weight) * parseFloat(unit_price)
			_this.find('[name="amount[]"]').val(amount)
			_this.find('[name="amount[]"]').siblings('p').html(parseFloat(amount).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2}))
			total+= amount;

		})
			$('[name="tamount"]').val(total)
			$('#tamount').html(parseFloat(total).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2,minimumFractionDigits:2}))


	}
	$('#manage-laundry').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_laundry',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})

</script>	