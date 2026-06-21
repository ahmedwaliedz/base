# Backend Feature Implementation Examples

Load this reference when you need concrete examples for Form Requests, services, controllers, models, error handling, filters, and relations.

## Contents

- [Form Request](#form-request)
- [Controller usage](#controller-usage)
- [Service pattern](#service-pattern)
- [Error handling](#error-handling)
- [Model relationships](#model-relationships)
- [Scopes](#scopes)
- [Index with filters](#index-with-filters)
- [Store with relations](#store-with-relations)

## Form Request

```php
// app/Http/Requests/StorePostRequest.php
class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }
}
```

### Controller usage

```php
public function store(StorePostRequest $request)
{
    $data = $request->validated();
    $post = $this->service->create($data);

    return redirect()->route('admin.posts.index');
}
```

## Service pattern

```php
// app/Services/Admin/PostService.php
class PostService extends CrudBaseService
{
    public function create(array $data): Post
    {
        // Business logic here
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        // Business logic here
        $post->update($data);
        return $post->fresh();
    }

    public function delete(Post $post): void
    {
        // Business logic here
        $post->delete();
    }
}
```

## Error handling

### Throwing

```php
throw new \App\Exceptions\ServiceException('Error message', 422);
```

### Catching in controller (admin-specific example)

The example below is appropriate for admin CRUD controllers that follow the existing `AdminBaseController` pattern and need to translate expected service failures into a user-facing response.

```php
try {
    $this->service->performAction($data);
} catch (ServiceException $e) {
    return back()->withErrors($e->getMessage());
}
```

> **API controllers:** do not add broad controller-level catch blocks by default. Allow expected exceptions to reach the centralized API exception renderer in `bootstrap/app.php`. Catch only errors the controller can genuinely recover from or translate intentionally. Never expose raw exception messages or stack traces.

## Model relationships

```php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}
```

## Scopes

```php
public function scopeActive($query)
{
    return $query->where('is_active', true);
}
```

## Index with filters

```php
// Service
public function getFiltered(array $filters): LengthAwarePaginator
{
    $query = Post::query();

    if (!empty($filters['search'])) {
        $query->where('title', 'like', "%{$filters['search']}%");
    }

    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }

    return $query->paginate(15);
}

// Controller
public function index(PostIndexRequest $request)
{
    $posts = $this->service->getFiltered($request->validated());
    return view('admin.posts.index', compact('posts'));
}
```

## Store with relations

```php
// Service
public function create(array $data): Post
{
    $post = Post::create($data);

    if (!empty($data['tags'])) {
        $post->tags()->sync($data['tags']);
    }

    return $post;
}
```
