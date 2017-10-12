<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * RolUsers Controller
 *
 * @property \App\Model\Table\RolUsersTable $RolUsers
 *
 * @method \App\Model\Entity\RolUser[] paginate($object = null, array $settings = [])
 */
class RolUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $roles = $this->Roles->find();

        $this->set(compact('roles'));
        $this->set('_serialize', ['roles']);
    }
    
    /**
     * View method
     *
     * @param string|null $id Personal id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $rol = $this->Roles->get($id, [
            'contain' => ['ControllerRoles.Controllers']
        ]);

        $this->set(compact('rol'));
        $this->set('_serialize', ['rol']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $rol_user = $this->RolUsers->newEntity($this->request->getData());
        
        if ($this->request->is('post')) {
            if ($this->RolUsers->save($rol_user)) {
                $code = 200;
                $message = 'El rol fue asignado correctamente';
            } else {
                $message = 'El rol no fue asignado correctamente';
            }
        }
        $this->set(compact('code', 'message'));
        $this->set('_serialize', ['code', 'message']);
    }
}
