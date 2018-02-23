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
        $tipo_id = $this->request->getQuery('tipo_id');
        $estado_id = $this->request->getQuery('estado_id');
        $text = $this->request->getQuery('text');
        $items_per_page = $this->request->getQuery('items_per_page');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->Servicios->find()
            ->contain(['Tipos']);

        if ($tipo_id) {
            $query->where(['Servicios.tipo_id' => $tipo_id]);
        }
        
        if ($estado_id) {
            $query->where(['Servicios.estado_id' => $estado_id]);
        }
        
        if ($text) {
            $query->where(['OR' => [
                'Servicios.descripcion LIKE' => '%' . $text . '%',
                'Servicios.detalle LIKE' => '%' . $text . '%'
            ]]);
        }
        
        $servicios = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Servicios'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('servicios', 'pagination'));
        $this->set('_serialize', ['servicios', 'pagination']);
    }

    /**
     * View method
     *
     * @param string|null $id Servicio id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $servicio = $this->Servicios->get($id, [
            'contain' => ['Tipos']
        ]);

        $this->set(compact('servicio'));
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
    
    public function getByTipo($tipo_id) {
        $servicios = $this->Servicios->findByTipoId($tipo_id);
              
        $this->set(compact('servicios'));
        $this->set('_serialize', ['servicios']);
    }
    
    public function getReport() {
        $tipo_id = $this->request->getParam('tipo_id');
        
        $servicios = $this->Servicios->find()
            ->contain(['Tipos', 'Recibos' => function($q) {
                return $q->where(['estado_id' => 4]);
            }]);
        
        if ($tipo_id != 0) {
            $servicios->where(['tipo_id' => $tipo_id]);
        }
        
        $this->set(compact('servicios'));
        $this->set('_serialize', ['servicios']);
    }
    
    public function search($texto = null) {
        $texto = $this->request->getParam('texto');

        $servicio = $this->Servicios->find()
            ->contain(['Tipos'])
            ->where(['OR' => [
                'Servicios.descripcion' => $texto,
                'Servicios.detalle' => $texto
            ]])
            ->first();
        
        $this->set(compact('servicio'));
        $this->set('_serialize', ['servicio']);
    }
    
    public function searchMany($search = null) {
        $servicio = $this->request->getParam('search');
        $servicios = $this->Servicios->find()
            ->where([
                'OR' => [
                    'Servicios.descripcion LIKE' => '%' . $servicio . '%',
                    'Servicios.detalle LIKE' => '%' . $servicio . '%'
                ],
                'Servicios.estado_id' => 1
            ]);
        
        $this->set(compact('servicios'));
        $this->set('_serialize', ['servicios']);
    }
}
