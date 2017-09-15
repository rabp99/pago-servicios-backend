<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Pagos Controller
 *
 * @property \App\Model\Table\PagosTable $Pagos
 *
 * @method \App\Model\Entity\Pago[] paginate($object = null, array $settings = [])
 */
class PagosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $pagos = $this->Pagos->find()
            ->contain(['Programaciones' => ['Servicios' => ['Tipos']]]);

        $this->set(compact('pagos'));
        $this->set('_serialize', ['pagos']);
    }

    /**
     * View method
     *
     * @param string|null $id Pago id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $pago = $this->Pagos->get($id, [
            'contain' => ['Servicios']
        ]);

        $this->set('pago', $pago);
        $this->set('_serialize', ['pago']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $pago = $this->Pagos->newEntity();
        $pago->estado_id = 1;
        $pago->fecha = date('Y-m-d');
        if ($this->request->is('post')) {
            $pago = $this->Pagos->patchEntity($pago, $this->request->getData());

            if ($this->Pagos->save($pago)) {
                $pago = $this->Pagos->get($pago->id, [
                    'contain' => ['Programaciones' => ['Servicios' => ['Tipos']]]
                ]);

                $code = 200;
                $message = 'El oago se ha registrado correctamente';
            } else {
                $message = 'El pago no se ha registrado correctamente';
            }
        }
        $this->set(compact('pago', 'code', 'message'));
        $this->set('_serialize', ['pago', 'code', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Pago id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pago = $this->Pagos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pago = $this->Pagos->patchEntity($pago, $this->request->getData());
            if ($this->Pagos->save($pago)) {
                $this->Flash->success(__('The pago has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pago could not be saved. Please, try again.'));
        }
        $servicios = $this->Pagos->Servicios->find('list', ['limit' => 200]);
        $this->set(compact('pago', 'servicios'));
        $this->set('_serialize', ['pago']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pago id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pago = $this->Pagos->get($id);
        if ($this->Pagos->delete($pago)) {
            $this->Flash->success(__('The pago has been deleted.'));
        } else {
            $this->Flash->error(__('The pago could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function getByDates() {
        $fecha_inicio = $this->request->param('fecha_inicio');
        $fecha_cierre = $this->request->param('fecha_cierre');
        
        $pagos = $this->Pagos->find()
            ->contain(['Programaciones' => ['Servicios' => ['Tipos']]])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Pagos.fecha', $fecha_inicio, $fecha_cierre, 'date');
            });
        
        $this->set(compact('pagos'));
        $this->set('_serialize', ['pagos']);
    }
}
