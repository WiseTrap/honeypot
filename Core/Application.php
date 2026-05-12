<?php

namespace WiseTrap\Core;

use Exception;
use WiseTrap\App\Models\DbModel;
use WiseTrap\Core\Session\Session;

class Application
{
    public string $userClass;
    public static Application $app;
    public static string $SRC_DIR;
    protected Response $response;
    protected Router $router;
    protected Request $request;
    public Database $database;
    public ?DbModel $user = null;
    public function __construct(string $srcDir, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$app      = $this;
        self::$SRC_DIR  = $srcDir;

        $this->response = new Response();
        $this->request  = new Request();
        $this->router   = new Router($this->request, $this->response);
        $this->database = new Database($config['db']);


        $primaryValue = Session::get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }
    }
    public function run(): void
    {
        try {
            echo $this->router->dispatch();
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    protected function handleException(Exception $e): void
    {
        $statusCode = (int) ($e->getCode() ?: 500);
        if ($statusCode < 100 || $statusCode > 599) {
            $statusCode = 500;
        }
        $this->response->setStatusCode($statusCode);

        if ($this->request->isApiRequest()) {
            $this->response->json([
                'error' => $e->getMessage(),
                'code'  => $statusCode
            ]);
        } else {
            if (Session::get('user')){
                echo View::renderView('error.index', ['exception' => $e]);
            }else{
                redirect('/auth');
            }
        }
    }
    public function isGuest(): bool
    {
        return $this->user === null;
    }
    public function login(DbModel $user): true
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        Session::make('user', $primaryValue);
        return true;
    }
    public function logout(): void
    {
        $this->user = null;
        Session::forget_all();
        redirect('/auth');
    }
}