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
        $this->loadModel('Programaciones');
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
        $programaciones = $this->Programaciones->find()
            ->where(['Programaciones.estado_id' => 4])
            ->contain(['Servicios']);
        
        foreach ($programaciones as $programacion) {
            if ($programacion->fecha_limite->format('Y-m-d') <= date('Y-m-d')) {
                exec('echo Advertencia  > tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('echo Servicio: ' . utf8_decode($programacion->servicio->descripcion) . ' >> tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('echo Detalle de Servicio: ' . $programacion->servicio->detalle . ' >> tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('echo Monto: ' . $programacion->monto . ' >> tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('echo Fecha de Vencimiento: ' . $programacion->fecha_vencimiento->format('Y-m-d') . ' >> tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('echo Revise el Sistema de Pago de Servicios  >> tmp-' . $programacion->id . '-' . $username . '.txt');

                exec('msg /SERVER:' . $ip. ' ' . $username . ' <tmp-' . $programacion->id . '-' . $username . '.txt');
                exec('del tmp-' . $programacion->id . '-' . $username . '.txt');
            }
        }
    }
}
