<div id="wrapper">
<div class="container">
  <h2>CompletePBX & Astribanks</h2>
  <div class="table-responsive">
  <form method="post" autocomplete="off">
  <table class="table table-hover table_cpbx_astribank">
    <thead>
      <tr>
       <th>FXS</th>
        <th>FXO</th>
        <th>E1/T1 PRI/CAS R2</th>
        <th>BRI</th>
        <th>I/O</th>

        <th>Astribank</th>
        <th>MSRP</th>
        <th>Qty</th>

      </tr>
    </thead>
    <tbody>
      {loop ports}
	  <tr>
        <td>{fxs}</td>
        <td>{fxo}</td>
        <td>{e1_t1}</td>
        <td>{bri}</td>
        <td>{io}</td>

        <td>{astribank}</td>
        <td>{msrp}</td>
        <td><input type="number" min=0 name="{astribank}"/></td>
      </tr>
      {/loop ports}
    </tbody>
  </table>
  </form>
<!--  table responsive  -->
  </div>
<!-- container   -->

<div class="panel panel-default">
  <button type="button" class="btn btn-link btn-sm">Create Quote</button>
  <button type="button" class="btn btn-link btn-sm clear_cpbx_astribank">Clear All</button>
</div>

<!-- wrapper -->
</div>
</div>
