<?php



#[Attribute(Attribute::TARGET_PROPERTY)]
class Herit
{

    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }
}