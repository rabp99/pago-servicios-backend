<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Servicios Controller
 *
 * @property \App\Model\Table\ServiciosTable $Servicios
 *
 * @method \App\Model\Entity\Servicio[] paginate($object = null, array $settings = [])
 */
class ServiciosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $servicios = $this->Servicios->find();

        $this->set(compact('servicios'));
        $this->set('_serialize', ['servicios']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function getPendientesPago() {
        $servicios_aux = $this->Servicios->find()
            ->contain(['Pagos' => function($q) {
                return $q->where(['MONTH(fecha)' => date('m')]);
            }])->toArray();
            
        $servicios = array();
        
        foreach ($servicios_aux as $servicio) {
            if (sizeof($servicio->pagos) == 0) {
                $servicios[] = $servicio;
            }
        }
        
        $this->set(compact('servicios'));
        $this->set('_serialize', ['servicios']);
    }

    /**
     * View method
     *
     * @param string|null $id Servicio id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $servicio = $this->Servicios->get($id, [
            'contain' => []
        ]);

        $this->set('servicio', $servicio);
        $this->set('_serialize', ['servicio']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $servicio = $this->Servicios->newEntity();
        $servicio->estado_id = 1;
        if ($this->request->is('post')) {
            $servicio = $this->Servicios->patchEntity($servicio, $this->request->getData());
            
            if ($this->Servicios->save($servicio)) {
                $code = 200;
                $message = 'El servicio fue guardado correctamente';
            } else {
                $message = 'El servicio no fue guardado correctamente';
            }
        }
        $this->set(compact('servicio', 'code', 'message'));
        $this->set('_serialize', ['servicio', 'code', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Servicio id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $servicio = $this->Servicios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $servicio = $this->Servicios->patchEntity($servicio, $this->request->getData());
            if ($this->Servicios->save($servicio)) {
                $this->Flash->success(__('The servicio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The servicio could not be saved. Please, try again.'));
        }
        $this->set(compact('servicio'));
        $this->set('_serialize', ['servicio']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Servicio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $servicio = $this->Servicios->get($id);
        if ($this->Servicios->delete($servicio)) {
            $this->Flash->success(__('The servicio has been deleted.'));
        } else {
            $this->Flash->error(__('The servicio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
