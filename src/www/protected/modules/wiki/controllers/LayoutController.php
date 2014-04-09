<?php
class LayoutController extends WindowController {
	public function actionIndex() {
		$this->render('view');
	}
	
	public function actionSave($uid = false) {		
		$comment = '';
		$page = WikiPage::model()->findByWikiUid($uid);
		if(!$page)
		{
			$page = new WikiPage();
			if (!$comment) {
				$comment = 'Создание страницы';
			}
		}

		$page->setWikiUid($uid);
		
		if(Yii::app()->request->getPost('WikiPage')) {
			$content = serialize(Yii::app()->request->getPost('WikiPage'));
			$page->content = $content;			
			
			/** @var $auth IWikiAuth */
			$auth = $this->getModule()->getAuth();
			if(!$auth->isGuest())
			{
				$page->user_id = $auth->getUserId();
			}

			$trans = $page->dbConnection->beginTransaction();

			$justCreated = false;
			if($page->isNewRecord)
			{
				$justCreated = true;
				$page->save();
			}

			$revId = $this->addPageRevision($page, $comment);
			if($revId)
			{
				$page->revision_id = $revId;				
				if($page->save()) {
					$trans->commit();
					Yii::app()->cache->delete($page->getCacheKey());						
					echo Yii::app()->createUrl('wiki/layout/load', array('uid' => $page->getWikiUid()));					
				}
			}			
		}
		
	}
	
	public function actionLoad($uid, $rev = null) {
		$page = WikiPage::model()->findByWikiUid($uid);
		if($page)
		{
			if($rev)
			{
				$revision = WikiPageRevision::model()->findByAttributes(array(
					'page_id' => $page->id,
					'id' => $rev,
				));

				if(!$revision)
				{
					throw new CHttpException(404);
				}

				$cacheId = $revision->getCacheKey();
			}
			else
			{
				$cacheId = $page->getCacheKey();
			}

			if(!($text = Yii::app()->cache->get($cacheId)))
			{
				if($rev)
				{
					$text = $revision->content;
				}
				else
				{
					$text = $page->content;
				}

				Yii::app()->cache->set($cacheId, $text);
			}
            $content = unserialize($text);
			$this->render('view', array(
				'page' => $page,
				'content' => $content,
			));
		}
		else
		{
			 throw new CHttpException(404);
		}
	}
	
	private function addPageRevision(WikiPage $page, $comment)
	{
		$revision = new WikiPageRevision();
		$revision->comment = $comment;
		$revision->content = $page->content;
		$revision->page_id = $page->id;

		/** @var $auth IWikiAuth */
		$auth = $this->getModule()->getAuth();
		if(!$auth->isGuest())
		{
			$revision->user_id = $auth->getUserId();
		}

		if($revision->save())
		{
			return $revision->id;
		}
		else
		{
			return false;
		}
	}
}
?>