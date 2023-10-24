<?php



#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToMany
{
    public string $name;

    public static string $classe = 'Statut';

    public function __construct(string $classe)
    {
//        $this->name = $class;
//        $this->classe = $classe;
    }
}