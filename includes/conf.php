<?php
if (isset($_POST["plan-new"])) {
	$plan = $_POST['plan-new'];
	
	
	$sql = $pdo->prepare('UPDATE `zenno` SET value = :value where id="7"');
	$sql->execute(array('value' => $plan));
	
	if ($sql) {
	successmes($url);
	    } else {
	errormes($url);
	}
}
?>
<div class="info-block bochka-block">
	<div class="inside">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				<?php
					    $plan = DBOnce('value','zenno','id=7');
				        echo '<p class="bochka"><strong>План на месяц:</strong><br>'.$plan.' т.</p>';
				?>
			</div>
			<div class="col-xs-12 col-sm-9">
				<form method="post">
					<div class="form-group row">
					    <div class="col-sm-8">
					        <p><input type="text" value="<?=$plan?>" class="form-control" name="plan-new" placeholder="Кол-во топлива"></p>
					    </div>
					    <div class="col-sm-4">
						    <p><button type="submit" name="plan" class="btn btn-warning">Обновить</button></p>
					    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
if (isset($_POST["password"])) {
	// вставка в таблицу пользователей
	$sql = $pdo->prepare("INSERT INTO `usertbl` SET `full_name` = :full_name, `phone` = :phone,`username` = :username, `password` = :password");
	$sql->execute(array('full_name' => $_POST['full_name'], 'phone' => $_POST['phone'], 'username' => $_POST['username'], 'password' => $_POST['password']));
	
	if ($sql) {
	successmes($url);
	    } else {
	errormes($url);
	}
}
?>
    <div class="info-block">
		<div class="inside">
			<h4>Добавить пользователя</h4>
			<form method="post">
			    <div class="form-group row">
			      <label for="staticEmail" class="col-sm-3 col-form-label">Полное имя</label>
			      <div class="col-sm-9">
			        <input type="text" class="form-control" name="full_name" value="" placeholder="Иванов И.И." required>
			      </div>
			    </div>	
			    <div class="form-group row">
			      <label for="staticEmail" class="col-sm-3 col-form-label">Телефон</label>
			      <div class="col-sm-9">
			        <input type="text" class="form-control" name="phone" value="" placeholder="79266556988" required>
			      </div>
			    </div> 
			    <div class="form-group row">
			      <label for="staticEmail" class="col-sm-3 col-form-label">Логин</label>
			      <div class="col-sm-9">
			        <input type="text" class="form-control" name="username" value="" placeholder="rubezh_ivanov" required>
			      </div>
			    </div>
			    <div class="form-group row">
			      <label for="staticEmail" class="col-sm-3 col-form-label">Пароль</label>
			      <div class="col-sm-9">
			        <input type="text" class="form-control" name="password" value="" placeholder="Пароль" required>
			      </div>
			    </div>       
				<button type="submit" name="Submit" class="btn btn-primary">Добавить пользователя</button>
			</form>

		</div>
    </div>

<?php
$sql = $pdo->prepare('SELECT * FROM `usertbl` order by id');
$sql->execute();
$sql = $sql->fetchAll(PDO::FETCH_BOTH);
?>

<div class="info-block">
	<div class="inside">
		<h2>Пользователи системы</h2>
		<table class="table table-hover table-striped mb-0" id="smenatable">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Пользователь</th>
					<th scope="col">Логин</th>
					<th scope="col">Пароль</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($sql as $result) {
					echo '<tr><td>'.$result['id'].'</td><td>'.$result['full_name'].'</td><td>'.$result['username'].'</td><td>'.$result['password'].'</td></tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>