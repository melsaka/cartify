# Cartify

Cartify is a Laravel package designed to simplify cart management in your Laravel applications. It allows you to add, retrieve, update, and remove items from a cart effortlessly. With Cartify, you can easily implement shopping cart functionality in your e-commerce projects.

## Installation

To get started with Cartify, you can install it via Composer. Open your terminal and run the following command:

```bash
composer require melsaka/cartify
```

Register the package's service provider in config/app.php.

```php
'providers' => [
    ...
    Melsaka\Cartify\CartifyServiceProvider::class,
    ...
];
```

Run the migrations to add the required table to your database:

```bash
php artisan migrate
```

## Configuration

To configure the package, publish its configuration file:

```bash
php artisan vendor:publish --tag=cartify
```

You can then modify the configuration file to change the cartify table name if you want, default: `carts`.

## Usage

Once you've installed Cartify, you can start using it in your Laravel application. Here are some of the key features and usage examples:

### Adding Items to the Cart

To add items to the cart, you can use the `add` method:

```php
use Melsaka\Cartify\Models\Cart;

$data = [
    'items' => [
        'key1' => 'value1',
        'key2' => [
            'name1' => 'ahmed',
            'name2' => 'mohamed'
        ],
    ],
    'other' => 'data',
];

$cart = Cart::add($data);
```

### Retrieving a Cart

You can retrieve a cart by it's identifier using the `ofOwner` method:

```php
use Melsaka\Cartify\Models\Cart;

$identifier = Cart::getIdentifier();

$cart = Cart::ofOwner($identifier);
```

### Setting Cart Content

To update the content of a cart, use the `set` method:

```php
$cart->set([
    'items.key1' => 'new-value',
    'other' => 'new-data',
]);
```

### Removing Items from the Cart

You can remove items from the cart using the `unset` method:

```php
$cart->unset('items.key1');
// or
$cart->unset(['items.key1', 'other']);
```

### Getting Cart Content

To retrieve the content of the cart, use the `getContent` method:

```php
$content = $cart->getContent();
// or
$value = $cart->getContent('items.key1');
```

## License

This package is released under the MIT license (MIT).
