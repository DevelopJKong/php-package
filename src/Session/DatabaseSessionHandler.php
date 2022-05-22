<?php

namespace Eclair\Session;

use Eclair\Database\Adaptor;

class DatabaseSessionHandler implements \SessionHandlerInterface
{
    public function open($savePath, $sessionName):bool
    {
        return true;
    }

    public function close():bool
    {
        return true;
    }

    public function read($id):string|false
    {
        $data = current(Adaptor::getAll('SELECT * FROM sessions WHERE `id` = ?', [$id]));

        if ($data) {
            $payload = $data->payload;
        } else {
            Adaptor::exec('INSERT INTO sessions(`id`) VALUES(?)', [$id]);
        }
        return $payload ?? '';
    }

    public function write($id, $paylode):bool
    {
        return Adaptor::exec('UPDATE sessions SET `payload` = ? WHERE `id` = ?', [$paylode, $id]);
    }

    public function destroy($id):bool
    {
        return Adaptor::exec('DELETE FROM sessions WHERE `id` = ?', [$id]);
    }

    public function gc($maxlifetime):int|false
    {
        if($sessions = Adaptor::getAll('SELECT * FROM sessions')) {
            foreach ($sessions as $session) {
                $timestamp = strtotime($session->created_at);
                if(time() - $timestamp > $maxlifetime) {
                    $this->destroy($session->id);
                }
            }
            return true;
        }
        return false;
    }
}
