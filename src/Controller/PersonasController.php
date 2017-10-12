<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Personas Controller
 *
 * @property \App\Model\Table\PersonasTable $Personas
 */
class PersonasController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $personas = $this->Personas->find()
            ->where(['Idestado' => 1]);

        $this->set(compact('personas'));
        $this->set('_serialize', ['personas']);
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
        $personal = $this->Personal->get($id, [
            'contain' => []
        ]);

        $this->set('personal', $personal);
        $this->set('_serialize', ['personal']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $personal = $this->Personal->newEntity();
        if ($this->request->is('post')) {
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
}
