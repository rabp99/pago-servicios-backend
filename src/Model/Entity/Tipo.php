<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Number;
use Cake\Utility\Hash;

/**
 * Tipo Entity
 *
 * @property int $id
 * @property string $descripcion
 * @property int $estado_id
 *
 * @property \App\Model\Entity\Estado $estado
 */
class Tipo extends Entity
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
    
    protected $_virtual = ['countServicios', 'countRecibos', 'countRecibosPendientes', 'montoPendiente'];
    
    protected function _getCountServicios() {
        if (isset($this->_properties['servicios'])) {
            return sizeof($this->_properties['servicios']);
        }
        return 0;
    }
    
    protected function _getCountRecibos() {
        if (isset($this->_properties['servicios'])) {
            $servicios = $this->_properties['servicios'];
            $countRecibos = 0;
            foreach ($servicios as $servicio) {
                if ($servicio->recibos) {
                    $countRecibos += sizeof($servicio->recibos);
                }
            }
            return $countRecibos;
        }
        return 0;
    }
    
    protected function _getCountRecibosPendientes() {
        if (isset($this->_properties['servicios'])) {
            $servicios = $this->_properties['servicios'];
            $countRecibosPendientes = 0;
            foreach ($servicios as $servicio) {
                if ($servicio->recibos) {
                    foreach ($servicio->recibos as $recibo) {
                        if ($recibo->estado_id == 4) {
                            $countRecibosPendientes += 1;
                        }
                    }
                }
            }
            return $countRecibosPendientes;
        }
        return 0;
    }
    
    protected function _getMontoPendiente() {
        if (isset($this->_properties['servicios'])) {
            $servicios = $this->_properties['servicios'];
            $montoTotal = 0;
            foreach ($servicios as $servicio) {
                if ($servicio->recibos) {
                    foreach ($servicio->recibos as $recibo) {
                        $montoTotal += $recibo->monto;
                    }
                }
            }
            $montoTotal = Number::format($montoTotal, [
                'places' => 2,
                'before' => 'S/ '
            ]);
            return $montoTotal;
        }
        return 0;
    }
}