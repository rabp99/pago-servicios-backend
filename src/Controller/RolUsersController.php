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
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Roles']
        ];
        $rolUsers = $this->paginate($this->RolUsers);

        $this->set(compact('rolUsers'));
        $this->set('_serialize', ['rolUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Rol User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rolUser = $this->RolUsers->get($id, [
            'contain' => ['Users', 'Roles']
        ]);

        $this->set('rolUser', $rolUser);
        $this->set('_serialize', ['rolUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rolUser = $this->RolUsers->newEntity();
        if ($this->request->is('post')) {
            $rolUser = $this->RolUsers->patchEntity($rolUser, $this->request->getData());
            if ($this->RolUsers->save($rolUser)) {
                $this->Flash->success(__('The rol user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rol user could not be saved. Please, try again.'));
        }
        $users = $this->RolUsers->Users->find('list', ['limit' => 200]);
        $roles = $this->RolUsers->Roles->find('list', ['limit' => 200]);
        $this->set(compact('rolUser', 'users', 'roles'));
        $this->set('_serialize', ['rolUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Rol User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rolUser = $this->RolUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rolUser = $this->RolUsers->patchEntity($rolUser, $this->request->getData());
            if ($this->RolUsers->save($rolUser)) {
                $this->Flash->success(__('The rol user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rol user could not be saved. Please, try again.'));
        }
        $users = $this->RolUsers->Users->find('list', ['limit' => 200]);
        $roles = $this->RolUsers->Roles->find('list', ['limit' => 200]);
        $this->set(compact('rolUser', 'users', 'roles'));
        $this->set('_serialize', ['rolUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Rol User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rolUser = $this->RolUsers->get($id);
        if ($this->RolUsers->delete($rolUser)) {
            $this->Flash->success(__('The rol user has been deleted.'));
        } else {
            $this->Flash->error(__('The rol user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
