<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ControllerRoles Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Controllers
 * @property \Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\ControllerRol get($primaryKey, $options = [])
 * @method \App\Model\Entity\ControllerRol newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ControllerRol[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ControllerRol|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ControllerRol patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ControllerRol[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ControllerRol findOrCreate($search, callable $callback = null, $options = [])
 */
class ControllerRolesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setEntityClass('ControllerRol');
        $this->setTable('controller_roles');
        $this->setDisplayField('permiso');
        $this->setPrimaryKey('id');

        $this->belongsTo('Controllers', [
            'foreignKey' => 'controller_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'rol_id',
            'joinType' => 'INNER'
        ]);
    }
}
