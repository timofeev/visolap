<?php
class WindowController extends Controller {
	protected function renderWindow($view, $data = array(), $class = false) {
		$windowContent = $this->renderPartial($view, $data, true);
		$id = 'w'.uniqid();
		$this->renderPartial('//window/layout', array('id' => $id, 'content' => $windowContent, 'class' => $class));
	}
	
	protected function renderWindowContent($window, $id) {
		$class = false;		
		if ($window['isData']) {
			$class = 'data-window';
		}
		$this->renderPartial('//window/layout', array('id' => $id, 'content' => $window['content'], 'window' => $window, 'class' => $class));
	}
}
?>