
    <div class="info-block">
		<div class="inside">
			<table class="table table-hover table-striped mb-0" id="smenatable">
						  <thead>
						    <tr>
						      <th scope="col">Пользователь</th>
						      <th scope="col">Активность</th>
						    </tr>
						  </thead>
						  <tbody>
		<?php
		    $sql = 'SELECT full_name, activity FROM `usertbl` WHERE activity '.$bwnow.' order by activity desc';
		    $sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result) {
			$datetime = date("Y-m-d H:i:s");  
			$strStart = $result['activity']; 
			$strEnd   = $datetime; 
		
		 
		
			$dteStart = new DateTime($strStart); 
			$dteEnd   = new DateTime($strEnd); 
		
		 
			$dteDiff  = $dteStart->diff($dteEnd); 
		
		 
			$razn = $dteDiff->format("%H:%I:%S"); 
			$raznm = date("i", strtotime($razn));
			$raznh = date("G", strtotime($razn));
			if ($raznm < 5) {
				$razn = 'Сейчас в системе';
				$color = 'text-success';
			} 
			if ($raznm >= 5 and $raznm < 60 and $raznh == 0) {
				$razn = 'Был в системе '.$raznm.' мин. назад';
				$color = 'text-danger';
			}
			if ($raznh > 0 and $raznh < 25) {
				$razn = 'Был в системе '.$raznh.' час. назад';
				$color = 'text-danger';
			}
		    echo '<tr><td><i class="fas fa-circle '.$color.' logi"></i> '.$result['full_name'].'</td><td style="width:50%">'.$razn.'</td></tr>';
		    }
		    
		?>
		</tbody>
				</table>
			<a class="btn btn-success hook in mt-3" data-toggle="collapse" href=".hook" aria-expanded="false" aria-controls="collapseExample">История авторизации</a>
			<div class="collapse hook">
			    <table class="table table-hover" id="smenatable">
						  <tbody>
							<?php
								$sql = 'SELECT full_name, activity FROM `usertbl` WHERE activity < "'.$now.' 00:00:00" and activity != "0000-00-00 00:00:00" order by activity desc';
		    $sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result) {
			    $newDate3 = date("d.m в G:i", strtotime($result['activity']));
			   echo '<tr><td><i class="far fa-circle text-secondary logi"></i> '.$result['full_name'].'</td><td style="width:50%">Был в системе '.$newDate3.'</td></tr>';
			}
			?>  
					</tbody>
				</table>
			</div>
		</div>
    </div>			
    <div class="info-block">
				<div class="inside">
					<p><input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Введите фамилию пользователя для поиска..."></p>
					<table class="table table-hover" id="logtable">
						  <thead>
						    <tr>
						      <th scope="col">Действие</th>
						      <th scope="col">User</th>
						      <th scope="col"><i class="far fa-calendar-alt"></i></th>
						    </tr>
						  </thead>
						  <tbody>
					<?php
						$sql = 'SELECT `action`, `user`, `datetime`, `imp` FROM `log` ORDER BY datetime DESC limit 500';
						
						$sql = $pdo->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {
								   
								   $userfn = DBOnce('full_name','usertbl','id='.$result['user']);
								   if ($result['imp'] == 1) {
									   $tbcl = ' class="table-danger"';
								   } else {
									   $tbcl = '';
								   }
								    echo '<tr '.$tbcl.'><td>'.$result['action'].'</td><td>'.$userfn.'</td><td>'.$result['datetime'].'</td></tr>';
							    }
					?>
 					</tbody>
				</table>					
				</div>
    </div>
<script>
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("logtable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
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