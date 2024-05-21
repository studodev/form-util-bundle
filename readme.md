# FormUtilBundle
A collection of utilities for forms in Symfony

## Features
* Disable client side validation
* Compute accept attribute on `FileType` based on constraints in form or data class
* Constraint to block disposable email domains

## Installation
```
composer require studodev/form-util-bundle
```

## Configuration
Create the configuration file at path `config/packages/form_util.yaml`
### Exemple
```YAML
form_util:
    disable_client_validation: true
    enable_constraint_based_accept_attribute: true
```

## Usage
### Block disposable email domains 
```PHP
class User {
    // ....

    #[Assert\Email]
    #[NotDisposableEmail]
    private string $email;
    
    // ....
}
```
