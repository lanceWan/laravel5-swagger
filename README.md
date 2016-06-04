# laravel5-swagger

基于 [slampenny/Swaggervel](https://github.com/slampenny/Swaggervel) 实现的 [swagger](http://swagger.io/) API文档，更新 swagger UI 到最新版本，支持多语言设置。

# Swagger 2

本扩展使用的是 Swagger UI 2.1.4 版本

# OAuth2

关于 OAuth2 的现在还未测试使用，后期会进行测试更新。。。

# 安装

* 在项目根目录下执行： `composer require iwanli/laravel5-swagger` 或在 `composer.json` 中添加 `"iwanli/laravel5-swagger": "~0.1"` 然后执行 `composer update`
* 添加 `Iwanli\Swagger\SwaggerServiceProvider::class` 到 `app/config/app.php` providers 数组中
* 最后执行 `php artisan vendor:publish`

**默认的访问路由**

1. json格式访问地址：www.example.com/docs
2. swagger UI 访问地址：www.example.com/api/docs

# 配置文件

在执行 `php artisan vendor:publish` 成功后，会生成 `config/swagger.php` 的配置文件，配置文件内容如下：

```php
<?php
return [
    /*
      |--------------------------------------------------------------------------
      | 多语言配置
      | 可选语言：en、es、fr、it、ja、pl、pt、ru、tr、zh-cn
      |--------------------------------------------------------------------------
    */
    'lang' => 'zh-cn',
    /*
      |--------------------------------------------------------------------------
      | 注释文档存放位置
      |--------------------------------------------------------------------------
    */
    'doc-dir' => storage_path() . '/docs',
    /*
      |--------------------------------------------------------------------------
      | 文档路由
      |--------------------------------------------------------------------------
    */
    'doc-route' => 'docs',
    /*
      |--------------------------------------------------------------------------
      | UI界面路由
      |--------------------------------------------------------------------------
    */
    'api-docs-route' => 'api/docs',
    /*
      |--------------------------------------------------------------------------
      | 监听目录
      |--------------------------------------------------------------------------
    */
    "app-dir" => "app",

	......

```

这里我默认是中文的，大部分情况下，大家可以不用修改配置，默认即可。

# 简单的使用

## 控制器中使用

```
<?php
...
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="itest.me/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger Petstore",
 *         description="This is a sample server Petstore server.  You can find out more about Swagger at <a href=""http://swagger.io"">http://swagger.io</a> or on irc.freenode.net, #swagger.  For this sample, you can use the api key ""special-key"" to test the authorization filters",
 *         termsOfService="http://helloreverb.com/terms/",
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Swagger",
 *         url="http://swagger.io"
 *     )
 * )
 */
class SimplePetsController
{
    /**
     * @SWG\Get(
     *     path="/pets",
     *     description="Returns all pets from the system that the user has access to",
     *     operationId="findPets",
     *     produces={"application/json", "application/xml", "text/xml", "text/html"},
     *     @SWG\Parameter(
     *         name="tags",
     *         in="query",
     *         description="tags to filter by",
     *         required=false,
     *         type="array",
     *         @SWG\Items(type="string"),
     *         collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="maximum number of results to return",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/pet")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(
     *             ref="#/definitions/errorModel"
     *         )
     *     )
     * )
     */
    public function findPets()
    {
    }
    /**
     * @SWG\Get(
     *     path="/pets/{id}",
     *     description="Returns a user based on a single ID, if the user does not have access to the pet",
     *     operationId="findPetById",
     *     @SWG\Parameter(
     *         description="ID of pet to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     produces={
     *         "application/json",
     *         "application/xml",
     *         "text/html",
     *         "text/xml"
     *     },
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(ref="#/definitions/pet")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     */
    public function findPetById()
    {
    }
    /**
     * @SWG\Post(
     *     path="/pets",
     *     operationId="addPet",
     *     description="Creates a new pet in the store.  Duplicates are allowed",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="pet",
     *         in="body",
     *         description="Pet to add to the store",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/petInput"),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="pet response",
     *         @SWG\Schema(ref="#/definitions/pet")
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     * @SWG\Definition(
     *     definition="petInput",
     *     allOf={
     *         @SWG\Schema(ref="pet"),
     *         @SWG\Schema(
     *             required={"name"},
     *             @SWG\Property(
     *                 property="id",
     *                 type="integer",
     *                 format="int64"
     *             )
     *         )
     *     }
     * )
     */
    public function addPet()
    {
    }
    /**
     * @SWG\Delete(
     *     path="/pets/{id}",
     *     description="deletes a single pet based on the ID supplied",
     *     operationId="deletePet",
     *     @SWG\Parameter(
     *         description="ID of pet to delete",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="pet deleted"
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/errorModel")
     *     )
     * )
     */
    public function deletePet()
    {
    }
}
```

## 模型中使用

```
<?php
...

/**
 * @SWG\Definition(definition="pet", required={"id", "name"})
 */
class SimplePet
{
    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @SWG\Property()
     * @var string
     */
    public $name;
    /**
     * @var string
     * @SWG\Property()
     */
    public $tag;
}
```

本扩展会自动监听app目录下面的所有控制器(controller)和模型(model)注释张带有@SWG的标签并解析，这里的示例我只是简单的先贴上别人的代码，后期我会将这些命令和界面全部放在个人的阿里云上，方便大家学习。

> 另外我在扩展里目录添加了一个 `Examples` 目录，大家可以参照这个例子，动动手在本地试试，网上的预览地址：[http://petstore.swagger.io/](http://petstore.swagger.io/) ，关于更多的参数命令：[http://swagger.io/specification/](http://swagger.io/specification/) ，本人也在深入研究中。大家可以互相交流讨论。

如果有上面错误或bug，欢迎反馈~ 创建一个 [Issues](https://github.com/lanceWan/laravel5-swagger/issues) 或邮件反馈 `709344897@qq.com`