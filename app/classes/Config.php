<?php

namespace app\classes;

/**
 * Class Config
 * @package app\classes
 */
class Config
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $db_name;

    /**
     * @return string
     */
    private function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->db_name;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @return false|string
     * @throws \Exception
     */
    public function init()
    {
        if ($this->paramsVerify()) {
            return $this->setConfig();
        }
    }

    /**
     * @return bool
     */
    public function paramsVerify()
    {
        parse_str($_POST["data"], $_POST);

        $temHost = (isset($_POST['host']) && $_POST['host'] != "");
        $temBanco = (isset($_POST['banco']) && $_POST['banco'] != "");
        $temUsuario = (isset($_POST['usuario']) && $_POST['usuario'] != "");
        $temSenha = (isset($_POST['senha']) && $_POST['senha'] != "");

        if ($temHost && $temBanco && $temUsuario && $temSenha) {
            //Setando variáveis
            $this->host = $_POST['host'];
            $this->db_name = $_POST['banco'];
            $this->user = $_POST['usuario'];
            $this->password = $_POST['senha'];

            return true;
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function setConfig()
    {
        try {
            $this->setSession();
            return json_encode(['msg' => FillMessage::MG0001, 'status' => 'success', 'conexao' => 'conectado']);
        } catch (\Exception $e) {
            return json_encode(['msg' => $e->getMessage(), 'status' => 'error', 'conexao' => 'conexão pendente']);
        }
    }

    /**
     * @return string
     */
    public function getStgringConfig()
    {
        return
        '<?php
        
    return [
        "driver"    => "mysql",
        "host"      => "' . $this->getHost() . '",
        "database"  => "' . $this->getDbName() . '",
        "username"  => "' . $this->getUser() . '",
        "password"  => "' . $this->getPassword() . '",
        "charset"   => "utf8",
        "collation" => "utf8_unicode_ci",
        "prefix"    => "",
    ];
';
    }

    /**
     *
     */
    public static function destroyConnection()
    {
        //Configuração do config.php
        $file = fopen("../config.php", "w+");

        //Seta a string com as configurações
        $string = "<?php";


        fwrite($file, $string);
        fclose($file);
    }

    /**
     *
     */
    public function prepararConexao()
    {
        $mensagem = json_encode(['erro' => '1']);

        if ($this->paramsVerify()) {
            $file = fopen("../config.php", "w+");

            //Seta a string com as configurações
            $string = $this->getStgringConfig();

            fwrite($file, $string);

            fclose($file);

            $mensagem = json_encode(['erro' => '0']);
        }

        return $mensagem;
    }

    /**
     */
    private function setSession()
    {
        $_SESSION['sucesso'] = "sucesso";
        $_SESSION['host'] = $this->getHost();
        $_SESSION['banco'] = $this->getDbName();
        $_SESSION['usuario'] = $this->getUser();
        $_SESSION['senha'] = $this->getPassword();
    }
}
