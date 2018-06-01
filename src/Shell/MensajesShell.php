<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

/**
 * Mensajes shell command.
 */
class MensajesShell extends Shell
{

    public function initialize() {
        parent::initialize();
        $this->loadModel('Recibos');
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main() {
        $this->out('Usa la funcion send + local/servidor');
    }
    
    public function send($ip, $username) {
        $recibos = $this->Recibos->find()
            ->where(['Recibos.estado_id' => 4])
            ->contain(['Servicios']);
        
        foreach ($recibos as $recibo) {
            if ($recibo->fecha_limite->format('Y-m-d') <= date('Y-m-d')) {
                if ($recibo->nro_recibo != null && $recibo->nro_recibo != 0) {
                    exec('echo Advertencia  > tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('echo Servicio: ' . utf8_decode($recibo->servicio->descripcion) . ' >> tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('echo Detalle de Servicio: ' . $recibo->servicio->detalle . ' >> tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('echo Monto: ' . $recibo->monto . ' >> tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('echo Fecha de Vencimiento: ' . $recibo->fecha_vencimiento->format('Y-m-d') . ' >> tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('echo Revise el Sistema de Pago de Servicios  >> tmp-' . $recibo->id . '-' . $username . '.txt');

                    exec('msg /SERVER:' . $ip. ' ' . $username . ' <tmp-' . $recibo->id . '-' . $username . '.txt');
                    exec('del tmp-' . $recibo->id . '-' . $username . '.txt');
                }
            }
        }
    }
}
