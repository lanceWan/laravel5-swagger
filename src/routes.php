<?php
Route::any(Config::get('swagger.doc-route').'/{page?}', function($page='api-docs.json') {
    $filePath = Config::get('swagger.doc-dir') . "/{$page}";
    if (File::extension($filePath) === "") {
        $filePath .= ".json";
    }
    if (!File::Exists($filePath)) {
        App::abort(404, "Cannot find {$filePath}");
    }
    $content = File::get($filePath);
    return Response::make($content, 200, array(
        'Content-Type' => 'application/json'
    ));
});
Route::get(Config::get('swagger.api-docs-route'), function() {
    if (Config::get('swagger.generateAlways')) {
        $appDir = base_path()."/".Config::get('swagger.app-dir');
        $docDir = Config::get('swagger.doc-dir');
        if (!File::exists($docDir) || is_writable($docDir)) {
            // delete all existing documentation
            if (File::exists($docDir)) {
                File::deleteDirectory($docDir);
            }
            File::makeDirectory($docDir);
            $defaultBasePath = Config::get('swagger.default-base-path');
            $defaultApiVersion = Config::get('swagger.default-api-version');
            $defaultSwaggerVersion = Config::get('swagger.default-swagger-version');
            $excludeDirs = Config::get('swagger.excludes');
            $swagger =  \Swagger\scan($appDir, [
                'exclude' => $excludeDirs
                ]);
            $filename = $docDir . '/api-docs.json';
            file_put_contents($filename, $swagger);
        }
    }
    if (Config::get('swagger.behind-reverse-proxy')) {
        $proxy = Request::server('REMOTE_ADDR');
        Request::setTrustedProxies(array($proxy));
    }
    Blade::setEscapedContentTags('{{{', '}}}');
    Blade::setContentTags('{{', '}}');
    //need the / at the end to avoid CORS errors on Homestead systems.
    $response = response()->view('swagger::index', array(
        'secure'         => Request::secure(),
        'urlToDocs'      => url(Config::get('swagger.doc-route')),
        'requestHeaders' => Config::get('swagger.requestHeaders'),
        'clientId'       => Request::input("client_id"),
        'clientSecret'   => Request::input("client_secret"),
        'realm'          => Request::input("realm"),
        'appName'        => Request::input("appName"),
        )
    );
    //need the / at the end to avoid CORS errors on Homestead systems.
    /*$response = Response::make(
        View::make('swagger::index', array(
                'secure'         => Request::secure(),
                'urlToDocs'      => url(Config::get('swagger.doc-route')),
                'requestHeaders' => Config::get('swagger.requestHeaders') )
        ),
        200
    );*/
    if (Config::has('swagger.viewHeaders')) {
        foreach (Config::get('swagger.viewHeaders') as $key => $value) {
            $response->header($key, $value);
        }
    }
    return $response;
});