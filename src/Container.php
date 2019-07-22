<?php
/**
 * 容器基础接口
 */
namespace pizepei\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * 容器对象实例（当前类）
     * @var Container
     */
    protected static $instance =null;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 基础容器中的对象实例
     * @var array
     */
    protected $baseInstances = [];
    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];
    /**
     * 基础容器标识
     * @var array
     */
    protected $baseBind = [];

    /**
     * 容器回调
     * @var array
     */
    protected $invokeCallback = [];

    /**
     * 注册一个服务
     */
    protected function setInstances($name ,$instances)
    {
        if (isset($instances[$name])){
            
        }

    }


    /**
     * 当调用一个不存在的方法时使用
     * @param $name
     * @param $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments)
    {
        /**
         * 判断是否在基础中
         */
        if ($this->baseBind[$name]){
            if (!isset($this->baseInstances[$name])){

                if (!empty($arguments)){
                    $this->baseInstances[$name] = new  $this->baseBind[$name](...$arguments);
                }else{
                    $this->baseInstances[$name] = new  $this->baseBind[$name]();
                }
            }
            return $this->baseInstances[$name];
        }
        return false;
    }

    /**
     * 当调用一个不存在的 静态  方法时使用
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

    /**
     * 根据容器标识返回容器的服务(不包括框架级别)
     * @param string $id Identifier of the entry to look for.
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (isset($this->instances[$id])){
            return $this->instances[$id];
        }
        throw new \Exception('Resources don t exist');
    }
    /**
     * 判断一个标识服务是否在容器中
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        if (isset($this->Instances[$id])){
            return true;
        }
        return false;
    }
}