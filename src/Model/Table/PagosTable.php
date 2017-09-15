<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Pagos Model
 *
 * @property \App\Model\Table\ServiciosTable|\Cake\ORM\Association\BelongsTo $Servicios
 *
 * @method \App\Model\Entity\Pago get($primaryKey, $options = [])
 * @method \App\Model\Entity\Pago newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Pago[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pago|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pago patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Pago[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pago findOrCreate($search, callable $callback = null, $options = [])
 */
class PagosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('pagos');
        $this->setDisplayField('fecha');
        $this->setPrimaryKey('id');

        $this->belongsTo('Servicios', [
            'foreignKey' => 'servicio_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Programaciones', [
            'foreignKey' => 'programacion_id',
            'joinType' => 'INNER'
        ])->setProperty('programacion');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->decimal('monto')
            ->requirePresence('monto', 'create')
            ->notEmpty('monto');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['servicio_id'], 'Servicios'));

        return $rules;
    }
    
    public function afterSave($event, $entity, $options) {
        $programacion_id = $entity->programacion->id;
        $programaciones = TableRegistry::get('Programaciones');
        
        $programacion = $programaciones->get($programacion_id);
        
        $programacion->estado_id = 3;
        
        $programaciones->save($programacion);
    }
}
