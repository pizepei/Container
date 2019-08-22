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
     * 函数
     * @var array
     */
    protected $closure  = [];

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
     * 注册一个普通服务
     */
    protected function setInstances($name ,$instances,bool $new=false)
    {
        # 不能绑定注册基础服务标识
        if (isset($this->baseBind[$name])){
            return false;
        }

        if ($concrete instanceof Closure) {# 如果是函数，先注册到bind中
            # 如果是true 就无论如何的写入bind
            if ($new){
                # 强制更新 这里如果原来有 不管是函数函数类的实例都会被覆盖
                $this->bind[$name] = $instances;
                return true;
            }else{

                # 判断是否已经有有就返回false 注册失败    这里同时判断是否已经有绑定的服务  （是否注册由isset($this->instances[$name])决定，因为所有类型服务都放在这里）
                if (isset($this->bind[$name]) || isset($this->instances[$name])){
                    return false;
                }else{
                    $this->bind[$name] = $instances;
                    return true;
                }

            }
        } else {
            # 如果是一个实例化的对象就直接绑定到服务中
            if ($new) {
                # 强制更新 这里如果原来有 不管是函数函数类的实例都会被覆盖
                $this->instances[$abstract] = $concrete;
            }else{
                # 判断是否已经有有就返回false 注册失败
                if (isset($this->instances[$name])){
                    return false;
                }else{
                    $this->instances[$abstract] = $concrete;
                    return true;
                }
            }
        }

    }
    /**
     * 当调用一个不存在的方法时使用（以动态方法方式使用服务）
     * @param $name
     * @param $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments)
    {
        # 判断是否在绑定中
        if (isset($this->baseBind[$name]) || isset($this->bind[$name])){
            # 判断是否已经注册
            if (!isset($this->instances[$name])){
                # 判断是否有参数
                if (!empty($arguments)){
                    $this->instances[$name] = new  $this->baseBind[$name](...$arguments);
                }else{
                    $this->instances[$name] = new  $this->baseBind[$name]();
                }
            }
            return $this->instances[$name];
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
    public function has(string $id):bool
    {
        if (isset($this->instances[$id])){
            return true;
        }
        return false;
    }

}