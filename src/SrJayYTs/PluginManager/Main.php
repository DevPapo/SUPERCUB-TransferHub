<?php

namespace SrJayYTs\PluginManager;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase {

    private Config $ipConfig;

    public function onEnable() : void {
        $this->initIpConfig();
        $this->getLogger()->info("SUPERCUB-TransferHub enabled!");
    }

    /*
      Inicializa el archivo ip.yml en la carpeta de datos del plugin.
      Si no existe, se crea con la IP por defecto "mcpe.wtf" y el puerto 19132.
     */
    private function initIpConfig() : void {
        $file = $this->getDataFolder() . "ip.yml";
        if(!file_exists($file)){
            @mkdir($this->getDataFolder());
            $default = [
                "ip" => "mcpe.wtf",
                "port" => 19132
            ];
            $this->ipConfig = new Config($file, Config::YAML, $default);
            $this->ipConfig->save();
            $this->getLogger()->info("Se creó ip.yml con la configuración por defecto.");
        } else {
            $this->ipConfig = new Config($file, Config::YAML);
        }
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if($command->getName() === "hub-network"){
            if(!$sender instanceof Player){
                $sender->sendMessage("Este comando solo puede ser usado en juego.");
                return true;
            }
            
            // Recarga la configuración al instante para tener datos actualizados.
            $this->ipConfig->reload();
            $ip = $this->ipConfig->get("ip", "mcpe.wtf");
            $port = (int)$this->ipConfig->get("port", 19132);
            
            $sender->sendMessage("Transferencia en curso al Hub de SUPERCUB...");
            $sender->transfer($ip, $port);
            return true;
        }
        return false;
    }
}
