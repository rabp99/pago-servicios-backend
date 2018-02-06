<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Programaciones Controller
 *
 * @property \App\Model\Table\ProgramacionesTable $Programaciones
 *
 * @method \App\Model\Entity\Programacione[] paginate($object = null, array $settings = [])
 */
class ProgramacionesController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['getPendientesPago']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $servicio_id = $this->request->getQuery('servicio_id');
        $estado_id = $this->request->getQuery('estado_id');
        $items_per_page = $this->request->getQuery('items_per_page');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->Programaciones->find()
            ->contain(['Servicios' => ['Tipos']]);

        if ($servicio_id) {
            $query->where(['Programaciones.servicio_id' => $servicio_id]);
        }
        
        if ($estado_id) {
            $query->where(['Programaciones.estado_id' => $estado_id]);
        }
        
        $programaciones = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Programaciones'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('programaciones', 'pagination'));
        $this->set('_serialize', ['programaciones', 'pagination']);
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
            'contain' => ['Servicios' => ['Tipos']]
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
        $programacion->fecha_registro = date('Y-m-d');
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
        $programaciones = $this->Programaciones->findByServicioId($servicio_id)
            ->contain(['Servicios']);
              
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
            ->contain(['Servicios' => ['Tipos'], 'Estados'])
            ->where(['Programaciones.estado_id' => 4])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Programaciones.fecha_vencimiento', $fecha_inicio, $fecha_cierre, 'date');
            });
        
        $this->set(compact('programaciones'));
        $this->set('_serialize', ['programaciones']);
    }
    
    public function getByDatesPagos() {
        $fecha_inicio = $this->request->param('fecha_inicio');
        $fecha_cierre = $this->request->param('fecha_cierre');

        $programaciones = $this->Programaciones->find()
            ->contain(['Servicios' => ['Tipos'], 'Estados'])
            ->where(['Programaciones.estado_id' => 3])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Programaciones.fecha_pago', $fecha_inicio, $fecha_cierre, 'date');
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
    
    public function cancelarPago() {
        $programacion = $this->Programaciones->newEntity($this->request->getData());
        $programacion->fecha_pago = null;
        $programacion->nro_documento = null;
        $programacion->estado_id = 4;
        
        if ($this->request->is('post')) {
            if ($this->Programaciones->save($programacion)) {
                $code = 200;
                $message = 'El pago fue cancelado correctamente';
            } else {
                $message = 'El pago no fue cancelado correctamente';
            }
        }
        $this->set(compact('programacion', 'code', 'message'));
        $this->set('_serialize', ['programacion', 'code', 'message']);
    }
    
    public function pagarMany() {
        if ($this->request->is('post')) {
            $programaciones_id = $this->request->getData('programaciones');
            $fecha_pago = $this->request->getData('fecha_pago');
            $nro_documento = $this->request->getData('nro_documento');

            $conn = ConnectionManager::get('default');
            $conn->begin();

            foreach ($programaciones_id as $programacion_id) {
                $programacion = $this->Programaciones->get($programacion_id);
                $programacion->fecha_pago = $fecha_pago;
                $programacion->nro_documento = $nro_documento;
                $programacion->estado_id = 3;
                
                if (!$this->Programaciones->save($programacion)) {
                    $conn->rollback();
                    $message = 'Las programaciones no fueron pagadas correctamente';
                    break;
                }
            }
            
            $code = 200;
            $message = 'Las programaciones fueron pagadas correctamente';
            $conn->commit();
        }
        $this->set(compact('programaciones', 'code', 'message'));
        $this->set('_serialize', ['programaciones', 'code', 'message']);
    }
    
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function saveMany() {
        $programaciones = $this->Programaciones->newEntities($this->request->getData('programaciones'));
        if ($this->request->is('post')) {
            $conn = ConnectionManager::get('default');
            $conn->begin();
            foreach ($programaciones as $programacion) {
                $programacion->fecha_registro = date('Y-m-d');
                if (!$this->Programaciones->save($programacion)) {
                    $conn->rollback();
                    $message = 'Las programaciones no fueron guardadas correctamente';
                    break;
                }
            }
            
            $code = 200;
            $message = 'Las programaciones fueron guardadas correctamente';
            $conn->commit();
        }
        $this->set(compact('programaciones', 'code', 'message'));
        $this->set('_serialize', ['programaciones', 'code', 'message']);
    }
}