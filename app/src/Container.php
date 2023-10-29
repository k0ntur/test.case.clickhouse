<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ContainerException;
use App\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $factories;
    private array $singletones;

    public function get(string $id)
    {
        if ($this->has($id)){
            $factory = $this->factories[$id];

            return $factory($this);
        }

        return $this->resolve($id);

    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }

    public function set(string $id, callable $factory, $singleton = false)
    {
        if (!$singleton) {
            $this->factories[$id] = $factory;
        } else {
            $this->factories[$id] = function() use ($id, $factory) {
                if (isset($this->singletones[$id])) {
                    return $this->singletones[$id];
                } else {
                    $this->singletones[$id] = $factory($this);
                    return $this->singletones[$id];
                }
            };
        }
    }

    public function resolve(string $id)
    {
        $refClass = new \ReflectionClass($id);

        if (!$refClass->isInstantiable()){
            throw new ContainerException('Class '.$id.' is not instantiable');
        }

        $constructor = $refClass->getConstructor();

        if (null == $constructor){
            return new $id;
        }

        $parameters = $constructor->getParameters();

        if(empty($parameters)) {
            return new $id;
        }

        $idDependencies = [];
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter){
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (null == $type){
                throw new ContainerException(
                    'Cant resolve class "'.$id.'" since its param "'.$name.'" has no typehint'
                );
            }

            if ($type instanceof \ReflectionUnionType){
                throw new ContainerException(
                    'Cant resolve class "'.$id.'" since its param "'.$name.'" has a UnionType'
                );
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()){
                $idDependencies[] = $this->get($type->getName());
            } else {
                throw new ContainerException(
                    'Cant resolve class "' . $id . '" since its param "' . $name . '" has a scalar type'
                );
            }
        }

        return $refClass->newInstanceArgs($idDependencies);
    }
}