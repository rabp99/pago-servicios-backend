<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 *
 * @method \App\Model\Entity\Role[] paginate($object = null, array $settings = [])
 */
class RolesController extends AppController
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
        $rol = $this->Roles->newEntity($this->request->getData());
        $rol->estado_id = 1;
        
        if ($this->request->is('post')) {
            if ($this->Roles->save($rol)) {
                $code = 200;
                $message = 'El rol fue registrado correctamente';
            } else {
                $message = 'El rol no fue registrado correctamente';
            }
        }
        $this->set(compact('code', 'message'));
        $this->set('_serialize', ['code', 'message']);
    }
}
