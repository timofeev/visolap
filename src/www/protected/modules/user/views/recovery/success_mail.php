<p>Здравствуйте.</p>
<p>Пароль для сайта <?php echo Yii::app()->name?> успешно изменен.</p>

<p>Ваши данные для входа на сайт:</p>
<table border="0" cellpadding="5">
	<tr>
		<td width="200">Имя пользователя:</td>
		<td><?php echo $username?></td>
	</tr>
	<tr>
		<td>Пароль:</td>
		<td><?php echo $password?></td>
	</tr>	
</table>
<p><a href="http://<?php echo $_SERVER['HTTP_HOST'].$this->createUrl('/auth')?>">Вход</a> на сайт.</p>
