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
    
    protected $_virtual = ['condicion', 'deuda_acumulada', 'descripcion_detallada', 'countRecibos', 'countRecibosPendientes', 'montoPendiente'];
    
    protected function _getCondicion() {
        $condicion = 'DEUDA';
        
        if (empty($this->_properties['recibos'])) {
            $condicion = 'PAGADO';
        }
        
        return $condicion;
    }
    
    protected function _getDescripcionDetallada() {
        $descripcion = $this->_properties['descripcion'];
        $detalle =  $this->_properties['detalle'];
        return $descripcion . ' - ' . $detalle;
    }
    
    
    protected function _getDeudaAcumulada() {
        $suma = 0;
        
        if (!empty($this->_properties['recibos'])) {
            foreach ($this->_properties['recibos'] as $programacion) {
                $suma += $programacion->monto;
            }
        }
        
        return $suma;
    }
    
    protected function _getCountRecibos() {
        if (!empty($this->_properties['recibos'])) {
            return sizeof($this->_properties['recibos']);
        }
        return 0;
    }
    
    protected function _getCountRecibosPendientes() {
        if (!empty($this->_properties['recibos'])) {
            $countRecibosPendientes = 0;
            $recibos = $this->_properties['recibos'];
            foreach ($recibos as $recibo) {
                if ($recibo->estado_id == 4) {
                    $countRecibosPendientes += 1;
                }
            }
            return $countRecibosPendientes;
        }
        return 0;
    }
    
    protected function _getMontoPendiente() {
        if (!empty($this->_properties['recibos'])) {
            $montoTotal = 0;
            $recibos = $this->_properties['recibos'];
            foreach ($recibos as $recibo) {
                if ($recibo->estado_id == 4) {
                    $montoTotal += $recibo->monto;
                }
            }
            return $montoTotal;
        }
        return 0;
    }
}
