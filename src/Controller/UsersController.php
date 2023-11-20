<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['token']);
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $personas = $this->Personas->find()
            ->where(['Idestado' => 1]);

        $this->set(compact('personas'));
        $this->set('_serialize', ['personas']);
    }

    public function getPersonas()
    {
        $users = $this->Users->find()
            ->where(['Users.Idestado' => 1])
            ->contain(['Personas'])->toArray();

        $personas = Hash::extract($users, '{n}.persona');

        $this->set(compact('personas'));
        $this->set('_serialize', ['personas']);
    }

    public function getAdmin()
    {
        $text = $this->request->getQuery('text');
        $items_per_page = $this->request->getQuery('items_per_page');

        $this->paginate = [
            'limit' => $items_per_page
        ];

        $query = $this->Users->find()
            ->where(['Users.Idestado' => 1])
            ->contain(['RolUsers.Roles']);

        if ($text) {
            $query->where(['Users.cPerUsuCodigo LIKE' => '%' . $text . '%']);
        }

        $count = $query->count();
        $users = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Users'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];

        $this->set(compact('users', 'pagination', 'count'));
        $this->set('_serialize', ['users', 'pagination', 'count']);
    }
    /**
     * View method
     *
     * @param string|null $id Personal id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Personas', 'RolUsers.Roles']
        ]);

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Personal id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personal = $this->Personal->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personal = $this->Personal->patchEntity($personal, $this->request->getData());
            if ($this->Personal->save($personal)) {
                $this->Flash->success(__('The personal has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personal could not be saved. Please, try again.'));
        }
        $this->set(compact('personal'));
        $this->set('_serialize', ['personal']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Personal id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personal = $this->Personal->get($id);
        if ($this->Personal->delete($personal)) {
            $this->Flash->success(__('The personal has been deleted.'));
        } else {
            $this->Flash->error(__('The personal could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }
        $user = $this->Users->get($user["id"], [
            "contain" => ['RolUsers.Roles.ControllerRoles.Controllers', 'Personas']
        ]);

        $this->set([
            'success' => true,
            'user' => $user,
            'token' => JWT::encode(
                [
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                Security::salt()
            ),
            '_serialize' => ['success', 'user', 'token']
        ]);
    }
}
