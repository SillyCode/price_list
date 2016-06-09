<div id="wrapper">
<div class="container">
  <h2>Calculator</h2>
	<form method="post" autocomplete="off">
	<div class="row">
		<div class="col-sm-2">
			<lable>Partner Discount: </label>
		</div>
		<div class="col-sm-2">
			<input type="text" name="partn_discount"/>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<lable>Local Currency: </label>
		</div>
		<div class="col-sm-2">
			<input type="text" name="local_currency"/>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<lable>Import Levies: </label>
		</div>
		<div class="col-sm-2">
			<input type="text" name="import_levies"/>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<lable>Extended Warranty: </label>
		</div>
		<div class="col-sm-2">
			<select name="warranty">
				{loop warranties}
				<option value={pn}>{title}</option>
				{/loop warranties}
			</select>
		</div>
	</div>
	<div class="table-responsive">
	  <table class="table table-hover table_calculator">
	  <thead>
      <tr>
		<td>Part Number</td>
		<td>Desrcription</td>
		<td>MSRP Unit Price ($)</td>
		<td>Qty</td>
		<td>MSRP Line Total ($)</td>
		<td>Partner Price ($)</td>
		<td>Partner Line Total ($)</td>
		<td>Line Total in Local Currency</td>
      </tr>
      </thead>
	  </table>
	  <button type="button" class="btn btn-primary btn-lg">Create Quote</button>
	  <button type="button" class="btn btn-default btn-lg">Clear All</button>
	</form>
  </div>
</div>
</div>