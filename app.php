<?php

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Extension\Front;
use \ECidade\V3\Extension\Dispatcher;
use \ECidade\V3\Extension\Router;
use \ECidade\V3\Extension\Request;
use \ECidade\V3\Extension\Response;
use \ECidade\V3\Extension\Manager as ExtensionManager;
use \ECidade\V3\Extension\Exceptions\ResponseException;
use \ECidade\V3\Extension\Glob;
use \ECidade\V3\Error\EntityFactory;
use \ECidade\V3\Error\Renderer as ErrorRenderer;

try {

  require_once(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');
  require_once(__DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR . 'RegisterEvents.php');

    $front = new Front();
    $request = new Request($front->getPath());
    $response = new Response();
    $router = new Router($request);
    $config = Registry::get('app.config');

    Registry::set('app.request', $request);
    Registry::set('app.response', $response);

    // Requiscao /extension/[name]
    if ($front->isExtension() && $request->getExtension() != null) {
        $front->createWindow();

        define('ECIDADE_WINDOW_REQUEST_PATH', $front->getWindowRequestPath());

        define('ECIDADE_CURRENT_EXTENSION_PATH', ECIDADE_EXTENSION_PACKAGE_PATH . $request->getExtension() . DS);
        define('ECIDADE_CURRENT_EXTENSION_REQUEST_PATH',
            ECIDADE_WINDOW_REQUEST_PATH . 'extension' . DS . mb_strtolower($request->getExtension()) . DS
        );

        ini_set('display_errors', $config->get('php.display_errors'));
        ini_set('error_reporting', $config->get('php.error_reporting'));
        error_reporting($config->get('php.error_reporting'));

        // Extensao contem arquivo de inicializacao
        if (file_exists(ECIDADE_CURRENT_EXTENSION_PATH . 'bootstrap.php')) {
            require_once(ECIDADE_CURRENT_EXTENSION_PATH . 'bootstrap.php');
        }

        // Registra eventos adicionado ao cache(metadado)
        if (!$request->isAsset()) {
            Registry::get('app.container')->get('app.configData')->loadEvents();
        }

        // Encode das paginas
        $response->setCharset($config->get('charset'));
        mb_internal_encoding($config->get('charset'));

        try {
            $request->session()->start();

            $dispatcher = new Dispatcher();
            $dispatcher->execute($request, $response);
            $response->output();
        } catch (Exception $error) {
            throw new ResponseException($error->getMessage(), $error->getCode());
        }

        exit();
    }
    // END EXTENSION

    // ecidade encoding charset must be LATIN1
    $config->set('charset', 'ISO-8859-1');
    $response->setCharset($config->get('charset'));
    mb_internal_encoding($config->get('charset'));

    $filePath = $front->getPath();

    // @todo - validar utilidade
    if ($front->isExtension() && $request->getExtension() == null) {
        $filePath = 'extension/' . $front->getPath();
    }

    // base '/', usa index
    if (empty($filePath)) {
        $filePath = 'index.php';
    }

    $realpath = realpath(ECIDADE_PATH . $filePath);
    $filePath = str_replace(ECIDADE_PATH, '', $realpath);
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

    $isLaravel = preg_match('/\/web\/[^ ]*/', $_SERVER['REQUEST_URI']);

    // Arquivo nao existe, 404
    if (($realpath === false || !file_exists($filePath)) && !$isLaravel) {
        throw new ResponseException('Pgina nao encontrada: ' . $filePath, 404);
    }

    // security issue
    // bloqueia acesso a arquivos acima do root path
    if (strpos($realpath, ECIDADE_PATH) !== 0 && !$isLaravel) {
        throw new ResponseException('Acesso negado: ' . $filePath, 403);
    }

    // security issue
    // bloqueia execucao scripts php e html no diretorio tmp/
    if (in_array($fileExtension, ['php', 'html']) && strpos($filePath, 'tmp/') === 0) {
        // @TODO usar ResponseException
        header('HTTP/1.0 403 Forbiden');
        exit;
    }

    if (!$isLaravel) {
        $front->fixQueryString();
    }
    $front->createWindow();

    // Requisicoes que devem iniciar sessao somente leitura
    if (preg_match(Glob::toRegex($config->get('app.request.session.readOnlyOn'), true, false), $filePath)) {
        $request->session()->writeable(false)->start();
        $filePath = modification($filePath);
    } elseif (preg_match(Glob::toRegex($config->get('app.request.session.attachOn'), true, false), $filePath)) {
        // Requisicoes que devem iniciar sessao com escrita
        $request->session()->start();
        $filePath = modification($filePath);
    }

    /**
     * Asset - define header e retorna o arquivo para o buffer de saida
     */
    if ($fileExtension != 'php' && !$isLaravel) {
        $request->session()->close();
        $response->setFile($filePath);
        $response->output();
        exit();
    }

    // Registra eventos adicionado ao cache(metadado)
    Registry::get('app.container')->get('app.configData')->loadEvents();

    $request->session()->start();
    $front->emulateRegisterLongArrays();

    // @TODO - achar melhor forma de emular register_globals
    // @see \DBSeller\Legacy\PHP53\Emulate::registerGlobals()
    // atualmente package DBSeller/Legacy  executado antes de criar sessao
    if (!ini_get('register_globals')) {
        $front->emulateRegisterGlobals(array('SESSION'));
    }

    // lazyload para verificar se usuario tem extensao desktop instalada
    Registry::get('app.container')->register('ECIDADE_DESKTOP', function () use ($request) {
        return ExtensionManager::isEnabled('Desktop', $request->session()->get('DB_login'));
    });

    $request->session()->close();
    $response->send();
    // Remove todas as variaveis criadas neste arquivo
    // para nao ter impacto em outros arquivos, exemplo: iniciar sessao no db_conecta.php
    unset($_SESSION, $front, $request, $response, $router, $config);

    /**
     * Eloquent bootstrap
     */
    /**
     * @var \ECidade\V3\Window\Session $session
     */
    $session = Registry::get('app.request')->session();
    $userLoggedIn = $session->has('DB_id_usuario');

    if ($userLoggedIn) {
        $eloquent = new EloquentBootstrap(
            $session->get('DB_servidor'),
            $session->get('DB_NBASE', $session->get('DB_base')),
            $session->get('DB_user'),
            $session->get('DB_senha'),
            $session->get('DB_porta')
        );
        $eloquent->bootstrap();
    }
    /**
     * End Eloquent bootstrap
     */

    if ($isLaravel) {
        $pos = explode('/', $_SERVER['REQUEST_URI']);
        $index = array_search('w', $pos);
        unset($pos[$index], $pos[$index + 1]);
        $_SERVER['REQUEST_URI'] = implode('/', $pos);
        require_once(__DIR__ . '/public/index.php');
    } else {
        require_once($filePath);
    }
} catch (ResponseException $exception) {
    $response = Registry::get('app.response');
    if ($response) {
        $response->setCode($exception->getCode() == 0 ? 500 : $exception->getCode());
    }

    $entity = EntityFactory::createFromException($exception);
    ErrorRenderer::render($entity);
}
