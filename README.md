# Règles de validation pour un NIR

Ce dépôt à pour but de proposer des contraintes sur la représentation des 
données d'identification française.

Nous proposons plusieurs règles :

- Numéro d'Inscription au Répertoire (NIR)
- Numéro National Provisoire (NNP)
- Migrant de passage (MIG)
- Numéro Identifiant d'Attente (NIA)

## Utilisation

### PHP natif

Cette partie explique comment utiliser les fonctionnalités du validateur.

L'exemple suivant montre comment valider un NIR :

```php
use Cnamts\Nir\Constraints\Nir;
use Symfony\Component\Validator\Validation;

$validator = Validation::createValidator();
$violations = $validator->validate('2 84 05 88 321 025 30', [new Nir()]);

if (count($violations) !== 0) {
    echo '<ul>';
    foreach ($violations as $violation) {
        echo '<li>'.$violation->getMessage().'</li>';
    }
    echo '</ul>';
}
```

### Symfony

Toutes les contraintes ont été intégrées pour pouvoir être utilisées dans
tous les formats supportés par Symfony :

#### Attributs

```php
// src/Entity/User.php
namespace App\Entity;

// ...
use Cnamts\Nir\Constraints as Assert;

class User
{
    #[Assert\Nir]
    private $identifier;
}
```

#### YAML

```yml
# config/validator/validation.yaml
App\Entity\User:
    properties:
        identifier:
            - Nir: ~
```

#### XML

```xml
<!-- config/validator/validation.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<constraint-mapping xmlns="http://symfony.com/schema/dic/constraint-mapping"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/constraint-mapping
        https://symfony.com/schema/dic/constraint-mapping/constraint-mapping-1.0.xsd">

    <class name="App\Entity\User">
        <property name="identifier">
            <constraint name="Nir"/>
        </property>
    </class>
</constraint-mapping>
```

#### PHP

```php
// src/Entity/User.php
namespace App\Entity;

// ...
use Cnamts\Nir\Constraints\Nir;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class User
{
    private $identifier;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('identifier', new Nir());
    }
}
```

## Contraintes

### Numéro d'Inscription au Répertoire (NIR)

Le NIR est un numéro à treize caractères dont la composition est définie par 
l'article 4 du décret n° 82-103 du 22 janvier 1982.

### Numéro National Provisoire (NNP)

Le NNP est attribué aux personnes nées à l'étranger qui n'ont jamais été 
immatriculées dans le système français. Les NNP ont officiellement disparu au 
31 décembre 2022.

### Migrant de passage (MIG)

Pour créer dans le Référentiel Individus (RFI) les individus présents dans les 
Bases de Données Opérantes (BDO) en tant que membres de la famille d'un migrant 
de passage, il est nécessaire de leur attribuer un numéro de type "Migrant", 
unique au niveau national.

### Numéro d'Ummatriculation d'Attente (NIA)

Le NIA, mis en place en 2016, a pour objectif de remplacer les numéros 
provisoires (NNP) créés de manière indépendante par les caisses.  
Il améliore la gestion de l'identification en instaurant un numéro provisoire 
unique et partagé entre tous les Organismes de Protection Sociale.
