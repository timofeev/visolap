<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Развертывание приложения Eye<span style="color: #9d261d; font-style: italic;">Frame</span></h1>

<div class="alert alert-success">
	Таблицы и учетные записи пользователей созданы
</div>

<div class="alert alert-error">
	<h3>Внимание!</h3>
	<p>Не забудьте выставить конфигурацию modules=>array('install' => array('enabled' = false)) для запрета запуска инсталлятора в файле: <pre>protected/config/main.php</pre></p>
</div>