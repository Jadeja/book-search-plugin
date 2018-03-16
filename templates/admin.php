<h1>Library Book Search</h1>
<?php $_SESSION['code'] = uniqid();	?>
<div class="col-6"><center><b>Book Search</b></center></div><br>
<div class="row">
	<div class="col-2">Book Name : </div> <div class="col-3"><input type="text" id="bname" name="bname" value="" /></div>
	<div class="col-2">Author      : </div><div class="col-3"><input type="text" id="author" name="author" value="" /></div>
</div><br>
<div class="row">
	<div class="col-2">Publisher : </div> <div class="col-3"><select id="publisher"><option value="">Please Select an Option</option><option value="p1">P1</option><option value="p2">P2</option></select></div>
	<div class="col-2">Rating    : </div> <div class="col-3"><select id="rating"><option value="">Please Select an Option</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>
</div><br>


<div class="row" data-role="rangeslider">
    <div class="col-2">Price Ranger:</div>
    	<div class="col-7">
    	<label id="st">From : </label> <input type="text" name="price-min" id="price-min" value="" min="1" max="100"> <label id="end">To : </label><input type="text" name="price-max" id="price-max" value="" min="1" max="100" />
    </div>
</div>
<br>

<div class="row"><div class="col-3  offset-4"><input type="submit" data-inline="true" id="search" value="Search"></div></div>
<br>
<!-- <input type="hidden" id="code" name="code" value="<?php  echo $_SESSION['code'] ?>">
 --><table id="results" class="table">
<tr><th>No</th><th>Book Name</th><th>Price</th><th>Auther</th><th>Publisher</th><th>Rating</th></tr>
<tr><td colspan="6" id="div1"> Empty </td></tr>
</table>
