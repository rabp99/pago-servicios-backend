<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $PerCod
 * @property string $cPerUsuCodigo
 * @property string $cPerUsuClave
 * @property string $hora
 * @property int $Cod_Acceso
 * @property int $Idestado
 * @property \Cake\I18n\Time $fecha_u
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true
    ];
    
    protected $_hidden = [
        'cPerUsuClave'
    ];
}
