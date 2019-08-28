<div class="info-block">
		<div class="inside">
			<h3 class="mb-3">Склад</h3>
			<p><input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Введите наименование для поиска..."></p>

<table id="myTable" class="table table-hover">
  <thead>
		<tr>
		    <th>Наименование</th>
		    <th width="25%">Кол-во</th>
    	</tr>
  </thead>
  <tbody>
<?php
// header('Content-type: text/html; charset=utf-8');
$OstatkiTovarov = simplexml_load_file('work/OUT/OstatkiTovarov.XML'); 

 /* Для каждого узла <character>, мы отдельно выведем имя <name>. */
for ($i = 0; $i <= 5; $i++) {
$sklad2 = $OstatkiTovarov->Sklad[$i];
$namesklad = $sklad2->Title;
if ($namesklad == 'Служба Главного Механика' or $namesklad == 'Склад АХО и спецодежды') {
foreach ($OstatkiTovarov->Sklad[$i]->Tovar as $sklad) {
	$name = $sklad->Name;
	$ei = $sklad->Edizm;
	$kol = $sklad->Kol;
	echo '<tr><td>'.$name.'</td><td>'.$kol.' '.$ei.'</td></tr>';
	
	
}
}

}
?>
</table>
<script>
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>
</tbody>
			</table>
		</div>
    </div>
