<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Persona Entity
 *
 * @property int $PerCod
 * @property string $Per_ape_pat
 * @property string $Per_nom
 * @property string $Per_dni
 * @property string $Per_telefono
 * @property string $Per_email
 * @property \Cake\I18n\Time $fecha
 * @property \Cake\I18n\Time $hora
 * @property int $Idestado
 * @property string $foto
 * @property string $perfil
 * @property string $profesion
 * @property string $inicial_prof
 * @property string $iniciales
 * @property string $denominacion
 */
class Persona extends Entity
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
    
    protected $_virtual = ['full_name'];
    
    protected function _getFullName() {
        return $this->_properties['Per_ape_pat'] . ', ' . $this->_properties['Per_nom'];
    }
     
}
