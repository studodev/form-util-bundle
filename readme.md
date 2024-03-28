# FormUtilBundle
A collection of utilities for forms in Symfony

## Features
* Disable client side validation
* Constraint to block disposable email domains

## Installation
```
composer require studodev/form-util-bundle
```

## Configuration
```YAML
form_util:
    disable_client_validation: true 
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
