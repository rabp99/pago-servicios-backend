<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicio Entity
 *
 * @property int $id
 * @property string $descripcion
 * @property int $dia
 * @property float $monto
 * @property int $estado_id
 * 
 * @property \App\Model\Entity\Estado $estado
 */
class Servicio extends Entity
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
    
    protected $_virtual = ['condicion', 'deuda_acumulada'];
    
    protected function _getCondicion() {
        $condicion = 'DEUDA';
        
        if (empty($this->_properties['programaciones'])) {
            $condicion = 'PAGADO';
        }
        
        return $condicion;
    }
    
    protected function _getDeudaAcumulada() {
        $suma = 0;
        
        if (!empty($this->_properties['programaciones'])) {
            foreach ($this->_properties['programaciones'] as $programacion) {
                $suma += $programacion->monto;
            }
        }
        
        return $suma;
    }
}
