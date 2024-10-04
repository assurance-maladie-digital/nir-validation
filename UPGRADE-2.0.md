# UPGRADE from 1.x to 2.0.0

### Configuration

  * Le support de PHP 7.3 a été supprimé. PHP 8.1 est maintenant la version minimale requise.
  * Le fichier `composer.json` a été mis à jour pour prendre en charge Symfony `^5.4`, `^6.4` et `^7.1`.

### Code source

  * Les annotations de validation ont été supprimées au profit des attributs, conformément à l'abandon de PHP 7.4.
  * Les annotations de type ont été mises à jour pour utiliser les types scalaires, en phase avec les nouvelles versions de PHP.
  * La signature de la méthode `validate` a été mise à jour pour utiliser le type `mixed` dans `MigValidator.php`, `NirValidator.php`, et `NnpValidator.php`.

    Avant :

    ```php
    public function validate($value, Constraint $constraint): void
    ```

    Après :

    ```php
    public function validate(mixed $value, Constraint $constraint): void
    ```

  * La validation du mois de naissance dans le NIR a été modifiée pour accepter les valeurs allant de 20 à 99 lorsque le mois est inconnu (pour des personnes nées à l’étranger).

    Avant :

    ```php
    '(?<moisNaissance>1[0-2]|0[1-9])'
    ```

    Après :

    ```php
    '(?<moisNaissance>0[1-9]|1[0-2]|[2-9][0-9])'
    ```

---
