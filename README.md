[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-8892BF.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/laravel-%5E11.0-FF2D20.svg)](https://laravel.com)

# Laravel Relation Updater

> Elegant and declarative model relation updater for Laravel.

## ✨ Features

- ✅ Simple, expressive API for updating Eloquent relationships
- ✅ Supports multiple relation types (`hasOne`, `hasMany`, etc.)
- ✅ Laravel 11+ ready
- ✅ PHP 8.2+ support
- ✅ Returns detailed result collections: created, updated, deleted

## 📦 Installation

Install via Composer:

```bash
composer require jmjl161100/laravel-relation-updater
```

The service provider will be auto-registered via Laravel package discovery.

## 🚀 Quick Start

### 1. Use the Trait in Your Model

```php
use Illuminate\Database\Eloquent\Model;
use Jmjl161100\RelationUpdater\Traits\HasRelationUpdater;

class Post extends Model
{
    use HasRelationUpdater;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

### 2. Update Relations with `{relationName}Update`

```php
$post = Post::find(1);

$result = $post->commentsUpdate([
    ['id' => 1, 'content' => 'Updated comment'], // Update
    ['content' => 'New comment']                // Create
]);
```

### 3. Get Operation Results

The returned result is an array of model collections:

```php
[
    'created' => Collection, // Newly created models
    'updated' => Collection, // Updated models
    'deleted' => Collection  // Models deleted due to sync
]
```

## ⚙️ Advanced Usage

### Field Mapping

If input keys differ from model attributes, provide a mapping:

```php
$post->commentsUpdate(
                        [
                            ['id' => 1, 'input_content' => 'Updated comment']
                        ], 
                        [
                            'input_content' => 'content'
                        ]
                     );
```

This maps `input_content` to the model's `content` attribute.

## 🛡 Security

If you discover a security vulnerability, please **do not** open an issue. Instead, contact the author directly.

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).
