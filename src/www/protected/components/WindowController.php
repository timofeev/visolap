<?php
class WindowController extends Controller {
	protected function renderWindow($view, $data = array()) {
		$windowContent = $this->renderPartial($view, $data, true);
		$id = 'w'.uniqid();
		$this->renderPartial('//window/layout', array('id' => $id, 'content' => $windowContent));
	}
}
?>