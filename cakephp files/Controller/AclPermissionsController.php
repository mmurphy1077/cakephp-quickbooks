<?php
App::uses('AppController', 'Controller');
/**
 * AclPermissions Controller
 *
 * @property AclPermission $AclPermission
 */
class AclPermissionsController extends AppController {
	
	public $acoType = ACL_ACO_TYPE_APPLICATION;
	
	public function beforeRender() {
		parent::beforeRender();
		$this->set('title_for_layout', __('Control Panel'));
	}
	
	/**
	 * Preliminary "Control Panel" dashboard for handling application-level
	 * 		configuration by the Creationsite, Inc. Webmaster in a web
	 * 		interface
	 */
	public function dashboard() {
		
	}
	
	public function index() {
		$acls = $this->AclPermission->find('all');
		$this->paginate = array('limit' => Configure::read('Paginate.list.limit'));
		$this->set('acls', $this->paginate('AclPermission'));
	}
	
	public function add() {
		if (!empty($this->request->data)) {
			if ($this->AclPermission->save($this->request->data)) {
				$this->ScreenMessage->success(__('save_true'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		}
		$this->set('aros', $this->AclPermission->AclRequester->generateTreeList(null, '{n}.AclRequester.id', '{n}.AclRequester.alias'));
		$this->set('acos', $this->AclPermission->AclControlled->generateTreeList(null, '{n}.AclControlled.id', '{n}.AclControlled.alias'));
	}
	
	public function delete($id = null) {
		if ($this->AclPermission->delete($id)) {
			$this->ScreenMessage->success(__('delete_true'));
		} else {
			$this->ScreenMessage->error(__('delete_false'));
		}
		$this->redirect(array('action' => 'index'));
	}
	
}