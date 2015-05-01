<?php
App::uses('AppController', 'Controller');
/**
 * AclControlleds Controller
 *
 * @property AclControlled $AclControlled
 */
class AclControlledsController extends AppController {
	
	public $acoType = ACL_ACO_TYPE_APPLICATION;
	
	public function beforeRender() {
		parent::beforeRender();
		$this->set('title_for_layout', __('Control Panel'));
	}
	
	public function index() {
		$this->paginate = array('limit' => Configure::read('Paginate.list.limit'));
		$acos = $this->paginate('AclControlled');
		$this->set('acos', $acos);
		$this->set('acoList', $this->AclControlled->generateTreeList(null, '{n}.AclControlled.id', '{n}.AclControlled.alias'));
	}
	
	public function view($id = null) {
		$aco = $this->AclControlled->getById($id, 'default');
		$aco['Children'] = $this->AclControlled->children($id);
		$this->set('aco', $aco);
		$this->set('acos', $this->AclControlled->find('list', array('fields' => array('id', 'alias'))));
	}
	
	public function add() {
		$this->set('acos', $this->AclControlled->generateTreeList(null, '{n}.AclControlled.id', '{n}.AclControlled.alias'));
		if (!empty($this->data)) {
			$this->AclControlled->create();
			if ($this->AclControlled->save($this->data)) {
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
		$this->AclControlled->id = $id;
		if ($this->AclControlled->exists($id)) {
			$this->set('acos', $this->AclControlled->generateTreeList(null, '{n}.AclControlled.id', '{n}.AclControlled.alias'));
			if (empty($this->data)) {				
				$this->data = $this->AclControlled->getById($id);
			} else {
				if ($this->AclControlled->save($this->data)) {
					$this->ScreenMessage->success(__('save_true'));
					$this->redirect(array('action' => 'view', $id));										
				} else {
					$this->ScreenMessage->error(__('save_false'));					
				}
			}			
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}		
	}
	
	public function delete($id = null) {
		$this->AclControlled->id = $id;
		if ($this->AclControlled->exists($id) && $this->AclControlled->delete($id)) {
			$this->ScreenMessage->success(__('delete_true'));
		} else {
			$this->ScreenMessage->error(__('delete_false'));
		}
		$this->redirect(array('action' => 'index'));		
	}
	
}