<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Reciboe Entity
 *
 * @property int $id
 * @property int $servicio_id
 * @property float $monto
 * @property string $nro_documento
 * @property \Cake\I18n\FrozenDate $fecha_registro
 * @property \Cake\I18n\FrozenDate $fecha_vencimiento
 * @property \Cake\I18n\FrozenDate $fecha_pago
 *
 * @property \App\Model\Entity\Servicio $servicio
 */
class Recibo extends Entity
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
    
    protected $_virtual = ['descripcion_detallada', 'fecha_limite'];
    
    protected function _getDescripcionDetallada() {
        if (!isset($this->_properties['monto'])) {
            return '';
        }
        if (!isset($this->_properties['fecha_vencimiento'])) {
            return '';
        }
        return 'S/ ' . $this->_properties['monto'] . ' - ' . $this->_properties['fecha_vencimiento']->format('Y-m-d');
    }
    
    protected function _getFechaLimite() {
        if (!isset($this->_properties['fecha_vencimiento'])) {
            return '';
        }
        return $this->_properties['fecha_vencimiento']->modify('-' . $this->_properties['dias_mensaje'] . ' days');
    }
}
