<?php

namespace FreedomtechHosting\PolydockApp;

class PolydockAppVariableDefinitionBase implements PolydockAppVariableDefinitionInterface
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
