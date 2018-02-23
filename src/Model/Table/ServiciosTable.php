<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Servicios Model
 *
 * @property |\Cake\ORM\Association\HasMany $Pagos
 * @property |\Cake\ORM\Association\BelongsTo $Estados
 *
 * @method \App\Model\Entity\Servicio get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servicio newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servicio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servicio|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servicio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servicio[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servicio findOrCreate($search, callable $callback = null, $options = [])
 */
class ServiciosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('servicios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Recibos', [
            'foreignKey' => 'servicio_id'
        ]);
        
        $this->belongsTo('Estados', [
            'foreignKey' => 'estado_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Tipos', [
            'foreignKey' => 'tipo_id',
            'joinType' => 'INNER'
        ]);
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
            ->scalar('descripcion')
            ->requirePresence('descripcion', 'create')
            ->notEmpty('descripcion');
        
        return $validator;
    }
}
