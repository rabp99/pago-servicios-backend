<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Programaciones Controller
 *
 * @property \App\Model\Table\ProgramacionesTable $Programaciones
 *
 * @method \App\Model\Entity\Programacione[] paginate($object = null, array $settings = [])
 */
class ProgramacionesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Servicios']
        ];
        $programaciones = $this->paginate($this->Programaciones);

        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }

    /**
     * View method
     *
     * @param string|null $id Programacione id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $programacion = $this->Programaciones->get($id, [
            'contain' => ['Servicios']
        ]);

        $this->set(compact('programacion'));
        $this->set('_serialize', ['programacion']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $programacion = $this->Programaciones->newEntity();
        $programacion->estado_id = 4;
        $programacion->fecha = date('Y-m-d');
        if ($this->request->is('post')) {
            $programacion = $this->Programaciones->patchEntity($programacion, $this->request->getData());
         
            if ($this->Programaciones->save($programacion)) {
                $code = 200;
                $message = 'La programación fue guardada correctamente';
            } else {
                $message = 'El programación no fue guardada correctamente';
            }
        }
        $this->set(compact('programacion', 'code', 'message'));
        $this->set('_serialize', ['programacion', 'code', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Programacione id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $programacione = $this->Programaciones->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $programacione = $this->Programaciones->patchEntity($programacione, $this->request->getData());
            if ($this->Programaciones->save($programacione)) {
                $this->Flash->success(__('The programacione has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The programacione could not be saved. Please, try again.'));
        }
        $servicios = $this->Programaciones->Servicios->find('list', ['limit' => 200]);
        $this->set(compact('programacione', 'servicios'));
        $this->set('_serialize', ['programacione']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Programacione id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $programacione = $this->Programaciones->get($id);
        if ($this->Programaciones->delete($programacione)) {
            $this->Flash->success(__('The programacione has been deleted.'));
        } else {
            $this->Flash->error(__('The programacione could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function getByServicio($servicio_id) {
        $programaciones = $this->Programaciones->findByServicioId($servicio_id);
              
        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }
    
    public function getByServicioNoPagados($servicio_id) {
        $programaciones = $this->Programaciones->find()
            ->where([
                'servicio_id' => $servicio_id, 
                'estado_id' => 4
            ]);
              
        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }
    
    public function getByDates() {
        $fecha_inicio = $this->request->param('fecha_inicio');
        $fecha_cierre = $this->request->param('fecha_cierre');
        
        $programaciones = $this->Programaciones->find()
            ->contain(['Servicios', 'Estados'])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Programaciones.fecha', $fecha_inicio, $fecha_cierre, 'date');
            });
        
        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }
    
    /**
     * Get Pendientes Pago method
     *
     * @return \Cake\Http\Response|void
     */
    public function getPendientesPago() {
        $programaciones = $this->Programaciones->find()
            ->where(['Programaciones.estado_id' => 4])
            ->contain(['Servicios']);
        
        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }
}
