<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Развертывание приложения Eye<span style="color: #9d261d; font-style: italic;">Frame</span></h1>

<div class="alert alert-block">
	Модуль служит для создания или сброса к начальным значениям таблиц и данных при развертывании приложения.
	<p>При инсталляции удаляются таблицы используемые в модуле "User", а так же таблицы управления правами доступа для пользователей.
	Создаются пользователи по умолчанию.
	</p>
	<p>Настройки для создаваемых по-умолчанию пользователей находятся в файле (modules => array ('install' => array()) <pre>protected/config/main.php</pre></p>
</div>

<div class="alert alert-error">
	<h3>Внимание!</h3>
	Если у вас уже созданы учетные записи пользователей, рекомендуем сделать бэкап базы данных перед запуском инсталлятора!
</div>

<div class="alert alert-block">
	Вы можете дописать нужную информацию отредактировав этот файл: <pre><?php echo __FILE__; ?></pre>
</div>
<a class="btn btn-danger pull-right" href="/install/start">Начать установку</a>