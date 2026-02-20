<?php

namespace FreedomtechHosting\PolydockApp;

class PolydockAppVariableDefinitionBase implements PolydockAppVariableDefinitionInterface
{
    public function __construct(protected string $name) {}

    /**
     * Get the name of the app variable definition
     *
     * @return string The name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the app vairable definition
     *
     * @param string $name
     * @return PolydockAppVariableDefinitionBase
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
