<?php
App::uses('AppController', 'Controller');
/**
 * AclRequesters Controller
 *
 * @property AclRequester $AclRequester
 */
class AclRequestersController extends AppController {
	
	public $acoType = ACL_ACO_TYPE_APPLICATION;
	
	public function beforeRender() {
		parent::beforeRender();
		$this->set('title_for_layout', __('Control Panel'));
	}
	
	public function index() {
		$this->paginate = array('limit' => Configure::read('Paginate.list.limit'));
		$aros = $this->paginate('AclRequester');
		$this->set('aros', $aros);
		$this->set('aroList', $this->AclRequester->generateTreeList(null, '{n}.AclRequester.id', '{n}.AclRequester.alias'));
	}
	
	public function view($id = null) {		
		$aro = $this->AclRequester->getById($id, 'default');
		$aro['Children'] = $this->AclRequester->children($id);
		$this->set('aro', $aro);
		$this->set('aros', $this->AclRequester->find('list', array('fields' => array('id', 'alias'))));
	}
	
	public function add() {
		$this->set('aros', $this->AclRequester->generateTreeList(null, '{n}.AclRequester.id', '{n}.AclRequester.alias'));
		if (!empty($this->request->data)) {
			$this->AclRequester->create();
			if ($this->AclRequester->save($this->request->data)) {
				$this->ScreenMessage->success(__('save_true'));
				$this->redirect(array('action' => 'index'));				
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		} else {
			$this->request->data['AclRequester']['id'] = null;
		}
		$this->render('edit');
	}
	
	public function edit($id = null) {
		$this->AclRequester->id = $id;
		if ($this->AclRequester->exists($id)) {
			$this->set('aros', $this->AclRequester->generateTreeList(null, '{n}.AclRequester.id', '{n}.AclRequester.alias'));
			if (empty($this->request->data)) {				
				$this->request->data = $this->AclRequester->getById($id);
			} else {
				if ($this->AclRequester->save($this->request->data)) {
					$this->ScreenMessage->success(__('save_true'));
					$this->redirect(array('action' => 'view', $id));										
				}
				else {
					$this->ScreenMessage->error(__('save_false'));					
				}
			}			
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}		
	}
	
	public function delete($id = null) {
		$this->AclRequester->id = $id;
		if ($this->AclRequester->exists($id) && $this->AclRequester->delete($id)) {
			$this->ScreenMessage->success(__('delete_true'));
		} else {
			$this->ScreenMessage->error(__('delete_false'));
		}
		$this->redirect(array('action' => 'index'));		
	}
	
}