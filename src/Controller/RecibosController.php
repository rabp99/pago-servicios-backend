<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Recibos Controller
 *
 * @property \App\Model\Table\RecibosTable $Recibos
 *
 * @method \App\Model\Entity\Reciboe[] paginate($object = null, array $settings = [])
 */
class RecibosController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['getPendientesPago', 'showAlerts', 'getEstadisticas']);
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
        $fecha_inicio = $this->request->getQuery('fecha_inicio');
        $fecha_cierre = $this->request->getQuery('fecha_cierre');
        $text = $this->request->getQuery('text');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->Recibos->find()
            ->contain(['Servicios' => ['Tipos']]);

        if ($servicio_id) {
            $query->where(['Recibos.servicio_id' => $servicio_id]);
        }
        
        if ($estado_id) {
            $query->where(['Recibos.estado_id' => $estado_id]);
        }
        
        if ($fecha_inicio && $fecha_cierre) {
            $query->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('fecha_vencimiento', $fecha_inicio, $fecha_cierre, 'date');
            });
        }
        
        if ($text) {
            $query->where(['Servicios.detalle' => $text]);
        }
        
        $recibos = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Recibos'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('recibos', 'pagination'));
        $this->set('_serialize', ['recibos', 'pagination']);
    }

    /**
     * View method
     *
     * @param string|null $id Reciboe id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $recibo = $this->Recibos->get($id, [
            'contain' => ['Servicios' => ['Tipos']]
        ]);

        $this->set(compact('recibo'));
        $this->set('_serialize', ['recibo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $recibo = $this->Recibos->newEntity();
        $recibo->fecha_registro = date('Y-m-d');
        if ($this->request->is('post')) {
            $recibo = $this->Recibos->patchEntity($recibo, $this->request->getData());
            
            if ($this->Recibos->save($recibo)) {
                $code = 200;
                $message = 'EL recibo fue guardado correctamente';
            } else {
                $message = 'El recibo no fue guardado correctamente';
            }
        }
        $this->set(compact('recibo', 'code', 'message'));
        $this->set('_serialize', ['recibo', 'code', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reciboe id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $programacione = $this->Recibos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $programacione = $this->Recibos->patchEntity($programacione, $this->request->getData());
            if ($this->Recibos->save($programacione)) {
                $this->Flash->success(__('The programacione has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The programacione could not be saved. Please, try again.'));
        }
        $servicios = $this->Recibos->Servicios->find('list', ['limit' => 200]);
        $this->set(compact('programacione', 'servicios'));
        $this->set('_serialize', ['programacione']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reciboe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $programacione = $this->Recibos->get($id);
        if ($this->Recibos->delete($programacione)) {
            $this->Flash->success(__('The programacione has been deleted.'));
        } else {
            $this->Flash->error(__('The programacione could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function getByServicio($servicio_id) {
        $recibos = $this->Recibos->findByServicioId($servicio_id)
            ->contain(['Servicios']);
              
        $this->set(compact('recibos'));
        $this->set('_serialize', ['recibos']);
    }
    
    public function getByServicioNoPagados($servicio_id) {
        $recibos = $this->Recibos->find()
            ->where([
                'servicio_id' => $servicio_id, 
                'estado_id' => 4
            ]);
              
        $this->set(compact('recibos'));
        $this->set('_serialize', ['recibos']);
    }
    
    public function getByDates() {
        $fecha_inicio = $this->request->param('fecha_inicio');
        $fecha_cierre = $this->request->param('fecha_cierre');

        $recibos = $this->Recibos->find()
            ->contain(['Servicios' => ['Tipos'], 'Estados'])
            ->where(['Recibos.estado_id' => 4])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Recibos.fecha_vencimiento', $fecha_inicio, $fecha_cierre, 'date');
            });
        
        $this->set(compact('recibos'));
        $this->set('_serialize', ['recibos']);
    }
    
    public function getByDatesPagos() {
        $fecha_inicio = $this->request->param('fecha_inicio');
        $fecha_cierre = $this->request->param('fecha_cierre');

        $recibos = $this->Recibos->find()
            ->contain(['Servicios' => ['Tipos'], 'Estados'])
            ->where(['Recibos.estado_id' => 3])
            ->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
                return $exp->between('Recibos.fecha_pago', $fecha_inicio, $fecha_cierre, 'date');
            });
        
        $this->set(compact('recibos'));
        $this->set('_serialize', ['recibos']);
    }
    
    /**
     * Get Pendientes Pago method
     *
     * @return \Cake\Http\Response|void
     */
    public function getPendientesPago() {
        $recibos = $this->Recibos->find()
            ->where(['Recibos.estado_id' => 4])
            ->contain(['Servicios']);
        
        $this->set(compact('recibos'));
        $this->set('_serialize', ['recibos']);
    }
    
    public function cancelarPago() {
        $programacion = $this->Recibos->newEntity($this->request->getData());
        $programacion->fecha_pago = null;
        $programacion->nro_documento = null;
        $programacion->estado_id = 4;
        
        if ($this->request->is('post')) {
            if ($this->Recibos->save($programacion)) {
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
            $recibos = $this->request->getData('recibos');
            $fecha = $this->request->getData('fecha');
            $nro_documento = $this->request->getData('nro_documento');
            
            $conn = ConnectionManager::get('default');
            $conn->begin();

            foreach ($recibos as $recibo) {
                $recibo = $this->Recibos->get($recibo['id']);
                $recibo->fecha_pago = $fecha;
                $recibo->nro_documento = $nro_documento;
                $recibo->estado_id = 3;
                
                if (!$this->Recibos->save($recibo)) {
                    $conn->rollback();
                    $message = 'Los recibos no fueron pagados correctamente';
                    break;
                }
            }
            
            $code = 200;
            $message = 'Los recibos fueron pagados correctamente';
            $conn->commit();
        }
        $this->set(compact('recibos', 'code', 'message'));
        $this->set('_serialize', ['recibos', 'code', 'message']);
    }
    
    /**
     * Save Many method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function saveMany() {
        $recibos = $this->Recibos->newEntities($this->request->getData('recibos'));
        if ($this->request->is('post')) {
            $conn = ConnectionManager::get('default');
            $conn->begin();
            foreach ($recibos as $programacion) {
                $programacion->fecha_registro = date('Y-m-d');
                if (!$this->Recibos->save($programacion)) {
                    $conn->rollback();
                    $message = 'Las recibos no fueron guardadas correctamente';
                    break;
                }
            }
            
            $code = 200;
            $message = 'Las recibos fueron guardadas correctamente';
            $conn->commit();
        }
        $this->set(compact('recibos', 'code', 'message'));
        $this->set('_serialize', ['recibos', 'code', 'message']);
    }
    
    /**
     * Get Estadisticas method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function getEstadisticas() {
        $fecha_inicio = $this->request->getQuery('fecha_inicio');
        $fecha_cierre = $this->request->getQuery('fecha_cierre');
        
        $totalCount = $this->Recibos->find()->where(['estado_id' => 4])->count();
        $sinPagarCount = $this->Recibos->find()->where(['estado_id' => 4])->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
            return $exp->between('fecha_vencimiento', $fecha_inicio, $fecha_cierre, 'date');
        })->count();
        $pagadosCount = $this->Recibos->find()->where(['estado_id' => 3])->where(function($exp) use ($fecha_inicio, $fecha_cierre) {
            return $exp->between('fecha_vencimiento', $fecha_inicio, $fecha_cierre, 'date');
        })->count();
        
        $this->set(compact('totalCount', 'sinPagarCount', 'pagadosCount'));
        $this->set('_serialize', ['totalCount', 'sinPagarCount', 'pagadosCount']);
    }
}